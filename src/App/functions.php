<?php

declare(strict_types=1);

use Framework\Http;

function dd(mixed $item)
{
    echo '<pre>';
    var_dump($item);
    echo '</pre>';
    die();
}
function redirectTo(string $path){
    header("Location: {$path}");
    http_response_code(Http::REDIRECT_STATUS_CODE);
    exit;
}
function e(mixed $value): string
{
  return htmlspecialchars((string) $value);
}