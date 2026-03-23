<?php
namespace app\Controllers;
use app\Core\Controller;
use app\Core\Security;
use app\Models\Election;
use app\Models\Vote;

class VotingController extends Controller {
    
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            exit;
        }
    }

    public function interface($electionUuid) {
        $electionModel = new Election();
        $election = $electionModel->getElectionWithPositions($electionUuid);

        if (!$election) {
            die("Election not found.");
        }

        $voteModel = new Vote();
        if ($voteModel->hasUserVoted($election['id'], $_SESSION['user_id'])) {
            die("You have already voted in this election. Thank you for participating.");
        }

        $this->view('voting/interface', [
            'title' => 'Voting Ballot - ' . Security::sanitize($election['title']),
            'election' => $election
        ]);
    }

    public function cast() {
        Security::checkCSRF($_POST['csrf_token'] ?? '');

        $electionId = (int)($_POST['election_id'] ?? 0);
        $votesData = $_POST['votes'] ?? []; // Expected format: [ pos_id => cand_id ]

        if (!$electionId || empty($votesData)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Invalid vote data.'], 400);
        }

        $voteModel = new Vote();

        // Check again to prevent race condition multi-submissions
        if ($voteModel->hasUserVoted($electionId, $_SESSION['user_id'])) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Already voted.'], 403);
        }

        $receipts = $voteModel->castVote($electionId, $_SESSION['user_id'], $votesData);

        if ($receipts) {
            $this->jsonResponse([
                'status' => 'success',
                'message' => 'Your vote has been securely recorded.',
                'receipts' => $receipts
            ]);
        } else {
            $this->jsonResponse(['status' => 'error', 'message' => 'System error recording vote.'], 500);
        }
    }
}
