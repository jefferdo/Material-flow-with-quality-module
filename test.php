<?php
//echo date('YW') . substr("123456789", -4, 4) + 1;


include_once $_SERVER['DOCUMENT_ROOT'] . '/services/database.php';



$servername = null;
$database = "mFlow";
$username = "admin";
$passwd = "12341234";
$conn = null;
$results = null;


 $query = "SELECT * FROM umf where id = '001'";

$db = new Database();
$results = $db->select($query);
if ($row = $results->fetch_array()) {
    echo $row['id'] . "<br/>";
    echo $row['prilev'] . "<br/>";
    echo $row['passwd'] . "<br/>";
    echo $row['lsl'] . "<br/>";
    echo $row['name'] . "<br/>";
    echo $row['key'] . "<br/>";
}

# Fill our vars and run on cli
# $ php -f db-connect-test.php


/* $servername = "192.168.1.6";
$username = "admin";
$password = "12341234";
$database = "mFlow";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
 */