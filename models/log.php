<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');

class log
{
    private $log;
    
    private $user;
    private $WO;

    public function __construct($WO, $user, $location)
    {
        if ($id == null) {
            $this->id = "";
        } else {
            $this->id = $id;
            $this->db = new Database();
            $query = "select id, qty, td as data from poht inner join podt on poht.id = podt.poid where ='" . $this->id . "'";
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

    public function addMaterials($matid)
    {
        //find material from db and add to collection
    }
}
