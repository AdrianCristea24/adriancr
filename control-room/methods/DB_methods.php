<?php


class DB_methods{

    private $conn;
    private $table = 'public_data';
    private $requestsTable = 'requests';
    private $homeTable = 'myhomedb';
    
    private $airTable = 'air';

    //Constructor DB

    public function __construct($db){

        $this->conn = $db;
    }

    //Read Post

    public function singleRead(){
        //create query
        $query = 'SELECT * 
        FROM ' . $this->table . 
        ' WHERE id = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt -> execute();

        return $stmt;

    }

    public function readRequests(){

        //echo 'ip: ' . $_SERVER['REMOTE_ADDR'];
        //create query
        $query = 'SELECT * 
        FROM ' . $this->requestsTable . ' ORDER BY id DESC LIMIT 15';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt -> execute();

        return $stmt;

    }

    public function edit(){

        date_default_timezone_set('Europe/Bucharest');
        $ip = $_SERVER['REMOTE_ADDR'];

        $query = '
        UPDATE '. $this->table . ' 
        SET data = "'. $this->newData .'", updated_at = "' . date('Y-m-d H:i:s') . '" , ip = "' . $ip . 
        '" WHERE id = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt -> execute();
    
        return $stmt;

    }
    
    public function readHome(){
        //create query
        $query = 'SELECT *
         FROM ' . $this->homeTable . 
        ' WHERE id = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt -> execute();

        return $stmt;

    }

    public function updateHome($val, $col){

        $query = '
        UPDATE '. $this->homeTable . ' 
        SET ' . $col . ' = "' . $val . 
        '" WHERE id = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt -> execute();

        return $stmt;
    }


    public function insertRequest(){

        date_default_timezone_set('Europe/Bucharest');
        $ip = $_SERVER['REMOTE_ADDR'];

        $query = '
        INSERT INTO '. $this->requestsTable . 
        ' (data, created_at, ip) VALUES ("' . $this->newData .'","'. date('Y-m-d H:i:s') . '","' . $ip . '")';
        
        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt -> execute();

        return $stmt;


    }

    public function flag($id){
        //create query
        $query = '
        UPDATE '. $this->table . ' 
        SET flag = 1 
        WHERE id = ' . $id;
        

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt -> execute();

        return $stmt;

    }

    public function airRead(){
        //create query
        $query = 
        'SELECT * 
        FROM ' . $this->airTable;
        

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt -> execute();

        return $stmt;

    }

    public function insertAir(){
        
        date_default_timezone_set('Europe/Bucharest');

        $query = "
        UPDATE ". $this->airTable . "
        SET data = '". $this->airData ."', last_request = '" . date('Y-m-d H:i:s') . "' WHERE id = 1";
        
        
        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt -> execute();
    
        return $stmt;


    }



}



?>