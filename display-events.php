<?php

    ini_set("session.cookie_httponly", 1);
    session_start();
    require 'database.php';
    
    //query to get events from user and order by time to display

    if(isset($_SESSION['user_id'])){
        $stmt = $mysqli->prepare("select * from events where user_id = ? ORDER BY time");
        if(!$stmt){
            echo json_encode(array(
                "error" => "Failed prepare",
                "message" => "Incorrect Username or Password"
            ));
            exit;
        }
        $myArray = [];
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($id, $uid, $name, $date, $time, $category);
        while($stmt->fetch()){
            array_push($myArray, array("name" => htmlentities($name), "date" => htmlentities($date), "time" => htmlentities($time), "category" => htmlentities($category), "token" => $_SESSION['token']));
        }
        $stmt->close();
        echo json_encode($myArray);
        exit;
    }


?>