<?php

namespace App\Controllers;

foreach (glob("models/*.php") as $filename) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $filename;
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/services/token.php";
require $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');

use alog;
use Illuminate\Http\Request;
use eftec\bladeone\BladeOne;
use Exception;
use PO;
use Token;
use User;

$views = $_SERVER['DOCUMENT_ROOT'] . '/views';
$cache = $_SERVER['DOCUMENT_ROOT'] . '/cache';

class UsersController
{
    private $views;
    private $cache;
    private $error = null;
    private $user;

    public function index()
    {
        $this->user = null;
        session_start();
        if (isset($_SESSION['uid']) && isset($_SESSION['key'])) {
            $this->user = new User($_SESSION['uid']);
            if ($this->user->session() == 1) {
                $this->search($this->user->getPriv());
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
                $this->user = new User($request->uid);
                $this->user->passwdT = $request->passwd;
                if ($this->user->login() == 1) {
                    $this->index();
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

    public function logout()
    {
        $this->user = new User($_SESSION['uid']);
        $this->user->logout();
        $this->index();
    }

    public function searchMatRec(Request $request)
    {
        $name = $request->poid;
        return "creating new user named $name";
    }

    public function matRec()
    {
        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("search", array(
            "title" => "Material Receive",
            "lable" => "Scan PO bardcode",
            "action" => "/matRec",
            "method" => "post",
            "csrfk" => $csrfk
        ));
    }


    public function search($prev)
    {
        $title = $prev['title'];
        $lable = $prev["S1"]['lable'];
        $action = "/" . $prev["S1"]['next'];

        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("search", array(
            "title" => $title,
            "lable" => $lable,
            "action" => $action,
            "method" => "post",
            "error" => $this->error,
            "csrfk" => $csrfk
        ));
    }

    public function preview()
    {
        return 0;
    }

    public function qa(Request $request)
    {
        $this->user = new User($_SESSION['uid']);
        if ($this->user->session() == 0) {
            $this->index();
        } else {
            $prev = $this->user->getPriv();
            $title = $prev['title'];
            $action = "/" . $prev["S2"]['next'];
            try {
                $po = new PO($request->id);
                $log = new alog($request->id, null);
                $log->checklog($this->user->priLev);
                if ($po->data == "") {
                    $csrfk = Token::setcsrfk();
                    $info = $prev["S2"]["info"];
                    foreach ($info as $key => $value) {
                        $info[$key] = $po->__get($value);
                    }
                    $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                    echo $blade->run("qa", array(
                        "title" => $title,
                        "id" => $po->id,
                        "info" => $info,
                        "stage" => "1",
                        "action" => $action,
                        "method" => "post",
                        "csrfk" => $csrfk
                    ));
                } else {
                    $this->error = "Invalid PO ID";
                    $this->index();
                }
            } catch (Exception $th) {
                $this->error = $th->getMessage();
                $this->index();
            }
        }
    }

    public function qaA(Request $request)
    {
        $po = new PO($request->id);
        return $po->accept();
    }

    public function qaR(Request $request)
    {
        $po = new PO($request->id);
        return $po->reject($request->reason);
    }
}
