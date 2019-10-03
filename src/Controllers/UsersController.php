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
                switch ($this->user->priLev) {
                    case "1":
                    case "2":
                    case "3":
                        $this->search($this->user->getPriv());
                        break;
                    case "4":
                        $this->kanban($this->user->getPriv());
                        break;
                    case "5":
                        $this->kanban($this->user->getPriv());
                        break;
                }
            } else {
                $csrfk = Token::setcsrfk();
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
                    header("Location: http://" . $_SERVER['HTTP_HOST']);
                    die();
                } else {
                    $this->error = "Check Usernanme and password";
                    header("Location: http://" . $_SERVER['HTTP_HOST']);
                    die();
                }
            } else {
                $this->error = "Something went wrong, try again";
                header("Location: http://" . $_SERVER['HTTP_HOST']);
                die();
            }
        } catch (Exception $th) {
            $this->error = "Something went wrong, try again";
            header("Location: http://" . $_SERVER['HTTP_HOST']);
            die();
        }
    }

    public function logout()
    {
        $this->user = new User($_SESSION['uid']);
        $this->user->logout();
        header("Location: http://" . $_SERVER['HTTP_HOST']);
        die();
    }

    public function searchMatRec(Request $request)
    {
        $name = $request->poid;
        return "creating new user named $name";
    }


    public function search($prev)
    {
        $title = $prev['title'];
        $lable = $prev["S1"]['lable'];
        $action = "/" . $prev["S1"]['next'];
        $csrfk = Token::setcsrfk();
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

    public function kanban($prev)
    {
        $title = $prev['title'];
        $poset = [];
        $po = new PO(null);
        $results = $po->getlcs($prev['stage'] - 1);
        while ($row = $results->fetch_array()) {
            $log = new alog($row['id'], null);
            $row["date"] = $log->getdate($prev['stage'] - 1);
            array_push($poset, $row);
        }

        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanban", array(
            "title" => $title,
            "body" => $prev['body'],
            "B1" => $poset,
            "csrfk" => $csrfk
        ));
    }

    public function preview()
    {
        return 0;
    }

    public function qa(Request $request)
    {
        if (Token::chkcsrfk($request->csrfk) == 1) {
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
                    if ($po->data != "") {
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
                        $this->error = "Not allowed to process, Old PO";
                        $this->index();
                    }
                } catch (Exception $th) {
                    $this->error = $th->getMessage();
                    $this->index();
                }
            }
        } else {
            $this->error = "Request Timeout";
            $this->index();
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

    public function getPO(Request $request)
    {
        $po = new PO($request->id);
        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanbangetPO", array(
            "title" => $po->id,
            "id" => $po->id,
            "info" => $po->data,
            "stage" => $po->priLev,
            "intitQty" => $po->qty,
            "action" => "makeWO",
            "method" => "post",
            "csrfk" => $csrfk
        ));
    }

    public function makeWO($id, $qty)
    {
        # code...
    }
}
