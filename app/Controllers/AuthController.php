<?php
namespace app\Controllers;
use app\Core\Controller;
use app\Core\Security;
use app\Models\User;

class AuthController extends Controller {
    
    public function loginView() {
        if(isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login', ['title' => 'Secure Login']);
    }

    public function login() {
        Security::checkCSRF($_POST['csrf_token'] ?? '');
        
        $email = Security::sanitize($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Protect against session fixation
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['uuid'] = $user['uuid'];
            $_SESSION['first_name'] = $user['first_name'];

            $this->jsonResponse(['status' => 'success', 'redirect' => '/MIME-VOTE/dashboard']);
        }

        $this->jsonResponse(['status' => 'error', 'message' => 'Invalid credentials.'], 401);
    }
    
    public function registerView() {
        $this->view('auth/register', ['title' => 'Register for UTEVS']);
    }

    public function register() {
        Security::checkCSRF($_POST['csrf_token'] ?? '');
        
        $firstName = Security::sanitize($_POST['first_name'] ?? '');
        $lastName = Security::sanitize($_POST['last_name'] ?? '');
        $email = Security::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if(strlen($password) < 8) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Password must be at least 8 characters.'], 400);
        }

        $userModel = new User();
        
        if ($userModel->findByEmail($email)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Email already registered.'], 400);
        }

        // Generate public secure UUID for auditing
        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        if ($userModel->create($uuid, $firstName, $lastName, $email, $password)) {
            $this->jsonResponse(['status' => 'success', 'redirect' => '/MIME-VOTE/login']);
        }

        $this->jsonResponse(['status' => 'error', 'message' => 'Registration failed.'], 500);
    }

    public function logout() {
        // Unset all session variables
        $_SESSION = [];

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        // Redirect to relative base path instead of absolute configured URL to prevent domain mismatches
        header("Location: /MIME-VOTE/");
        exit();
    }
}
