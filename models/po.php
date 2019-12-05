<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');

class PO
{
    private $id;
    private $pono;
    private $qty;
    private $data;
    private $date;

    private $user;
    private $lcs = 0;

    private $db;

    private $max = 300;

    public function __set($name, $value)
    {
        switch ($name) {
            case 'id':
                $this->id = $value;
                break;
            case 'pono':
                $this->pono = $value;
                break;
            case 'qty':
                $this->qty = $value;
                break;
            case 'lcs':
                $this->lcs = $value;
                break;
            case 'data':
                $this->data = $value;
                break;
            case 'date':
                $this->date = $value;
            case 'user':
                $this->user = $value;
                break;
            default:
                throw new Exception("Invalid setter: " . $name, 1);
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'pono':
                return $this->pono;
                break;
            case 'qty':
                return $this->qty;
                break;
            case 'lcs':
                return $this->lcs;
                break;
            case 'data':
                return $this->data;
                break;
            case 'date':
                return $this->date;
            case 'user':
                return $this->user;
                break;
            case 'priLev':
                return $this->user->getPriv();
                break;
            default:
                throw new Exception("Invalid getter: " . $name, 1);
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == null) {
            $this->id = "";
        } else if ($id == "new") {
            $prefix = date("Yz");
            $query = "SELECT id from poht where id LIKE 'PO" . $prefix . "%' ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = "PO" . $prefix . str_pad(substr($row['id'], -4) + 1, 4, "0", STR_PAD_LEFT);
                    $this->user = new User(null);
                } else {
                    $this->id = "PO" . $prefix . "0001";
                    $this->user = new User(null);
                }
            } else {
                $this->id = "PO" . $prefix . "0001";
                $this->user = new User(null);
            }
            $this->date = date("Y-m-d H:i:s");
        } else {
            $this->id = $id;
            $query = "SELECT poht.* , podt.td as data from poht inner join podt on poht.id = podt.poid where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->pono = $row['pono'];
                    $this->qty = $row['qty'];
                    $this->lcs = $row['lcs'];
                    $this->data = $row['data'];
                    $this->date = $row['date'];
                    $this->user = new user(null);
                    if ($this->user->priLev != $this->lcs + 1 and $this->lcs != 3) {
                        throw new Exception('Not allowed to proccess [LCS: ' . $this->lcs . " priLev:" . $this->user->priLev . "]", 0);
                    }
                } else {
                    throw new Exception('Invalid PO ID', 0);
                }
            }
        }
    }

    public function getlcs($lcs)
    {
        $this->db = new Database();
        $query = "SELECT poht.id, poht.pono, poht.qty, poht.date, poht.lcs, podt.td as data from poht inner join podt on poht.id = podt.poid where lcs ='" . $lcs . "'";
        return $this->db->select($query);
    }

    public function accept()
    {
        $this->db = new Database();
        $lcs = $this->user->priLev;
        $nStage = $this->user->getPriv()['nStage'];
        $log = new alog($this->id, null);
        if ($log->checklog($lcs) != 1) {
            $query = "update poht set lcs = '" . ($nStage - 1) . "' where id = '" . $this->id . "'";
            $this->db->iud($query);
            $query = "select lcs from poht where id = '" . $this->id . "'";
            if (mysqli_num_rows($this->db->select($query)) > 0) {
                $log = new alog($this->id, "0");
                $log->add();
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function reject($rNO)
    {
        $log = new alog($this->id, $rNO);
        if ($log->add() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function archive()
    {
        //modify detail related to po in case of need
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($dt)
    {
        $query = "INSERT INTO podt set id = '" . $dt . "'";
        $stat = $this->db->iud($query);
        return $stat;
    }

    public function addMat($roll)
    {
        $roll = new Roll("new");
        if ($roll->save() == 1) {
            if ($roll->assignPO($this->id) == 1)
                return 1;
            else {
                $roll->delete();
                return 0;
            }
        } else return 2;
    }

    public function getMat()
    {
        $this->db = new Database();
        $query = "SELECT inht.*, indt.*, umf.name as user from inht inner join indt on inht.id = indt.roid inner join umf on inht.ab = umf.id where indt.poid = '" . $this->id . "'";
        return $this->db->select($query);
    }

    public function getWF()
    {
        $this->db = new Database();
        $query = "SELECT wfht.*, umf.name as user FROM wfht inner join umf on wfht.ab = umf.id where poid = '" . $this->id . "'";
        return $this->db->select($query);
    }

    public function addAsNew()
    {
        if (!$this->hasExist()) {
            $this->db = new Database();
            $query = "INSERT INTO poht(id, qty, lcs, pono, max) VALUES('" . $this->id . "','" . $this->qty . "','" . $this->lcs . "','" . $this->pono . "', '" . $this->max . "')";
            if ($this->db->iud($query)) {
                $query = "INSERT INTO podt(poid,td) VALUES('" . $this->id . "','" . $this->data . "')";
                if ($this->db->iud($query) == 1) {
                    return 1;
                } else {
                    throw new Exception("Somthing went Wrong: 0x02 : " . $query, 1);
                }
            } else {
                throw new Exception("Somthing went Wrong: 0x01", 1);
            }
        } else {
            return 2;
        }
    }

    public function hasExist()
    {
        if (isset($this->pono)) {
            $this->db = new Database();
            $query = "Select * from poht where pono = '" . $this->pono . "'";
            if (mysqli_num_rows($this->db->select($query)) > 0) {
                $log = new alog($this->id, "0");
                $log->add();
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception("Set PO First", 1);
        }
    }
}
