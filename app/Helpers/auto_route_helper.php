<?php

if (!function_exists('parse_uri')) {
    /**
     * Parse URI into controller, method, and params based on rules.
     *
     * Rules:
     * 1 segment: Controller::index
     * 2 segments: Controller::method (or Controller::index + param)
     * 3+ segments: Folder\Controller::method (or Folder\Controller::index + param)
     *
     * @param string $uri
     * @return array
     */
    function parse_uri($uri)
    {
        $uri = trim($uri, '/');
        $segments = explode('/', $uri);
        $count = count($segments);
        
        $namespace = 'App\\Controllers';
        $controller = '';
        $method = 'index';
        $params = [];
        
        if ($count === 1) {
            // /dashboard -> Dashboard::index
            $controller = ucfirst($segments[0]);
        } elseif ($count === 2) {
            // /admin/login -> Admin\Login::index (Folder\Controller) OR Login::admin (Controller::method)
            // BUT Rule says: 2 segments -> Controller::method
            // Let's check if the second segment is a parameter (numeric or alphanumeric mixed)
            
            $seg1 = ucfirst($segments[0]);
            $seg2 = $segments[1];
            
            if (is_parameter($seg2)) {
                // /user/123 -> User::index($id)
                $controller = $seg1;
                $method = 'index';
                $params[] = $seg2;
            } else {
                // /admin/login -> Admin\Login::index ?? OR Login::admin ??
                // User rule: 2 segments -> Controller + method.
                // Wait, user example: /admin/login -> App\Controllers\Admin\Login::index
                // This contradicts "2 segments -> Controller + method".
                // Let's look at the user's specific examples in the prompt:
                // 1 segment: /dashboard -> Dashboard::index
                // 2 segments: /admin/login -> Admin\Login::index
                // 3+ segments: /admin/ruangan/edit -> Admin\Ruangan::edit
                
                // OK, the user's examples imply that we should prefer Folder structure.
                // Let's try to treat as Folder\Controller::index first?
                // Actually, let's follow the logic:
                // If 2 segments: Admin\Login
                
                $controller = $seg1 . '\\' . ucfirst($seg2);
                $method = 'index';
            }
        } else {
            // 3+ segments
            // /admin/ruangan/edit -> Admin\Ruangan::edit
            // /admin/ruangan/edit/12 -> Admin\Ruangan::edit($id)
            
            $lastSegment = array_pop($segments);
            
            if (is_parameter($lastSegment)) {
                // Treat as parameter
                $params[] = $lastSegment;
                $methodSegment = array_pop($segments);
                
                // Check if method segment is also a parameter (unlikely but possible)
                // For now assume it's the method
                $method = $methodSegment;
            } else {
                // Treat as method
                $method = $lastSegment;
            }
            
            // Remaining segments are the controller path
            $controllerPath = array_map('ucfirst', $segments);
            $controller = implode('\\', $controllerPath);
        }
        
        $role = '';
        if ($count >= 1) {
            $role = $segments[0];
        }

        return [
            'namespace' => $namespace,
            'controller' => $controller,
            'fqn' => $namespace . '\\' . $controller,
            'method' => $method,
            'params' => $params,
            'role' => $role
        ];
    }
}

if (!function_exists('is_parameter')) {
    function is_parameter($segment) {
        // Numeric or alphanumeric (contains numbers)
        // Pure alpha is method.
        // ctype_alpha returns true if all characters are letters.
        return !ctype_alpha($segment);
    }
}

if (!function_exists('controller_exists')) {
    function controller_exists($fqn)
    {
        return class_exists($fqn);
    }
}

if (!function_exists('create_controller')) {
    function create_controller($fqn, $method = 'index')
    {
        // $fqn = App\Controllers\Admin\Ruangan
        $parts = explode('\\', $fqn);
        $className = array_pop($parts);
        $namespace = implode('\\', $parts);
        
        // Convert namespace to path
        // App\Controllers -> APPPATH . Controllers
        $path = str_replace(['App\\', '\\'], [APPPATH, DIRECTORY_SEPARATOR], $namespace);
        $filePath = $path . DIRECTORY_SEPARATOR . $className . '.php';
        
        // Create directory if not exists
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        
        if (file_exists($filePath)) {
            return true;
        }
        
        $content = "<?php\n\nnamespace {$namespace};\n\nuse App\Controllers\BaseController;\n\nclass {$className} extends BaseController\n{\n    public function {$method}()\n    {\n        return \"Auto-generated method: {$method}\";\n    }\n}\n";
        
        return file_put_contents($filePath, $content) !== false;
    }
}

