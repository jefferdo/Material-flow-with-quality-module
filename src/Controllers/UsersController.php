<?php

namespace App\Controllers;

foreach (glob("models/*.php") as $filename) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $filename;
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/services/token.php";
require $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use Illuminate\Http\Request;
use eftec\bladeone\BladeOne;
use Token;

$views = $_SERVER['DOCUMENT_ROOT'] . '/views';
$cache = $_SERVER['DOCUMENT_ROOT'] . '/cache';

class UsersController
{

    public function index()
    {
        $user = null;
        if (isset($_SESSION['uid']) && isset($_SESSION['key'])) {
            $user = new User($_SESSION('uid'));
            if ($user->session() == 1) { } else {
                $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
                echo $blade->run("login", array(
                    "csrfk" => Token::setcsrfk()
                ));
            }
        } else {
            $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
            echo $blade->run("login", array(
                "csrfk" => Token::setcsrfk()
            ));
        }
    }

    public function login(Request $request)
    {
        if (Token::chkcsrfk($request->csrfk) == 1) {
            $name = $request->uid;
            return "creating new user : $name";
        } else {
            return "error";
        }
    }

    public function searchMatRec(Request $request)
    {
        $name = $request->poid;
        return "creating new user named $name";
    }

    public function matRec()
    {
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run("search", array(
            "title" => "Material Receive",
            "lable" => "Scan PO bardcode",
            "action" => "/matRec",
            "method" => "post",
            "csrfk" => Token::setcsrfk()
        ));
    }

    public function preview()
    {
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run("qa", array(
            "title" => "Material Receive",
            "id" => "1234",
            "stage" => "0",
            "lable" => "Scan PO bardcode",
            "action" => "/matRec",
            "method" => "post",
            "csrfk" => Token::setcsrfk()
        ));
    }

    public function qa(Request $request)
    {
        echo "got it " . $request->id . " ". $request->stage . " " . $request->csrfk;
    }
}
