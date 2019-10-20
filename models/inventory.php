<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');

class Roll
{
    private $id;
    private $date;
    private $supid;
    private $lgth;
    private $hgt;
    private $wdth;
    private $shrk;
    private $color;
    private $aa;
    private $ua;
    private $stg;

    private $po;

    private $storage;

    private $db;

    private $user;


    public function __set($name, $value)
    {
        switch ($name) {
            case 'id':
                $this->id = $value;
                break;
            case 'poid':
                $this->po = new PO($value);
                break;
            case 'date':
                $this->date = $value;
                break;
            case 'supid':
                $this->supid = $value;
                break;
            case 'lgth':
                $this->lgth = $value;
                break;
            case 'hgt':
                $this->hgt = $value;
                break;
            case 'wdth':
                $this->wdth = $value;
                break;
            case 'shrk':
                $this->shrk = $value;
                break;
            case 'color':
                $this->color = $value;
                break;
            case 'aa':
                $this->aa = $value;
                break;
            case 'ua':
                $this->ua = $value;
                break;
            case 'stg':
                $this->stg = $value;
                break;
            case 'userid':
                $this->user = new User($value);
                break;
            default:
                throw new Exception("Invalid Setter: " . $name, 1);
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'po':
                return $this->po;
                break;
            case 'date':
                return $this->date;
                break;
            case 'supid':
                return $this->supid;
                break;
            case 'lgth':
                return $this->lgth;
                break;
            case 'hgt':
                return $this->hgt;
                break;
            case 'wdth':
                return $this->wdth;
                break;
            case 'shrk':
                return $this->shrk;
                break;
            case 'color':
                return $this->color;
                break;
            case 'aa':
                return $this->aa;
                break;
            case 'ua':
                return $this->ua;
                break;
            case 'stg':
                return $this->stg;
                break;
            default:
                throw new Exception("Invalid Getter: " . $name, 1);
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == "new") {
            $query = "Select id from inht  where id LIKE 'R" . date("YW") . "%' ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = "R" . date("YW") . str_pad(substr($row['id'], -5) + 1, 5, "0", STR_PAD_LEFT);
                    $this->user = new User(null);
                } else {
                    $this->id = "R" . date("YW") . "00001";
                    $this->user = new User(null);
                }
            } else {
                $this->id = "R" . date("YW") . "00001";
                $this->user = new User(null);
            }
            $this->date = date("Y-m-d H:i:s");
        } else if ($id == null) {
            $this->id = null;
        } else {
            $this->id = $id;
            $query = "SELECT * from inht INNER JOIN indt on inht.id = indt.roid where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->date = $row['date'];
                    $this->supid = $row['supid'];
                    $this->lgth = $row['lgth'];
                    $this->hgt = $row['hgt'];
                    $this->wdth = $row['wdth'];
                    $this->shrk = $row['shrk'];
                    $this->color = $row['color'];
                    $this->aa = $row['aa'];
                    $this->ua = $row['ua'];
                    $this->stg = $row['stg'];
                    $this->po = $row['poid'];
                } else {
                    throw new Exception('Invalid Roll ID : 0x02', 0);
                }
            } else {
                throw new Exception('Invalid Roll ID : 0x01', 0);
            }
        }
    }

    public function save()
    {
        $this->db = new Database();
        $query = "INSERT INTO inht (id, date, supid, lgth, hgt, wdth, ab) VALUES ('" . $this->id . "', '" . $this->date . "', '" . $this->supid . "', '" . $this->lgth . "', '" . $this->hgt . "', '" . $this->wdth . "', '" . $this->user->id . "')";
        $stat = $this->db->iud($query);
        return $stat;
    }

    public function delete()
    {
        $this->db = new Database();
        $query = "DELETE FROM inht where id = '" . $this->id . "'";
        $stat = $this->db->iud($query);
        return $stat;
    }

    public function setShrink($shrk)
    {
        # code...
    }

    public function assignPO($poid)
    {
        $this->poid = $poid;
        $query = "INSERT INTO indt (roid, poid) VALUES ('" . $this->id . "', '" . $this->po->id . "')";
        $stat = $this->db->iud($query);
        return $stat;
    }
}


class waterFall
{

    private $id;
    private $shrk;
    private $poid;

    private $rolls;

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == "new") {
            $query = "Select id from wfht where id LIKE 'R" . date("YW") . "%' ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = "W" . date("YW") . str_pad(substr($row['id'], -5) + 1, 5, "0", STR_PAD_LEFT);
                    $this->user = new User(null);
                } else {
                    $this->id = "W" . date("YW") . "00001";
                    $this->user = new User(null);
                }
            } else {
                $this->id = "W" . date("YW") . "00001";
                $this->user = new User(null);
            }
            $this->date = date("Y-m-d H:i:s");
        } else if ($id == null) {
            $this->id = null;
        } else {
            $this->id = $id;
            $query = "SELECT * from wfht INNER JOIN wfdt on wfht.id = wfdt.wfid inner join inht on wfdt.roid = inht.id where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->date = $row['date'];
                } else {
                    throw new Exception('Invalid Roll ID : 0x02', 0);
                }
            } else {
                throw new Exception('Invalid Roll ID : 0x01', 0);
            }
        }
    }
}
