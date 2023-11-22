<?php

namespace App\Controllers;
use App\Controllers\Controller;
use App\Models\User;
use App\Models\UserManager;

class RegisterController extends Controller {
    public function index() {
        $errors = [];

        if(isset($_POST['submit'])) {
            $user = new User();
            $user
            ->setName(strip_tags($_POST['name']))
            ->setEmail(strip_tags($_POST['email']))
            ->setPassword(password_hash($_POST['password'],PASSWORD_DEFAULT))
            ->setRoles('["ROLE_MEMBER"]');
            // Si la méthode validate ne retourne pas d'erreurs on fait l'insert dans la table
            $errors = $user->validate();
            if (empty($errors)){
                $userArray = $user->toArray();
                $userManager = new UserManager();
                $userManager->insert( $userArray );
                header('Location:?page=home');
            }
        }

        $this->render('./views/template_register.phtml', ['errors' => $errors]);
    }
}