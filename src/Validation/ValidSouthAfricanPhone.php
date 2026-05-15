<?php

namespace Marius\BasicForm\Validation;

class ValidSouthAfricanPhone implements Validation
{
    public function validate(string $phone): bool
    {
        $cleanPhone = preg_replace('/[\s\-\(\)]+/', '', $phone);
        $pattern = '/^(\+27|27|0)[6-8][0-9]{8}$/';

        return (bool)preg_match($pattern, $cleanPhone);
    }
}