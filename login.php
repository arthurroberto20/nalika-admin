<!DOCTYPE html>
<html>
<head>
       <link rel="stylesheet" href="css/bootstrap.min.css">
              <link rel="stylesheet" href="css/loginn.css">

       <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" media="screen" />
 
    <!-- admin custom CSS -->
    <title></title>
</head>
<body>

</body>
</html>
<?php
// core configuration
include_once "config/core.php";
 
// set page title
$page_title = "Login";
 
// include login checker
$require_login=false;
include_once "login_checker.php";
 
// default to false
$access_denied=false;
 
// if the login form was submitted
if($_POST){
    // include classes
include_once "config/database.php";
include_once "objects/user.php";
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$user = new User($db);
 
// check if email and password are in the database
$user->email=$_POST['email'];
 
// check if email exists, also get user details using this emailExists() method
$email_exists = $user->emailExists();
 
// validate login
if ($email_exists && password_verify($_POST['password'], $user->password) && $user->status==1){
 
    // if it is, set the session value to true
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user->id;
    $_SESSION['access_level'] = $user->access_level;
    $_SESSION['firstname'] = htmlspecialchars($user->firstname, ENT_QUOTES, 'UTF-8') ;
    $_SESSION['lastname'] = $user->lastname;
 
    // if access level is 'Admin', redirect to admin section
    if($user->access_level=='Admin'){
        header("Location: {$home_url}admin/index.php?action=login_success");
    }
 
    // else, redirect only to 'Customer' section
    else{
        header("Location: {$home_url}index.php?action=login_success");
    }
}
 
// if username does not exist or password is wrong 
else{
    $access_denied=true;
}
} 
// include page header HTML
include_once "navigation.php";
 
echo "<div class='col-sm-6 col-md-4 col-md-offset-4'>";
 
    // alert messages will be here
 
    // actual HTML login form
    echo "<div class='account-wall'>";
        echo "<div id='my-tab-content' class='tab-content'>";
            echo "<div class='tab-pane active' id='login'>";
                echo "<img class='profile-img' src='img/login-coffe.gif'>";
                echo "<form class='form-signin' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                    echo "<input type='text' name='email' class='form-control' placeholder='Email' required autofocus />";
                    echo "<input type='password' name='password' class='form-control' placeholder='Password' required />";
                    echo "<input type='submit' class='btn btn-lg btn-primary btn-block' value='Log In' />";
                    echo "<div class='margin-1em-zero text-align-center'>
    <a href='{$home_url}forgot_password'>Forgot password?</a>
</div>";
                echo "</form>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
 
echo "</div>";
 
// footer HTML and JavaScript codes
include_once "layout_foot.php";
?>