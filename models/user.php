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
    private $key;

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
            case 'lsl':
                return $this->lsl;
                break;
            case 'name':
                return $this->name;
                break;
            default:
                throw new Exception("Invalid Getter", 1);
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'id':
                $this->id = $value;
                break;
            case 'priLev':
                $this->priLev = $value;
                break;
            case 'passwd':
                $this->passwd = $value;
                break;
            case 'lsl':
                $this->lsl = $value;
                break;
            case 'name':
                $this->name = $value;
                break;
            default:
                throw new Exception("Invalid setter", 1);
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
            $query = "SELECT * FROM umf where ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($this->data = !null) {
                    $this->id = $results['id'];
                    $this->priLev = $results['priLev'];
                    $this->passwd = $results['passwd'];
                    $this->lsl = $results['lsl'];
                    $this->name = $results['name'];
                    $this->key = $results['key'];
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

    public function login()
    {
        $token = new Token();
        session_start();
        $_SESSION["uid"] = $this->id;
        $this->key = $token->getToken(40);
        if ($this->save() > 0) {
            $_SESSION['key'] = $this->key;
        }
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
