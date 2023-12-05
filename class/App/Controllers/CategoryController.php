<?php

namespace App\Controllers;

use App\Controllers\Controller;

use App\Models\Category;
use App\Models\CategoryManager;
use App\Services\Authenticator;

class CategoryController extends Controller
{

    public function __construct()
    {
        if (!Authenticator::isGranted("ROLE_ADMIN")) header("Location:?page=login");
    }

    public function index()
    {
        $manager = new CategoryManager();
        $categories = $manager->getAll();
        $this->render('./views/template_category.phtml', [
            'categories' => $categories
        ]);
    }

    public function new()
    {
        // On anticipe d'éventuelles erreurs en créant un tableau
        $errors = [];
        if (isset($_POST['submit'])) {
            $category = new Category();
            $name = strip_tags(trim($_POST['name']));
            $category
                ->setName($name)
                ->setSlug(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))));

            $errors = $category->validate();
            if(empty($errors)) {
                $catArray = $category->toArray();
                $manager = new CategoryManager();
                $manager->insert($catArray);
                header('Location:?page=category');
            }
        }

        $this->render('./views/template_category_new.phtml', [
            'errors' => $errors
        ]);
    }

    public function edit()
    {
        $errors = [];
        $id = $_GET['id'];
        $manager = new CategoryManager();
        $category = $manager->getOneById($_GET['id']);

        if (isset($_POST['submit'])) {
            $newCat = new Category();
            $name = strip_tags(trim($_POST['name']));
            $newCat
                ->setName($name)
                ->setSlug(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))));
            
            $errors = $newCat->validate();
            if(empty($errors)) {
                $catArray = $newCat->toArray();
                $catArray[] = $id;
                $manager->update($catArray);
                header('Location:?page=category');
            }

        }

        $this->render('./views/template_category_edit.phtml', [
            'errors' => $errors, 'category' => $category
        ]);
    }

    public function delete()
    {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $manager = new CategoryManager();
            if ($manager->delete($id)) {
                header("Location:?page=category");
            }
        }
    }
}
