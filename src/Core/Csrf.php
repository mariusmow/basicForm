<?php

namespace Marius\BasicForm\Core;

class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function verify(string $candidate): bool
    {
        $expected = $_SESSION['csrf_token'] ?? '';
        if ($expected === '' || $candidate === '') {
            return false;
        }

        return hash_equals($expected, $candidate);
    }
}
