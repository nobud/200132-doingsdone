<?php
require_once 'data-bd.php';
require_once 'functions.php';
require_once 'init.php';

session_start();

if (!isset($_SESSION['user'])) {
 header('Location: /guest.php');
 exit();
}

try {
  // текущий пользователь
  $current_user_id = $_SESSION['user']['id'];
  // проекты
  $projects = get_projects($link, $sql_projects, $current_user_id);

  // количество задач в проектах
  $count_task_in_projects = get_count_tasks($link, $show_complete_tasks, $current_user_id);

  $active_project_id = 0;
  $is_set_project_id = isset($_GET['id']);
  // если в параметрах запроса задан id проекта
  if ($is_set_project_id) {
    // проверить - существует ли выбраный id проекта
    if (!check_is_correct_project_id($projects, $_GET['id'])) {
      show_error_content('Проект с id=' . $active_project_id . ' не найден');
    }
    $active_project_id = $_GET['id'];
  }

  // задачи
  $tasks = get_tasks($link, $is_set_project_id, $show_complete_tasks, $current_user_id);

  $page_content = include_template ('index.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'directory_upload_file' => $directory_upload_file,
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
    'title' => $title,
    'user' => $_SESSION['user'] ?? []
  ]);

  echo $layout_content;
}
catch(Exception $e) {
  show_error_content($e->getMessage());
}

