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

    private $PO;

    private $db;

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == null) {
            $query = "SELECT id from woht ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                $this->id = date('YW') . substr($results['id'], -4, 4);
            }
        } else {
            $this->id = $id;
            $query = "SELECT * from woht where ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                $this->id = $results['id'];
                $this->initDt = $results['initdt'];
                $this->apDt = $results['apdt'];
                $this->PO = new PO($results['poid']);
                $this->size = $results['size'];
                $this->color = $results['color'];
                $this->qty = $results['qty'];
                $this->lcs = $results['lcs'];
                $this->prt = ($results['prt'] == 0 ? false : true);
                $this->emb = ($results['emb'] == 0 ? false : true);
                $this->wsh = ($results['wsh'] == 0 ? false : true);
                $this->sub = ($results['sub'] == 0 ? false : true);
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
}
