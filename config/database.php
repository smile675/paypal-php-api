<?php

class Database
{
    // db params -> local machine
    private $host = 'localhost';
    //private $host = '192.168.43.18:3306'; // while using real device
    private $db_name = 'paypal';
    private $username = 'root';
    private $password = '';
    public $db;



    //db connect
    public function getConnection()
    {
        $this->db = null;

        //connect to database
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);

        //check connection
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }/*else{
        echo "Connected successfully!";
       }*/

        return $this->db;
    }
}