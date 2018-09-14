<?php
session_start();
require_once 'database.php';

$db = new Database();
$db->connect();

if(isset($_SESSION["user_id"])){
// echo "Session is set"; // for testing purposes
    header("Location: tasks.php");
}

if(isset($_POST["login"])){
        if(!empty($_POST['username']) && !empty($_POST['password'])) {
            $username=$_POST['username'];
            $password=$_POST['password'];

            $logged_if = $db->select('users', '*', "username='".$username."' AND password='".$password."'");
            $logged = $db->getResult();
            if($logged_if) {
                    $_SESSION['username']=$logged['username'];
                    $_SESSION['user_id']=$logged['id'];
                    // Redirect browser
                    header("Location: tasks.php");
            } else {
                $message =  "Invalid username or password!";
            }
        } else {
            $message = "All fields are required!";
        }
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
       <meta charset="utf-8">
       <title>login</title>
      <link href="style.css" media="screen" rel="stylesheet">
</head>
<body>
            <h1>LOGIN</h1>
    <?php if (!empty($message)) {echo "<p class=\"error\">" . "MESSAGE: ". $message . "</p>";} ?>
    <div class="container mlogin">
            <form name="loginform" id="loginform" action="" method="POST">
        <div id="login">
                <p>
                    <label for="user_login">Username<br />
                    <input type="text" name="username" id="username" class="input" value="" size="20" /></label>
                </p>
                <p>
                    <label for="user_pass">Password<br />
                    <input type="password" name="password" id="password" class="input" value="" size="20" /></label>
                </p>
        </div>
                    <p class="submit">
                    <input type="submit" name="login" class="button" value="Log In" />
                </p>
                <p class="regtext">No account yet?<br> <a href="register.php" >Register Here</a>!</p>
            </form>
    </div>

	<?php include("includes/footer.php"); ?>
