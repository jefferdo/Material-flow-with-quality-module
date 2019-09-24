<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');

class User
{
    private $id;
    private $priLev;
    private $passwd;
    private $lsl;
    private $name;

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == null) {
            $this->id = "";
        } else {
            $this->id = $id;
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
}
