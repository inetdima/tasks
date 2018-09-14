<?php
require_once 'database.php';

$db = new Database();
$db->connect();
session_start();
if( isset($_POST["show_projects"]) ) {
    $db->select('projects', '*', 'user='.$_SESSION['user_id'], 'id ASC');
    $resultProjects = $db->getResult();
    $db->select('tasks', '*', 'user='.$_SESSION['user_id'], 'priority DESC');
    $resultTasks = $db->getResult();
    echo json_encode( array('projects'=>$resultProjects, 'tasks'=>$resultTasks) );
}
if( $_POST["project_action"] == 'add' && $_POST["project"] ) {
    $db->insert('projects',array('', $_POST["project"], $_SESSION['user_id']));
    echo 'Successfully added project';
}
if( $_POST["project_action"] == 'edit' && $_POST["project"] ) {
    $db->update('projects', array('name'=>$_POST["project"]), array('id',$_POST["idProject"]),"=");
    echo 'Successfully edited project';
}
if( $_POST["project_action"] == 'delete' && $_POST["idProject"] ) {
    $db->delete('projects', 'id='.$_POST["idProject"]);
    $res = $db->getResult();
    echo json_encode($res);
}
if( $_POST["task_action"] == 'delete' && $_POST["idTask"] ) {
    $db->delete('tasks', 'id='.$_POST["idTask"]);
    $res = $db->getResult();
    echo json_encode($res);
}
if( $_POST["task_action"] == 'edit' && $_POST["task"] ) {
    $db->update('tasks', array('name'=>$_POST["task"]), array('id',$_POST["idTask"]),"=");
    echo 'Successfully edited project';
}
if( $_POST["task_action"] == 'status' ) {
    if( $_POST["status"] == 'true' ) {
        $db->update('tasks', array('status'=>'done'), array('id',$_POST["idTask"]),"=");
    } else {
        $db->update('tasks', array('status'=>''), array('id',$_POST["idTask"]),"=");
    }
}
if( $_POST["task_action"] == 'order' ) {
    $db->select('tasks', '*', 'id='.$_POST["idTask"]);
    $resultTask = $db->getResult();
    $priority = $resultTask['priority'];
    $nextTask = false;
    if( $_POST["order"] == 'up' ) {
        $db->select('tasks', '*', "project_id='". $_POST["idProject"] ."' AND `priority`=". ($priority + 1) );
        $nextTask = $db->getResult();
        $db->update('tasks', array('priority'=>$priority+1), array('id',$_POST["idTask"]),"=");
        if($nextTask) $db->update('tasks', array('priority'=>$priority), array('id',$nextTask["id"]),"=");
        $result = $db->getResult();
    }
    if( $_POST["order"] == 'down' ) {
        $db->select('tasks', '*', "project_id='". $_POST["idProject"] ."' AND `priority`=". ($priority - 1) );
        $prevTask = $db->getResult();
        $db->update('tasks', array('priority'=>$priority - 1), array('id',$_POST["idTask"]),"=");
        if($prevTask) $db->update('tasks', array('priority'=>$priority), array('id',$prevTask["id"]),"=");
        $result = $db->getResult(); 
    }
}
if( $_POST["task_action"] == 'deadline' ) {
    if( $_POST["deadline"] ) {
        $db->update('tasks', array('date'=>$_POST["deadline"]), array('id',$_POST["idTask"]),"=");
    }
}
if( isset($_POST["newTask"]) ) {
    $db->select('tasks', '*', 'project_id='.$_POST["idProject"], 'priority DESC limit 1');
    $resultTasks = $db->getResult();
    $priority = $resultTasks['priority'] + 1;
    $db->insert( 'tasks', array('', $_POST["newTask"], '', '', $priority, $_POST["idProject"], $_SESSION['user_id']) );
    echo 'Successfully added task';
}
/*echo "<pre>";
print_r($resultTasks['id']);
echo "</pre>";
  */
?>