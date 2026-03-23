<?php
namespace app\Models;
use app\Core\Database;

class Election {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getActiveElections() {
        $sql = "SELECT * FROM elections WHERE status = 'active' ORDER BY start_date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getElectionWithPositions($electionId) {
        $stmt = $this->db->prepare("SELECT * FROM elections WHERE id = :id OR uuid = :uuid");
        $stmt->execute([':id' => $electionId, ':uuid' => $electionId]);
        $election = $stmt->fetch();

        if ($election) {
            $posStmt = $this->db->prepare("SELECT * FROM positions WHERE election_id = :id ORDER BY display_order ASC");
            $posStmt->execute([':id' => $election['id']]);
            $positions = $posStmt->fetchAll();

            foreach ($positions as &$pos) {
                $candStmt = $this->db->prepare("SELECT id, first_name, last_name, photo_url, bio FROM candidates WHERE position_id = :pos_id");
                $candStmt->execute([':pos_id' => $pos['id']]);
                $pos['candidates'] = $candStmt->fetchAll();
            }
            $election['positions'] = $positions;
        }

        return $election;
    }
}
