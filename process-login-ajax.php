<?php
    //checks the login info against the database and sends info back to the js page

    session_start();
    //link database and check if token is the same
    ini_set("session.cookie_httponly", 1);
    require 'database.php';

?>

<?php
    
    header("Content-Type: application/json"); // setting the proper header, not an html page
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true); // getting the information from the fetch() call, decoding it so it's callable
    
    $username = trim((string)$json_obj['username']); //cleaning input
    $password = trim((string)$json_obj['password']); //cleaning input

    //checking the session token against what was sent through the fetch call
    // if(!hash_equals($_SESSION['token'], $grabbed_token)){
    //     console.log($_SESSION['token'] + " --- " + $grabbed_token);
        
    //     die("Request forgery detected");
    // }
    
    //Check for username with posted username against the database
    $stmt = $mysqli->prepare("select Count(*), id, password_hash from users where user_name=?");
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
    
    $stmt->bind_result($cnt, $user_id, $pass_hash);
    $stmt->fetch();

    //Couldn't find username
    if($cnt == 0){
        $_SESSION['error_message'] = "No account with that username.";
        echo json_encode(array(
            "success" => false,
            "message" => $_SESSION['error_message']
        ));
        exit;
    }
    else {
        //Found username and check hashed passwords against each other
        if($cnt == 1 && password_verify($password, $pass_hash)){
            
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 
            // send data back
            echo json_encode(array(
                "success" => true,
                'token' => $_SESSION['token']
            ));
    
            exit;
        }
        else {

            //username was found but password was wrong
            $_SESSION['error_message'] = 'Invalid Login';
            echo json_encode(array(
                "success" => false,
                "message" => $_SESSION['error_message']
            ));
            exit;
        }
    }
    $stmt->close();
           
?> 