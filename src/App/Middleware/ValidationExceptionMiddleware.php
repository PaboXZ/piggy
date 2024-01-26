<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface{

    public function process(callable $next){
        try{
            $next();
        }
        catch(ValidationException $e){
            $_SESSION['errors'] = $e->errors;

            $oldFormData = $_POST;
            $excludedFields = ['password', 'confirmPassword'];
            $formattedFormData = array_diff_key(
                $oldFormData,
                array_flip($excludedFields)
            );

            $_SESSION['oldFormData'] = $formattedFormData;

            $referer = $_SERVER['HTTP_REFERER'];
            redirectTo($referer);
        }
    }
}