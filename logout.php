<?php
    //logs out the user and destroys the session. sends back a json array to confirm
    ini_set("session.cookie_httponly", 1); //security
    session_start();
    session_destroy();

    echo json_encode(array(
        "success" => true
    ));
    exit;
?>