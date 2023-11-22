<?php
namespace App\Controllers;
use App\Controllers\Controller;
use App\Services\Authenticator;

class LoginController extends Controller {
    public function index() {
        $errors = [];
        if(isset($_POST['login'])) {
            $auth = new Authenticator();
            $success = $auth->login(strip_tags($_POST['mail']),strip_tags($_POST['password']));
            if($success['verif']) header("Location:?page=home");
            else $errors = $success['errors'];
        }

        $this->render('./views/template_login.phtml', ['errors' => $errors]);
    }

}