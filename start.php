<?php
require_once 'data-bd.php';
require_once 'functions.php';
require_once 'init.php';

session_start();

if (!isset($_SESSION['user'])) {
header('Location: /guest.php');
exit();
}

try{
  // текущий пользователь
  $current_user_id = $_SESSION['user']['id'];

  // флаг - показывать завершенные задачи
  $show_complete_tasks = isset($_GET['show_completed']) && $_GET['show_completed'] ? 1 : 0;

  // проекты
  $projects = get_projects($link, $sql_projects, $current_user_id);
  // количество задач в проектах
  $count_task_in_projects = get_count_tasks($link, $show_complete_tasks, $current_user_id);

  $active_project_id = $_GET['id'] ?? 0;
}
catch(Exception $e) {
  show_error_content($e->getMessage());
}