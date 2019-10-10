<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');

class alog
{
    private $log;
    private $user;

    private $db;

    public function __get($name)
    {
        switch ($name) {
            case 'log':
                return $this->log;
                break;
        }
    }

    public function __construct($id, $rNO)
    {
        $this->id = $id;
        if ($rNO !=  null) {
            $this->user = new User(null);
            $this->db =  new Database();
            $this->log = $id . "@" . date("Y-m-d H:i:s") . "@" . $this->user->id . "@" . $this->user->priLev . "@" . $rNO;
        }
    }

    public function add()
    {
        $this->log = $this->db->real_escape_string($this->log);
        $query = "INSERT INTO alt (log) VALUES ('" . $this->log . "')";
        $this->db->iud($query);
        $query = "SELECT * from alt where log = '" . $this->log . "'";
        if (mysqli_num_rows($this->db->select($query)) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function checklog($lcs)
    {
        $query = "SELECT * FROM alt WHERE log LIKE '" . $this->id . "@%@%@" . $lcs . "@%'";
        $this->db = new Database();
        if (mysqli_num_rows($this->db->select($query)) > 0) {
            //throw new Exception('Not allowed to proccess [LCS: ' . $lcs . "]", 0);
        }
    }

    public function getdate($lcs)
    {
        $query = "SELECT * FROM alt WHERE log LIKE '" . $this->id . "@%@%@" . $lcs . "@%'";
        $this->db = new Database();
        if ($results = $this->db->select($query)) {
            if ($row = $results->fetch_array()) {
                $this->log = $row['log'];
            }
        }
        return explode("@", $this->log)[1];
    }

    public function getuser($lcs)
    {
        $query = "SELECT * FROM alt WHERE log LIKE '" . $this->id . "@%@%@" . $lcs . "@%'";
        $this->db = new Database();
        if ($results = $this->db->select($query)) {
            if ($row = $results->fetch_array()) {
                $this->log = $row['log'];
            }
        }
        return explode("@", $this->log)[2];
    }

    public function getreason($lcs)
    {
        $query = "SELECT * FROM alt WHERE log LIKE '" . $this->id . "@%@%@" . $lcs . "@%'";
        $this->db = new Database();
        if ($results = $this->db->select($query)) {
            if ($row = $results->fetch_array()) {
                $this->log = $row['log'];
            }
        }
        return explode("@", $this->log)[3];
    }
}
