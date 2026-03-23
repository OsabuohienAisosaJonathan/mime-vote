<?php
namespace app\Controllers;
use app\Core\Controller;
use app\Models\Election;
use app\Models\Vote;

class VoterController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    public function dashboard() {
        $electionModel = new Election();
        $activeElections = $electionModel->getActiveElections();

        $data = [
            'title' => 'Voter Dashboard',
            'user' => [
                'name' => $_SESSION['first_name'],
                'uuid' => $_SESSION['uuid']
            ],
            'active_elections' => $activeElections
        ];

        $this->view('dashboard/voter', $data);
    }
}
