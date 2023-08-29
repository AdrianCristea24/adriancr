
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

    $resp = curl_exec($curl);
    curl_close($curl);

    return $resp;
}

// ---------------------------------------------------------------- Verify user acces to the page



if (!isset($_GET['username']) || !isset($_GET['token'])){
    echo 'error: Params Not Set';
    return 0;
}

$url = "https://api.carfix.adriancristea.dev.ascensys.ro/v1/user/verify-access"."?username=" . $_GET['username'] . "&token=" . $_GET['token'];
$resp = do_curl($url);

if (is_null($resp) || $resp!=1){
    echo 'error: Not Authorized, please check your latest email';
    return 0;
}
// ---------------------------------------------------------------- The user is allowed on the page

$url = "https://api.carfix.adriancristea.dev.ascensys.ro/v1/user/get-preferences"."?username=" . $_GET['username'];
$resp = do_curl($url);
$currentSettings = json_decode($resp);

$arr = array('RCA', 'CASCO', 'CI', 'Permis', 'Talon', 'CIV', 'ITP', 'Vinieta', 'Extinctor', 'Trusă-medicală', 'Revizie', 'Inspecție-Risc', 'Polita', 'Task', 'Service');

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Unsubscribe</title>

    <link rel="stylesheet" href="./index.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&family=Roboto+Condensed&display=swap" rel="stylesheet">    
</head>

<body>

    <div class='center' style='min-width: 300px; max-width: 400px; background: #f5f5f5'>
        <img src="./carfix.png" width="120px" height="35px" style="margin-bottom:10px">
        
        <?php
        
            if(isset($_GET['update'])){
                
                echo '<p><b>Preferintele tale au fost actualizate cu succes.</b></p>';
                return 0;
            }
        
        ?>
        
        
        <p><b>Abonare / Dezabonare notificări mail</b></p>

        <form method='POST'  id = 'settings' name = 'settings' style="background: #f5f5f5">
            
            <?php
                
                
                foreach ($arr as $key => $alert){
                    echo '<div>';

                    if ($currentSettings[$key]->val!=0)
                    echo '<input type="checkbox" id='. $alert .' name='. $alert . ' value=' .$key. ' checked >';
                    else
                    echo '<input type="checkbox" id='. $alert .' name='. $alert . ' value=' .$key. ' >';

                    echo '<label for='. $alert .'> &nbsp'. $alert .'</label>';
                    echo '</div>';

                }
                

            ?>


             <div>
                <button type="submit">Submit</button>
            </div>

        </form>
    </div>
    

</body>



</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>

    const form = document.getElementById('settings');

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);

        var name = "<?php echo $_GET['username'] ?>";

        formData.append('username', name);

        var obj = { 
            method: 'POST',
            body: formData
        }

        fetch('./middleman.php', obj)
        .then(function(res) {
            
            console.log(res);
            window.location.replace(window.location.href + '&update=1');
        })

      
    
    })


</script>
