<?php

    //link database and check if token is the same
    ini_set("session.cookie_httponly", 1);
    session_start();
    require 'database.php';
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");

    }
    $password = trim((string) $_POST['password']);
    
    //Check for username with posted username
    $stmt = $mysqli->prepare("select Count(*), id, password_hash from users where user_name=?");
    if(!$stmt){
        $_SESSION['error_message'] = $mysqli->error;
        header("Location: login.php");
        exit;
    }
    $stmt->bind_param('s', $username);
    $username = trim((string) $_POST['username']);

    $stmt->execute();
    
    $stmt->bind_result($cnt, $user_id, $pass_hash);
    $stmt->fetch();

    //Couldn't find username
    if($cnt == 0){
        $_SESSION['error_message'] = "No account with that username.";
        header("Location: login.php");
        exit;
    }
    else {
        //Found username and check hashed passwords against each other
        if($cnt == 1 && password_verify($password, $pass_hash)){
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
    
            header("Location: calendar.php");
            exit;
        }
        else {
            $_SESSION['error_message'] = 'Invalid Login';
            header("Location: login.php");
            exit;
        }
        
    }

    $stmt->close();
            
?> 
