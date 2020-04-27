<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    require 'database.php';  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendar</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/time-input-polyfill"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Sen&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="calendar-style.css">

</head>
<body>
    <div class="container-fluid">
        <div class="navbar navbar-expand-lg navbar-light bg-light dont-show" id="nav">
            
            <a class= "navbar-brand" id="current-user"></a>
        
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="category-toggle-drop" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Category Toggle
                        </a>
                        <div class="dropdown-menu" >
                            <div id='options' class="p-1">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input catform" value="all" checked>ALL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input catform" value="school">School
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input catform" value="work">Work
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input catform" value="family">Family
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input catform" value="friends">Friends
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input catform" value="other">Other
                                    </label>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="p-1"><button id="toggle-category-btn" class="btn btn-success btn-sm"> GO </button></div>
                        
                        </div>
                    </li>
                </ul>
                <div class="dont-show" id="logout-btn">
                    <button class="btn btn-dark">Logout</button>
                </div>
            </div>
        </div>




        <div class = "login-area p-3">
        
                <div class="row" id="username-group">
                    <label for="username" class="col-1 col-form-label">Username</label>
                    <div class="col-2">
                        <input type="text" class="form-control" id="username" placeholder="username" name="username" required>
                    </div>
                    
                </div>
                
                
                <div class="row" id="password-group">
                    <label for="password" class="col-1 col-form-label">Password</label>
                    <div class='col-2'>
                        <input type="password" class="form-control" id="password" placeholder="password" name="password" required>
                    </div>
                    <div class="p-1">
                    <button class="btn btn-primary" id='login-btn'>Login</button>
                    </div>
                    <div class="p-1">
                        <button class="btn btn-primary" id="create-btn" data-toggle="modal" data-target="#addAccountModal">Create Account</button>
                    </div>
                </div>
                <p id="error-message"></p>   
        </div>
        <div>
            <button class="btn btn-outline-success" id="today-btn">TODAY</button>
            <button class="btn btn-outline-danger" id="delete-all-btn">DELETE ALL EVENTS</button>

        </div>

       
        
        <div class="row justify-content-center p-3 ">
            <p id="current-month"></p>
            
        </div>
        <div class="row justify-content-center p-1">
            <div class="pr-2">
                <button class = "btn btn-light btn-sm button-circle p-1" id="prev_month_btn"><i class="fa fa-arrow-left"></i></button>
            </div>
            <div class="pl-2">
                <button class = "btn btn-light  btn-sm button-circle p-1" id="next_month_btn"><i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
        
        <div class="days row justify-content-center">
            <div class="sun px-5">
                <p>Sunday</p>
            </div>
            <div class="mon px-5">
                <p>Monday</p>
            </div>
            <div class="tues px-5">
                <p>Tuesday</p>
            </div>
            <div class="wed px-5">
                <p>Wednesday</p>
            </div>
            <div class="thurs px-5">
                <p>Thursday</p>
            </div>
            <div class="fri px-5">
                <p>Friday</p>
            </div>
            <div class="sat px-5">
                <p>Saturday</p>
            </div>
            
        </div>
        <div id="month">

        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" data-backdrop="static" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="day-title">will change</h3>
                    <button class="btn btn-light  btn-sm button p-2" id="add_event">Add Event</button>
                    <button class="btn btn-light  btn-sm button p-2" data-dismiss="modal" id="close_day">X</button>
                </div>
                <div class="modal-body">
                    <form method="post" id="add_form">
                        <h4 id="add_event_h4">Add Event for </h4>
                        <input type="text" placeholder="Name of Event" id="event_name" maxlength="50" required />
                        <input type="time" id="event_time" required />
                        <select class="" id="category-select" required>
                            <option value="" disabled selected>Category</option>
                            <option value="school">School</option>
                            <option value="work">Work</option>
                            <option value="family">Family</option>
                            <option value="friends">Friends</option>
                            <option value="other">Other</option>
                        </select>

                        
                            <button class="btn btn-light" id="get_other_users">
                                Add Event for Other Users
                            </button>
                            <div id="display-all-users">
                                <div class="showtheusers" id="show-all-users">
                                </div>
                            </div>
                        <input type="submit" class="btn btn-light  btn-sm button p-2" id="form_submit" value="Submit" />

                        <button class="btn btn-light  btn-sm button p-2" id="form_cancel">X</button>

                    </form>

                    <div id="display_events"></div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                <div class="form-group row text-center p-1">
                    <label for="new_username" class="col-form-label col-sm-4">Username</label>
                    <div class="col-7 p-1">
                        <input type="text" class="form-control" id="new_username" placeholder="username" name="new_username" required>
                    </div>
                    <label for="new_password" class="col-form-label col-sm-4 pass-class">Password</label>
                    <div class="col-7 pass-class p-1">
                        <input type="password" class="form-control" id="new_password" placeholder="password" name="new_password" required>
                    </div>
                    <label for="new_cpassword" class="col-form-label col-sm-4 pass-class">Confirm Password</label>
                    <div class="col-7 pass-class p-1">
                        <input type="password" class="form-control" id="new_cpassword" placeholder="confirm password" name="confirmpassword" required>
                </div>

        </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="submit-create-account">Create</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="http://classes.engineering.wustl.edu/cse330/content/calendar.min.js"></script>
    <script src="main.js"></script>
</body>
</html>