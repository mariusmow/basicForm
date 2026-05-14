<?php

namespace Marius\BasicForm\Core;

class Validator
{
    public static function run(array $rules, array $messages = [], ?array $data = null): void
    {
        try {
            $errors = [];
            $data = $data ?? self::resolveRequestData();

            if (!empty($rules)) {
                foreach ($rules as $field => $field_rules) {
                    $value = $data[$field] ?? '';
                    foreach ($field_rules as $field_rule) {
                        $field_option = null;
                        if (str_contains($field_rule, ':')) {
                            $field_options = explode(':', $field_rule);
                            $field_rule = $field_options[0];
                            $field_option = $field_options[1] ?? null;
                        }

                        switch ($field_rule) {
                            case "min" :
                                if ((int)$field_option == 0) {
                                    throw new \Exception("Missing or invalid MIN rule option for $field");
                                }

                                if (strlen(trim($value)) < $field_option) {
                                    if (isset($messages[$field])) {
                                        $errors[$field][] = $messages[$field];
                                    } else {
                                        $errors[$field][] = ucfirst($field) . ' must be at least ' . $field_option . ' characters. ';
                                    }
                                }
                                break;
                            case "max" :
                                if ((int)$field_option == 0) {
                                    throw new \Exception("Missing or invalid MAX rule option for $field.");
                                }

                                if (strlen(trim($value)) > $field_option) {
                                    if (isset($messages[$field])) {
                                        $errors[$field][] = $messages[$field];
                                    } else {
                                        $errors[$field][] = ucfirst($field) . ' may not exceed ' . $field_option . ' characters. ';
                                    }
                                }
                                break;
                            case "required" :
                                if (empty($value)) {
                                    if (isset($messages[$field])) {
                                        $errors[$field][] = $messages[$field];
                                    } else {
                                        $errors[$field][] = ucfirst($field) . ' is required. ';
                                    }
                                }
                                break;
                            case "email" :
                                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                    if (isset($messages[$field])) {
                                        $errors[$field][] = $messages[$field];
                                    } else {
                                        $errors[$field][] = ucfirst($field) . ' is not a valid email address. ';
                                    }
                                }
                                break;
                            case "regex" :
                                if (empty($field_option)) {
                                    throw new \Exception("Missing or invalid REGEX rule option.");
                                }

                                if (!preg_match($field_option, $value)) {
                                    if (isset($messages[$field])) {
                                        $errors[$field][] = $messages[$field];
                                    } elseif (isset($messages[$field . '.' . $field_rule])) {
                                        $errors[$field][] = $messages[$field . '.' . $field_rule];
                                    } else {
                                        $errors[$field][] = 'Please provide a valid "' . $field . '".';
                                    }
                                }
                                break;
                        }
                    }
                }
            }

            if (!empty($errors)) {
                http_response_code(422); // Unprocessable Entity
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                exit();
            }

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit();
        }
    }

    private static function resolveRequestData(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            if ($raw !== false && $raw !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    return array_merge($_REQUEST, $decoded);
                }
            }
        }

        return $_REQUEST;
    }
}