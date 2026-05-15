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
                                    throw new \Exception(ucfirst($field) . " missing or invalid MIN rule option for $field");
                                }

                                if (strlen(trim($value)) < $field_option) {
                                    $errors[$field][] = self::setMessage($messages, $field, $field_rule, ucfirst($field) . " must be at least $field_option  characters.");
                                }
                                break;
                            case "max" :
                                if ((int)$field_option == 0) {
                                    throw new \Exception(ucfirst($field) . " missing or invalid MAX rule option for $field.");
                                }

                                if (strlen(trim($value)) > $field_option) {
                                    $errors[$field][] = self::setMessage($messages, $field, $field_rule, ucfirst($field) . " may not exceed $field_option characters.");
                                }
                                break;
                            case "required" :
                                if (empty($value)) {
                                    $errors[$field][] = self::setMessage($messages, $field, $field_rule, ucfirst($field) . " is required.");
                                }
                                break;
                            case "email" :
                                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                    $errors[$field][] = self::setMessage($messages, $field, $field_rule, ucfirst($field) . " is not a valid email address. ");
                                }
                                break;
                            case "fn" :
                                if (empty($field_option)) {
                                    throw new \Exception(ucfirst($field) . " missing or invalid function rule option.");
                                }

                                if (class_exists($field_option)) {
                                    $customRule = new $field_option();
                                    if (method_exists($field_option, 'validate')) {
                                        $isValid = $customRule->validate($value);
                                    } else {
                                        throw new \Exception(ucfirst($field) . " is not a valid function.");
                                    }
                                } else {
                                    throw new \Exception(ucfirst($field) . " is not a valid function for custom rule.");
                                }

                                if (!$isValid) {
                                    $errors[$field][] = self::setMessage($messages, $field, $field_rule, 'Please provide a valid "' . $field . '".');
                                }
                                break;
                        }
                    }
                }
            }

            if (!empty($errors)) {
                http_response_code(422);
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

    private static function setMessage(array $messages, string $field, string $field_rule, string $overwrite): string
    {
        if (isset($messages[$field])) {
            return $messages[$field];
        } elseif (isset($messages[$field . "." . $field_rule])) {
            return $messages[$field . "." . $field_rule];
        } else {
            return $overwrite;
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