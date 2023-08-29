<?php

    class Database{
        //DB parameters
        private $host = 'localhost';
        private $db_name = 'adrianc2_control_room';
        private $username = 'adrianc2_adriancristea';
        private $pass = '1q2wAdrian';
        private $conn;

        //DB connect:

        public function connect(){

            $this->conn = null;

            try{ // try to connect to the database
                $this->conn = new PDO('mysql:host='. $this->host .';dbname='. $this->db_name, $this->username,$this->pass);

                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e){
                echo "Connection err: " . $e -> getMessage();
            }

            return $this->conn; //return the connection so we can use query on our DB in POST method
        }
    }

    
?>