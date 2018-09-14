<?php
require_once 'database.php';

$db = new Database();
$db->connect();
if(isset($_POST["register"])){


if(!empty($_POST['full_name']) && !empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password'])) {
	$full_name=$_POST['full_name'];
	$email=$_POST['email'];
	$username=$_POST['username'];
	$password=$_POST['password'];

    $db->select('users', '*', "username='".$username."' OR email='".$email."'");
    $logged = $db->getResult();
    if(!empty($logged)) {
    	 $message = "That username already exists! Please try another one!";
	} else {
        $registered = $db->insert('users', array($full_name,$email, $username, $password), 'full_name, email, username,password');
    	if($registered){
    	 $message = 'Account Successfully Created <a href="index.php">Login Here</a>';
    	} else {
    	 $message = "Failed to insert data information!";
    	}
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
       <title>REGISTER</title>
      <link href="style.css" media="screen" rel="stylesheet">
</head>
<body>
	<h1>REGISTER</h1>
<?php if (!empty($message)) {echo "<p class=\"error\">" . "MESSAGE: ". $message . "</p>";} ?>
<div class="container mregister">
<form name="registerform" id="registerform" action="register.php" method="post">
			<div id="login">
	<p>
		<label for="user_login">Full Name<br />
		<input type="text" name="full_name" id="full_name" class="input" size="32" value=""  /></label>
	</p>


	<p>
		<label for="user_pass">Email<br />
		<input type="email" name="email" id="email" class="input" value="" size="32" /></label>
	</p>

	<p>
		<label for="user_pass">Username<br />
		<input type="text" name="username" id="username" class="input" value="" size="20" /></label>
	</p>

	<p>
		<label for="user_pass">Password<br />
		<input type="password" name="password" id="password" class="input" value="" size="32" /></label>
	</p>

	</div>

    		<p class="submit">
    		<input type="submit" name="register" id="register" class="button" value="Register" />
    	</p>

	<p class="regtext">Already have an account? <a href="index.php" >Login Here</a>!</p>
</form>

	</div>



	<?php include("includes/footer.php"); ?>