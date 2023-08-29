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

    $result = $DB->readHome();

    $post_arr = array();

    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    extract($row);

    $post_item = array(
        'id' => $id,
        'Terasa' => $Terasa,
        'Sopron' => $Sopron,
        'Stropitori' => $Stropitori,
        'change_at' => $change_at,
    );
    
    
    if (isset($_GET['arduino'])){
        
        if ($_GET['category'] === 'Time'){
            
            echo Date('H');
            return 1;
            
        }
        
        if($_GET['category'] === 'Terasa'){
            if ((intval(Date('H')) >= 20 && intval(Date('H')) <= 23) || (intval(Date('H')) >= 0 && intval(Date('H')) <= 5 )){ //TERASA
                echo $post_item[$_GET['category']];
                return 1;
                
            }
            
            echo 1;
            return 0;
        }
        
        if ((intval(Date('H')) >= 0 && intval(Date('H')) <= 23) && $_GET['category'] === 'Stropitori'){ //STROPITORI

            $date = date_create();
            $now = intval(date_timestamp_get($date));
            $last_change = intval($post_item['change_at']);
            
            if ( $post_item['Stropitori'] == 0 && $now - $last_change > 600){ // 10 MIN and stop
                $ch = curl_init("http://adriancr.ro/control-room/apis/editHome.php?stropitori=1"); // make request to stop the sprinklers
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $data = curl_exec($ch);
                echo 1; //stop
                return 1;
            }
        
            echo $post_item[$_GET['category']];
            return 1;
            
        }
        
        echo 0;
        return 0;
    }
    
    echo json_encode($post_item);
    return 0;

?>