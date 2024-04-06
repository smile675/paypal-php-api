<?php
class GlobalFunctions
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insertRecord($email, $t_id)
    {
        $email = mysqli_real_escape_string($this->db, $email);
        $t_id = mysqli_real_escape_string($this->db, $t_id);

        // Use prepared statement to insert data
        $query = "INSERT INTO record (email, t_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $email, $t_id);

        // Execute the statement
        $result = mysqli_stmt_execute($stmt);

        // Check if the query was successful
        if ($result) {
            return true;
        } else {
            return false;
        }
    }


    public function updateRecord($t_id)
    {
        // $t_id = mysqli_real_escape_string($this->db, $t_id);
        $query = "UPDATE record SET p_status = TRUE WHERE t_id = '$t_id'";
        $result = mysqli_query($this->db, $query);
        return $result;
    }
}
