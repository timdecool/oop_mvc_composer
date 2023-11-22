<?php
namespace App\Models;

use App\Models\AbstractManager;
use App\Services\Database;
use App\Models\User;

class UserManager extends AbstractManager
{

    public function __construct(){
        self::$db = new Database();
        self::$tableName = 'user';
        self::$obj = new User();
    }

    public function updateUser($name,$email,$roles,$id) {
        $statement = "UPDATE user SET name=?,email=?,roles=? WHERE id =?";
        self::$db->query($statement,[$name,$email,$roles,$id]);
    }
}