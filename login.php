
<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="login-style.css">
</head>

<body> 
<?php
    ini_set("session.cookie_httponly", 1);
	session_start(); 
	require 'database.php';
?>
    <!-- Login Console for user -->
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-sm-7 col-md-6 col-lg-4 align-self-center mx-auto">
                <div id="login-block">
                    <div class="card text-center login-card">
                        <div class="card-body">
                            <h3 class="card-title p-2">Login</h3>
                            <hr>
                            <form class="p-2" method="POST" action="process-login.php">
                                <div class="form-group row">
                                    <label for="username" class="col-form-label col-sm-4">Username</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" id="username" placeholder="username" name="username" required>
                                    </div>
									<label for="password" class="col-form-label col-sm-4 pass-class">Password</label>
                                    <div class="col-8 pass-class">
										<input type="password" class="form-control" id="password" placeholder="password" name="password" required>
                                    </div>
								</div>
								<input type="hidden" name="token" value="<?php 	$_SESSION['token'] = bin2hex(random_bytes(32)); echo $_SESSION['token'];?>" />
								<button type="submit" class="btn btn-primary">Login</button>
								
                            </form>

                            <!-- Create Account Option -->
							<form method='post' action="create-account.php">
								<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
								<button type="submit" class="btn btn-primary" id="create-account" >Sign Up</button>
                            </form>

                            <!-- Guest Option -->
							<form method='post' action="calendar.php">
								<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                                <input type="hidden" name="guest" value="<?php $_SESSION['guest'] = true; $_SESSION['username'] = "Guest"; echo $_SESSION['guest'];?>" />
								<button type="submit" class="btn btn-primary">Continue as Guest</button>
							</form>

                        </div>

                    </div>
                    <!-- Prints out error message based on attempt -->
                    <div id="login-attempt" class="p-3 mx-auto">
                        <?php
                            if(isset($_SESSION['error_message'])){
                                echo '<p id="error-message">' . $_SESSION['error_message'] . ' </p>';
							} 
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div> 

</body>

</html>