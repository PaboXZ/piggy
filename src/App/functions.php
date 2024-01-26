<?php

declare(strict_types=1);

function dd(mixed $item)
{
    echo '<pre>';
    var_dump($item);
    echo '</pre>';
    die();
}
function redirectTo(string $path){
    header("Location: {$path}");
    http_response_code(302);
    exit;
}
function e(mixed $value): string
{
  return htmlspecialchars((string) $value);
}