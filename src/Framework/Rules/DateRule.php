<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class DateRule implements RuleInterface {
    public function validate(array $data, string $field, array $params): bool{
        return (bool) strtotime($data[$field]);
    }
    public function getMessage(array $data, string $field, array $params): string{
        return "Invalid date format";
    }
}