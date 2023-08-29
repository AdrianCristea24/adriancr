<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Updated</title>

    <link rel="stylesheet" href="../index.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&family=Roboto+Condensed&display=swap" rel="stylesheet">    
</head>


<div class='center'>

<?php
    //headers
    header('Acces-Control-Allow-Origin: *');
    header('Acces-Control-Allow-Methods: POST');
    header('Acces-Control-Allow-Headers: Acces-Control-Allow-Headers,Content-Type,Acces-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../Database.php';
    include_once '../methods/DB_methods.php';

    //Instantiate DB $ conn
    $database = new Database();
    $db = $database->connect();

    //Instantiate post object
    $DB = new DB_methods($db);
    

    if(isset($_GET['terasa'])){

        $DB->updateHome($_GET['terasa'], 'Terasa');

    }
    
    if(isset($_GET['sopron'])){

        $DB->updateHome($_GET['sopron'], 'Sopron');

    }
    
    if(isset($_GET['stropitori'])){
        $date = date_create();
        $unix = date_timestamp_get($date);
        
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
        
        if ($_GET['stropitori'] != $Stropitori){
            $DB->updateHome($unix, 'change_at');
        }
        
        $DB->updateHome($_GET['stropitori'], 'Stropitori');


    }

    else{
        echo 'Error sending the request, contact @lxixadi';
    }

?>


</div>
</html>

