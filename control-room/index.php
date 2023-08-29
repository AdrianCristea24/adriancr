
<?php
$url = "http://adriancr.ro/control-room/apis/singleRead.php";
$data = file_get_contents($url);

$data = file_get_contents($url);
$res  = json_decode(file_get_contents("http://adriancr.ro/control-room/apis/readRequests.php"));

//string to json
$data=str_replace('},

]',"}

]",$data);

$data = json_decode($data);
//parse the values
$currentValue =  $data[0]->data;
$updated = $data[0]->updated_at;
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Control Room </title>

    <link rel="stylesheet" href="index.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&family=Roboto+Condensed&display=swap" rel="stylesheet">    
</head>

<body>
    <div class="center" style="margin-bottom:75px; background: #f5f5f5">
        <h3>Introdu text-ul dorit </h2>
        <p>(current: <?php echo '<b>'.$currentValue.'</b>'; ?>, updated: <?php echo '<b>' . $updated . '</b>'; ?>)</p>
        <form method="POST" action="./apis/edit.php">
            <div>
                <input name="Uploaded" id="Uploaded" placeholder="Orice" minlength="2" maxlength="26" style='display:block; width:auto; margin-bottom:10px' required>
                <button type="submit" name="submit" value='Submit' class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>


    <div class="center" style="border: 2px solid brown;background: #f5f5f5">

        <p><b>History:</b></p>
        <?php

            for($i=0;$i<sizeof($res);$i++){

                $userText = $res[$i]->data;
                $created_at = $res[$i]->created_at;

                $text = $i+1 .'. <b>' . $userText . '</b> [' . $created_at . ']'; 

                echo '<p class="history">'. $text . '</p>';
            }
        
        ?>

    </div>

</body>


</html>


