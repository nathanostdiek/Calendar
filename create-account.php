<?php
    ini_set("session.cookie_httponly", 1);
    session_start(); 
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="login-style.css">
</head>

<body> 
    <!-- Create Account Console -->
    <div class="container">
    <h3 class="card-title p-2">Create New Account</h3>
    <hr>
    <form class="form p-2" method="POST" action="process-create-account.php">
        <div class="form-group row text-center">
            <label for="username" class="col-form-label col-sm-4">Username</label>
            <div class="col-7">
                <input type="text" class="form-control" id="username" placeholder="username" name="username" required>
            </div>
            <label for="password" class="col-form-label col-sm-4 pass-class">Password</label>
            <div class="col-7 pass-class">
                <input type="password" class="form-control" id="password" placeholder="password" name="password" required>
            </div>
            <label for="cpassword" class="col-form-label col-sm-4 pass-class">Confirm Password</label>
            <div class="col-7 pass-class">
                <input type="password" class="form-control" id="cpassword" placeholder="confirm password" name="confirmpassword" required>
            </div>

        </div>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <div class="col text-center">
        <button type="submit" class="btn btn-primary text-block">Create Account</button>

        </div>

    </form>

    <form class="col text-center" action="login.php">
        <button type="submit" class="btn btn-primary" id="create-account">Cancel</button>
    </form>

    <!-- Print Error message to inform user -->
    <div id="login-attempt" class="p-3 mx-auto">
        <?php
            if(isset($_SESSION['error_message2'])){
                echo '<p id="error-message">' . $_SESSION['error_message2'] . ' </p>';
            } 
        ?>
    </div>
    </div>
</body>

</html>