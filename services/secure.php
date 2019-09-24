<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/services/token.php');

class Secure { // Secure The login

    public function secureLog()
    {
        $stat = 0;
        $db = new Database();
        if (isset($_COOKIE['uid']) && isset($_COOKIE['key'])) {
            $stat = 1;
            $query = "select * from umf where uid='{$_COOKIE['uid']}'";
            if ($result = $db->select($query)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if($_COOKIE['key'] == $row['keyR']){
                        $stat = 1;
                    }
                    else {
                        $this->logout();
                        $stat = 0;
                    }
                }
            }
        }
        else {
            $stat = 0;
            $this->logout();
        }
    
        return $stat;
    }

    public function logout()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
                header("Location:" . '/' . "cms/");
            }
        }
    }
}
?>