<?php
ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';
    
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);
$grabbed_token = $json_obj['token'];
//goes through and deletes all events for one user
    if(!hash_equals($_SESSION['token'], $grabbed_token)){
        die("Request forgery detected");
    }
    
    $stmt = $mysqli->prepare("delete from events where user_id = ?");
        
    if(!$stmt){
        // $_SESSION['error_message'] = 'error during add';
        // printf("Query Prep Failed: %s\n", $mysqli->error);
        echo json_encode(array(
            "error" => "Failed prepare",
            "message" => "Incorrect Username or Password"
        ));
        exit;
    }

    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "status" => "Success",
    ));
?>
