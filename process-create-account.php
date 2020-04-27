<?php
    //creates a new account by accessing the database 
    ini_set("session.cookie_httponly", 1);
    session_start();
    require 'database.php';


    header("Content-Type: application/json"); // setting the proper header, not an html page
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true); // getting the information from the fetch() call, decoding it so it's callable
    
    $username = trim((string)$json_obj['username']); //cleaning input
    $password = trim((string)$json_obj['password']); //cleaning input
    $cpassword = trim((string)$json_obj['cpassword']); //cleaning input
    //$grabbed_token = $json_obj['token'];

    

    //check passwords confirm
    if($password != $cpassword){
        $_SESSION['error_message2'] = "Passwords did not match";

        echo json_encode(array(
            "success" => false,
            "message" => $_SESSION['error_message']
        ));
        exit;
    }

    //pulls user from the session variable
    $stmt = $mysqli->prepare("select * from users where user_name=?");
    //checks the statement
    if(!$stmt){
        $_SESSION['error_message'] = $mysqli->error;
        echo json_encode(array(
            "success" => false,
            "message" => $_SESSION['error_message']
        ));
        exit;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    //check username isn't already taken
    if($row > 0){
        $_SESSION['error_message'] = "Username '" . $username . "' already exists. Account not created.";
        echo json_encode(array(
            "success" => false,
            "message" => $_SESSION['error_message']
        ));
        exit;
    }

    //create account and send back to login
    else {

        $hashed_pass = password_hash($password, PASSWORD_BCRYPT); //hashes the password
        $stmt2 = $mysqli->prepare("insert into users (user_name,password_hash) values (?,?)");
        //checks the statement
        if(!$stmt2){
            $_SESSION['error_message2'] = $mysqli->error;
            echo json_encode(array(
                "success" => false,
                "message" => $_SESSION['error_message']
            ));
            exit;
        }
        $stmt2->bind_param('ss', $username, $hashed_pass);
        $stmt2->execute();
        $stmt2->close();

        //sets the session variables
        $_SESSION['username'] = $username;
        $_SESSION['error_message'] = "Account Created.";

        //returns json array with the success and username
        echo json_encode(array(
            "success" => true,
            "new_user" => $username
        ));
        
        exit;
        
    }

?>