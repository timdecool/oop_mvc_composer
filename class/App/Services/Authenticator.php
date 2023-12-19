<?php 
namespace App\Services;
use App\Models\UserManager;

class Authenticator {
    public function __construct() {
        if(!isset($_SESSION)) session_start();
        if(isset($_COOKIE[CONFIG_COOKIE_NAME])) {
            $user = unserialize($_COOKIE[CONFIG_COOKIE_NAME]);
            $this->setSessionData($user);
        }
        if(isset($_POST['cookie-yes']) || isset($_COOKIE[CONFIG_COOKIE_NAME])) {
            $_SESSION['cookie'] = true;
        }
        else if(isset($_POST['cookie-no'])) {
            $_SESSION['cookie'] = false;
        }
    }

    public function login(string $email, string $password) :array {
        $verif = false;
        $errors = [];
        $manager = new UserManager();
        $user = $manager->getOneByField("email",$email);
        if($user) $verif = password_verify($password, $user['password']);
        else $errors[] = 'Email inconnu.';
        if($verif) $this->setSessionData($user);
        else if($user && !$verif) $errors[] = 'Mot de passe incorrect.';
        return ["verif" => $verif, "errors" => $errors];
    }

    public function setSessionData(array $userData): void {
        $_SESSION['user'] = $userData;
    }    

    public function logout(): void {
        unset($_SESSION['user']);
    }

    public function isLogged() {
        $isLogged = false;
        if(isset($_SESSION['user'])) $isLogged = true;
        return $isLogged;
    }

    public static function isGranted($role) {
        $isGranted = isset($_SESSION['user']) && in_array($role,json_decode($_SESSION['user']['roles']));
        return $isGranted;
    }
}