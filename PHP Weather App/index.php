<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>

    <link rel="stylesheet" href="style/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body id="body">

    <div class="searchBox">
        <form action="index.php" method="post" autocomplete="off">



            <input type="text" name="location" id="location" class="searchBox-tooltip" placeholder="Enter Town or City">

            <div class="tooltip" id="tooltip">
                <span class="tooltiptext" id="tooltiptext">Enter Town or City</span>
            </div>

            <button type="submit" class="searchButton-tooltip">Search</button>
        </form>


    </div>


    <?php

    include "config/config.php";


    if (isset($_POST["location"])) {

        $origLocation = $_POST["location"];

        $location = strtolower($origLocation);


        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            // error was suppressed with the @-operator
            if (0 === error_reporting()) {
                return false;
            }

            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            $url = "http://api.openweathermap.org/data/2.5/weather?q=" . $location . "&appid=" . API_KEY;

            $response = file_get_contents("$url");

            $jsonData = json_decode($response, true);

            $name = $jsonData["name"];

            $tempKelvin =  $jsonData["main"]["temp"];
            $temperature = intval((floatval($tempKelvin) - 273.15));  // Celsius

            $pressure = $jsonData["main"]["pressure"]; // Milibars

            $weather = $jsonData["weather"][0];


            $weatherID = strval($weather["id"])[0];

            $weatherType = $weather["main"];
            $icon = $weather["icon"];
            $icon = "http://openweathermap.org/img/wn/" . $icon . "@2x.png";


            $table = "<table><thead><tr>";
            $iconPart = "<th id='iconVal'><img src='$icon' alt='$weatherType'></th>";

            $locationPart  = "<th id='nameVal'><h2>$name</h2></th>";

            $table .= $iconPart .= $locationPart .= "</tr></thead><tbody><tr><td id='tempVal'><h3>$temperature C</h3></td></tr><tr><td id='pressureVal'><h3>$pressure Millibars</h3></td></tr>";


            echo $table;

            if ($weatherID == "2") { // Thunderstorm
                // https://unsplash.com/photos/td7G4W1HSIE
                $image = "resources/thunderstorm.jpg";
            } else if ($weatherID == "3") { // Drizzle
                // https://unsplash.com/photos/qPvBmSvmohs
                $image = "resources/drizzle.jpg";
            } else if ($weatherID == "5") {  // Rain
                // https://unsplash.com/photos/22x7fxFpl_8
                $image = "resources/rain.jpg";
            } else if ($weatherID == "6") { // Snow
                // https://unsplash.com/photos/h9iajxf4k0w
                $image = "resources/snow.jpg";
            } else if ($weatherID == "7") { // Atmosphere
                // https://unsplash.com/photos/aebPbwAWjDs
                $image = "resources/atmosphere.jpg";
            } else if ($weatherType == "Clear" and $weatherID == "8") {  // Clear
                // https://unsplash.com/photos/lX5wMeZUidA
                $image = "resources/clear.jpg";
            } else if ($weatherType != "Clear" and $weatherID == "8") {  // Clouds
                // https://unsplash.com/photos/ROVBDer29PQ
                $image = "resources/clouds.jpg";
            } else {
                $image = "resources/weather.jpg";
            }

            echo "
    
            <style>
            
            body {
                background-image: url($image);
                background-repeat: no-repeat;
                background-size: cover;
            
            }
            
            </style>
            
            ";
        } catch (ErrorException $e) {
            echo "<script> 
            var errorMsg = '$origLocation' + ' is an invalid location!';
            window.alert(errorMsg);
            </script>";
        }
    }

    ?>

    <script>
        $("#location").bind("keyup", function(e) {

            if (document.getElementById("location").value.length == 0) {
                document.getElementById("tooltiptext").style.visibility = "hidden";
            } else {
                $('label[for="location"]').show();
                document.getElementById("tooltiptext").style.visibility = "visible";
                document.getElementById("tooltiptext").style.opacity = "1";

            }
        })
    </script>

</body>





</html>