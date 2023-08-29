<?php
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

$result = $DB->airRead();

$post_arr = array();

$row = $result->fetch(PDO::FETCH_ASSOC);
    
extract($row);

$post_item = array(
    'id' => $id,
    'last_request' => $last_request,
    'data' => $data
);

//push to data
array_push($post_arr, $post_item);

date_default_timezone_set('Europe/Bucharest');
if(strtotime(Date('Y-m-d H:i:s')) - strtotime($post_arr[0]['last_request']) > 21000){
    getAndUpdateData();
}

//var_dump(json_decode($post_arr[0]['data']));
$res = json_decode($post_arr[0]['data']);

//var_dump($res);
$date = Date('Y-m-d H:i');
//$res = json_decode($res);

$currentFlight = getFlight($res, $date);
$flightType = getFlightType($currentFlight);

if(isset($_GET['expected'])){
    
    if ($_GET['expected'] == 'FlightType'){
        echo getFlightType($currentFlight);
    }
    else if ($_GET['expected'] == 'Time'){
        echo getBucharestInteractionTime($currentFlight, $flightType, 0, 1);
    }
    else if ($_GET['expected'] == 'Airport'){
        echo getAirport($currentFlight);
    }
    else if ($_GET['expected'] == 'Airline'){
        echo getAirline($currentFlight, $flightType);
    }
}
else{
    
    $currentFlight = getFlight($res, $date,1);
}


//-----------------------------------------FUNCTIONS-------------------------------------------------

function getFlight($data, $date, $all = 0){

    $departures = $data->departures;
    $arrivals = $data->arrivals;

    $closestTakeOff = $departures[sizeof($departures)-1];
    $closestLanding = $arrivals[sizeof($arrivals)-1];
    
    $minTakeOffTime = Date('Y-m-d H:i', strtotime('+6 hours'));
    $minLandingTime =  Date('Y-m-d H:i', strtotime('+6 hours'));
    
    foreach($departures as $flight){
        $now = $flight->departure->actualTimeLocal;
        
        if($all && $now >= $date)showAll($flight);
        
        if($now >= $date && $now <  $minTakeOffTime){
            $minTakeOffTime = $now;
        }
    }
    
    foreach($departures as $flight){
        $now = $flight->departure->actualTimeLocal;
        if($now==$minTakeOffTime){
            $closestTakeOff = $flight;
            break;
        }
    }
    

    foreach($arrivals as $flight){
        $now = $flight->arrival->actualTimeLocal;
        
        if($all)showAll($flight);
        
        if($now >= $date && $now <  $minLandingTime){
            $minLandingTime = $now;
        }
    }
    
    foreach($arrivals as $flight){
        $now = $flight->arrival->actualTimeLocal;
        if($now==$minLandingTime){
            $closestLanding = $flight;
            break;
        }
    }
    
    if($all){
        
        echo '->>>>>>> <br>';
        showAll($closestTakeOff);
        showAll($closestLanding);
        
        
    }

    
    $toShowFlight = getClosest($closestTakeOff, $closestLanding);
    
    if(needUpdate($toShowFLight)){
        getAndUpdateData();
    }
    
    return $toShowFlight;
}


function getAirport($flight){

    if (isset($flight->arrival->airport->name)){
        $name = $flight->arrival->airport->name;
        return $name;
    }

    $name =$flight->departure->airport->name;
    return $name;

}

function getBucharestInteractionTime($flight, $flightType, $forVerify = 0, $inText = 0){

    if ($flightType == 1){
        $time =$flight->departure->actualTimeLocal;
    }
    else{
        $time =$flight->arrival->actualTimeLocal;
    }
    
    
    if(is_null($time)){
        
        if ($flightType == 1){
            $time =$flight->departure->scheduledTimeLocal;
        }
        else{
            $time =$flight->arrival->scheduledTimeLocal;
        }
    }
    
    if($forVerify)
        return $time;
    
    if($inText)
        return secToTime(strtotime($time) - strtotime(Date('Y-m-d H:i:s')));
    
    
    $date = new DateTime($time);
    return $date->format('H:i'); 

}

function getFlightType($flight){


    if (isset($flight->departure->airport->name)){
        return 0; // arrival
    }

    return 1; //departure

}

function getAirline($flight, $flightType){


    if ($flightType == 1){
        //departures
        return $flight->airline->name;
    }

    //arrivals
    return $flight->airline->name;

}

function showAll($currentFlight){
    $flightType = getFlightType($currentFlight);
    echo $flightType . '<br>';
    echo getBucharestInteractionTime($currentFlight, $flightType, 0, 0) . '<br>';
    echo getAirport($currentFlight) . '<br>';
    echo getAirline($currentFlight, $flightType) . '<br>';
    
}

function getClosest($flight1, $flight2){
    
    $win = $flight1;
    //echo '<br>';
    //echo getBucharestInteractionTime($flight1, 1, 1) . ' \ ' . getBucharestInteractionTime($flight2, 0, 1);
    if(getBucharestInteractionTime($flight1, 1, 1) > getBucharestInteractionTime($flight2, 0, 1)){
        
        $win = $flight2;
        
    }
    
    return $win;
    
}

function secToTime($sec){
    
    if ($sec < 10){
        return '   now';
    }
    
    if ($sec < 60){
        return 'in ' . $sec . 'sec';
    }
    
    $min = intval($sec/60);
    
    if ($min<60){
        return 'in ' . $min . 'min';
    }
    
    $h = intval($min/60);
    $min -= 60*$h;
    
    return 'in ' . $h . ':'. $min . 'h';
    
    
}


function needUpdate($flight){
    $type = getFlightType($flight);
    
    $time = getBucharestInteractionTime($flight, $type, 1);
    
    if(strtotime($time) - strtotime(Date('Y-m-d H:i') < -300)){
        return 1;
    }
    return 0;
}

function getAndUpdateData(){
    //Instantiate DB $ conn
    $database = new Database();
    $db = $database->connect();
    
    //Instantiate post object
    $DB = new DB_methods($db);

    $newRes = getApiData();
    //var_dump($newRes);

    $DB->airData = $newRes;
    $result = $DB->insertAir();
    $_SESSION['API_REQUESTED'] = true;
}


function getApiData(){
    $curl = curl_init();
    date_default_timezone_set('Europe/Bucharest');
    $nowDate = Date('Y-m-d') . "T" . Date('H:i');
    $finishDate = date("Y-m-d H:i", strtotime('+6 hours'));
    $finishDate = date("Y-m-d", strtotime($finishDate)) . 'T' . date("H:i", strtotime($finishDate));
    
   $url = "https://aerodatabox.p.rapidapi.com/flights/airports/icao/LROP/" .$nowDate. "/" . $finishDate ."?withLeg=true&withCancelled=false&withCodeshared=false&withCargo=false&withPrivate=false&withLocation=false";

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: aerodatabox.p.rapidapi.com",
            "X-RapidAPI-Key: 2a02b0f90dmsh7a88650422e8a27p17b3e7jsnc4856f6e8e32"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return $response;
    }
    return 0;
}

?>