<?php
class GlobalFunctions{
    private $db;
    public function __construct($db){
        $this->db = $db;
    }

    public function insertRecord($email, $t_id){
        $email = mysqli_real_escape_string($this->db, $email);
        $t_id = mysqli_real_escape_string($this->db, $t_id);
        $query = "INSERT INTO record (email, t_id ) VALUES ($userId, $t_id)";
        $result = mysqli_query($this->db, $query);
        return $result;
    }

    public function updateRecord($t_id){
        $t_id = mysqli_real_escape_string($this->db, $t_id);
        $query = "UPDATE record SET p_status = TRUE WHERE t_id = $t_id";
        $result= mysqli_query($this->db, $query);
        return $result;
    }
}