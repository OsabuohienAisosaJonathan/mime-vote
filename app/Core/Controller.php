<?php
namespace app\Core;

class Controller {
    protected function view($view, $data = []) {
        // Make extracting variables secure (though typical in MVC)
        extract($data);
        
        $viewFile = APP_DIR . '/Views/' . $view . '.php';
        $layoutFile = APP_DIR . '/Views/layouts/main.php';

        if (file_exists($viewFile)) {
            // Buffer the view content
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();

            // Load the layout and inject the view content
            if (file_exists($layoutFile)) {
                require_once $layoutFile;
            } else {
                echo $content;
            }
        } else {
            die("View $view not found!");
        }
    }

    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    protected function redirect($url) {
        $baseUrl = require CONFIG_DIR . '/app.php';
        header("Location: " . $baseUrl['base_url'] . $url);
        exit();
    }
}
