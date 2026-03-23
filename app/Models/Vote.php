<?php
namespace app\Models;
use app\Core\Database;
use app\Core\Security;

class Vote {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function hasUserVoted($electionId, $userId) {
        $stmt = $this->db->prepare("SELECT has_voted FROM election_voters WHERE election_id = :e_id AND user_id = :u_id");
        $stmt->execute([':e_id' => $electionId, ':u_id' => $userId]);
        $record = $stmt->fetch();
        return $record && $record['has_voted'] == 1;
    }

    public function castVote($electionId, $userId, $votesData) {
        // $votesData = [ position_id => candidate_id ]
        
        try {
            $this->db->beginTransaction();

            // 1. Ensure eligibility and mark as voted (Identity Layer)
            $stmt = $this->db->prepare("SELECT has_voted FROM election_voters WHERE election_id = :e_id AND user_id = :u_id FOR UPDATE");
            $stmt->execute([':e_id' => $electionId, ':u_id' => $userId]);
            $voter = $stmt->fetch();

            if ($voter && $voter['has_voted'] == 1) {
                throw new \Exception("You have already cast your ballot. Multiple voting is cryptographically prohibited.");
            } elseif (!$voter) {
                // Auto-enlist upon voting for open elections
                $insertSql = "INSERT INTO election_voters (election_id, user_id, has_voted) VALUES (:e_id, :u_id, 1)";
                $this->db->prepare($insertSql)->execute([':e_id' => $electionId, ':u_id' => $userId]);
            } else {
                // Update pre-registered voter
                $updateSql = "UPDATE election_voters SET has_voted = 1 WHERE election_id = :e_id AND user_id = :u_id";
                $this->db->prepare($updateSql)->execute([':e_id' => $electionId, ':u_id' => $userId]);
            }

            $receiptCodes = [];

            // 2. Insert Anonymized Encrypted Votes (Anonymity Layer)
            $voteSql = "INSERT INTO votes (election_id, position_id, encrypted_candidate_data, vote_hash) 
                        VALUES (:e_id, :p_id, :enc_data, :v_hash)";
            $vStmt = $this->db->prepare($voteSql);

            $receiptSql = "INSERT INTO receipts (vote_hash, receipt_code, election_id) VALUES (:v_hash, :code, :e_id)";
            $rStmt = $this->db->prepare($receiptSql);

            foreach ($votesData as $positionId => $candidateId) {
                $encryptedData = Security::encryptVote($candidateId);
                $voteHash = Security::generateVoteHash($encryptedData, $electionId);
                
                $vStmt->execute([
                    ':e_id' => $electionId,
                    ':p_id' => $positionId,
                    ':enc_data' => $encryptedData,
                    ':v_hash' => $voteHash
                ]);

                // 3. Generate Receipt
                $receiptCode = "UTEVS-" . strtoupper(substr(uniqid(), -5)) . "-" . substr($voteHash, 0, 8);
                $rStmt->execute([
                    ':v_hash' => $voteHash,
                    ':code' => $receiptCode,
                    ':e_id' => $electionId
                ]);

                $receiptCodes[] = $receiptCode;
            }

            // 4. Update real-time Observer stats
            $obsSql = "INSERT INTO observer_stats (election_id, total_cast, last_vote_cast_at) 
                       VALUES (:e_id, 1, NOW()) 
                       ON DUPLICATE KEY UPDATE total_cast = total_cast + 1, last_vote_cast_at = NOW()";
            $this->db->prepare($obsSql)->execute([':e_id' => $electionId]);

            $this->db->commit();
            return $receiptCodes;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Vote Transaction Failed: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
