<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');


class Storage
{
    private $id;
    private $lgth;
    private $hgt;
    private $wdth;

    public function __set($name, $value)
    {
        switch ($name) {
            case 'id':
                $this->id = $value;
                break;
            case 'lgth':
                $this->lgth = $value;
                break;
            case 'hgt':
                $this->hgt = $value;
                break;
            case 'wdth':
                $this->wdth = $value;
                break;
            default:
                throw new Exception("Invalid Setter: " . $name, 1);
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'lgth':
                return $this->lgth;
                break;
            case 'hgt':
                return $this->hgt;
                break;
            case 'wdth':
                return $this->wdth;
                break;
            default:
                throw new Exception("Invalid Getter: " . $name, 1);
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == "new") {
            $query = "SELECT id from inht ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'] + 1;
                }
            } else {
                $this->id = "0000001";
            }
        } else if ($id == null) {
            $this->id = null;
        } else {
            $this->id = $id;
            $query = "SELECT * from woht where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->date = $row['date'];
                    $this->supid = $row['supid'];
                    $this->lgth = $row['lgth'];
                    $this->hgt = $row['hgt'];
                    $this->wdth = $row['wdth'];
                    $this->shrk = $row['shrk'];
                    $this->color = $row['color'];
                    $this->aa = $row['aa'];
                    $this->ua = $row['ua'];
                    $this->stg = $row['stg'];
                    $this->po = new PO($row['poid']);
                } else {
                    throw new Exception('Invalid Roll ID : 0x02', 0);
                }
            } else {
                throw new Exception('Invalid Roll ID : 0x01', 0);
            }
        }
    }
}
