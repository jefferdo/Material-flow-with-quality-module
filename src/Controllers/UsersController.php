<?php

namespace App\Controllers;

foreach (glob("models/*.php") as $filename) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $filename;
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/services/token.php";
require $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use Illuminate\Http\Request;
use eftec\bladeone\BladeOne;
use Exception;
use Token;
use User;

$views = $_SERVER['DOCUMENT_ROOT'] . '/views';
$cache = $_SERVER['DOCUMENT_ROOT'] . '/cache';

class UsersController
{
    private $views;
    private $cache;
    private $error = null;

    public function index()
    {
        $user = null;
        if (isset($_SESSION['uid']) && isset($_SESSION['key'])) {
            $user = new User($_SESSION['uid']);
            if ($user->session() == 1) { 
                echo "set";
                die();
            } else {
                $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                echo $blade->run("login", array(
                    "csrfk" => $csrfk,
                    "error" => $this->error
                ));
            }
        } else {
            $csrfk = Token::setcsrfk();
            $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
            echo $blade->run("login", array(
                "csrfk" => $csrfk,
                "error" => $this->error
            ));
        }
    }

    public function login(Request $request)
    {
        try {
            if (Token::chkcsrfk($request->csrfk) == 1) {
                $user = new User($request->uid);
                $user->passwdT = $request->passwd;
                if ($user->login() == 1) {
                    echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
                } else {
                    $this->error = "Check Usernanme and password";
                    $this->index();
                }
            } else {
                $this->error = "Something went wrong, try again";
                $this->index();
            }
        } catch (Exception $th) {
            $this->error = "Something went wrong, try again";
            $this->index();
        }
    }

    public function searchMatRec(Request $request)
    {
        $name = $request->poid;
        return "creating new user named $name";
    }

    public function matRec()
    {
        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run("search", array(
            "title" => "Material Receive",
            "lable" => "Scan PO bardcode",
            "action" => "/matRec",
            "method" => "post",
            "csrfk" => $csrfk
        ));
    }

    public function preview()
    {
        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run("qa", array(
            "title" => "Material Receive",
            "id" => "1234",
            "stage" => "0",
            "lable" => "Scan PO bardcode",
            "action" => "/matRec",
            "method" => "post",
            "csrfk" => $csrfk
        ));
    }

    public function qa(Request $request)
    {
        echo "got it " . $request->id . " " . $request->stage . " " . $request->csrfk;
    }
}
