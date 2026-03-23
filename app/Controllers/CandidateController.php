<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Security;
use app\Core\Database;

class CandidateController extends Controller {

    public function __construct() {
        Security::requireAdmin();
    }

    public function index() {
        $db = Database::getInstance();
        $sql = "SELECT c.*, p.title as position_title, e.title as election_title 
                FROM candidates c 
                JOIN positions p ON c.position_id = p.id 
                JOIN elections e ON p.election_id = e.id 
                ORDER BY e.created_at DESC, p.display_order ASC, c.last_name ASC";
        $candidates = $db->query($sql)->fetchAll();

        $this->view('admin/candidates', [
            'title' => 'Manage Candidates',
            'candidates' => $candidates
        ]);
    }

    public function create() {
        $db = Database::getInstance();
        $positions = $db->query("SELECT p.id, p.title as position_title, e.title as election_title 
                                 FROM positions p 
                                 JOIN elections e ON p.election_id = e.id 
                                 WHERE e.status != 'completed' 
                                 ORDER BY e.created_at DESC")->fetchAll();

        $this->view('admin/candidate_form', [
            'title' => 'Register Candidate',
            'action' => '/MIME-VOTE/admin/candidates/store',
            'candidate' => null,
            'positions' => $positions
        ]);
    }

    public function store() {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        $positionId = (int)($_POST['position_id'] ?? 0);
        $firstName = Security::sanitize($_POST['first_name'] ?? '');
        $lastName = Security::sanitize($_POST['last_name'] ?? '');
        $bio = Security::sanitize($_POST['bio'] ?? '');
        $manifesto = Security::sanitize($_POST['manifesto_url'] ?? '');

        // Simple default avatar for now
        $photoUrl = 'default-avatar.png'; 

        $db = Database::getInstance();
        $sql = "INSERT INTO candidates (position_id, first_name, last_name, bio, manifesto_url, photo_url) 
                VALUES (:position_id, :first_name, :last_name, :bio, :manifesto_url, :photo_url)";
        
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            ':position_id' => $positionId,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':bio' => $bio,
            ':manifesto_url' => $manifesto,
            ':photo_url' => $photoUrl
        ]);

        if ($success) {
            header("Location: /MIME-VOTE/admin/candidates");
            exit;
        }
        echo "Failed to create candidate";
    }

    public function edit($id) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM candidates WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $candidate = $stmt->fetch();

        if (!$candidate) {
            die("Candidate not found.");
        }

        $positions = $db->query("SELECT p.id, p.title as position_title, e.title as election_title 
                                 FROM positions p 
                                 JOIN elections e ON p.election_id = e.id 
                                 WHERE e.status != 'completed' 
                                 ORDER BY e.created_at DESC")->fetchAll();

        $this->view('admin/candidate_form', [
            'title' => 'Edit Candidate',
            'action' => "/MIME-VOTE/admin/candidates/update/{$candidate['id']}",
            'candidate' => $candidate,
            'positions' => $positions
        ]);
    }

    public function update($id) {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        $positionId = (int)($_POST['position_id'] ?? 0);
        $firstName = Security::sanitize($_POST['first_name'] ?? '');
        $lastName = Security::sanitize($_POST['last_name'] ?? '');
        $bio = Security::sanitize($_POST['bio'] ?? '');
        $manifesto = Security::sanitize($_POST['manifesto_url'] ?? '');

        $db = Database::getInstance();
        $sql = "UPDATE candidates 
                SET position_id = :position_id, first_name = :first_name, last_name = :last_name, bio = :bio, manifesto_url = :manifesto_url 
                WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            ':position_id' => $positionId,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':bio' => $bio,
            ':manifesto_url' => $manifesto,
            ':id' => $id
        ]);

        if ($success) {
            header("Location: /MIME-VOTE/admin/candidates");
            exit;
        }
        echo "Failed to update candidate";
    }

    public function delete($id) {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM candidates WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header("Location: /MIME-VOTE/admin/candidates");
        exit;
    }
}
