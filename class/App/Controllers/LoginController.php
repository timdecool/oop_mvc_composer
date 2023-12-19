<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Authenticator;
use \ReCaptcha\ReCaptcha;

class LoginController extends Controller
{
    public function index()
    {
        $errors = [];
        if (isset($_POST['login'])) {
            // C'est le ReCaptcha
            $recaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

            if (!$resp->isSuccess()) {
                $errors[] = ["Veuillez ne pas être un robot."];
            }
            // Fin ReCaptcha

            $auth = new Authenticator();
            $success = $auth->login(strip_tags($_POST['mail']), strip_tags($_POST['password']));
            if ($success['verif'] && empty($errors)) {
                if(isset($_POST['remember'])) {
                    $cookieData = serialize($_SESSION['user']);
                    setcookie(CONFIG_COOKIE_NAME, $cookieData, time()+3600*24*365);
                }
                header("Location:?page=home");
            }
            else $errors[] = $success['errors'];
        }

        $this->render('./views/template_login.phtml', ['errors' => $errors]);
    }
}