if (!function_exists('method_exists_in_class')) {
    function method_exists_in_class($fqn, $method)
    {
        if (!class_exists($fqn)) {
            return false;
        }
        return method_exists($fqn, $method);
    }
}

if (!function_exists('add_method_to_controller')) {
    function add_method_to_controller($fqn, $method)
    {
        // Get file path from FQN
        $reflector = new \ReflectionClass($fqn);
        $filePath = $reflector->getFileName();
        
        if (!file_exists($filePath)) {
            return false;
        }
        
        $content = file_get_contents($filePath);
        
        // Check if method already exists (double check)
        if (strpos($content, "function $method") !== false) {
            return true;
        }
        
        // Append method before the last closing brace
        $methodStub = "\n    public function {$method}()\n    {\n        return \"Auto-generated method: {$method}\";\n    }\n";
        
        $pattern = '/}\s*$/';
        $replacement = $methodStub . "}\n";
        
        $newContent = preg_replace($pattern, $replacement, $content);
        
        return file_put_contents($filePath, $newContent) !== false;
    }
}

if (!function_exists('write_auto_route')) {
    function write_auto_route($uri, $controllerFqn, $method, $params = [])
    {
        $autoRoutesFile = APPPATH . 'Config/AutoRoutes.php';
        
        if (!file_exists($autoRoutesFile)) {
            // Create file if not exists
            $content = "<?php\n\nnamespace Config;\n\nuse CodeIgniter\Router\RouteCollection;\n\nfunction autoRoutes(RouteCollection \$routes)\n{\n}\n";
            file_put_contents($autoRoutesFile, $content);
        }
        
        $content = file_get_contents($autoRoutesFile);
        
        // Ensure FQN starts with backslash to avoid double namespacing
        if (strpos($controllerFqn, '\\') !== 0) {
            $controllerFqn = '\\' . $controllerFqn;
        }
        
        // Construct generic URI and destination
        $genericUri = $uri;
        $genericDest = $controllerFqn . '::' . $method;
        
        if (!empty($params)) {
            $uriSegments = explode('/', $uri);
            $destParams = [];
            $paramCount = 1;
            
            // We need to replace the actual values in URI with placeholders
            // and append $1, $2 etc to destination
            
            // Strategy: Reconstruct URI from known segments + placeholders
            // The params are at the end of the URI based on our parsing logic
            
            // Remove the param values from the end of uriSegments
            $baseSegments = array_slice($uriSegments, 0, count($uriSegments) - count($params));
            
            foreach ($params as $param) {
                if (is_numeric($param)) {
                    $baseSegments[] = '(:num)';
                } else {
                    $baseSegments[] = '(:segment)';
                }
                $destParams[] = '$' . $paramCount++;
            }
            
            $genericUri = implode('/', $baseSegments);
            $genericDest .= '/' . implode('/', $destParams);
        }
        
        // Check if route already exists
        $routeDef = "\$routes->get('{$genericUri}', '{$genericDest}');";
        
        if (strpos($content, $routeDef) !== false) {
            return true;
        }
        
        // Insert route
        $pattern = '/(}\s*)$/s';
        $replacement = "    {$routeDef}\n\$1";
        $newContent = preg_replace($pattern, $replacement, $content, 1);
        
        return file_put_contents($autoRoutesFile, $newContent) !== false;
    }
}

if (!function_exists('validate_auto_route')) {
    function validate_auto_route($role, $module)
    {
        // Super Admin Override
        if ($role === 'super_admin') {
            return true;
        }
        
        // Siswa Restriction
        if ($role === 'siswa') {
            return false;
        }
        
        $db = \Config\Database::connect();
        $builder = $db->table('allowed_routes');
        
        $exists = $builder->where('role', $role)
                          ->where('module', $module)
                          ->where('enabled', 1)
                          ->countAllResults();
                          
        return $exists > 0;
    }
}