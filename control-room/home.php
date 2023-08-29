<?php
$url = "http://adriancr.ro/control-room/apis/readHome.php";

$data = file_get_contents($url);

//string to json
$data=str_replace('},

]',"}

]",$data);

$data = json_decode($data);

$terasa = $data->Terasa;
$sopron = $data->Sopron;
$stropitori = $data->Stropitori;

//parse the values
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>My Home </title>

    <link rel="stylesheet" href="index.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&family=Roboto+Condensed&display=swap" rel="stylesheet">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="center" style="margin-bottom:75px; background: #f5f5f5">
        <h3>My Smart Home </h2>

        <form id='form1' method="GET">
            <div>
            <input type="checkbox" id="terasa" name="terasa" value="1">
            <label for="data"> 3 Lumini Terasa</label><br>
            <input type="checkbox" id="sopron" name="sopron" value="1">
            <label for="data"> Lumina Sopron</label><br>
            <br>
            <input type="checkbox" id="stropitori" name="stropitori" value="1">
            <label for="data"> Stropitori</label><br>

            <span id="console">watering for 13:20 min now</span><br>
            <button type="submit" name="submit" value='Submit' class="btn btn-success">Submit</button>
            </div>
        </form>
        
    </div>


</body>

<script>

$('#terasa').prop('checked', !<?php echo $terasa ?>);
$('#sopron').prop('checked', <?php echo $sopron ?>);
$('#stropitori').prop('checked',!<?php echo $stropitori ?>);

$('#form1').submit(function(e){
    e.preventDefault();
    terasa = $('#terasa').is(":checked") ? 0 : 1;
    sopron = $('#sopron').is(":checked") ? 1 : 0;
    stropitori = $('#stropitori').is(":checked") ? 0 : 1;
    
    $.ajax({
        url:'./apis/editHome.php?terasa=' + terasa + '&sopron=' + sopron + '&stropitori=' + stropitori,
        type:'GET',
        success:function(msg){
            if(!msg){
                
            }
            console.log(msg);
            const span = document.getElementById('console');

            // ✅ Change (replace) the text of the span
            span.textContent = 'change submited';
            setTimeout(() => { getLastActionTime(); }, 2000);

        }
    });
});

function getLastActionTime(){
    $.ajax({
            url:'./apis/readHome.php',
            type:'GET',
            success:function(res){
                if(!res){
                    
                }
                console.log(res);
                const obj = JSON.parse(res);
                
                
                const span = document.getElementById('console');
                var s = new Date(obj['change_at'] * 1000).toLocaleTimeString("ro-RO");
                span.textContent = 'Last change at: ' + s;
    
            }
    });
}
getLastActionTime();

</script>


</html>