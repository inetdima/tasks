<?php
// Database Constants
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "base_tasks");
$con = mysql_connect(DB_SERVER,DB_USER, DB_PASS) or die(mysql_error());
    mysql_select_db(DB_NAME) or die("Cannot select DB");
$query =mysql_query("SELECT status FROM tasks group by status");
$query =mysql_query("SELECT * FROM tasks, projects WHERE `tasks`.`project_id` = `projects`.`id`");
$numrows=mysql_num_rows($query);
while($row=mysql_fetch_assoc($query)) {
    echo '<pre>';
    print_r($row);
    echo '</pre>';
}
?>