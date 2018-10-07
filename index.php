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
$show_complete_tasks = rand(0, 1);

// если подключение не установлено
if (!$link) {
  // подготовить шаблон с сообщением об ошибке
  $error = mysqli_connect_error();
  $error_content = include_template('error.php', ['error' => $error]);
} else {
  // подключение установлено - выполнить запросы
  // пользователь
  $res_user = get_res_stmt($link, $sql_user, [$current_user_id]);
  $content = get_row($link, $res_user);
  $user_name = $content['value']['name'];
  $error_content = $content['error_content'];
  if (!strlen($error_content)) {
    // проекты
    $res_projects = get_res_stmt($link, $sql_projects, [$current_user_id]);
    $content = get_rows($link, $res_projects);
    $projects = $content['values'];
    $error_content = $content['error_content'];
    // если список проектов получен без ошибок
    if (!strlen($error_content)) {
      // получить количество задач в проекте
      $res_count_tasks_in_projects = get_res_stmt($link, get_sql_count_task_in_project($show_complete_tasks), [$current_user_id]);
      $content = get_rows($link, $res_count_tasks_in_projects);
      $count_task_in_projects = array_column($content['values'], 'count_tasks', 'id');
      $error_content = $content['error_content'];
      // задачи
      $res_tasks = null;
      // если в параметрах запроса задан id проекта
      if (isset($_GET['id'])) {
        // получить id проектов
        $id_projects = array_column($projects, 'id');
        // если id выбранного проекта существует
        if (in_array($_GET['id'], $id_projects)) {
          // получить задачи для выбранного проекта
          $res_tasks = get_res_stmt($link, get_sql_tasks_in_project($show_complete_tasks), [$_GET['id']]);
        } else {
          // вернуть код ответа 404
          http_response_code($CODE_NOT_FOUND);
          $error_content = include_template('error.php', ['error' => 'Проект с id=' . $_GET['id'] . ' не найден']);
        }
        // если id проекта не задан
      } else {
        // получить все задачи пользователя
        $res_tasks = get_res_stmt($link, get_sql_tasks_all($show_complete_tasks), [$current_user_id]);
      }
      if ($res_tasks) {
        $content = get_rows($link, $res_tasks);
        $tasks = $content['values'];
        $error_content = $error_content . $content['error_content'];
      }
    }

    $page_content = include_template ('index.php', [
      'projects' => $projects,
      'tasks' => $tasks,
      'show_complete_tasks' => $show_complete_tasks
    ]);

    $side_content = include_template ('side-projects.php', [
      'projects' => $projects,
      'count_task_in_projects' => $count_task_in_projects,
      'scriptname' => pathinfo(__FILE__, PATHINFO_BASENAME)
    ]);

    $layout_content = include_template('layout.php', [
      'main_content' => $page_content,
      'side_content' => $side_content,
      'error_content' => $error_content,
      'user_name' => $user_name,
      'title' => $title
    ]);
  }
}

echo $layout_content;