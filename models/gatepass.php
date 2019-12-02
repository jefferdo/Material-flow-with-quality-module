<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');

class GatePass
{
    private $id;
    private $date;
    private $status = 0;
    private $units;
    private $name;
    private $destination;
    private $user;

    private $apd;

    public function __set($name, $value)
    {
        switch ($name) {
            case 'id':
                $this->id = $value;
                break;
            case 'date':
                $this->date = $value;
                break;
            case 'status':
                $this->status = $value;
                break;
            case 'units':
                $this->units = $value;
                break;
            case 'name':
                $this->name = $value;
                break;
            case 'destination':
                $this->destination = $value;
                break;
            case 'user':
                $this->user = new User($value);
                break;
            case 'apd':
                $this->apd = $value;
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
            case 'date':
                return $this->date;
                break;
            case 'status':
                return $this->status;
                break;
            case 'units':
                return $this->units;
                break;
            case 'name':
                return $this->name;
                break;
            case 'destination':
                return $this->destination;
                break;
            case 'user':
                return $this->user;
                break;
            case 'apd':
                return $this->apd;
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
            $query = "SELECT id from gpht  where id LIKE 'GP" . $prefix . "%' ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = "GP" . $prefix . str_pad(substr($row['id'], -4) + 1, 4, "0", STR_PAD_LEFT);
                    $this->user = null;
                } else {
                    $this->id = "GP" . $prefix . "0001";
                    $this->user = null;
                }
            } else {
                $this->id = "GP" . $prefix . "0001";
                $this->user = null;
            }
            $this->date = date("Y-m-d H:i:s");
        } else {
            $this->id = $id;
            $query = "SELECT * from gpht where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->date = $row['date'];
                    $this->status = $row['status'];
                    $this->user = $row['ab'];
                    $this->apd = $row['apd'];
                    $this->name = $row['name'];
                    $this->destination = $row['destination'];
                } else {
                    throw new Exception('Invalid GP ID', 0);
                }
            }
        }
    }

    public function getUnits()
    {
        $query = "SELECT * from gpdt where gpid ='" . $this->id . "'";
        if ($results = $this->db->select($query)) {
            while ($row = $results->fetch_array()) { }
        } else {
            throw new Exception('Invalid GP ID', 0);
        }
    }
}


class GPUnit
{
    private $gpid;
    private $unitid;
    private $qty = 1;

    public function __set($name, $value)
    {
        switch ($name) {
            case 'gpid':
                $this->gpid = $value;
                break;
            case 'unitid':
                $this->unitid = $value;
                break;
            case 'qty':
                $this->qty = $value;
                break;
            default:
                throw new Exception("Invalid setter: " . $name, 1);
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case 'gpid':
                return $this->gpid;
                break;
            case 'unitid':
                return $this->unitid;
                break;
            case 'qty':
                return $this->qty;
                break;
            default:
                throw new Exception("Invalid getter: " . $name, 1);
        }
    }

    public function __construct($gpid)
    {
        $this->db = new Database();
        if ($id == null) {
            $this->gpid = "";
        } else {
            $this->gpid = $gpid;
            $query = "SELECT * from gpht where gpid ='" . $this->gpid . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->gpid = $row['gpid'];
                    $this->unitid = $row['unitid'];
                    $this->qty = $row['qty'];
                } else {
                    throw new Exception('Invalid GP ID', 0);
                }
            }
        }
    }
}
