<?php
require_once 'data-bd.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'start.php';

try {
  // список полей с ошибками
  $fields_with_error = [];

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // проверка заполнения обязательных полей
    $fields_with_error = check_fields_required(['name', 'project']);

    // дата
    if (isset($_POST['date']) && $_POST['date']) {
      $date_deadline = $_POST['date'];
      // проверка формата даты на валидность
      if (is_correct_date($date_deadline)) {
        // сохранить дату в формате для БД
        $date_deadline = format_date($date_deadline, $format = 'Y.m.d H:i:s');
      } else {
        $fields_with_error['date'] = 'Некорретная дата';
      }
    } else {
      $date_deadline = '';
    }

    if (!isset($fields_with_error['project'])) {
      // проверка существования выбранного идентификатора проекта
      if (!check_is_correct_project_id($projects, $_POST['project'])) {
        show_error_content('Проект с id ' . $_POST['project'] . ' не найден');
      }
    }

    // если нет ошибок валидации - сохранить данные
    if (empty($fields_with_error)) {
      // начать транзакцию
      trans_begin($link);
      // добавить задачу в БД
      $id_task = add_task($link, [
        $_POST['name'],
        empty($_POST['date']) ? null : $_POST['date'],
        $_POST['project'],
        $current_user_id
      ]);

      $result_load = true;
      if ($id_task) {
        // загрузить файл
        if (isset($_FILES['preview']) && $_FILES['preview']['tmp_name']) {
          $tmp_file_name = $_FILES['preview']['tmp_name'];
          $file_path = __DIR__ . $directory_upload_file;
          if(!file_exists($file_path)) {
            mkdir($file_path);
          }
          $uploaded_file = uniqid();
          $file_url = $directory_upload_file . $uploaded_file;
          if (!is_uploaded_file($tmp_file_name)) {
            throw new Exception('Не удалось загрузить файл');
          }
          if (!move_uploaded_file($tmp_file_name, $file_path . $uploaded_file)) {
            throw new Exception('Не удалось сохранить файл на сервере');
          }
          // сохранить ссылку на файл в БД
          $sql_set_file_task = 'UPDATE task SET attached = ? WHERE id = ?';
          $result_load = is_res_stmt($link, $sql_set_file_task, [$uploaded_file, $id_task]);
        } else {
          $uploaded_file = "";
        }
      }

      if ($id_task && $result_load) {
        // завершить транзакцию
        trans_commit($link);
        header('Location: ' . $script_name);
        exit();
      } else {
        $error = mysqli_error($link);
        // откатить транзакцию
        trans_rollback($link);
        show_error_content($error);
      }
    }
  }

  $page_content = include_template('form-task.php', [
    'projects' => $projects,
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