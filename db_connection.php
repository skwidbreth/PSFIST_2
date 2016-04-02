<?php

require '../credentials.php';
$servername = "localhost";
$dbname = "Project_Safe_Flight";
$target_dir = "uploads/";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = '';

//SURVEY INFO
$name = $_POST['name'];
$date = $_POST['date'];
$hour = $_POST['hour'];
$minute = $_POST['minute'];
$ampm = $_POST['ampm'];
$startTime = $hour.":".$minute.$ampm;
$duration = $_POST['duration'];
$weather = $_POST['weather'];
$route = $_POST['route'];

//SITE INFO
$site_array = $_POST['site'];

foreach($site_array as $siteNumber => $value){
    
    if(isset($_POST['nonenotes'.$siteNumber.''])){
        $noneNotes = $_POST['nonenotes'.$siteNumber.''];
        $siteImage = $_FILES['siteimage'.$siteNumber.'']['tmp_name'];
        
        if($noneNotes == ''){
            $noneNotes = "NULL";
        }
        
        if(isset($siteImage)){
            $target_file = ($target_dir . rand(1, 9999999) . strtolower(basename($siteImage)));

            if(move_uploaded_file($siteImage, $target_file)){
                $image_url = $target_file;
            }
            else{
                $image_url = "NULL";
            };
        }
        
        $sql .= "INSERT INTO PSFIST (volunteer, date, start_time, duration, weather, route, site, species, deadinjured, sex, age, action, notes, image_url)
        VALUES ('$name', '$date', '$startTime','$duration', '$weather','$route', '$siteNumber', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '$noneNotes' ,'$image_url');";
    }
    else{
        $species_array[$siteNumber] = $_POST['species'.$siteNumber.''];
        $deadinjured_array[$siteNumber] = $_POST['deadinjured'.$siteNumber.''];
        $sex_array[$siteNumber] = $_POST['sex'.$siteNumber.''];
        $age_array[$siteNumber] = $_POST['age'.$siteNumber.''];
        $action_array[$siteNumber] = $_POST['action'.$siteNumber.''];
        $notes_array[$siteNumber] = $_POST['notes'.$siteNumber.''];
        $image_array[$siteNumber] = $_FILES['image'.$siteNumber.'']['tmp_name'];

        foreach($species_array[$siteNumber] as $key => $species){

            $deadinjured = $deadinjured_array[$siteNumber][$key];
            $sex = $sex_array[$siteNumber][$key];
            $age = $age_array[$siteNumber][$key];
            $action = $action_array[$siteNumber][$key];
            $notes = $notes_array[$siteNumber][$key];

            if($image_array[$siteNumber][$key]){
                $target_file = ($target_dir . rand(1, 9999999) . strtolower(basename($image_array[$siteNumber][$key])));

                if(move_uploaded_file($image_array[$siteNumber][$key], $target_file)){
                    $image_url = $target_file;
                }
            }
            else{
                $image_url = "NULL";
            };

            $sql .= "INSERT INTO PSFIST (volunteer, date, start_time, duration, weather, route, site, species, deadinjured, sex, age, action, notes, image_url)
            VALUES ('$name', '$date','$startTime', '$duration', '$weather','$route', '$siteNumber', '$species', '$deadinjured', '$sex', '$age', '$action', '$notes', '$image_url');";

        };
    };
};



if ($conn->multi_query($sql) === TRUE) {
    $conn->close();
    header("Location:confirmation.html");
};


?>