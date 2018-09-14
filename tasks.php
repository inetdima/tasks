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
<script>
$(document).ready(function(){
    $('.add_project').on('click', function(){
        $('.projects_list').append('<div class="project_list"><div class="title"><i class="fa fa-calendar" aria-hidden="true"></i><form action="#" class="form_add_project"><input type="text" name="name_project"><button type="submit">Save</button></form></div></div>');
    });
    $('.projects_list').on('click', '.project_list .title .edit', function () {
        var project_name = $(this).parents('.title').find('.name').text();
        if($(this).parents('.title').find('.form_edit_project').length == 0) $(this).parents('.title').find('.name').hide().after('<form action="#" class="form_edit_project"><input type="text" name="name_project" value="'+project_name+'"><button type="submit">Save</button></form>');
        else {
            $(this).parents('.title').find('.name').show();
            $(this).parents('.title').find('.form_edit_project').remove();
        }
    });
    $('.projects_list').on('submit', 'form.form_add_project', function () {
        var name_project = $(this).find('input').val();
        if ( name_project != '' ) {
        $.ajax({
            type: "POST",
            url: "actions.php",
            data: {project_action: 'add', project: name_project}
        }).done(function( result ) {
            show_projects();
        });
        }
        return false;
    });
    $('.projects_list').on('submit', 'form.form_edit_project', function () {
        var item_project = $(this).parents('.project_list');
        var name_project = $(this).find('input').val();
        if ( name_project != '' ) {
            console.log(item_project.data('id'));
            $.ajax({
                type: "POST",
                url: "actions.php",
                data: {project_action: 'edit', project: name_project, idProject: item_project.data('id')}
            }).done(function( result ) {
                console.log(result);
                show_projects();
            });
        }
        return false;
    });
    $('.projects_list').on('click', '.project_list .title .delete', function () {
        if( confirm('Are you sure you want to delete the project?') ) {
            var item_project = $(this).parents('.project_list');
            if ( item_project.data('id') != '' ) {
                $.ajax({
                    type: "POST",
                    url: "actions.php",
                    data: {project_action: 'delete', idProject: item_project.data('id')}
                }).done(function( result ) {
                    item_project.remove();
                    //alert('Successfully removed project');
                });
            }
        }
        return false;
    });
    /*task*/
    $('.projects_list').on('submit', 'form.add_task', function () {
        var id_project = $(this).parents('.project_list').data('id');
        var name_task = $(this).find('input').val();
        if ( name_task != '' ) {
            $.ajax({
                type: "POST",
                url: "actions.php",
                data: {newTask: name_task, idProject: id_project}
            }).done(function( result ) {
                show_projects();
            });
        }
        return false;
    });
    $('.projects_list').on('click', '.task_item .edit', function () {
        var task_name = $(this).parents('.task_item').find('.task_title').text();
        if($(this).parents('.task_item').find('.form_edit_task').length == 0) $(this).parents('.task_item').find('.task_title').hide().after('<form action="#" class="form_edit_task task_title"><input type="text" name="name_task" value="'+task_name+'"><button type="submit">Save</button></form>');
        else {
            $(this).parents('.task_item').find('.task_title').show();
            $(this).parents('.task_item').find('.form_edit_task').remove();
        }
    });
    $('.projects_list').on('click', '.task_item .deadline_edit', function () {
        var deadline = $(this).parents('.task_deadline').find('span').text();
        if($(this).parents('.task_deadline').find('.form_deadline_edit').length == 0) $(this).parents('.task_deadline').find('span').hide().after('<form action="#" class="form_deadline_edit"><input type="date" name="deadline" value="'+deadline+'"><button type="submit">Save</button></form>');
        else {
            $(this).parents('.task_deadline').find('span').show();
            $(this).parents('.task_deadline').find('.form_deadline_edit').remove();
        }
    });
    $('.projects_list').on('submit', 'form.form_deadline_edit', function () {
        var item_task = $(this).parents('.task_item');
        var deadline = $(this).parents('.task_deadline').find('input').val();
        if ( deadline != '') {
            $.ajax({
                type: "POST",
                url: "actions.php",
                data: {task_action: 'deadline', deadline: deadline, idTask: item_task.data('id')}
            }).done(function( result ) {
                console.log(result);
                show_projects();
            });
        }
        return false;
    });
    $('.projects_list').on('click', '.task_item .up', function () {
        var id_project = $(this).parents('.project_list').data('id');
        var item_task = $(this).parents('.task_item');
            $.ajax({
                type: "POST",
                url: "actions.php",
                data: {task_action: 'order', order: 'up', idTask: item_task.data('id'), idProject: id_project}
            }).done(function( result ) {
                console.log('result', result);
                 show_projects();
           });
    });
    $('.projects_list').on('click', '.task_item .down', function () {
        var id_project = $(this).parents('.project_list').data('id');
        var item_task = $(this).parents('.task_item');
            $.ajax({
                type: "POST",
                url: "actions.php",
                data: {task_action: 'order', order: 'down', idTask: item_task.data('id'), idProject: id_project}
            }).done(function( result ) {
                console.log('result', result);
                show_projects();
            });
    });
    $('.projects_list').on('change', '.task_item [type="checkbox"]', function () {
        var item_task = $(this).parents('.task_item');
        var item_task_checkbox = $(this).is(':checked');
            $.ajax({
                type: "POST",
                url: "actions.php",
                data: {task_action: 'status', status: item_task_checkbox, idTask: item_task.data('id')}
            }).done(function( result ) {
            });
        if(item_task_checkbox) $(this).parents('.task_item').addClass('done');
        else $(this).parents('.task_item').removeClass('done');
        return false;
    });
    $('.projects_list').on('submit', 'form.form_edit_task', function () {
        var item_task = $(this).parents('.task_item');
        var name_task = $(this).find('input').val();
        if ( name_task != '' && name_task != item_task.find('div.task_title').text()) {
            $.ajax({
                type: "POST",
                url: "actions.php",
                data: {task_action: 'edit', task: name_task, idTask: item_task.data('id')}
            }).done(function( result ) {
                show_projects();
            });
        }
        return false;
    });
    $('.projects_list').on('click', '.task_item .delete', function () {
        if( confirm('Are you sure you want to delete the task?') ) {
            var item_task = $(this).parents('.task_item');
            if ( item_task.data('id') != '' ) {
                $.ajax({
                    type: "POST",
                    url: "actions.php",
                    data: {task_action: 'delete', idTask: item_task.data('id')}
                }).done(function( result ) {
                    item_task.remove();
                    //alert('Successfully removed project');
                });
            }
        }
        return false;
    });
    show_projects();
});
function show_projects(){
    $.ajax({
        type: "POST",
        url: "actions.php",
        dataType: 'json',
        data: {show_projects:'yes'}
    }).done(function(result) {
        console.log(result['projects']);
        if( !$.isEmptyObject(result['projects']) ) {
            if(!result['projects'][0]) result['projects'][0] = result['projects'];
            var nowDate= new Date();
            var all_projects = '';
            $.each( result['projects'], function(i, project){
                if(project['id']) {
                    all_projects += '<div class="project_list" data-id="'+project['id']+'"><div class="title"><i class="fa fa-calendar" aria-hidden="true"></i><span class="name">'+project['name']+'</span>';
                    all_projects += '<button class="delete"><i class="fa fa-trash" aria-hidden="true"></i></button><button class="edit"><i class="fa fa-pencil" aria-hidden="true"></i></button></div>';
                    all_projects += '<form action="#" class="add_task"><input type="text" placeholder="Start typing here to create a task..."><button class="edit">Add Task</button></form>';
                    if(!result['tasks'][0]) result['tasks'][0] = result['tasks'];
                    $.each( result['tasks'], function(i, task){
                        if(project['id'] == task['project_id']) {
                            var otherDate=new Date(task['date']);
                            var delta=otherDate.getTime()-nowDate.getTime();
                            var daysago = delta/1000/60/60/24;
                            all_projects += '<div class="task_item';
                            if(daysago > 1 && daysago < 3) all_projects += ' soon_deadline';
                            if(daysago < 1) all_projects += ' already_deadline';
                            if(task['status'] == 'done') all_projects += ' done';
                            all_projects += '" data-id="'+task['id']+'">';
                            all_projects += '<span class="checkbox"><input type="checkbox"';
                            if(task['status'] == 'done') all_projects += ' checked="checked"';
                            all_projects += '></span><div class="task_title">'+task['name']+'</div>';
                            all_projects += '<div class="task_actions"><button class="order"><span class="up"></span><span class="down"></span></button><button class="edit"><i class="fa fa-pencil" aria-hidden="true"></i></button><button class="delete"><i class="fa fa-trash" aria-hidden="true"></i></button></div>';
                            all_projects += '<div class="task_deadline"><span title="deadline task">'+task['date']+'</span><button class="deadline_edit"><i class="fa fa-pencil" aria-hidden="true"></i></button></div></div>';
                        }
                    });
                    all_projects += '</div>';
                }
            });
            $('.projects_list').html(all_projects);
        }
    });
}
</script>
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