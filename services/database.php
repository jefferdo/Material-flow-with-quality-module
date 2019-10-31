<?php
error_reporting(0);
class Database
{
    
    private $servername = "192.168.4.221";
    //private $servername = "192.168.1.6";
    private $database = "mFlow";
    private $username = "admin";
    private $passwd = "12341234";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->passwd, $this->database);
            // Check connection            
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 0);
        }
    }

    function __destruct()
    {
        try {
            $this->conn->close();
        } catch (Exception $th) {
            //throw $th;
        }
    }

    public function getCon()
    {
        return $this->conn;
    }


    public function iud($query)
    {
        $stat = 0;
        try {
            if ($this->conn->query($query) === true) {
                $stat = 1;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 0);
        }
        return $stat;
    }

    public function select($query)
    {

        $result = null;
        try {
            $result = mysqli_query($this->conn, $query);
        } catch (Exception $e) {
            throw new Exception('null', 0);
        }

        return $result;
    }

    public function del($query)
    {

        $stat = 0;
        try {
            if ($this->conn->query($query) === TRUE) {
                $stat = 1;
            }
        } catch (Exception $e) {
            $stat = 0;
            echo $e;
        }

        return $stat;
    }

    public function real_escape_string($string)
    {
        return mysqli_real_escape_string($this->conn, $string);
    }
}
