<?php
    //headers
    header('Acces-Control-Allow-Origin: *');
    header('Acces-Control-Allow-Methods: GET');
    header('Acces-Control-Allow-Headers: Acces-Control-Allow-Headers,Content-Type,Acces-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../Database.php';
    include_once '../methods/DB_methods.php';
    
    date_default_timezone_set('Europe/Bucharest');

    //Instantiate DB $ conn
    $database = new Database();
    $db = $database->connect();

    //Instantiate post object
    $DB = new DB_methods($db);

    $result = $DB->singleRead();

    $post_arr = array();

    $row = $result->fetch(PDO::FETCH_ASSOC);
        
    extract($row);
    
    if(isset($_GET['arduino'])){
        echo $data;
        return 1;
    }

    $post_item = array(
        'id' => $id,
        'data' => $data,
        'updated_at' => $updated_at
    );
    
    if(strtoupper($post_item['data'])=='QWESLEEP'){
        $post_item['data'] = '{secret}';
    }
    
    //push to data
    array_push($post_arr, $post_item);

    //Turn to JSON output so we can read the api    
    echo json_encode($post_arr);
    return 1;
?>