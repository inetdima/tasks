<?php
session_start();
if(!isset($_SESSION["user_id"])) {
    header("location:index.php");
} else {
?>
<!DOCTYPE html>
<html lang="en">
  <head>
       <meta charset="utf-8">
    <title>Simple todo lists</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="jquery.js"></script>
    <script src="ajax.js"></script>
</head>
<body>
    <h1>Simple todo lists</h1>
    <div class="subtitle">From ruby garage<br>Welcome, <span><?php echo $_SESSION['username'];?>!</span> <a href="logout.php">Logout</a></div>
    <div class="projects_list">
        <div class="project_list">
        </div>
    </div>
    <button class="add_project button">Add TODO List</button>
    <div class="copyright">&copy; Ruby Garage</div>
</body>
</html>
<?php } ?>
