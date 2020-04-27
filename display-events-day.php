<?php

    ini_set("session.cookie_httponly", 1);
    session_start();
    require 'database.php';
    // if(!hash_equals($_SESSION['token'], $_POST['token'])){
    //     die("Request forgery detected");
    // }
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);
    $date = trim((string)$json_obj['date']);
    if(isset($_SESSION['user_id'])){
        $stmt = $mysqli->prepare("select * from events where user_id = ? and date = ? ORDER BY time");
        if(!$stmt){
            echo json_encode(array(
                "error" => "Failed prepare",
                "message" => "Incorrect Username or Password"
            ));
            exit;
        }
        //creates array of event data and sends it over to js
        $myArray = [];
        $stmt->bind_param('is', $_SESSION['user_id'], $date);
        $stmt->execute();
        $stmt->bind_result($id, $uid, $name, $date, $time, $category);
        while($stmt->fetch()){
            array_push($myArray, array("name" => htmlentities($name), "date" => htmlentities($date), "time" => htmlentities($time), "category" => htmlentities($category)));
        }
        $stmt->close();
        echo json_encode($myArray);
        exit;
    }


?>