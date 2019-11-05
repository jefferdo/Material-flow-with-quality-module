<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');

class Roll
{
    private $id;
    private $matid;
    private $date;
    private $supid;

    private $width;
    private $length;
    private $color;
    private $sino;
    private $batchNo;
    private $rollNos;
    private $fabricType;
    private $baleNo;
    private $shade;
    private $noPcs;
    private $GSM;
    private $gw;
    private $nw;

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
            case 'matid':
                $this->matid = $value;
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
            case 'length':
                $this->length = $value;
                break;
            case 'width':
                $this->width = $value;
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
            case "sino":
                $this->sino = $value;
                break;
            case "batchNo":
                $this->batchNo = $value;
                break;
            case "rollNos":
                $this->rollNos = $value;
                break;
            case "fabricType":
                $this->fabricType = $value;
                break;
            case "baleNo":
                $this->baleNo = $value;
                break;
            case "shade":
                $this->shade = $value;
                break;
            case "noPcs":
                $this->noPcs = $value;
                break;
            case "GSM":
                $this->GSM = $value;
                break;
            case "gw":
                $this->gw = $value;
                break;
            case "nw":
                $this->nw = $value;
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
            case 'matid':
                return $this->matid;
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
            case 'length':
                return $this->length;
                break;
            case 'width':
                return $this->width;
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
            case "sino":
                return $this->sino;
                break;
            case "batchNo":
                return $this->batchNo;
                break;
            case "rollNos":
                return $this->rollNos;
                break;
            case "fabricType":
                return $this->fabricType;
                break;
            case "baleNo":
                return $this->baleNo;
                break;
            case "shade":
                return $this->shade;
                break;
            case "noPcs":
                return $this->noPcs;
                break;
            case "GSM":
                return $this->GSM;
                break;
            case "gw":
                return $this->gw;
                break;
            case "nw":
                return $this->nw;
                break;
            default:
                throw new Exception("Invalid Getter: " . $name, 1);
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == "new") {
            $prefix = date("Yz");
            $query = "SELECT id from inht  where id LIKE 'R" . $prefix . "%' ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = "R" . $prefix . str_pad(substr($row['id'], -3) + 1, 3, "0", STR_PAD_LEFT);
                    $this->user = new User(null);
                } else {
                    $this->id = "R" . $prefix . "001";
                    $this->user = new User(null);
                }
            } else {
                $this->id = "R" . $prefix . "001";
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
                    $this->matid = $row['matid'];
                    $this->date = $row['date'];
                    $this->supid = $row['supid'];
                    $this->length = $row['length'];
                    $this->width = $row['width'];
                    $this->shrk = $row['shrk'];
                    $this->color = $row['color'];
                    $this->aa = $row['aa'];
                    $this->ua = $row['ua'];
                    $this->stg = $row['stg'];
                    $this->po = $row['poid'];
                    $this->sino = $row['sino'];
                    $this->batchNo = $row['batchNo'];
                    $this->rollNos = $row['rollNos'];
                    $this->fabricType = $row['fabricType'];
                    $this->baleNo = $row['baleNo'];
                    $this->shade = $row['shade'];
                    $this->noPcs = $row['noPcs'];
                    $this->GSM = $row['GSM'];
                    $this->gw = $row['gw'];
                    $this->nw = $row['nw'];
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
        $query = "INSERT INTO inht (id, date, supid, length, hgt, width, ab) VALUES ('" . $this->id . "', '" . $this->date . "', '" . $this->supid . "', '" . $this->length . "', '" . $this->hgt . "', '" . $this->width . "', '" . $this->user->id . "')";
        $stat = $this->db->iud($query);
        return $stat;
    }

    public static function getTCount()
    {
        $query = "SELECT count(*) as c FROM inht";
        $db = new Database();
        if ($results = $db->select($query)) {
            if ($row = $results->fetch_array()) {
                return $row['c'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
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

    public function getCountTotal()
    {
        $count = 0;
        $this->db = new Database();
        $query = "SELECT COUNT(id) as nor from inht";
        if ($results = $this->db->select($query)) {
            if ($row = $results->fetch_array()) {
                $count = $row['nor'];
            }
        }

        return $count;
    }
}


class waterFall
{

    private $id;
    private $shrk;
    private $date;

    private $po;
    private $rolls;

    private $db;

    private $user;

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'shrk':
                return $this->shrk;
                break;
            case 'poid':
                return $this->po->id;
                break;
            case 'rolls':
                return $this->rolls;
                break;
            case 'date':
                return $this->date;
                break;
            default:
                throw new Exception("Invalid getter: " . $name, 1);
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'shrk':
                $this->shrk = $value;
                break;
            case 'date':
                $this->date = $value;
                break;
            case 'poid':
                $this->po = new PO($value);
                break;
            default:
                throw new Exception("Invalid setter: " . $name, 1);
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == "new") {
            $query = "Select id from wfht where id LIKE 'WF" . date("YW") . "%' ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = "WF" . date("YW") . str_pad(substr($row['id'], -5) + 1, 5, "0", STR_PAD_LEFT);
                    $this->user = new User(null);
                } else {
                    $this->id = "WF" . date("YW") . "00001";
                    $this->user = new User(null);
                }
            } else {
                $this->id = "WF" . date("YW") . "00001";
                $this->user = new User(null);
            }
            $this->date = date("Y-m-d H:i:s");
        } else if ($id == null) {
            $this->id = null;
        } else {
            $this->id = $id;
            $query = "SELECT wfht.*, wfht.date as cdt, umf.name as user from wfht inner join poht on wfht.poid = poht.id inner join umf on wfht.ab = umf.id where wfht.id = '" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->date = $row['cdt'];
                    $this->poid = $row['poid'];
                    $this->shrk = $row['shrk'];
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
        $query = "INSERT INTO wfht(id, shrk, poid, ab) VALUES ('" . $this->id . "', '" . $this->shrk . "', '" . $this->poid . "',  '" . $this->user->id . "')";
        $stat = $this->db->iud($query);
        if ($stat == 0)
            throw new Exception('Invalid Request at WF', 0);
        else
            return $stat;
    }

    public function getSqn()
    {
        $this->db = new Database();
        $query = "SELECT wfht.*, wfdt.* from wfht inner join wfdt on wfht.id = wfdt.wfid where wfdt.wfid = '" . $this->id . "'";
        return $this->db->select($query);
    }

    public function addRoll($roid)
    {
        $this->db = new Database();
        $roll = new Roll($roid);
        $query = "SELECT * from wfdt where wfid = '" . $this->id . "' AND roid = '" . $roid . "'";
        if ($results = $this->db->select($query)) {
            if ($results->fetch_array() == null) {
                $query = "INSERT INTO wfdt (wfid, roid, sqn) VALUES ('" . $this->id . "', '" . $roll->id . "', '" . $this->getLSN() . "')";
                $stat = $this->db->iud($query);
                if ($stat == 0)
                    throw new Exception('Invalid Request at Roll: 0x001' . $query, 0);
                else
                    return $stat;
            } else {
                throw new Exception('Invalid Request at Roll: 0x002' . $query, 0);
            }
        } else {
            throw new Exception('Invalid Request at Roll: 0x003' . $query, 0);
        }
    }

    public function getLSN()
    {
        $query = "Select sqn from wfdt where wfid = '" . $this->id . "' ORDER BY sqn desc LIMIT 1";
        if ($results = $this->db->select($query)) {
            if ($row = $results->fetch_array()) {
                return ($row['sqn'] + 1);
            } else {
                return "1";
            }
        } else {
            return "1";
        }
    }
}
