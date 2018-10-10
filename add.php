<?php
require_once 'functions.php';
require_once 'init.php';

// пользователь
$user = get_user_data($link, $sql_user, $current_user_id);
$user_name = get_user_name($user);

// проекты
$projects = get_projects($link, $sql_projects, $current_user_id);

// количество задач в проектах
$count_task_in_projects = get_count_tasks($link, $show_complete_tasks, $current_user_id);

$page_content = include_template ('form-task.php', [
'projects' => $projects,
]);

$side_content = include_template ('side-projects.php', [
  'projects' => $projects,
  'count_task_in_projects' => $count_task_in_projects,
  'scriptname' => $script_name,
  'active_project_id' => 0
]);

$layout_content = include_template('layout.php', [
  'main_content' => $page_content,
  'side_content' => $side_content,
  'user_name' => $user_name,
  'title' => $title
]);

echo $layout_content;