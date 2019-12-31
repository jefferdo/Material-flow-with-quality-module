<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');

class WO
{
    private $id = null;
    private $initdt = null;
    private $apdt = null;
    private $size = null;
    private $color = null;
    private $lcs = null;
    private $prt = 0;
    private $emb = 0;
    private $wsh = 0;
    private $sub = 0;
    private $area = null;
    private $pqty = null;
    private $aqty = null;
    private $finQty = 0;
    private $fg = 0;

    private $user;

    private $po;

    private $db;

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'initdt':
                return $this->initdt;
                break;
            case 'apdt':
                return $this->apdt;
                break;
            case 'size':
                return $this->size;
                break;
            case 'color':
                return $this->color;
                break;
            case 'lcs':
                return $this->lcs;
                break;
            case 'prt':
                return $this->prt;
                break;
            case 'emb':
                return $this->emb;
                break;
            case 'wsh':
                return $this->wsh;
                break;
            case 'area':
                return $this->area;
                break;
            case 'pqty':
                return $this->pqty;
                break;
            case 'aqty':
                return $this->aqty;
                break;
            case 'po':
                return $this->po;
                break;
            case 'userid':
                return $this->user->id;
                break;
            case 'cus':
                $cus = json_decode($this->po->data)->Customer;
                return $cus;
                break;
            case 'style':
                $cus = json_decode($this->po->data)->Style;
                return $cus;
                break;
            case 'finQty':
                return $this->finQty;
                break;
            case 'fg':
                return $this->fg;
                break;
            default:
                throw new Exception("Invalid Getter: " . $name, 1);
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'apdt':
                $this->apdT = $value;
                break;
            case 'size':
                $this->size = $value;
                break;
            case 'color':
                $this->color = $value;
                break;
            case 'lcs':
                $this->lcs = $value;
                break;
            case 'prt':
                $this->prt = $value;
                break;
            case 'emb':
                $this->emb = $value;
                break;
            case 'wsh':
                $this->wsh = $value;
                break;
            case 'area':
                $this->area = $value;
                break;
            case 'pqty':
                $this->pqty = $value;
                break;
            case 'aqty':
                $this->aqty = $value;
                break;
            case 'userid':
                $this->user = new User($value);
                break;
            case 'finQty':
                $this->finQty = $value;
                break;
            case 'fg':
                $this->fg = $value;
                break;
            default:
                throw new Exception("Invalid setter: " . $name, 1);
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == "new") {
            $prefix = date("Yz");
            $this->initdt = date("Y-m-d H:i:s");
            $this->apdt = $this->initdt;
            $query = "SELECT id from woht where id LIKE 'WO" . $prefix . "%' ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = "WO" . $prefix . str_pad(substr($row['id'], -4) + 1, 4, "0", STR_PAD_LEFT);
                } else {
                    $this->id = "WO" . $prefix . "0001";
                    $this->user = new User(null);
                }
            } else {
                $this->id = "WO" . $prefix . "0001";
            }
        } else if ($id == null) {
            $this->id = null;
        } else {
            $this->id = $id;
            $query = "SELECT * from woht where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->initdt = $row['initdt'];
                    $this->apdt = $row['apdt'];
                    $this->po = new PO($row['poid']);
                    $this->size = $row['size'];
                    $this->color = $row['color'];
                    $this->pqty = $row['pqty'];
                    $this->lcs = $row['lcs'];
                    $this->prt = $row['prt'];
                    $this->emb = $row['emb'];
                    $this->wsh = $row['wsh'];
                    $this->sub = $row['sub'];
                    $this->userid = $row['ab'];
                    $this->finQty = $row['finQty'];
                    $this->fg = $row['fg'];
                    if ($this->user->priLev != $this->lcs + 1 and $this->lcs > 3) {
                        throw new Exception('Not allowed to process [LCS: ' . $this->lcs . " priLev:" . $this->user->priLev . "]", 0);
                    }
                } else {
                    throw new Exception('Invalid WO ID : 0x02', 0);
                }
            } else {
                throw new Exception('Invalid WO ID : 0x01', 0);
            }
        }
    }

    public function printlable($wono)
    {
        $curl = curl_init();
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where woht.id = '" . $wono . "'";
        if ($results = $this->db->select($query)) {
            if ($row = $results->fetch_array()) {
                curl_setopt_array($curl, array(
                    CURLOPT_PORT => "8088",
                    CURLOPT_URL => "http://192.168.4.222:8088/Integration/printwo/Execute",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\n\t\"style\": \"" . (json_decode($row['data'])->Style) . "\",\n\t\"wono\": \"" . $row['id'] . "\",\n\t\"qty\": \"" . $row['pqty'] . "\"\n,\n\t\"cus\":\"" . $row['data']->Customer . "\"\n}",
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/json"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                }
            } else {
                echo "Not Found 0x01: " . $query;
            }
        } else {
            echo "Not Found 0x02: " . $query;
        }
    }

    public function save()
    {
        $stat = 0;
        $this->db = new Database();
        $query = "INSERT INTO woht (id, initdt, apdt, poid, size, color, lcs, prt, emb, wsh, sub, area, pqty, aqty, ab) VALUES ('" . $this->id . "', '" . $this->initdt . "', '" . $this->apdt . "', '" . $this->po->id . "', '" . $this->size . "', '" . $this->color . "', '" . $this->lcs . "', '" . $this->prt . "', '" . $this->emb . "', '" . $this->wsh . "', '" . $this->sub . "', '" . $this->pqty . "', '" . $this->pqty . "', '" . $this->pqty . "', '" . $this->userid . "') ON DUPLICATE KEY UPDATE initdt = '" . $this->initdt . "', apdt = '" . $this->apdt . "', poid = '" . $this->po->id . "', size = '" . $this->size . "', color = '" . $this->color . "', lcs = '" . $this->lcs . "', prt = '" . $this->prt . "', emb = '" . $this->emb . "', wsh = '" . $this->wsh . "', sub = '" . $this->sub . "', area = '" . $this->pqty . "', pqty = '" . $this->pqty . "', aqty = '" . $this->pqty . "'";
        $stat = $this->db->iud($query);
        if ($stat == 0) {
            throw new Exception('Invalid Request at WO', 0);
        } else {
            return $stat;
        }
    }

    public function getPO($poid)
    {
        $this->po = new PO($poid);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setDates($type, $date)
    {
        $query = "INSERT INTO wodt (woid, type, adate) VALUES ('" . $this->id . "', '" . $type . "', '" . $date . "')";
        $this->db = new Database();
        return $this->db->iud($query);
    }

    public static function getfullqty($id)
    {
        $tqty = 0;
        $this->db = new Database();
        $query = "SELECT SUM(DISTINCT qty) as tqty FROM poht where poid = '" . $id . "'";
        if ($results = $this->db->select($query)) {
            if ($row = $results->fetch_array()) {
                $tqty = $row['id'];
            }
        }
        return $tqty;
    }
    public function getpap($lcs)
    {
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where lcs ='" . ($lcs - 2) . "' AND initdt = apdt";
        return $this->db->select($query);
    }

    public function getaped($lcs)
    {
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where lcs ='" . ($lcs) . "' AND initdt < apdt";
        return $this->db->select($query);
    }

    public function getcomed($lcs)
    {
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where lcs > '" . $lcs . "' AND initdt < apdt";
        return $this->db->select($query);
    }

    public function getSupPen()
    {
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where lcs = '8' AND initdt < apdt";
        return $this->db->select($query);
    }

    public function getSupInp()
    {
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where lcs = '9' AND initdt < apdt";
        return $this->db->select($query);
    }

    public function getSupOutside()
    {
        $this->db = new Database();
        $query = "SELECT woht.*, wodt.adate as adate, wodt.type from woht inner join wodt on woht.id = wodt.woid where woht.lcs = '7'";
        return $this->db->select($query);
    }


    public function getSupLoc()
    {
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where lcs = '7' AND id NOT IN (SELECT woid from wodt)";
        return $this->db->select($query);
    }

    public function getSupIn()
    {
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where lcs = '8' AND initdt < apdt";
        return $this->db->select($query);
    }

    public function getWash()
    {
        $this->db = new Database();
        $query = "SELECT woht.*, wodt.adate as adate, wodt.type from woht inner join wodt on woht.id = wodt.woid where woht.lcs = '11'  and wodt.type = '3'";
        return $this->db->select($query);
    }
    public function getFSM()
    {
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where lcs = '12' AND initdt < apdt";
        return $this->db->select($query);
    }

    public function getFIN()
    {
        $this->db = new Database();
        $query = "SELECT woht.*, podt.td as data from woht inner join podt on woht.poid = podt.poid where lcs = '13' AND initdt < apdt";
        return $this->db->select($query);
    }

    public function getlcs($lcs)
    {
        $this->db = new Database();
        $query = "SELECT woht.*, JSON_EXTRACT(podt.td,'$.Style') as style, JSON_EXTRACT(podt.td,'$.Product') as product from woht INNER JOIN poht on woht.poid = poht.id inner join podt on poht.id = podt.poid where woht.lcs = '" . $lcs . "'";
        return $this->db->select($query);
    }

    public function accept()
    {
        $this->db = new Database();
        $this->lcs = $this->user->priLev - 1;
        $log = new alog($this->id, null);
        if ($log->checklog($this->lcs + 1) != 1) {
            $query = "update woht set lcs = '" . ($this->lcs + 1) . "' where id = '" . $this->id . "'";
            $this->db->iud($query);
            $query = "select lcs from woht where id = '" . $this->id . "' and lcs = '" . ($this->lcs + 1) . "'";
            if (mysqli_num_rows($this->db->select($query)) > 0) {
                $log = new alog($this->id, "0");
                $log->add();
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }

    public function accepti()
    {
        $this->db = new Database();
        $this->lcs = $this->user->priLev - 1;
        $log = new alog($this->id, null);
        if ($log->checklog($this->lcs + 1) != 1) {
            $query = "update woht set finQty = finQty + 1 where id = '" . $this->id . "' and finQty < pqty";
            if ($this->db->iud($query) > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }

    public function acceptif()
    {
        if ($this->fg < $this->finQty) {
            $this->db = new Database();
            $this->lcs = $this->user->priLev - 1;
            $log = new alog($this->id, null);
            if ($log->checklog($this->lcs + 1) != 1) {
                $query = "update woht set fg = fg + 1 where id = '" . $this->id . "' and fg < finQty";
                if ($this->db->iud($query) > 0) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 2;
            }
        } else {
            return "All Aprroved";
        }
    }

    public function acceptF()
    {
        if ($this->wsh == 1) {
            $this->db = new Database();
            $this->lcs = $this->user->priLev - 1;
            $log = new alog($this->id, null);
            if ($log->checklog($this->lcs + 1) != 1) {
                $query = "update woht set lcs = '" . ($this->lcs + 1) . "' where id = '" . $this->id . "'";
                $this->db->iud($query);
                $query = "select lcs from woht where id = '" . $this->id . "' and lcs = '" . ($this->lcs + 1) . "'";
                if (mysqli_num_rows($this->db->select($query)) > 0) {
                    $log = new alog($this->id, "0");
                    $log->add();
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 2;
            }
        } else {
            $this->db = new Database();
            $this->lcs = $this->user->priLev - 1;
            $log = new alog($this->id, null);
            if ($log->checklog($this->lcs + 1) != 1) {
                $query = "update woht set lcs = '14', fg = finQty where id = '" . $this->id . "'";
                $this->db->iud($query);
                $query = "select lcs from woht where id = '" . $this->id . "' and lcs = '14' and fg = finQty";
                if (mysqli_num_rows($this->db->select($query)) > 0) {
                    $log = new alog($this->id, "0");
                    $log->add();
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 2;
            }
        }
    }

    public function reject($rNO)
    {
        $log = new alog($this->id, $rNO);
        if ($log->add() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function ready()
    {
        $this->db = new Database();
        $this->apdt = date("Y-m-d H:i:s");
        $this->lcs = $this->user->priLev - 1;
        $log = new alog($this->id, null);
        if ($log->checklog($this->lcs + 1) != 1) {
            $query = "UPDATE woht set lcs = '" . ($this->lcs + 1) . "', apdt = '" . $this->apdt . "' where id = '" . $this->id . "'";
            $this->db->iud($query);
            $query = "SELECT lcs from woht where id = '" . $this->id . "' and lcs = '" . ($this->lcs + 1) . "'";
            if (mysqli_num_rows($this->db->select($query)) > 0) {
                $log = new alog($this->id, "0");
                $log->add();
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }

    public function setDate($type, $date)
    {
        $this->db = new Database();
        $query = "SELECT * from wodt where type = '" . $type . "' AND woid = '" . $this->id . "'";
        if (mysqli_num_rows($this->db->select($query)) > 0) {
            throw new Exception("Recode Already Exists: 0x01 " . $query, 1);
        } else {
            $query = "INSERT INTO wodt (woid, type, adate) VALUES ('" . $this->id . "', '" . $type . "', '" . $date . "')";
            if ($this->db->iud($query) > 0) {
                return 1;
            } else {
                throw new Exception("Error Processing Request: 0x02", 1);
            }
        }
    }

    public function getDates()
    {
        $this->db = new Database();
        $query = "SELECT * FROM wodt where woid = '" . $this->id . "'";
        return $this->db->select($query);
    }
}
