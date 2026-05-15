<?php

namespace Marius\BasicForm\Core;

class Xss
{
    static function clean(string $value): string {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}