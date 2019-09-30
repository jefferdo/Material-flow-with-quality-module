<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');

class User
{
    private $id;
    private $priLev;
    private $passwd;
    private $passwdT;
    private $lsl;
    private $name;
    private $key;
    private $bc;

    private $db;

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'priLev':
                return $this->priLev;
                break;
            case 'passwd':
                return $this->passwd;
                break;
            case 'passwdT':
                return $this->passwdT;
                break;
            case 'lsl':
                return $this->lsl;
                break;
            case 'name':
                return $this->name;
                break;
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'priLev':
                $this->priLev = $value;
                break;
            case 'passwdT':
                $this->passwdT = Token::sh1salt($value);
                break;
            case 'lsl':
                $this->lsl = $value;
                break;
            case 'name':
                $this->name = $value;
                break;
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == 'new') {
            $query = "SELECT id from umf ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                $this->id = $results['id'] + 1;
            }
        } else {
            $this->id = $id;
            $query = "SELECT * FROM umf where id = '" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->priLev = $row['prilev'];
                    $this->passwd = $row['passwd'];
                    $this->lsl = $row['lsl'];
                    $this->name = $row['name'];
                    $this->key = $row['hkey'];
                    $this->bc = $row['bc'];
                }
            }
        }
    }

    public function save()
    {
        $this->db = new Database();
        $token = new Token();
        $query = "INSERT INTO umf (id, prilev, passwd, name, lsl, bc, hkey) VALUES ('" . $this->id . "', '" . $this->priLev . "', '" . $this->passwd . "', '" . $this->name . "', '" . $this->lsl . "', '" . $this->bc . "', '" . $this->key . "') ON DUPLICATE KEY UPDATE prilev = '" . $this->priLev . "', passwd ='" . $this->passwd . "', name = '" . $this->name . "', lsl = '" . $this->lsl . "', bc = '" . $this->bc . "', hkey = '" . $this->key . "'";
        $stat = $this->db->iud($query);
        return $stat;
    }

    public function login()
    {
        $stat = 0;
        if ($this->passwd == $this->passwdT) {
            $this->lsl = date("Y-m-d H:i:s");
            $token = new Token();
            session_start();
            $_SESSION["uid"] = $this->id;
            $this->key = $token->getToken(40);
            if ($this->save() > 0) {
                $_SESSION['key'] = $this->key;
                $stat = 1;
            } else {
                $stat = 0;
            }
        } else {
            $stat = 0;
        }
        return $stat;
    }

    public function session()
    {
        $stat = 0;
        try {
            if ($this->key != $_SESSION['key']) {
                $this->logout();
            } else {
                $stat = 1;
            }
        } catch (Exception $th) {
            $this->logout();
            $stat = 1;
        }
        return $stat;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }
}
