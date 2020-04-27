<?php

ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

if(!$json_obj==null){
    $title = trim((string)$json_obj['title']);

    //get userid, date, time, token
    
    $user_id = $_SESSION['user_id'];
    $date = trim((string)$json_obj['date']);
    $time = trim((string)$json_obj['time']);
    $token = trim((string)$json_obj['token']);
    $groupusers = $json_obj['groupusers'];
    
    if(!hash_equals($_SESSION['token'], $token)){
        die("Request forgery detected");
    }
    $category = trim((string)$json_obj['category']);
    $grabbed_token = $json_obj['token'];
    
    $count = 0;
    //checks for events associated with the logged in user
    if(isset($title) && isset($user_id) && isset($date) && isset($time)){

        while($count < count($groupusers) + 1){
            $stmt = $mysqli->prepare("insert into events (user_id, name, date, time, category) values (?,?,?,?,?)");
                
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
    
            // echo json_encode(array(
            //     "success" => $title,
            // ));
            if($count != count($groupusers)){
                $user_id = $groupusers[(int)$count];
            }
            $count = $count + 1;
        }
        echo json_encode(array(
            "error" => "Error",
            "message" => count($groupusers)
    
        ));
        exit;
    
        }
        else{
            echo json_encode(array(
            "error" => "Error",
            "message" => "Exact same event already exists."
        ));
        exit;
        }
    }
    else{
        echo json_encode(array(
            "error" => "Error",
            "message" => "Incorrect Username or Password"
        ));
        exit;
    }
?>