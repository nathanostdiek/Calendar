<?php
ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';


$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);
$title = trim((string)$json_obj['title']);

if(!$json_obj==null){
    $user_id = $_SESSION['user_id'];
    $date = trim((string)$json_obj['date']);
    $time = trim((string)$json_obj['time']);
    $category = trim((string)$json_obj['category']);
    $grabbed_token = $json_obj['token'];
    
    
    if(!hash_equals($_SESSION['token'], $grabbed_token)){
        die("Request forgery detected");
    }
    
    if(isset($title) && isset($user_id) && isset($date) && isset($time)){
    
        $stmt = $mysqli->prepare("delete from events where user_id = ? AND name = ? AND date=? AND time=? and category = ?");
            
        if(!$stmt){
            // $_SESSION['error_message'] = 'error during add';
            // printf("Query Prep Failed: %s\n", $mysqli->error);
            echo json_encode(array(
                "error" => "Failed prepare",
                "message" => "Incorrect Username or Password"
            ));
            exit;
        }
    
        $stmt->bind_param('issss', $user_id, $title, $date, $time, $category);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
            "status" => "Success",
        ));
        exit;
    }

}
?>

