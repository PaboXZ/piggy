<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class DateFormatRule implements RuleInterface{
    public function validate(array $data, string $field, array $params): bool{
        if(empty($params[0]))
            throw new InvalidArgumentException("Date format not specified");

        $parsedDate = date_parse_from_format($params[0], $data[$field]);
        return !($parsedDate['error_count'] + $parsedDate['warning_count']); 
    }
    public function getMessage(array $data, string $field, array $params): string{
        return "Invalid date format";
    }
}