<?php

namespace App\Controllers;

foreach (glob("models/*.php") as $filename) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $filename;
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/services/token.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/services/database.php";
require $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');

use alog;
use Database;
use Illuminate\Http\Request;
use eftec\bladeone\BladeOne;
use Exception;
use GatePass;
use PO;
use Roll;
use Token;
use User;
use waterFall;
use WO;

$views = $_SERVER['DOCUMENT_ROOT'] . '/views';
$cache = $_SERVER['DOCUMENT_ROOT'] . '/cache';

class UsersController
{
    private $views;
    private $cache;
    private $error;
    private $user;


    public function __construct()
    {
        /*         $this->user = null;
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->user = new User($_SESSION['uid']);
        $this->user->session(); */ }

    public function index()
    {
        $this->user = null;
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['uid']) && isset($_SESSION['key'])) {
            $this->user = new User($_SESSION['uid']);
            if ($this->user->session() == 1) {
                switch ($this->user->priLev) {
                    case "0":
                        $this->showAdmin($this->user->getPriv());
                        break;
                    case "6":
                    case "9":
                    case "10":
                    case "13":
                    case "14":
                        $this->search($this->user->getPriv());
                        break;
                    case "1":
                        $this->showInventory($this->user->getPriv());
                        break;
                    case "2":
                        $this->showInInventory($this->user->getPriv());
                        break;
                    case "4":
                        $this->kanban($this->user->getPriv());
                        break;
                    case "5":
                        $this->kanban_issue($this->user->getPriv());
                        break;
                    case "7":
                        $this->showCuttingOut($this->user->getPriv());
                        break;
                    case "8":
                        $this->showSuperMarket($this->user->getPriv());
                        break;
                    case "100":
                        $this->showSup($this->user->getPriv());
                        break;
                    case "102":
                        $this->showOverview($this->user->getPriv());
                        break;
                    case "103":
                        $this->showGate($this->user->getPriv());
                        break;
                    case "11":
                        $this->showWashing($this->user->getPriv());
                        break;
                    case "12":
                        $this->showSuperMarketF($this->user->getPriv());
                        break;
                    case "3":
                    default:
                        $this->p404();
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


    public function showAdmin($prev)
    {
        if ($prev['stage'] == 0) {
            $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
            echo $blade->run("adminHome", array(
                "title" => $prev['title'],
                "name" => $this->user->name
            ));
        } else {
            $this->error = "Access Denied";
            $this->index();
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
            $row['style'] = (json_decode($row['data'])->Style);
            $row['product'] = (json_decode($row['data'])->Product);
            $row['cus'] = (json_decode($row['data'])->Customer);
            $row['matdt'] = (json_decode($row['data'])->matDate);
            array_push($poset, $row);
        }
        $npo = count($poset, 0);

        $csrfk = Token::setcsrfk();
        $nor = Roll::getTCount();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("MR", array(
            "title" => $title,
            "lable" => $lable,
            "action" => $action,
            "method" => "post",
            "error" => $this->error,
            "csrfk" => $csrfk,
            "PO" => $poset,
            "npo" => $npo,
            "nor" => $nor
        ));
    }

    public function showInInventory($prev)
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
        echo $blade->run("MI", array(
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

    public function showCuttingOut($prev)
    {
        $title = $prev['title'];
        $lable = $prev["S1"]['lable'];
        $action = "/" . $prev["S1"]['next'];
        $woset = [];
        $wo = new WO(null);
        $results = $wo->getlcs($prev['stage'] - 1);
        $woset = $results;

        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanbanCutOut", array(
            "title" => $title,
            "lable" => $lable,
            "action" => $action,
            "method" => "post",
            "error" => $this->error,
            "csrfk" => $csrfk,
            "WO" => $woset,
        ));
    }

    public function showGate($prev)
    {
        $title = $prev['title'];
        $lable = $prev["S1"]['lable'];
        $action = "/" . $prev["S1"]['next'];
        $woset = [];
        $wo = new WO(null);
        $gp = new GatePass(null);
        $results = $wo->getlcs($prev['stage'] - 1);
        $woset = $results;

        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("GateHome", array(
            "title" => $title,
            "lable" => $lable,
            "action" => $action,
            "method" => "post",
            "error" => $this->error,
            "csrfk" => $csrfk,
            "WO" => $woset,
            "GP" => $gp->getGPs()
        ));
    }

    public function NewGP(Request $request)
    {
        if (Token::chkcsrfk($request->csrfk) == 1) {
            $title = "Create New Gate Pass";
            $gp = new GatePass('new');
            $stat = $gp->addNew();
            if ($stat == 1) {
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/editGP/" . base64_encode($gp->id));
                die();
            } else {
                $this->error = "Something went wrong [Error : 0x0010]. Try Again: " . $stat;
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
                die();
            }
        } else {
            $this->error = "Invalid Request [Error : 0x0009]. Try Again";
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
            die();
        }
    }

    public function printwo(Request $request)
    {
        try {
            $wo = new WO(null);
            return $wo->printlable($request->wono);
        } catch (Exception $th) {
            return "error " . $request->wono . " " . $th;
        }
    }

    public function printPDF($key)
    {
        $gp = new GatePass(base64_decode($key));
        $gp->getPDF();
    }

    public function editGP($key)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['uid']) && isset($_SESSION['key'])) {
            $this->user = new User(null);
            if ($this->user->session() == 1) {
                $gp = new GatePass(base64_decode($key));
                $csrfk = Token::setcsrfk();
                $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                echo $blade->run("CreateGateHome", array(
                    "title" => $title,
                    "csrfk" => $csrfk,
                    "id" => $gp->id,
                    "date" => $gp->date,
                    "ab" => $gp->user->name,
                    "rname" => $gp->name,
                    "destination" => $gp->destination,
                    "status" => $gp->status,
                    "wo" => $gp->getSupOutside(),
                    "AW" => $gp->getUnits()
                ));
            } else {
                $this->error = "Invalid Request [Error : 0x0010]. Try Again";
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
                die();
            }
        } else {
            $this->error = "Invalid Request [Error : 0x0011]. Try Again";
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
            die();
        }
    }

    public function DelGP(Request $request)
    {
        if (Token::chkcsrfk($request->csrfk) == 1) {
            $gp = new GatePass($request->id);
            $stat = $gp->delete();
            if ($stat == 1) {
                return 1;
            } else {
                throw new Exception('Something Went Wrong', 2);
            }
        } else {
            throw new Exception('Something Went Wrong', 3);
        }
    }

    public function addGPUnits(Request $request)
    {
        if (Token::chkcsrfk($request->csrfk) == 1) {
            try {
                $gp = new GatePass($request->gpid);
                return $gp->addUnit($request->unitid);
            } catch (Exception $th) {
                throw new Exception('Something Went Wrong: ' . $th, 3);
            }
        } else {
            throw new Exception('Something Went Wrong', 3);
        }
    }

    public function delGPUnits(Request $request)
    {
        if (Token::chkcsrfk($request->csrfk) == 1) {
            try {
                $gp = new GatePass($request->gpid);
                return $gp->delUnit($request->unitid);
            } catch (Exception $th) {
                throw new Exception('Something Went Wrong: ' . $th, 3);
            }
        } else {
            throw new Exception('Something Went Wrong', 3);
        }
    }

    public function updateGPRND(Request $request)
    {
        if (Token::chkcsrfk($request->csrfk) == 1) {
            try {
                $gp = new GatePass($request->gpid);
                $gp->name = $request->rname;
                $gp->destination = $request->destination;
                return $gp->update();
            } catch (Exception $th) {
                throw new Exception('Something Went Wrong: ' . $th, 3);
            }
        } else {
            throw new Exception('Something Went Wrong', 3);
        }
    }

    public function updateGPStatus(Request $request)
    {
        if (Token::chkcsrfk($request->csrfk) == 1) {
            try {
                $gp = new GatePass($request->gpid);
                $gp->status = $request->status;
                return $gp->update();
            } catch (Exception $th) {
                throw new Exception('Something Went Wrong: ' . $th, 3);
            }
        } else {
            throw new Exception('Something Went Wrong', 3);
        }
    }

    public function secure()
    {
        return Token::setcsrfk();
    }

    public function showWashing($prev)
    {
        $title = $prev['title'];
        $lable = $prev["S1"]['lable'];
        $action = "/" . $prev["S1"]['next'];
        $woset = [];
        $wo = new WO(null);
        $results = $wo->getlcs($prev['stage'] - 1);
        $woset = $results;

        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanbanWashing", array(
            "title" => $title,
            "lable" => $lable,
            "action" => "$action",
            "method" => "post",
            "error" => $this->error,
            "csrfk" => $csrfk,
            "WO" => $woset
        ));
    }

    public function sendForWo(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (true) {
            $this->user = new User($_SESSION['uid']);
            if ($this->user->session() == 0) {
                $this->index();
            } else {
                $prev = $this->user->getPriv();
                $title = $prev['title'];
                $action = "/" . $prev["S2"]['next'];
                try {
                    $wo = new WO($request->id);
                    $dates = $wo->getDates();
                    $pdates  = [];
                    while ($row = $dates->fetch_array()) {
                        $pdates[$row['type']] = $row['date'];
                    }
                    $log = new alog($request->id, null);
                    $log->checklog($this->user->priLev);
                    if ($wo->lcs != "0") {
                        $csrfk = Token::setcsrfk();
                        $info = $prev["S2"]["info"];
                        foreach ($info as $key => $value) {
                            $info[$key] = $wo->__get($value);
                        }
                        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                        echo $blade->run("kanbangetCuttingWO", array(
                            "title" => $title,
                            "id" => $wo->id,
                            "info" => $info,
                            "dates" => $pdates,
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

    public function sendForWa(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (true) {
            $this->user = new User($_SESSION['uid']);
            if ($this->user->session() == 0) {
                $this->index();
            } else {
                $prev = $this->user->getPriv();
                $title = $prev['title'];
                $action = "/" . $prev["S1"]['next'];
                try {
                    $wo = new WO($request->id);
                    $dates = $wo->getDates();
                    $pdates  = [];
                    while ($row = $dates->fetch_array()) {
                        $pdates[$row['type']] = $row['date'];
                    }
                    $log = new alog($request->id, null);
                    $log->checklog($this->user->priLev);
                    if ($wo->lcs != "0") {
                        $csrfk = Token::setcsrfk();
                        $info = $prev["S2"]["info"];
                        foreach ($info as $key => $value) {
                            $info[$key] = $wo->__get($value);
                        }
                        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                        echo $blade->run("kanbangetSendForWa", array(
                            "title" => $title,
                            "id" => $wo->id,
                            "info" => $info,
                            "dates" => $pdates,
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

    public function addDateWo(Request $request)
    {
        $wo = new WO($request->id);
        try {
            $wo->setDate($request->type, $request->date);
        } catch (Exception $th) {
            $this->error = $th->getMessage();
        }
        $this->index();
    }

    public function addDateWa(Request $request)
    {
        $wo = new WO($request->id);

        try {
            $wo->setDate(3, $request->date);
        } catch (Exception $th) {
            $this->error = $th->getMessage();
        }
        $this->index();
    }

    public function showSup($prev)
    {
        $title = $prev['title'];
        $wopen = [];
        $woinp = [];


        $wo = new WO(null);
        $results = $wo->getSupPen();
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['initdt'];
            array_push($wopen, $row);
        }

        $wo = new WO(null);
        $results = $wo->getSupInp();
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['apdt'];
            array_push($woinp, $row);
        }

        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanbanSup", array(
            "title" => $title,
            "body" => $prev['body'],
            "B1" => $wopen,
            "B2" => $woinp,
            "csrfk" => $csrfk,
            "error" => $this->error
        ));
    }

    public function showSuperMarket($prev)
    {
        $title = $prev['title'];
        $woout = [];
        $woloc = [];
        $woin = [];

        $wo = new WO(null);
        $results = $wo->getSupOutside();
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['initdt'];
            array_push($woout, $row);
        }

        $wo = new WO(null);
        $results = $wo->getSupLoc();
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['initdt'];
            array_push($woloc, $row);
        }

        $wo = new WO(null);
        $results = $wo->getSupPen();
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['initdt'];
            array_push($woin, $row);
        }

        $action = "qa_wo";
        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanbanSuperMarket", array(
            "title" => $title,
            "body" => $prev['body'],
            "B1" => $woout,
            "B2" => $woloc,
            "B3" => $woin,
            "csrfk" => $csrfk,
            "error" => $this->error,
            "action" => $action
        ));
    }

    public function showSuperMarketF($prev)
    {
        $title = $prev['title'];
        $wooutwa = [];
        $wofsm = [];
        $wofin = [];

        $wo = new WO(null);
        $results = $wo->getWash();
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['initdt'];
            array_push($wooutwa, $row);
        }

        $wo = new WO(null);
        $results = $wo->getFSM();
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['initdt'];
            array_push($wofsm, $row);
        }

        $wo = new WO(null);
        $results = $wo->getFIN();
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['initdt'];
            array_push($wofin, $row);
        }

        $action = "qa_wo";
        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("kanbanSuperMarketF", array(
            "title" => $title,
            "body" => $prev['body'],
            "B1" => $wooutwa,
            "B2" => $wofsm,
            "B3" => $wofin,
            "csrfk" => $csrfk,
            "error" => $this->error,
            "action" => $action
        ));
    }

    public function showOverview($prev)
    {
        $title = $prev['title'];
        $db = new Database();

        $posatMR = [];
        $posatMRc = 0;

        $query = "select * from inht";
        $posatMR = $db->select($query);
        $posatMRc = mysqli_num_rows($posatMR);

        $rollinfo = [];
        $rollinfoc = 0;

        $query = "SELECT inht.*, indt.poid, umf.name, 0 as status from inht inner join indt on inht.id = indt.roid inner join poht on indt.poid = poht.id inner join umf on inht.ab = umf.id where poht.lcs = 1 AND inht.id NOT IN (select roid from wfdt) union select inht.*, indt.poid,  umf.name, 1 as status from inht inner join indt on inht.id = indt.roid inner join poht on indt.poid = poht.id inner join wfdt on inht.id = wfdt.roid inner join umf on inht.ab = umf.id where poht.lcs = 1;";
        $rollinfo = $db->select($query);
        $rollinfoc = mysqli_num_rows($rollinfo);

        $poctin = [];
        $poctinc = 0;

        $query = "SELECT woht.*, umf.name from woht inner join umf on woht.ab = umf.id where lcs = 6";
        $poctin = $db->select($query);
        $poctinc = mysqli_num_rows($poctin);

        $poswin = [];
        $poswinc = 0;

        $query = "SELECT woht.*, umf.name from woht inner join umf on woht.ab = umf.id where lcs = 9";
        $poswin = $db->select($query);
        $poswinc = mysqli_num_rows($poswin);


        $csrfk = Token::setcsrfk();
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("Overview", array(
            "title" => $title,
            "csrfk" => $csrfk,

            "posatMR" => $posatMR,
            "posatMRc" => $posatMRc,

            "rollinfo" => $rollinfo,
            "rollinfoc" => $rollinfoc,

            "poctin" => $poctin,
            "poctinc" => $poctinc,

            "poswin" => $poswin,
            "poswinc" => $poswinc
        ));
    }

    public function getSW()
    {
        $db = new Database();
        $html = "";
        $query = "SELECT woht.*, umf.name from woht inner join umf on woht.ab = umf.id where lcs = 9";
        if ($results = $db->select($query)) {
            while ($item = $results->fetch_array()) {
                $row = "<tr>" .
                    "<td>" . $item['id'] . "</td>" .
                    "<td>" . $item['poid'] . "</td>" .
                    "<td>" . $item['pqty'] . "</td>" .
                    "<td><span style='font-size:120%' class='badge badge-info badge-pill live'>" . $item['finQty'] . "</span></td>" .
                    "<td>" . $item['apdt'] . "</td>" .
                    "<td style='text-transform:capitalize'>" . $item['name'] . "</td>" .
                    "</tr>";
                $html .= $row;
            }
        }
        return $html;
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
                    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
                    die();
                }
            } else {
                $this->error = "Something went wrong, try again 0x002";
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
                die();
            }
        } catch (Exception $th) {
            $this->error = "Something went wrong, try again 0x001";
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
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
            $row['style'] = (json_decode($row['data'])->Style);
            array_push($wosetp, $row);
        }

        $wo = new WO(null);
        $results = $wo->getaped($prev['stage']);
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['apdt'];
            $row['style'] = (json_decode($row['data'])->Style);
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
            $row['style'] = (json_decode($row['data'])->Style);
            array_push($wosetp, $row);
        }

        $wo = new WO(null);
        $results = $wo->getaped($prev['stage'] - 1);
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['apdt'];
            $row['style'] = (json_decode($row['data'])->Style);
            array_push($woset, $row);
        }

        $wo = new WO(null);
        $results = $wo->getcomed($prev['stage']);
        while ($row = $results->fetch_array()) {
            $row["date"] = $row['apdt'];
            $row['style'] = (json_decode($row['data'])->Style);
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


    public function addNewPO(Request $request)
    {
        $po = new PO("new");
        $po->pono = $request->po['PONO'];
        $po->data = json_encode($request->po);
        $po->qty = $request->po['Qty'];
        return $po->addAsNew();
    }


    public function preview()
    {
        /* $roll = new Roll('new');
        print_r($roll->id);
        exit; */
        $title = "Preview";
        $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
        echo $blade->run("qai", array(
            "title" => $title,
            "nor" => 4567
        ));

        /* header("HTTP/1.1 404 Not Found");
        die(); */
    }

    public function p404()
    {
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run("404");
    }

    public function qa(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
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
        if (!isset($_SESSION)) {
            session_start();
        }
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

    public function qa_items(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (true) {
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
                        echo $blade->run("qai", array(
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

    public function qa_itemsF(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (true) {
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
                        echo $blade->run("qaif", array(
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

    public function qaAF(Request $request)
    {

        $wo = new WO($request->id);
        return $wo->acceptF();
    }

    public function qaAi(Request $request)
    {
        $wo = new WO($request->id);
        return $wo->accepti();
    }

    public function qaAif(Request $request)
    {
        $wo = new WO($request->id);
        return $wo->acceptif();
    }

    public function qaF(Request $request)
    {

        $wo = new WO($request->id);
        return $wo->acceptF();
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
        $style = $wo->style;
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
            "style" => $style,
            "initdt" => $initdt,
            "action" => "readyWO",
            "method" => "post",
            "csrfk" => $csrfk
        ));
    }

    public function makeWO(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
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
                        $this->error = "Somthing Went Wrong: " . $e->getMessage();
                        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
                        die();
                    }
                } else {
                    $this->error = "Invalid Request";
                    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
                    die();
                }
            }
        } else {
            $this->error = "Request Timeout";
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/?error=" . $this->error);
            die();
        }
    }

    public function readyWO(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
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

    public function addMat(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->user = new User($_SESSION['uid']);
        if ($this->user->session() == 0) {
            $this->index();
        } else {
            $prev = $this->user->getPriv();
            $title = $prev['title'];
            $action = "/" . $prev["S2"]['next'];
            try {
                $po = new PO($request->id);
                $rolls = $po->getMat();
                $log = new alog($request->id, null);
                $log->checklog($this->user->priLev);
                if ($po->data != "") {
                    $csrfk = Token::setcsrfk();
                    $info = $prev["S2"]["info"];
                    foreach ($info as $key => $value) {
                        $info[$key] = $po->__get($value);
                    }
                    $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                    echo $blade->run("addMat", array(
                        "title" => $title,
                        "id" => $po->id,
                        "info" => $info,
                        "rolls" => $rolls,
                        "nor" => mysqli_num_rows($rolls),
                        "cus" => json_decode($po->data)->Customer,
                        "cdt" => $po->date,
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
    }


    public function inMat(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->user = new User($_SESSION['uid']);
        if ($this->user->session() == 0) {
            $this->index();
        } else {
            $prev = $this->user->getPriv();
            $title = $prev['title'];
            $action = "/" . $prev["S2"]['next'];
            try {
                $po = new PO($request->id);
                $rolls = $po->getMat();
                $log = new alog($request->id, null);
                $log->checklog($this->user->priLev);
                if ($po->data != "") {
                    $csrfk = Token::setcsrfk();
                    $info = $prev["S2"]["info"];
                    foreach ($info as $key => $value) {
                        $info[$key] = $po->__get($value);
                    }
                    $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                    echo $blade->run("inMat", array(
                        "title" => $title,
                        "id" => $po->id,
                        "info" => $info,
                        "rolls" => $rolls,
                        "nor" => mysqli_num_rows($rolls),
                        "cus" => json_decode($po->data)->Customer,
                        "cdt" => $po->date,
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
    }

    public function addRoll(Request $request)
    {
        $po =  new PO($request->poid);
        return $po->addMat($request->h, $request->w, $request->l);
    }

    public function creWF(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->user = new User($_SESSION['uid']);
        if ($this->user->session() == 0) {
            $this->index();
        } else {
            $prev = $this->user->getPriv();
            try {
                $po = new PO($request->poid);
                $wfs = $po->getWF();
                $log = new alog($request->id, null);
                $log->checklog($this->user->priLev);

                $csrfk = Token::setcsrfk();
                $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                echo $blade->run("creWF", array(
                    "id" => $request->poid,
                    "wfs" => $wfs,
                    "now" => mysqli_num_rows($wfs),
                    "cus" => json_decode($po->data)->Customer,
                    "cdt" => $po->date,
                    "action" => $action,
                    "method" => "post",
                    "csrfk" => $csrfk
                ));
            } catch (Exception $th) {
                $this->error = $th->getMessage();
                $this->index();
            }
        }
    }

    public function addWF(Request $request)
    {
        $wf =  new waterFall("new");
        $wf->poid = $request->poid;
        $wf->shrk = $request->s;
        if ($wf->save() == 1) {
            return $wf->id;
        } else {
            throw new Exception("Error Processing Request", 1);
        }
    }

    public function buildWF(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->user = new User($_SESSION['uid']);
        if ($this->user->session() == 0) {
            $this->index();
        } else {
            $prev = $this->user->getPriv();
            try {
                $wf =  new waterFall($request->id);
                $sqn = $wf->getSqn();
                $log = new alog($request->id, null);
                $log->checklog($this->user->priLev);

                $csrfk = Token::setcsrfk();
                $blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
                echo $blade->run("buildWF", array(
                    "id" => $request->id,
                    'poid' => $wf->poid,
                    'date' => $wf->date,
                    "sqn" => $sqn,
                    "nos" => mysqli_num_rows($sqn),
                    "cus" => json_decode($po->data)->Customer,
                    "cdt" => $po->date,
                    "action" => $action,
                    "method" => "post",
                    "csrfk" => $csrfk
                ));
            } catch (Exception $th) {
                $this->error = $th->getMessage();
                $this->index();
            }
        }
    }

    public function addRollWF(Request $request)
    {
        $wf =  new waterFall($request->wfid);
        if ($wf->addRoll($request->roid) == 1) {
            return $wf->id;
        } else {
            throw new Exception("Error Processing Request", 1);
        }
    }

    public function getBarcode($key)
    {
        return Token::getBarcode($key);
    }
}
