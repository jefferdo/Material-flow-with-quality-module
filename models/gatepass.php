<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/models/log.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/services/fpdf/fpdf.php");

class GatePass
{
    private $id;
    private $date;
    private $status = 0;
    private $units;
    private $name;
    private $destination;
    private $user;

    private $apd;

    private $db;

    public function __set($name, $value)
    {
        switch ($name) {
            case 'id':
                $this->id = $value;
                break;
            case 'date':
                $this->date = $value;
                break;
            case 'status':
                $this->status = $value;
                break;
            case 'units':
                $this->units = $value;
                break;
            case 'name':
                $this->name = $value;
                break;
            case 'destination':
                $this->destination = $value;
                break;
            case 'user':
                $this->user = new User($value);
                break;
            case 'apd':
                $this->apd = $value;
                break;
            default:
                throw new Exception("Invalid setter: " . $name, 1);
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
                break;
            case 'date':
                return $this->date;
                break;
            case 'status':
                return $this->status;
                break;
            case 'units':
                return $this->units;
                break;
            case 'name':
                return $this->name;
                break;
            case 'destination':
                return $this->destination;
                break;
            case 'user':
                return $this->user;
                break;
            case 'apd':
                return $this->apd;
                break;
            default:
                throw new Exception("Invalid getter: " . $name, 1);
        }
    }

    public function __construct($id)
    {
        $this->db = new Database();
        if ($id == null) {
            $this->id = "";
        } else if ($id == "new") {
            $prefix = date("Yz");
            $query = "SELECT id from gpht  where id LIKE 'GP" . $prefix . "%' ORDER BY id desc LIMIT 1";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = "GP" . $prefix . str_pad(substr($row['id'], -4) + 1, 4, "0", STR_PAD_LEFT);
                    $this->user = new User(null);
                } else {
                    $this->id = "GP" . $prefix . "0001";
                    $this->user = new User(null);
                }
            } else {
                $this->id = "GP" . $prefix . "0001";
                $this->user = new User(null);
            }
            $this->date = date("Y-m-d H:i:s");
        } else {
            $this->id = $id;
            $query = "SELECT * from gpht where id ='" . $this->id . "'";
            if ($results = $this->db->select($query)) {
                if ($row = $results->fetch_array()) {
                    $this->id = $row['id'];
                    $this->date = $row['date'];
                    $this->status = $row['status'];
                    $this->user = new User($row['ab']);
                    $this->apd = $row['apd'];
                    $this->name = $row['name'];
                    $this->destination = $row['destination'];
                } else {
                    throw new Exception('Invalid GP ID', 0);
                }
            }
        }
    }

    public function getUnits()
    {
        $this->db = new Database();
        $query = "SELECT woht.* from gpdt inner join woht on gpdt.unitid = woht.id where gpid ='" . $this->id . "'";
        return $this->db->select($query);
    }

    public function getGPs()
    {
        $this->db = new Database();
        $query = "SELECT gpht.* , umf.name as uname from gpht inner join umf on gpht.ab = umf.id";
        return $this->db->select($query);
    }

    public function getSupOutside()
    {
        $this->db = new Database();
        $query = "SELECT woht.* from woht left join gpdt on woht.id = gpdt.unitid where lcs = '7' AND gpdt.unitid is null";
        return $this->db->select($query);
    }

    public function addNew()
    {
        $query = "INSERT INTO gpht (id, date, ab, status) values('" . $this->id . "', '" . $this->date . "', '" . $this->user->id . "', '" . $this->status . "')";
        $this->db = new Database();
        if ($this->db->iud($query) == 1) {
            return 1;
        } else {
            return $query;
        }
    }

    public function update()
    {
        $query = "UPDATE gpht set status = '" . $this->status . "', destination = '" . $this->destination . "', name = '" . $this->name . "' where id = '" . $this->id . "'";
        $this->db = new Database();
        if ($this->db->iud($query) == 1) {
            return 1;
        } else {
            return $query;
        }
    }

    public function delete()
    {
        $query = "DELETE FROM gpht WHERE gpht.id = '" . $this->id . "'";
        $this->db = new Database();
        if ($this->db->iud($query) == 1) {
            if ($this->db->iud($query) == 1) {
                $query = "DELETE FROM gpdt WHERE gpdt.gpid = '" . $this->id . "'";
                return 1;
            } else {
                return $query;
            }
        } else {
            return $query;
        }
    }

    public function addUnit($unitid)
    {
        $unit = new GPUnit($this->id, $unitid);
        return $unit->addNew();
    }

    public function delUnit($unitid)
    {
        $unit = new GPUnit($this->id, $unitid);
        return $unit->delete();
    }

    public function getPDF()
    {
        $pdf = new GatePassPDF($this);
        $pdf->AliasNbPages();
        $pdf->AddPage('P', 'A4', 0);
        $pdf->headerTable();
        $pdf->viewDetail();
        $pdf->Output('i', "GatePass_" . $this->id . ".pdf");
    }
}

class GPUnit
{
    private $gpid;
    private $unitid;
    private $qty = 1;

    private $db;

    public function __set($name, $value)
    {
        switch ($name) {
            case 'gpid':
                $this->gpid = $value;
                break;
            case 'unitid':
                $this->unitid = $value;
                break;
            case 'qty':
                $this->qty = $value;
                break;
            default:
                throw new Exception("Invalid setter: " . $name, 1);
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case 'gpid':
                return $this->gpid;
                break;
            case 'unitid':
                return $this->unitid;
                break;
            case 'qty':
                return $this->qty;
                break;
            default:
                throw new Exception("Invalid getter: " . $name, 1);
        }
    }

