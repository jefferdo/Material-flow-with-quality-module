<?php
foreach (glob("models/*.php") as $filename) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $filename;
}

require "vendor/autoload.php";

Use eftec\bladeone\BladeOne;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';
$blade = new BladeOne($views,$cache,BladeOne::MODE_AUTO);
echo $blade->run("login");