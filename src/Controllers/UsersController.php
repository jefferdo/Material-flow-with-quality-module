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
use WO;

$views = $_SERVER['DOCUMENT_ROOT'] . '/views';
$cache = $_SERVER['DOCUMENT_ROOT'] . '/cache';

class UsersController
{
    private $views;
    private $cache;
    private $error;
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
                        $this->showInventory($this->user->getPriv());
                        break;
                    case "2":
                    case "3":
                    case "6":
                    case "7":
                    case "8":
                    case "9":
                        $this->search($this->user->getPriv());
                        break;
                    case "4":
                        $this->kanban($this->user->getPriv());
                        break;
                    case "5":
                        $this->kanban_issue($this->user->getPriv());
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

    public function showInventory($prev)
    {
        $title = $prev['title'];
        $lable = $prev["S1"]['lable'];
        $action = "/" . $prev["S1"]['next'];
        $poset = [];
        $po = new PO(null);
        $results = $po->getlcs($prev['stage'] - 1);
        while ($row = $results->fetch_array()) {
            $log = new alog($row['id'], null);
            $row["date"] = $log->getdate($prev['stage'] - 1);
            $row['style'] = (json_decode($row['data'])->Style);
            $row['product'] = (json_decode($row['data'])->Product);
            $row['cus'] = (json_decode($row['data'])->Customer);
            $row['cus'] = (json_decode($row['data'])->Customer);
            $row['cdt'] = (json_decode($row['data'])->initDate);
            $row['matdt'] = (json_decode($row['data'])->matDate);
            array_push($poset, $row);
        }
        $npo = count($poset, 0);
        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("MR", array(
            "title" => $title,
            "lable" => $lable,
            "action" => $action,
            "method" => "post",
            "error" => $this->error,
            "csrfk" => $csrfk,
            "PO" => $poset,
            "npo" => $npo
        ));
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
        $wosetp = [];
        $woset = [];

        $po = new PO(null);
        $results = $po->getlcs($prev['stage'] - 1);
        while ($row = $results->fetch_array()) {
            $log = new alog($row['id'], null);
            $row["date"] = $log->getdate($prev['stage'] - 1);
            $row['style'] = (json_decode($row['data'])->Style);
            array_push($poset, $row);
        }

        $wo = new WO(null);
        $results = $wo->getpap($prev['stage']);
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['initdt'];
            array_push($wosetp, $row);
        }

