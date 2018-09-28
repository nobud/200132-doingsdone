<?php
require_once 'queries.php';
require_once 'functions.php';
require_once 'init.php';

// имя страницы
$title = 'Дела в порядке';
$projects = [];
$tasks = [];
$error_content = '';
$current_user_id = 1;

if (!$link) {
  $error = mysqli_connect_error();
  $error_content = include_template('error.php', ['error' => $error]);
} else {
  $res_user = get_res_stmt($link, $sql_user, [$current_user_id]);
  $content = get_row($link, $res_user);
  $user_name = $content['value']['name'];
  $error_content = $content['error_content'];

  $res_projects = get_res_stmt($link, $sql_projects, [$current_user_id]);
  $content = get_rows($link, $res_projects);
  $projects = $content['values'];
  $error_content = $content['error_content'];
  if (!strlen($error_content)) {
    $res_tasks = get_res_stmt($link, $sql_tasks, [$current_user_id]);
    $content = get_rows($link, $res_tasks);
    $tasks = $content['values'];
    $error_content = $content['error_content'];
  }
  $page_content = include_template ('index.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => rand(0, 1)
  ]);
  $side_content = include_template ('side.php', [
    'projects' => $projects,
    'tasks' => $tasks
  ]);
  $layout_content = include_template('layout.php', [
    'main_content' => $page_content,
    'side_content' => $side_content,
    'error_content' => $error_content,
    'user_name' => $user_name,
    'title' => $title
  ]);
}

echo $layout_content;