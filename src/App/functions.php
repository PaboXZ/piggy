<?php

declare(strict_types=1);

function dd(mixed $item)
{
    echo '<pre>';
    var_dump($item);
    echo '</pre>';
    die();
}