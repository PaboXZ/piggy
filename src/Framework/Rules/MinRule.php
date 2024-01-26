<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class MinRule implements RuleInterface {
    public function validate(array $data, string $field, array $params): bool {
        if(empty($params[0]))
            throw new InvalidArgumentException("Too few arguments passed to validate function - " . self::class);

        return $data[$field] >= (int) $params[0];
    }
    public function getMessage(array $data, string $field, array $params): string {
        return "Must be at least {$params[0]}";
    }
}