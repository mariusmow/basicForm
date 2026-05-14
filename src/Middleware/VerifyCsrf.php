<?php

namespace Marius\BasicForm\Middleware;

use Marius\BasicForm\Core\Csrf;

class VerifyCsrf
{
    public function handle(): void
    {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN']
            ?? $_POST['_csrf']
            ?? '';

        if (!Csrf::verify((string) $token)) {
            http_response_code(419);
            header('Content-Type: application/json');
            echo json_encode([
                'status'  => 'error',
                'message' => 'CSRF token mismatch.',
            ]);
            exit;
        }
    }
}
