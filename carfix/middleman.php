<?php

function do_curl($url){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
    "Accept: */*",
    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLmNhcmZpeC5jcmlzdGlhbmNpbXVjYS5kZXYuYXNjZW5zeXMucm8iLCJhdWQiOiJodHRwczpcL1wvYXBpLmNhcmZpeC5jcmlzdGlhbmNpbXVjYS5kZXYuYXNjZW5zeXMucm8iLCJpYXQiOjE2NDU2MDU0NTQsIm5iZiI6MTY0NTYwNTQ1NCwiZXhwIjoxNjc3MTQxNDU0LCJqdGkiOjM5N30.HAYrLsmfQ4FGtqe4rqruheth6eD6nLApyqlc14jsJAI",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $data = $_POST;

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $resp = curl_exec($curl);
    curl_close($curl);

    return $resp;
}

$url = "https://api.carfix.adriancristea.dev.ascensys.ro/v1/user/update-preferences";
$resp = do_curl($url);

if($resp!=0){
    return 0;
}

var_dump($resp);

?>