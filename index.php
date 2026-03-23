<?php
/**
 * UTEVS - Universal Transparent E-Voting System
 * Entry Point
 */

define('ROOT_DIR', __DIR__);
define('APP_DIR', ROOT_DIR . '/app');
define('CORE_DIR', APP_DIR . '/Core');
define('CONFIG_DIR', ROOT_DIR . '/config');

require_once CONFIG_DIR . '/app.php';
require_once CONFIG_DIR . '/database.php';

// Global PSR-4 Autoloader
spl_autoload_register(function ($class) {
    if (strpos($class, 'app\\') === 0) {
        $file = ROOT_DIR . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Initialize Security (Session securely started, CSRF generated)
app\Core\Security::init();

// Initialize Router
$router = new app\Core\Router();

// Define Routes
// Home/Landing Page
$router->get('/', 'HomeController@index');

// Auth Routes
$router->get('/login', 'AuthController@loginView');
$router->post('/login', 'AuthController@login');
$router->get('/admin/login', 'AuthController@loginView'); // Alias for easier intuitive access
$router->get('/register', 'AuthController@registerView');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Voter Dashboard & Voting
$router->get('/dashboard', 'VoterController@dashboard');
$router->get('/vote/([\w-]+)', 'VotingController@interface');
$router->post('/vote/cast', 'VotingController@cast');
$router->get('/receipt/([\w-]+)', 'VotingController@receipt');

// Admin Routes (Protected via AdminController middleware)
$router->get('/admin/dashboard', 'AdminController@dashboard');

// Election Management
$router->get('/admin/elections', 'ElectionController@index');
$router->get('/admin/elections/create', 'ElectionController@create');
$router->post('/admin/elections/store', 'ElectionController@store');
$router->get('/admin/elections/edit/([\w-]+)', 'ElectionController@edit');
$router->post('/admin/elections/update/([\w-]+)', 'ElectionController@update');
$router->post('/admin/elections/delete/([\w-]+)', 'ElectionController@delete');

// Position Management
$router->get('/admin/positions', 'PositionController@index');
$router->get('/admin/positions/create', 'PositionController@create');
$router->post('/admin/positions/store', 'PositionController@store');
$router->get('/admin/positions/edit/([\w-]+)', 'PositionController@edit');
$router->post('/admin/positions/update/([\w-]+)', 'PositionController@update');
$router->post('/admin/positions/delete/([\w-]+)', 'PositionController@delete');

// Candidate Management
$router->get('/admin/candidates', 'CandidateController@index');
$router->get('/admin/candidates/create', 'CandidateController@create');
$router->post('/admin/candidates/store', 'CandidateController@store');
$router->get('/admin/candidates/edit/([\w-]+)', 'CandidateController@edit');
$router->post('/admin/candidates/update/([\w-]+)', 'CandidateController@update');
$router->post('/admin/candidates/delete/([\w-]+)', 'CandidateController@delete');

// Observer Mode (Public)
$router->get('/observer', 'ObserverController@index');
$router->get('/observer/election/([\w-]+)', 'ObserverController@liveStats');
$router->get('/api/observer/stats/([\w-]+)', 'ObserverController@apiStats');

// Dispatch the request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
