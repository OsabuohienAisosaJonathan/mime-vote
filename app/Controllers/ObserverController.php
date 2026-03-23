<?php
namespace app\Controllers;
use app\Core\Controller;
use app\Core\Database;

class ObserverController extends Controller {
    
    public function __construct() {
        // Observers are public/anonymous, so no auth check required
    }

    public function index() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT id, uuid, title, start_date, end_date FROM elections WHERE is_public_results = 1 OR status = 'active'");
        $elections = $stmt->fetchAll();

        $this->view('observer/dashboard', [
            'title' => 'Public Observer Network',
            'elections' => $elections
        ]);
    }

    public function apiStats($electionUuid) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT e.title, os.total_eligible, os.total_cast, os.last_vote_cast_at
            FROM elections e
            LEFT JOIN observer_stats os ON e.id = os.election_id
            WHERE e.uuid = :uuid OR e.id = :uuid
        ");
        $stmt->execute([':uuid' => $electionUuid]);
        $stats = $stmt->fetch();

        if ($stats) {
            $this->jsonResponse(['status' => 'success', 'data' => $stats]);
        } else {
            $this->jsonResponse(['status' => 'error', 'message' => 'Not found'], 404);
        }
    }
}
