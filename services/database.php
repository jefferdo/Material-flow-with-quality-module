<?php
class Database
{
    private $servername = null;
    private $database = "mFlow";
    private $username = "admin";
    private $passwd = "12341234";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->passwd, $this->database, 3306, null);
            // Check connection            
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }

    public function getCon() {
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
            $stat = 0;
            echo $e;
        }

        return $stat;
    }

    public function select($query)
    {

        $result = null;
        try {
            $result = mysqli_query($this->conn, $query);
        } catch (Exception $e) {
            $result = null;
            echo $e;
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
}
 