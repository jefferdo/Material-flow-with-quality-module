<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');

class WO
{
    private $id = null;
    private $initdt = null;
    private $apdt = null;
    private $size = null;
    private $color = null;
    private $lcs = null;
    private $prt = 0;
    private $emb = 0;
    private $wsh = 0;
    private $sub = 0;
    private $area = null;
    private $pqty = null;
    private $aqty = null;

    private $po;

    private $db;

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'initdt':
                return $this->initdt;
                break;
            case 'apdt':
                return $this->apdt;
                break;
            case 'size':
                return $this->size;
                break;
            case 'color':
                return $this->color;
                break;
            case 'lcs':
                return $this->lcs;
                break;
            case 'prt':
                return $this->prt;
                break;
            case 'emb':
                return $this->emb;
                break;
            case 'wsh':
                return $this->wsh;
                break;
            case 'area':
                return $this->area;
                break;
            case 'pqty':
                return $this->pqty;
                break;
            case 'aqty':
                return $this->aqty;
                break;
            case 'po':
                return $this->po;
                break;
            case 'user':
                return $this->po->user;
                break;
            case 'cus':
                $cus = json_decode($this->po->data)->Customer;
                return $cus;
                break;
            default:
                throw new Exception("Invalid Getter: " . $name, 1);
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'apdt':
                $this->apdT = $value;
                break;
            case 'size':
                $this->size = $value;
                break;
            case 'color':
                $this->color = $value;
                break;
            case 'lcs':
                $this->lcs = $value;
                break;
            case 'prt':
                $this->prt = $value;
                break;
            case 'emb':
                $this->emb = $value;
                break;
            case 'wsh':
                $this->wsh = $value;
                break;
            case 'area':
                $this->area = $value;
                break;
            case 'pqty':
                $this->pqty = $value;
                break;
            case 'aqty':
                $this->aqty = $value;
                break;
            default:
                throw new Exception("Invalid setter: " . $name, 1);
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == "new") {
            $query = "SELECT id from woht ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = date("YW") . substr($row['id'], -4) + 1;
                    $this->initdt = date("Y-m-d H:i:s");
                    $this->apdt = $this->initdt;
                }
            }
        } else if ($id == null) {
            $this->id = null;
        } else {
            $this->id = $id;
            $query = "SELECT * from woht where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->initdt = $row['initdt'];
                    $this->apdt = $row['apdt'];
                    $this->po = new PO($row['poid']);
                    $this->size = $row['size'];
                    $this->color = $row['color'];
                    $this->pqty = $row['pqty'];
                    $this->lcs = $row['lcs'];
                    $this->prt = $row['prt'];
                    $this->emb = $row['emb'];
                    $this->wsh = $row['wsh'];
                    $this->sub = $row['sub'];
                    if ($this->user->priLev != $this->lcs + 1 and $this->lcs > 3) {
                        throw new Exception('Not allowed to proccess [LCS: ' . $this->lcs . " priLev:" . $this->user->priLev . "]", 0);
                    }
                } else {
                    throw new Exception('Invalid WO ID : 0x02', 0);
                }
            } else {
                throw new Exception('Invalid WO ID : 0x01', 0);
            }
        }
    }

    public function save()
    {
        $this->db = new Database();
        $query = "INSERT INTO woht (id, initdt, apdt, poid, size, color, lcs, prt, emb, wsh, sub, area, pqty, aqty) VALUES ('" . $this->id . "', '" . $this->initdt . "', '" . $this->apdt . "', '" . $this->po->id . "', '" . $this->size . "', '" . $this->color . "', '" . $this->lcs . "', '" . $this->prt . "', '" . $this->emb . "', '" . $this->wsh . "', '" . $this->sub . "', '" . $this->pqty . "', '" . $this->pqty . "', '" . $this->pqty . "') ON DUPLICATE KEY UPDATE initdt = '" . $this->initdt . "', apdt = '" . $this->apdt . "', poid = '" . $this->po->id . "', size = '" . $this->size . "', color = '" . $this->color . "', lcs = '" . $this->lcs . "', prt = '" . $this->prt . "', emb = '" . $this->emb . "', wsh = '" . $this->wsh . "', sub = '" . $this->sub . "', area = '" . $this->pqty . "', pqty = '" . $this->pqty . "', aqty = '" . $this->pqty . "'";
        $stat = $this->db->iud($query);
        if ($stat == 0)
            throw new Exception('Invalid Request at WO', 0);
        else
            return $stat;
    }


    public function getPO($poid)
    {
        $this->po = new PO($poid);
    }


    public function getId()
    {
        return $this->id;
    }

    public function QA($status)
    { }

    public static function getfullqty($id)
    {
        $tqty = 0;
        $this->db = new Database();
        $query = "SELECT SUM(DISTINCT qty) as tqty FROM poht where poid = '" . $id . "'";
        if ($results = $this->db->select($query)) {
            if ($row = $results->fetch_array()) {
                $tqty = $row['id'];
            }
        }
        return $tqty;
    }
    public function getpap($lcs)
    {
        $this->db = new Database();
        $query = "SELECT *  from woht where lcs ='" . $lcs . "' AND initdt = apdt";
        return $this->db->select($query);
    }

    public function getaped($lcs)
    {
        $this->db = new Database();
        $query = "SELECT *  from woht where lcs ='" . ($lcs + 1) . "' AND initdt < apdt";
        return $this->db->select($query);
    }

    public function getcomed($lcs)
    {
        $this->db = new Database();
        $query = "SELECT *  from woht where lcs > '" . $lcs . "' AND initdt < apdt";
        return $this->db->select($query);
    }

    public function accept()
    {
        $this->db = new Database();
        $this->lcs = $this->user->priLev - 1;
        $log = new alog($this->id, null);
        if ($log->checklog($this->lcs + 1) != 1) {
            $query = "update woht set lcs = '" . ($this->lcs + 1) . "' where id = '" . $this->id . "'";
            $this->db->iud($query);
            $query = "select lcs from woht where id = '" . $this->id . "' and lcs = '" . ($this->lcs + 1) . "'";
            if (mysqli_num_rows($this->db->select($query)) > 0) {
                $log = new alog($this->id, "0");
                $log->add();
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
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

    public function ready()
    {
        $this->db = new Database();
        $this->apdt = date("Y-m-d H:i:s");
        $this->lcs = $this->user->priLev - 1;
        $log = new alog($this->id, null);
        if ($log->checklog($this->lcs + 1) != 1) {
            $query = "UPDATE woht set lcs = '" . ($this->lcs + 1) . "', apdt = '" . $this->apdt . "' where id = '" . $this->id . "'";
            $this->db->iud($query);
            $query = "SELECT lcs from woht where id = '" . $this->id . "' and lcs = '" . ($this->lcs + 1) . "'";
            if (mysqli_num_rows($this->db->select($query)) > 0) {
                $log = new alog($this->id, "0");
                $log->add();
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
}
