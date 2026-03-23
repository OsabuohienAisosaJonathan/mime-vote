<?php
namespace app\Core;

class Security {
    
    public static function init() {
        // Secure Session Configuration
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 0); // Set to 1 in production with HTTPS
            ini_set('session.use_strict_mode', 1);
            session_start();
        }
        self::generateCSRF();
    }

    public static function generateCSRF() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function checkCSRF($token) {
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            die("CSRF Token Validation Failed.");
        }
    }

    public static function requireAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: /MIME-VOTE/login");
            exit();
        }
        if (!isset($_SESSION['role_id']) || !in_array((int)$_SESSION['role_id'], [1, 2], true)) {
            http_response_code(403);
            die("403 Forbidden: Administrator Access Required.");
        }
    }

    // XSS Mitigation
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    // AES-256 Encryption for Votes
    public static function encryptVote($candidateId) {
        $config = require CONFIG_DIR . '/app.php';
        $key = $config['encryption_key'];
        
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        $encrypted = openssl_encrypt($candidateId, 'aes-256-cbc', $key, 0, $iv);
        // Store both IV and Encrypted payload separated by ::
        return base64_encode($iv . '::' . $encrypted);
    }

    public static function decryptVote($encryptedPayload) {
        $config = require CONFIG_DIR . '/app.php';
        $key = $config['encryption_key'];
        
        $payload = base64_decode($encryptedPayload);
        list($iv, $encrypted) = explode('::', $payload, 2);
        
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
    
    // Cryptographic Hash for Public Logs/Receipts
    public static function generateVoteHash($encryptedVoteData, $electionId) {
        $config = require CONFIG_DIR . '/app.php';
        $salt = $config['hash_salt'];
        $timestamp = time();
        
        // SHA-256 Hash
        return hash('sha256', $encryptedVoteData . $electionId . $salt . $timestamp);
    }
}
