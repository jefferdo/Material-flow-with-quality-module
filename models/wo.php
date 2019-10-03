<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');

class WO
{
    private $id;
    private $initDt;
    private $apDt;
    private $size;
    private $color;
    private $qty;
    private $lcs;
    private $prt = false;
    private $emb = false;
    private $wsh = false;
    private $sub = false;

    private $po;

    private $db;

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'inttDt':
                return $this->inttDt;
                break;
            case 'apDT':
                return $this->apDT;
                break;
            case 'size':
                return $this->size;
                break;
            case 'color':
                return $this->color;
                break;
            case 'qty':
                return $this->qty;
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
            default:
                throw new Exception("Invalid Getter", 1);
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'inttDt':
                $this->inttDt = $value;
                break;
            case 'apDT':
                $this->apDT = $value;
                break;
            case 'size':
                $this->size = $value;
                break;
            case 'color':
                $this->color = $value;
                break;
            case 'qty':
                $this->qty = $value;
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
            default:
                throw new Exception("Invalid setter", 1);
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == "new") {
            $query = "SELECT id from woht ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                $this->id = $results['id'] + 1;
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

    public function add()
    {
        //add new po for supplier (supplier should be pre registered)
    }

    public function archive()
    {
        //modify detail related to po in case of need
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