        $wo = new WO(null);
        $results = $wo->getaped($prev['stage']);
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['apdt'];
            array_push($woset, $row);
        }

        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanban", array(
            "title" => $title,
            "body" => $prev['body'],
            "B1" => $poset,
            "B2" => $wosetp,
            "B3" => $woset,
            "csrfk" => $csrfk,
            "error" => $this->error
        ));
    }

    public function kanban_issue($prev)
    {
        $title = $prev['title'];
        $wosetp = [];
        $woset = [];
        $wosetc = [];

        $wo = new WO(null);
        $results = $wo->getpap($prev['stage'] - 1);
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['initdt'];
            array_push($wosetp, $row);
        }

        $wo = new WO(null);
        $results = $wo->getaped($prev['stage'] - 1);
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['apdt'];
            array_push($woset, $row);
        }

        $wo = new WO(null);
        $results = $wo->getcomed($prev['stage']);
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['apdt'];
            array_push($wosetc, $row);
        }

        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanban", array(
            "title" => $title,
            "body" => $prev['body'],
            "B1" => $wosetp,
            "B2" => $woset,
            "B3" => $wosetc,
            "csrfk" => $csrfk,
            "error" => $this->error
        ));
    }


    public function preview()
    {
        $title = "Preview";
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("addMat", array(
            "title" => $title
        ));

        /* header("HTTP/1.1 404 Not Found");
        die(); */
    }

    public function qa(Request $request)
    {
        session_start();
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

    public function qa_wo(Request $request)
    {

        session_start();
        if (Token::chkcsrfk($request->csrfk) == 1) {
            $this->user = new User($_SESSION['uid']);
            if ($this->user->session() == 0) {
                $this->index();
            } else {
                $prev = $this->user->getPriv();
                $title = $prev['title'];
                $action = "/" . $prev["S2"]['next'];
                try {
                    $wo = new WO($request->id);
                    $log = new alog($request->id, null);
                    $log->checklog($this->user->priLev);
                    if ($wo->lcs != "0") {
                        $csrfk = Token::setcsrfk();
                        $info = $prev["S2"]["info"];
                        foreach ($info as $key => $value) {
                            $info[$key] = $wo->__get($value);
                        }

                        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                        echo $blade->run("qa", array(
                            "title" => $title,
                            "id" => $wo->id,
                            "info" => $info,
                            "stage" => $prev["stage"],
                            "action" => $action,
                            "method" => "post",
                            "csrfk" => $csrfk
                        ));
                    } else {
                        $this->error = "Not allowed to process, Old WO";
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
        if ($request->stage < 4) {
            $po = new PO($request->id);
            return $po->accept();
        } else {
            $wo = new WO($request->id);
            return $wo->accept();
        }
    }

    public function qaR(Request $request)
    {
        if ($request->stage < 4) {
            $po = new PO($request->id);
            return $po->reject($request->reason);
        } else {
            $wo = new WO($request->id);
            return $wo->reject($request->reason);
        }
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
            "orderQty" => $po->qty,
            "action" => "makeWO",
            "method" => "post",
            "csrfk" => $csrfk
        ));
    }

    public function getWO(Request $request)
    {
        $wo = new WO($request->id);
        $size = $wo->size;
        $color = $wo->color;
        $qty = $wo->pqty;
        $cus = $wo->cus;
        $initdt = $wo->initdt;
        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanbangetWO", array(
            "title" => $wo->id,
            "id" => $wo->id,
            "size" => $size,
            "color" => $color,
            "qty" => $qty,
            "cus" => $cus,
            "initdt" => $initdt,
            "action" => "readyWO",
            "method" => "post",
            "csrfk" => $csrfk
        ));
    }

    public function makeWO(Request $request)
    {
        session_start();
        if (Token::chkcsrfk($request->csrfk) == 1) {

            $this->user = new User($_SESSION['uid']);
            if ($this->user->session() == 0) {
                $this->error = "Request Timeout";
                header("Location: http://" . $_SERVER['HTTP_HOST']);
                die();
            } else {
                if ($request->rqty > $request->oqtyf) {
                    try {
                        $wo = new WO("new");
                        $wo->getPO($request->poid);
                        $wo->size = $request->sizef;
                        $wo->color = $request->colorf;
                        $wo->pqty = $request->rqty;
                        $wo->lcs = $this->user->priLev;

                        if ($wo->save() > 0) {
                            $this->error = "Success";
                            header("Location: http://" . $_SERVER['HTTP_HOST']);
                            die();
                        } else {
                            $this->error = "Request failed";
                            header("Location: http://" . $_SERVER['HTTP_HOST']);
                            die();
                        }
                    } catch (Exception $e) {
                        $this->error = $e->getMessage();
                        header("Location: http://" . $_SERVER['HTTP_HOST']);
                        die();
                    }
                } else {
                    $this->error = "Invalid Request";
                    header("Location: http://" . $_SERVER['HTTP_HOST']);
                    die();
                }
            }
        } else {
            $this->error = "Request Timeout";
            header("Location: http://" . $_SERVER['HTTP_HOST']);
            die();
        }
    }

    public function readyWO(Request $request)
    {
        session_start();
        if (Token::chkcsrfk($request->csrfk) == 1) {

            $this->user = new User($_SESSION['uid']);
            if ($this->user->session() == 0) {
                $this->error = "Request Timeout";
                header("Location: http://" . $_SERVER['HTTP_HOST']);
                die();
            } else {
                try {
                    $wo = new WO($request->id);
                    if ($wo->ready() > 0) {
                        $this->error = "Success";
                        header("Location: http://" . $_SERVER['HTTP_HOST']);
                        die();
                    } else {
                        $this->error = "Request failed";
                        header("Location: http://" . $_SERVER['HTTP_HOST']);
                        die();
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                    exit;
                    header("Location: http://" . $_SERVER['HTTP_HOST']);
                    die();
                }
            }
        } else {
            $this->error = "Request Timeout";
            echo $this->error;
            exit;
            header("Location: http://" . $_SERVER['HTTP_HOST']);
            die();
        }
    }

    public function getBarcode($key)
    {
        return Token::getBarcode($key);
    }
}
