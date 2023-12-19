<?php

namespace App\Controllers;
use App\Controllers\Controller;
use App\Models\User;
use App\Models\UserManager;
use ReCaptcha\ReCaptcha;

class RegisterController extends Controller {
    public function index() {
        $errors = [];
        if(isset($_POST['submit'])) {

            // C'est le ReCaptcha
            $recaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            var_dump($resp->isSuccess());

            if (!$resp->isSuccess()) {
                $errors[] = ["Veuillez ne pas être un robot."];
            }
            // Fin ReCaptcha

            // C'est nos vérifs
            $user = new User();
            $user
            ->setName(strip_tags($_POST['name']))
            ->setEmail(strip_tags($_POST['email']))
            ->setPassword(password_hash($_POST['password'],PASSWORD_DEFAULT))
            ->setRoles('["ROLE_MEMBER"]');
            // Si la méthode validate ne retourne pas d'erreurs on fait l'insert dans la table
            $errors[] = $user->validate();
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