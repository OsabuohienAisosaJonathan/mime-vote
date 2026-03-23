<?php
namespace app\Controllers;
use app\Core\Controller;

class HomeController extends Controller {
    public function index() {
        $data = [
            'title' => 'UTEVS | Trust Through Transparency'
        ];
        $this->view('home/index', $data);
    }
}
