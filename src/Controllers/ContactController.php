<?php

namespace Marius\BasicForm\Controllers;

use Marius\BasicForm\Core\Database;
use Exception;
use Marius\BasicForm\Core\Validator;

class ContactController
{
    public function index(): void
    {
        require_once __DIR__ . '/../../views/layout.php';
    }

    public function list()
    {
        header('Content-Type: application/json');

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        try {
            $db = Database::getInstance();

            $whereClause = "";
            $params = [];
            if ($search !== '') {
                $whereClause = " WHERE name LIKE :s_name"
                    . " OR email LIKE :s_email"
                    . " OR phone LIKE :s_phone"
                    . " OR message LIKE :s_message";
                $like = "%$search%";
                $params = [
                    's_name'    => $like,
                    's_email'   => $like,
                    's_phone'   => $like,
                    's_message' => $like,
                ];
            }

            // Get total count for filtered results
            $totalRows = $db->fetch("SELECT COUNT(*) as count FROM contacts $whereClause", $params)['count'];
            $totalPages = ceil($totalRows / $perPage);

            // Get paginated and filtered results
            $sql = "SELECT * FROM contacts $whereClause ORDER BY name DESC LIMIT :limit OFFSET :offset";

            // Merge search params with pagination params
            $queryParams = array_merge($params, [
                'limit' => $perPage,
                'offset' => $offset
            ]);

            $entries = $db->fetchAll($sql, $queryParams);

            echo json_encode([
                'status' => 'success',
                'data' => $entries,
                'meta' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_rows' => (int)$totalRows,
                    'has_next' => $page < $totalPages,
                    'has_prev' => $page > 1,
                    'search' => $search
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function store()
    {
        header('Content-Type: application/json');

        $input = $_POST ?: json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            $input = [];
        }

        Validator::run(
            [
                'name' => ['required', 'min:2', 'max:100'],
                'email' => ['required', 'email'],
                'phone' => ['required', 'regex:/^(\+27|27|0)[6-8][0-9]{8}$/', 'min:10'],
                'message' => ['required', 'min:2', 'max:255'],
            ],
            [
                'phone.regex' => "Please provide a valid South African phone number (e.g., 071 123 4567 or +27 71 123 4567)."
            ],
            $input
        );

        try {
            $db = Database::getInstance();
            $sql = "INSERT INTO contacts (name, email, phone, message, created_at) 
                    VALUES (:name, :email, :phone, :message, NOW())";

            $db->query($sql, [
                'name' => htmlspecialchars(strip_tags($input['name'])),
                'email' => filter_var($input['email'], FILTER_SANITIZE_EMAIL),
                'phone' => htmlspecialchars(strip_tags($input['phone'])),
                'message' => htmlspecialchars(strip_tags($input['message']))
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => 'Thank you! Your message has been securely saved.'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'A server error occurred while saving your data.' . $e->getMessage()
            ]);
        }
    }
}