<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Security;
use app\Core\Database;

class AdminController extends Controller {
    
    public function __construct() {
        // Enforce Admin Access for all methods in this controller
        Security::requireAdmin();
    }

    public function dashboard() {
        $db = Database::getInstance();
        
        // Fetch High-Level Metrics
        $stats = [
            'total_elections' => 0,
            'active_elections' => 0,
            'total_candidates' => 0,
            'total_votes' => 0,
            'total_users' => 0
        ];

        try {
            $stats['total_elections'] = $db->query("SELECT COUNT(*) FROM elections")->fetchColumn();
            $stats['active_elections'] = $db->query("SELECT COUNT(*) FROM elections WHERE start_date <= NOW() AND end_date >= NOW()")->fetchColumn();
            $stats['total_candidates'] = $db->query("SELECT COUNT(*) FROM candidates")->fetchColumn();
            $stats['total_votes'] = $db->query("SELECT COUNT(*) FROM votes")->fetchColumn();
            $stats['total_users'] = $db->query("SELECT COUNT(*) FROM users WHERE role_id = 3")->fetchColumn();
        } catch (\PDOException $e) {
            // Handle quietly or log
        }

        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'userFirstName' => $_SESSION['first_name'] ?? 'Admin'
        ]);
    }
}
