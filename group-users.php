
<?php

ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';
//query to get all users except the user logged in for group events
    $stmt = $mysqli->prepare("select id, user_name from users where not id = ?");
    if(!$stmt){
        printf("Query Prep Failed: ");
        exit;
    }
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($uid, $name);
    $myArray = [];
    while($stmt->fetch()){
        array_push($myArray, array("name" => htmlentities($name), "id" => htmlentities($uid)));
    }
    $stmt->close();
    echo json_encode($myArray);
    exit;


?>
