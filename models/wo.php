<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');

class WO
{
    private $id;
    private $initdt;
    private $apdt;
    private $size;
    private $color;
    private $lcs;
    private $prt = false;
    private $emb = false;
    private $wsh = false;
    private $sub = false;
    private $area;
    private $pqty;
    private $aqty;

    private $po;

    private $db;

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'inttDt':
                return $this->inttdt;
                break;
            case 'apDT':
                return $this->apdT;
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
            default:
                throw new Exception("Invalid Getter", 1);
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'apDT':
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
                throw new Exception("Invalid setter: ". $name, 1);
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
                }
            }
        } else if ($id == null) {
            $this->id = null;
        } else {
            $this->id = $id;
            $query = "SELECT * from woht where ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->initDt = $row['initdt'];
                    $this->apDt = $row['apdt'];
                    $this->po = new PO($row['poid']);
                    $this->size = $row['size'];
                    $this->color = $row['color'];
                    $this->qty = $row['qty'];
                    $this->lcs = $row['lcs'];
                    $this->prt = ($row['prt'] == 0 ? false : true);
                    $this->emb = ($row['emb'] == 0 ? false : true);
                    $this->wsh = ($row['wsh'] == 0 ? false : true);
                    $this->sub = ($row['sub'] == 0 ? false : true);
                }
            }
        }
    }

    public function save()
    {
        $this->db = new Database();
        $query = "INSERT INTO umf (id, initdt, apdt, poid, size, color, lcs, prt, emb, wsh, sub, area, pqty, aqty) VALUES ('" . $this->id . "', '" . $this->initdt . "', '" . $this->apdt . "', '" . $this->po->id . "', '" . $this->size . "', '" . $this->color . "', '" . $this->lcs . "', '" . $this->prt . "', '" . $this->emb . "', '" . $this->wsh . "', '" . $this->sub . "', '" . $this->area . "', '" . $this->pqty . "', '" . $this->aqty . "') ON DUPLICATE KEY UPDATE initdt = '" . $this->initdt . "', apdt = '" . $this->apdt . "', poid = '" . $this->po->id . "', size = '" . $this->size . "', color = '" . $this->color . "', lcs = '" . $this->lcs . "', prt = '" . $this->prt . "', emb = '" . $this->emb . "', wsh = '" . $this->wsh . "', sub = '" . $this->sub . "', area = '" . $this->area . "', pqty = '" . $this->pqty . "', aqty = '" . $this->aqty . "'";
        $stat = $this->db->iud($query);
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
}
