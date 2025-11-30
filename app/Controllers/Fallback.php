<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MissingRouteModel;
use App\Models\SettingModel;
use App\Models\AutoRouteLogModel;

class Fallback extends BaseController
{
    protected $missingRouteModel;
    protected $settingModel;
    protected $logModel;

    public function __construct()
    {
        $this->missingRouteModel = new MissingRouteModel();
        $this->settingModel = new SettingModel();
        $this->logModel = new AutoRouteLogModel();
        helper(['auto_route', 'auth']);
    }

    public function index()
    {
        // Get session
        $session = session();
        
        // 0. Check if this is a static file request (images, css, js, etc.)
        $uri = service('uri')->getPath();
        $uri = ltrim($uri, '/');
        
        // Remove index.php if present
        $uri = preg_replace('/^index\.php\//', '', $uri);
        
        // DEBUG LOG
        log_message('debug', 'Fallback triggered for URI: ' . $uri);
        
        // If requesting uploads or static files, return 404 immediately without processing
        if (preg_match('/^uploads\//', $uri) || preg_match('/\.(jpg|jpeg|png|gif|css|js|ico|svg|woff|woff2|ttf|eot)$/i', $uri)) {
            log_message('debug', 'Static file/upload request, returning 404');
            return $this->response
                ->setStatusCode(404)
                ->setBody('<h1>404 - File Not Found</h1>');
        }
        
        // 1. Check if Auto-Route Generator is Enabled
        $settings = $this->settingModel->getSettings();
        $autoRouteEnabled = $settings['auto_route_enabled'] ?? 1;
        
        log_message('debug', 'Auto-route enabled: ' . ($autoRouteEnabled ? 'YES' : 'NO'));
        
        if (!$autoRouteEnabled) {
            // Return 404 response directly
            return $this->response
                ->setStatusCode(404)
                ->setBody('<h1>404 - Page Not Found</h1><p>Auto-route generator is disabled.</p>');
        }
        
        // 2. Environment Check
        if (ENVIRONMENT === 'production') {
            return $this->response
                ->setStatusCode(404)
                ->setBody('<h1>404 - Page Not Found</h1>');
        }

        // 3. Get URI
        $uri = service('uri')->getPath();
        
        // Remove leading slash
        $uri = ltrim($uri, '/');
        
        // Remove index.php if present
        if (strpos($uri, 'index.php') === 0) {
            $uri = substr($uri, 9);
            $uri = ltrim($uri, '/');
        }
        
        // 4. Parse URI
        $parsed = parse_uri($uri);
        $controller = $parsed['controller'];
        $method = $parsed['method'];
        $fqn = $parsed['fqn'];
        
        // 5. Security Checks
        
        // a. Ensure User is Logged In
        if (!$session->get('logged_in')) {
            // Log rejected attempt
            $this->logMissingRoute($uri, $controller, $method, 'rejected_auth');
            $this->logActivity($uri, $controller, $method, 'blocked', 'Not authenticated');
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // b. Get User Role & URI Role
        $userRole = $session->get('role');
        $uriRole = $parsed['role'] ?? ''; // We need to update parse_uri to return role
        
        // If parse_uri doesn't return role, we assume the first segment is the role
        if (empty($uriRole)) {
            $segments = explode('/', $uri);
            $uriRole = $segments[0];
        }
        
        // c. Role Match Check (Skip for super_admin)
        if ($userRole !== 'super_admin') {
            if ($uriRole !== $userRole) {
                $this->logMissingRoute($uri, $controller, $method, 'rejected_role_mismatch');
                $this->logActivity($uri, $controller, $method, 'blocked', 'Role mismatch');
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }
        
        // d. Whitelist Check
        // Module is typically the second segment: admin/siswa -> siswa
        $segments = explode('/', $uri);
        $module = $segments[1] ?? 'home';
        
        if (!validate_auto_route($userRole, $module)) {
            $this->logMissingRoute($uri, $controller, $method, 'rejected_whitelist');
            $this->logActivity($uri, $controller, $method, 'blocked', 'Not in whitelist');
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // 6. Log to Database (Pending)
        $this->logMissingRoute($uri, $controller, $method);
        
        // 7. Generate Controller if missing
        if (!controller_exists($fqn)) {
            if (!create_controller($fqn, $method)) {
                $this->logActivity($uri, $controller, $method, 'blocked', 'Failed to create controller');
                return "Failed to create controller: $fqn";
            }
        }
        
        // 8. Generate Method if missing
        if (!method_exists_in_class($fqn, $method)) {
            if (!add_method_to_controller($fqn, $method)) {
                $this->logActivity($uri, $controller, $method, 'blocked', 'Failed to add method');
                return "Failed to add method: $method to $fqn";
            }
        }
        
        // 9. Write Route
        write_auto_route($uri, $fqn, $method, $parsed['params']);
        
        // 10. Log successful generation
        $this->logActivity($uri, $controller, $method, 'generated', 'Success');
        
        // 11. Redirect
        return redirect()->to(base_url($uri));
    }
    
    private function logMissingRoute($uri, $controller, $method, $status = 'pending')
    {
        $data = [
            'uri' => $uri,
            'guessed_controller' => $controller,
            'guessed_method' => $method,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->missingRouteModel->save($data);
    }
    
    private function logActivity($uri, $controller, $method, $status, $note = '')
    {
        $session = session();
        $userId = $session->get('logged_in') ? $session->get('user_id') : null;
        $userRole = $session->get('logged_in') ? $session->get('role') : null;
        $ip = $this->request->getIPAddress();
        
        $data = [
            'user_id' => $userId,
            'ip' => $ip,
            'role' => $userRole,
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->logModel->save($data);
    }
}