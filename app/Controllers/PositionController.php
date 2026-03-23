<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Security;
use app\Core\Database;

class PositionController extends Controller {

    public function __construct() {
        Security::requireAdmin();
    }

    public function index() {
        $db = Database::getInstance();
        $sql = "SELECT p.*, e.title as election_title 
                FROM positions p 
                JOIN elections e ON p.election_id = e.id 
                ORDER BY e.created_at DESC, p.display_order ASC";
        $positions = $db->query($sql)->fetchAll();

        $this->view('admin/positions', [
            'title' => 'Manage Positions',
            'positions' => $positions
        ]);
    }

    public function create() {
        $db = Database::getInstance();
        $elections = $db->query("SELECT id, title FROM elections ORDER BY created_at DESC")->fetchAll();

        $this->view('admin/position_form', [
            'title' => 'Create Position',
            'action' => '/MIME-VOTE/admin/positions/store',
            'position' => null,
            'elections' => $elections
        ]);
    }

    public function store() {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        $electionId = (int)($_POST['election_id'] ?? 0);
        $title = Security::sanitize($_POST['title'] ?? '');
        $maxVotes = (int)($_POST['max_votes_per_user'] ?? 1);
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        $db = Database::getInstance();
        $sql = "INSERT INTO positions (election_id, title, max_votes_per_user, display_order) 
                VALUES (:election_id, :title, :max_votes_per_user, :display_order)";
        
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            ':election_id' => $electionId,
            ':title' => $title,
            ':max_votes_per_user' => $maxVotes,
            ':display_order' => $displayOrder
        ]);

        if ($success) {
            header("Location: /MIME-VOTE/admin/positions");
            exit;
        }
        echo "Failed to create position";
    }

    public function edit($id) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM positions WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $position = $stmt->fetch();

        if (!$position) {
            die("Position not found.");
        }

        $elections = $db->query("SELECT id, title FROM elections ORDER BY created_at DESC")->fetchAll();

        $this->view('admin/position_form', [
            'title' => 'Edit Position',
            'action' => "/MIME-VOTE/admin/positions/update/{$position['id']}",
            'position' => $position,
            'elections' => $elections
        ]);
    }

    public function update($id) {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        $electionId = (int)($_POST['election_id'] ?? 0);
        $title = Security::sanitize($_POST['title'] ?? '');
        $maxVotes = (int)($_POST['max_votes_per_user'] ?? 1);
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        $db = Database::getInstance();
        $sql = "UPDATE positions 
                SET election_id = :election_id, title = :title, max_votes_per_user = :max_votes_per_user, display_order = :display_order 
                WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            ':election_id' => $electionId,
            ':title' => $title,
            ':max_votes_per_user' => $maxVotes,
            ':display_order' => $displayOrder,
            ':id' => $id
        ]);

        if ($success) {
            header("Location: /MIME-VOTE/admin/positions");
            exit;
        }
        echo "Failed to update position";
    }

    public function delete($id) {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM positions WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header("Location: /MIME-VOTE/admin/positions");
        exit;
    }
}
