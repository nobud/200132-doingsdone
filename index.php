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

  // флаг - показывать завершенные задачи
  $show_complete_tasks = isset($_GET['show_completed']) && $_GET['show_completed'] ? 1 : 0;

  // проекты
  $projects = get_projects($link, $sql_projects, $current_user_id);
  // количество задач в проектах
  $count_task_in_projects = get_count_tasks($link, $show_complete_tasks, $current_user_id);

  $is_set_project_id = isset($_GET['id']);
  $active_project_id = $_GET['id'] ?? 0;
  // если в параметрах запроса задан id проекта
  if ($is_set_project_id) {
    // проверить - существует ли выбраный id проекта
    if (!check_is_correct_project_id($projects, $active_project_id)) {
      show_error_content('Проект с id=' . $active_project_id . ' не найден');
    }
  }

  // изменение статуса задачи при клике
  $id_task = $_GET['task_id'] ?? 0;
  if ($id_task && isset($_GET['check'])) {
    // начать транзакцию
    trans_begin($link);
    // инвертировать статус задачи
    $is_set = change_status_task($link, [
      $id_task,
    ]);
    if ($is_set) {
      // завершить транзакцию
      trans_commit($link);
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
      $error = mysqli_error($link);
      // откатить транзакцию
      trans_rollback($link);
      show_error_content($error);
    }
  }

  // получить список задач
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
    'active_project_id' => $active_project_id,
    'show_complete_tasks' => $show_complete_tasks
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

