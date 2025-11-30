<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\AutoRouteLogModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class AutoRouteManager extends BaseController
{
    protected $settingModel;
    protected $logModel;
    protected $db;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->logModel = new AutoRouteLogModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $settings = $this->settingModel->getSettings();
        $autoRouteEnabled = $settings['auto_route_enabled'] ?? 1;

        // Get statistics
        $stats = [
            'total_logs' => $this->logModel->countAll(),
            'generated' => $this->logModel->where('status', 'generated')->countAllResults(),
            'blocked' => $this->logModel->where('status', 'blocked')->countAllResults(),
            'ignored' => $this->logModel->where('status', 'ignored')->countAllResults(),
            'total_allowed_routes' => $this->db->table('allowed_routes')->where('enabled', 1)->countAllResults(),
        ];

        $data = [
            'title' => 'Auto-Route Manager',
            'active' => 'autoroute',
            'auto_route_enabled' => $autoRouteEnabled,
            'stats' => $stats
        ];

        return view('admin/autoroute/index', $data);
    }

    public function updateToggle()
    {
        $enabled = $this->request->getPost('enabled');
        $enabled = ($enabled === '1' || $enabled === 'true') ? 1 : 0;

        if ($this->settingModel->updateSettings(['auto_route_enabled' => $enabled])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Auto-route generator ' . ($enabled ? 'enabled' : 'disabled'),
                'enabled' => $enabled
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update setting'
        ]);
    }

    public function allowed()
    {
        $builder = $this->db->table('allowed_routes');
        $allowedRoutes = $builder->orderBy('role', 'ASC')->orderBy('module', 'ASC')->get()->getResultArray();

        $data = [
            'title' => 'Allowed Routes',
            'active' => 'autoroute',
            'allowed_routes' => $allowedRoutes
        ];

        return view('admin/autoroute/allowed', $data);
    }

    public function addAllowed()
    {
        $module = $this->request->getPost('module');
        $role = $this->request->getPost('role');

        if (empty($module) || empty($role)) {
            return redirect()->back()->with('error', 'Module and role are required');
        }

        $builder = $this->db->table('allowed_routes');
        
        // Check if already exists
        $existing = $builder->where('module', $module)->where('role', $role)->get()->getRow();
        if ($existing) {
            return redirect()->back()->with('error', 'This route already exists');
        }

        $builder->insert([
            'module' => $module,
            'role' => $role,
            'enabled' => 1
        ]);

        return redirect()->to('/admin/autoroute/allowed')->with('success', 'Route added successfully');
    }

    public function deleteAllowed($id)
    {
        $builder = $this->db->table('allowed_routes');
        $builder->delete(['id' => $id]);

        return redirect()->to('/admin/autoroute/allowed')->with('success', 'Route deleted successfully');
    }

    public function toggleAllowed($id)
    {
        $builder = $this->db->table('allowed_routes');
        $route = $builder->where('id', $id)->get()->getRow();

        if ($route) {
            $newStatus = $route->enabled ? 0 : 1;
            $builder->where('id', $id)->update(['enabled' => $newStatus]);
            
            return redirect()->to('/admin/autoroute/allowed')->with('success', 'Route status updated');
        }

        return redirect()->to('/admin/autoroute/allowed')->with('error', 'Route not found');
    }

    public function logs()
    {
        $perPage = 50;
        $page = $this->request->getGet('page') ?? 1;

        $logs = $this->logModel
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);

        $data = [
            'title' => 'Auto-Route Activity Logs',
            'active' => 'autoroute',
            'logs' => $logs,
            'pager' => $this->logModel->pager
        ];

        return view('admin/autoroute/logs', $data);
    }

    public function clearLogs()
    {
        try {
            $this->db->table('autoroute_activity_log')->truncate();
            return redirect()->to('/admin/autoroute/logs')->with('success', 'All logs cleared successfully');
        } catch (DatabaseException $e) {
            return redirect()->to('/admin/autoroute/logs')->with('error', 'Failed to clear logs');
        }
    }
}
