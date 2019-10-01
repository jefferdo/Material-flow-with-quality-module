<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');

class PO
{
    private $id;
    private $qty;
    private $data;

    private $customer;

    private $db;

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'qty':
                return $this->qty;
                break;
            case 'data':
                return $this->data;
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
            $query = "SELECT poht.id, poht.qty, podt.td as data from poht inner join podt on poht.id = podt.poid where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    if ($this->data = !null) {
                        $this->id = $row['id'];
                        $this->qty = $row['qty'];
                        $this->data = json_decode($row['td'], true);
                    }
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

    public function getData()
    {
        return $this->data;
    }
}
