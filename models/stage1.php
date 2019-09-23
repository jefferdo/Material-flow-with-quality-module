<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');

class PO
{
    private $id;
    private $supplier;
    private $materials;

    public function __construct($id)
    {
        if ($id == null) {
            $token = new Token();
            $this->id = "POID" . date("dmY") . $token->getTokenNoOnly(4);
        } else {
            $this->id = $id;
        }
    }

    public function add()
    {
        //add new po for supplier (supplier should be pre registered)
    }

    public function modify()
    {
        //modify detail related to po in case of need
    }

    public function addMaterials($matid)
    {
        //find material from db and add to collection
    }
}

class INVOICE
{
    private $id;
    private $materials;
    private $supplier;
    private $PO;
}
