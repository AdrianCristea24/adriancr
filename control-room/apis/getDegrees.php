<?php
    //headers
    header('Acces-Control-Allow-Origin: *');
    header('Acces-Control-Allow-Methods: GET');
    header('Acces-Control-Allow-Headers: Acces-Control-Allow-Headers,Content-Type,Acces-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../Database.php';
    include_once '../methods/DB_methods.php';
    
    date_default_timezone_set('Europe/Bucharest');

    if ($_GET['expected'] == 'time'){
     echo ($date = date('H:i'));
    }
    else if ($_GET['expected'] == 'degrees'){
        
        $xml = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/weather?lat=44.42&lon=26.10&appid=17629c6c7072b884955bd2ed2b813feb&units=metric"));
        echo (intval($xml->main->temp) . "'C");
    }
    
    return 0;

?>