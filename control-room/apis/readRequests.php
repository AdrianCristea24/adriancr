<?php
    //headers
    header('Acces-Control-Allow-Origin: *');
    header('Acces-Control-Allow-Methods: GET');
    header('Acces-Control-Allow-Headers: Acces-Control-Allow-Headers,Content-Type,Acces-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../Database.php';
    include_once '../methods/DB_methods.php';

    //Instantiate DB $ conn
    $database = new Database();
    $db = $database->connect();

    //Instantiate post object
    $DB = new DB_methods($db);

    $result = $DB->readRequests();


    $post_arr = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
            
        extract($row);
        //get all values from this row into $variables
        $post_item = array(
            'id' => $id,
            'data' =>$data,
            'created_at' =>$created_at,
        );
        
        if(strtoupper($post_item['data'])=='QWESLEEP'){
            $post_item['data'] = '{secret}';
        }

        //push to data
        array_push($post_arr, $post_item);
    }   

    //Turn to JSON output so we can read the api    
    echo json_encode($post_arr);
    return 1;
?>