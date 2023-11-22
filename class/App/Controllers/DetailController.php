<?php
namespace App\Controllers;
use App\Controllers\Controller;
use App\Services\Authenticator;

class DetailController extends Controller {
    public function __construct() {
        if (!Authenticator::isGranted("ROLE_MEMBER")) header("Location:?page=login");
    }

    public function index() {
        $this->render('./views/template_detail.phtml',[
        ]);

    }
}
