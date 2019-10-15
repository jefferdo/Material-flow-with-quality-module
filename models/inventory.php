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


    public function __set($name, $value)
    {
        switch ($name) {
            case 'id':
                $this->id = $value;
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
            $query = "SELECT id from inht ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = "R" + date("YW") . substr($row['id'], -4) + 1;
                }
            } else {
                $this->id = "R" + date("YW") . "0001";
            }
            $this->date = date("Y-m-d H:i:s");
        } else if ($id == null) {
            $this->id = null;
        } else {
            $this->id = $id;
            $query = "SELECT * from woht where id ='" . $this->id . "'";
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
                    $this->po = new PO($row['poid']);
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
        $token = new Token();
        $query = "INSERT INTO inht(id, date, supid, lgth, hgt, wdth, shrk, color, aa, ua, stg) VALUES (<{id: }>, <{date: CURRENT_TIMESTAMP}>, <{supid: }>, <{lgth: }>, <{hgt: }>, <{wdth: }>, <{shrk: }>, <{color: }>, <{aa: }>, <{ua: }>, <{stg: }>)";
        $stat = $this->db->iud($query);
        return $stat;
    }
}
