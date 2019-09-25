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
            default:
                throw new Exception("Invalid Getter", 1);
        }
    }

    public function __construct($id)
    {
        if ($id == null) {
            $this->id = "";
        } else {
            $this->id = $id;
            $this->db = new Database();
            $query = "SELECT id, qty, td as data from poht inner join podt on poht.id = podt.poid where ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($this->data = !null) {
                    $this->id = $results['id'];
                    $this->qty = $results['qty'];
                    $this->data = json_decode($results['td'], true);
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
