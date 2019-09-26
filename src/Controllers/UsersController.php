<?php

namespace App\Controllers;

foreach (glob("models/*.php") as $filename) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $filename;
}

require "vendor/autoload.php";

use Illuminate\Http\Request;
use eftec\bladeone\BladeOne;

class UsersController
{
    private $views = __DIR__ . '/views';
    private $cache = __DIR__ . '/cache';

    public function index()
    {

        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run("login");
    }

    public function searchMatRec(Request $request)
    {
        $name = $request->poid;
        return "creating new user named $name";
    }

    public function matRec()
    {
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run("search", array(
            "title" => "Material Receive",
            "lable" => "Scan PO bardcode",
            "action" => "/matRec",
            "method" => "post"
        ));
    }
}
