<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class LengthMaxRule implements RuleInterface{

    public function validate(array $data, string $field, array $params): bool {

        if(empty($params[0]))
            throw new InvalidArgumentException("Maximum length not specified");

        return strlen($data[$field]) <= (int) $params[0];
    }

    public function getMessage(array $data, string $field, array $params): string {
        return "Exceeded max length value (".(int) $params[0].")";
    }
}