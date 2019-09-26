<?php
//echo date('YW') . substr("123456789", -4, 4) + 1;

foreach (glob("models/*.php") as $filename)
{
    echo $_SERVER['DOCUMENT_ROOT'] . '/' . $filename;
    echo "<br/>";
}