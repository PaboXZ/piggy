<?php

declare(strict_types=1);

namespace App\Services;

use Dotenv\Exception\ValidationException as ExceptionValidationException;
use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService {

    public function __construct(private Database $database){

    }

    public function isEmailTaken(string $email){
        $emailCount = $this->database->query("SELECT COUNT(*) FROM users WHERE email = :email", ['email' => $email])->count();

        if($emailCount){
            throw new ValidationException(['email' => ['Email is already registered']]);
        }

    }

    public function create($formData){
        $this->database->query("INSERT INTO users (email, password, age, country, social_media_url) VALUES (:email, :password, :age, :country, :social)",
        [
           'email' => $formData['email'],
           'password' => password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            'age' => $formData['age'],
            'country' => $formData['country'],
            'social' => $formData['socialMediaURL']
        ]);

        session_regenerate_id();

        $_SESSION['user'] = $this->database->id();
    }

    public function verifyUser(string $password, string $email){
        $db = $this->database->query("SELECT * FROM users WHERE email = :email", ['email' => $email]);

        $userRow = $db->find();

        if(!$userRow || !password_verify($password, $userRow['password']))
            throw new ValidationException(['password' => ['Invalid credentials']]);

        session_regenerate_id();

        $_SESSION['user'] = $userRow['id'];

    }

    public function logout(){
        session_destroy();

        //session_regenerate_id();
        $params = session_get_cookie_params();
        setcookie(
            'PHPSESSID',
            '',
            time() - 3600,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
}