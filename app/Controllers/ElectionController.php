<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Security;
use app\Models\Election;
use app\Core\Database;

class ElectionController extends Controller {

    public function __construct() {
        Security::requireAdmin();
    }

    public function index() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM elections ORDER BY created_at DESC");
        $elections = $stmt->fetchAll();

        $this->view('admin/elections', [
            'title' => 'Manage Elections',
            'elections' => $elections
        ]);
    }

    public function create() {
        $this->view('admin/election_form', [
            'title' => 'Create Election',
            'action' => '/MIME-VOTE/admin/elections/store',
            'election' => null
        ]);
    }

    public function store() {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        // Generate a UUID and a secure receipt seed
        $uuid = bin2hex(random_bytes(16));
        $title = Security::sanitize($_POST['title'] ?? '');
        $description = Security::sanitize($_POST['description'] ?? '');
        $startDate = Security::sanitize($_POST['start_date'] ?? '');
        $endDate = Security::sanitize($_POST['end_date'] ?? '');
        $status = Security::sanitize($_POST['status'] ?? 'draft');

        $db = Database::getInstance();
        $sql = "INSERT INTO elections (uuid, title, description, start_date, end_date, status, created_by) 
                VALUES (:uuid, :title, :description, :start_date, :end_date, :status, :created_by)";
        
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            ':uuid' => $uuid,
            ':title' => $title,
            ':description' => $description,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':status' => $status,
            ':created_by' => $_SESSION['user_id']
        ]);

        if ($success) {
            header("Location: /MIME-VOTE/admin/elections");
            exit;
        }

        echo "Failed to create election";
    }

    public function edit($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM elections WHERE id = :id OR uuid = :uuid");
        $stmt->execute([':id' => $id, ':uuid' => $id]);
        $election = $stmt->fetch();

        if (!$election) {
            die("Election not found.");
        }

        $this->view('admin/election_form', [
            'title' => 'Edit Election',
            'action' => "/MIME-VOTE/admin/elections/update/{$election['id']}",
            'election' => $election
        ]);
    }

    public function update($id) {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        $title = Security::sanitize($_POST['title'] ?? '');
        $description = Security::sanitize($_POST['description'] ?? '');
        $startDate = Security::sanitize($_POST['start_date'] ?? '');
        $endDate = Security::sanitize($_POST['end_date'] ?? '');
        $status = Security::sanitize($_POST['status'] ?? 'draft');

        $db = Database::getInstance();
        $sql = "UPDATE elections 
                SET title = :title, description = :description, start_date = :start_date, end_date = :end_date, status = :status 
                WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':status' => $status,
            ':id' => $id
        ]);

        if ($success) {
            header("Location: /MIME-VOTE/admin/elections");
            exit;
        }

        echo "Failed to update election";
    }

    public function delete($id) {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM elections WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header("Location: /MIME-VOTE/admin/elections");
        exit;
    }
}
