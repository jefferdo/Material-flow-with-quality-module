<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');

class PO
{
    private $id;
    private $qty;
    private $data;

    private $customer;
    private $user;
    private $lcs;

    private $db;

    private $rolls = [];

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'qty':
                return $this->qty;
                break;
            case 'lcs':
                return $this->lcs;
                break;
            case 'data':
                return $this->data;
            case 'user':
                return $this->user;
                break;
        }
    }

    public function __construct($id)
    {
        if ($id == null) {
            $this->id = "";
        } else {
            $this->id = $id;
            $this->db = new Database();
            $query = "SELECT poht.id, poht.qty, poht.lcs, podt.td as data from poht inner join podt on poht.id = podt.poid where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->qty = $row['qty'];
                    $this->lcs = $row['lcs'];
                    $this->data = $row['data'];
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
        $query = "SELECT poht.id, poht.qty, poht.lcs, podt.td as data from poht inner join podt on poht.id = podt.poid where lcs ='" . $lcs . "'";
        return $this->db->select($query);
    }

    public function accept()
    {
        $this->db = new Database();
        $lcs = $this->user->priLev;
        $log = new alog($this->id, null);
        if ($log->checklog($lcs) != 1) {
            $query = "update poht set lcs = '" . $lcs . "' where id = '" . $this->id . "'";
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

    public function addMat($h, $w, $l)
    {
        $roll = new Roll("new");
        $roll->hgt = $h;
        $roll->wdth = $w;
        $roll->lgth = $l;
        $roll->save();
    }
}
