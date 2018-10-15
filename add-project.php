<?php
require_once 'data-bd.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'start.php';

try {
  // список полей с ошибками
  $fields_with_error = [];
  $id_project = 0;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // проверка заполнения обязательных полей
    $fields_with_error = check_fields_required(['name']);

    $name = $_POST['name'];
    if (!isset($fields_with_error['name'])) {
      // проверка существования проекта с таким же именем у текущего пользователя
      if (is_project_exist($link, $current_user_id, $name)) {
        $fields_with_error['name'] = 'Проект с таким именем уже существует';
      }
    }

    // если нет ошибок валидации - сохранить данные
    if (empty($fields_with_error)) {
      // начать транзакцию
      trans_begin($link);
      // добавить проект в БД
      $id_project = add_project($link, [
        $name,
        $current_user_id
      ]);

      if ($id_project) {
        // завершить транзакцию
        trans_commit($link);
        header('Location: '  . set_url(['id' =>  $id_project], $script_name));
        exit();
      } else {
        $error = mysqli_error($link);
        // откатить транзакцию
        trans_rollback($link);
        show_error_content($error);
      }
    }
  }

  $page_content = include_template('form-project.php', [
    'errors' => $fields_with_error
  ]);

  $side_content = include_template('side-projects.php', [
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