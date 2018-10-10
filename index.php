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

$active_project_id = 0;
$is_set_project_id = isset($_GET['id']);
// если в параметрах запроса задан id проекта
if ($is_set_project_id) {
  // проверить - существует ли выбраный id проекта
  check_is_correct_project_id($projects, $_GET['id']);
  $active_project_id = $_GET['id'];
}

// задачи
$tasks = get_tasks($link, $is_set_project_id, $show_complete_tasks, $current_user_id);

$page_content = include_template ('index.php', [
  'projects' => $projects,
  'tasks' => $tasks,
  'show_complete_tasks' => $show_complete_tasks
]);

$side_content = include_template ('side-projects.php', [
  'projects' => $projects,
  'count_task_in_projects' => $count_task_in_projects,
  'scriptname' => $script_name,
  'active_project_id' => $active_project_id
]);

$layout_content = include_template('layout.php', [
  'main_content' => $page_content,
  'side_content' => $side_content,
  'user_name' => $user_name,
  'title' => $title
]);

echo $layout_content;