    public function __construct($gpid, $unitid)
    {
        $this->db = new Database();
        $query = "SELECT * from gpht where id ='" . $gpid . "'";
        if (0 < mysqli_num_rows($this->db->select($query))) {
            $this->gpid = $gpid;
            $this->unitid = $unitid;
        } else {
            $this->gpid = null;
            throw new Exception('Invalid GP ID', 0);
        }
    }

    public function addnew()
    {
        if ($this->gpid != null) {
            $query = "INSERT INTO gpdt (gpid, unitid, qty) values('" . $this->gpid . "', '" . $this->unitid . "', '" . $this->qty . "')";
            $this->db = new Database();
            if ($this->db->iud($query) == 1) {
                return 1;
            } else {
                return $query;
            }
        } else {
            throw new Exception('GPID is empty', 0);
        }
    }

    public function delete()
    {
        if ($this->gpid != null) {
            $query = "DELETE FROM gpdt WHERE gpid = '" . $this->gpid . "' AND unitid = '" . $this->unitid . "'";
            $this->db = new Database();
            if ($this->db->iud($query) == 1) {
                return 1;
            } else {
                return $query;
            }
        } else {
            throw new Exception('GPID is empty', 0);
        }
    }
}

class GatePassPDF extends FPDF
{
    private $gp;
    protected $page_w;
    protected $page_h;

    public function __construct($gp)
    {
        parent::__construct();
        if ($gp != null) {
            $this->gp = $gp;
            $this->SetTopMargin(30);
        }
        $this->SetTitle("GatePass_" . $this->gp->id);
    }

    public function header()
    {
        $this->page_w = $this->GetPageWidth() - 20;
        $this->page_h = $this->GetPageHeight();

        $this->image('nblogo.jpg', 10, 10);

        $this->SetFont('Arial', 'BU', 12);
        $this->Cell($this->page_w / 6 * 4, 5, "Advice Of Dispatch", 0, 0, "L");

        $this->SetFont('Arial', 'B', 12);
        $this->Cell($this->page_w / 6 * 2, 5, "GP No: " . $this->gp->id, 0, 0, "R");
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 6, "NOBLESWARE (PVT) LTD", 0, 0, "C");
        $this->Ln();

        $this->SetFont('Arial', '', 13);
        $this->Cell(0, 6, "POKUNUWITA - HORANA", 0, 0, "C");
        $this->Ln();

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 6, "TELL: 034-22632396 / 034-5706706  FAX: 034-2262384", 0, 0, "C");
        $this->Ln();

        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 6, "[System Generated :" . date("Y-m-d H:i:s") . "]", 0, 0, "R");
        $this->Ln();
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, "Date: " . $this->gp->date, 0, 0, "L");
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, "  M/s", 1, 1, "L");
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 10, "  Receiver's Name: " . $this->gp->name . "\n" . "  Address: " . $this->gp->destination,  "LRTB", "L", false);
    }

    public function headerTable()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($this->page_w / 6 * 3, 10, "Description", 1, 0, "C");
        $this->Cell($this->page_w / 6 * 2, 10, "Code", 1, 0, "C");
        $this->Cell($this->page_w / 6 * 1, 10, "Qty", 1, 0, "C");
        $this->Ln();
    }

    public function viewDetail()
    {
        $this->SetFont('Arial', '', 10);
        $units = $this->gp->getUnits();
        while ($row = $units->fetch_array()) {
            $this->Cell($this->page_w / 6 * 3, 10, $row['size'] . " | " . $row['color'], 1, 0, "C");
            $this->Cell($this->page_w / 6 * 2, 10, $row['id'], 1, 0, "C");
            $this->Cell($this->page_w / 6 * 1, 10, $row['pqty'], 1, 0, "C");
            $this->Ln();
        }

        $this->Ln(20);
    }

    public function footer()
    {
        $this->SetFont('Arial', '', 11);
        $this->SetY(-60);
        $this->Cell($this->page_w / 4 * 1, 20, "", "RTL", 0, "C");
        $this->Cell($this->page_w / 4 * 1, 20, "", "T", 0, "C");
        $this->Cell($this->page_w / 4 * 1, 20, "", "T", 0, "C");
        $this->Cell($this->page_w / 4 * 1, 20, "", "TR", 0, "C");
        $this->Ln();
        $this->Cell($this->page_w / 4 * 1, 5, "Genarated D/T", "RL", 0, "C");
        $this->Cell($this->page_w / 4 * 1, 5, "..................................", "", 0, "C");
        $this->Cell($this->page_w / 4 * 1, 5, "..................................", "", 0, "C");
        $this->Cell($this->page_w / 4 * 1, 5, "..................................", "R", 0, "C");
        $this->Ln();
        $this->Cell($this->page_w / 4 * 1, 10, date("Y-m-d H:i:s"), "RBL", 0, "C");
        $this->Cell($this->page_w / 4 * 1, 10, "Passed By", "B", 0, "C");
        $this->Cell($this->page_w / 4 * 1, 10, "Director /Manager", "B", 0, "C");
        $this->Cell($this->page_w / 4 * 1, 10, "Receiver's Signature", "BR", 0, "C");
        $this->Ln();
        $this->page_h = $this->GetPageHeight();
        $this->image('logo-dark-flow.png', 10, $this->page_h - 20, 20);
        $this->SetY(-20);
        $this->Cell(0, 10, "Page " . $this->PageNo() . '/{nb}', "", 0, "R");
        $this->SetFont('Arial', '', 8);
        $this->SetY(-15);
        $this->Cell(0, 10, date('Y') . iconv('utf-8', 'cp1252', " © mFLow powered by LASL"), 0, 0, "L");
    }
}
