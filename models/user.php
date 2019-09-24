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
            $query = "SELECT * FROM umf where ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($this->data = !null) {
                    $this->id = $results['id'];
                    $this->priLev = $results['priLev'];
                    $this->passwd = $results['passwd'];
                    $this->lsl = $results['lsl'];
                    $this->name = $results['name'];
                }
            }
        }
    }

    public function save()
    {
        $this->db = new Database();
        $token = new Token();
        $query = "INSERT INTO umf (id, priLev, passwd, name, lsl) VALUES ('" . $this->id . "', '" . $this->priLev . "', '" . $token->sh1salt($this->passwd) . "', '" . $this->name . "', '" . $this->lsl . "') ON DUPLICATE KEY UPDATE priLev = '" . $this->priLev . "', passwd ='" . $token->sh1salt($this->passwd) . "', name = '" . $this->name . "', lsl = '" . $this->lsl . "'";
        $stat = $this->db->iud($query);
        return $stat;
    }
}
