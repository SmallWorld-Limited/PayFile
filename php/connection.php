<?php

class db_connect{
    
    private $servername;
    private $username;
    private $password;
    private $dbname;

    public function connect(){
        
        $this->servername = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->dbname = "payment_signing_system";

        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        
        if($conn -> connect_error){
            die("connection failed:". $conn->connect_error);
        }
        else{
            //echo "connection successful";
        }
        return $conn;
        
    }


}

?>