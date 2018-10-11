<?php
// Имя пользователя по его id
$sql_user = 'SELECT name FROM account WHERE id = ?';

// Список проектов у текущего пользователя
$sql_projects = 'SELECT * FROM project WHERE account_id = ?';

// Получить текст запроса - Количество задач для каждого проекта
function get_sql_count_task_in_project($show_complete_task) {
  $sql_count_task_in_project = 'SELECT project.id as id, count(project_id) AS count_tasks FROM project ' .
    'LEFT JOIN task ON (project.id = task.project_id' . get_sql_add_check_status($show_complete_task) . ') WHERE project.account_id = ? GROUP BY id, project_id';
  return $sql_count_task_in_project;
}

// сформировать часть запроса в зависимости от значения флажка "показывать выполненные задачи"
function get_sql_add_check_status($show_complete_task) {
  return $show_complete_task ? '' : ' AND status = 0 ';
}

// получить текст запроса - Список всех задач у текущего пользователя
function get_sql_tasks_all($show_complete_task) {
  $sql_tasks_all = 'SELECT * FROM task WHERE account_id = ? ' . get_sql_add_check_status($show_complete_task) . ' ORDER BY date_deadline DESC';
  return $sql_tasks_all;
}

// получить текст запроса - Список задач, относящихся к выбранному проекту
function get_sql_tasks_in_project($show_complete_task) {
  $sql_tasks_in_project = 'SELECT * FROM task WHERE project_id = ? ' . get_sql_add_check_status($show_complete_task) . ' ORDER BY date_deadline DESC';
  return $sql_tasks_in_project;
}

// получить данные о пользователе по его id
function get_user_data($link, $sql_user, $id) {
  $res_user = get_res_stmt($link, $sql_user, [$id]);
  $user = get_row($link, $res_user);
  return $user;
}

// получить имя пользователя из данных пользователя
function get_user_name($user) {
  return $user['name'];
}

// получить проекты
function get_projects($link, $sql_projects, $current_user_id) {
  $res_projects = get_res_stmt($link, $sql_projects, [$current_user_id]);
  $projects = get_rows($link, $res_projects);
  return $projects;
}

// получить количество задач в проектах
function get_count_tasks($link, $show_complete_tasks, $current_user_id) {
  $res_count_tasks_in_projects = get_res_stmt($link, get_sql_count_task_in_project($show_complete_tasks), [$current_user_id]);
  $count_tasks_in_all_projects = get_rows($link, $res_count_tasks_in_projects);
  $count_task_in_projects = array_column($count_tasks_in_all_projects, 'count_tasks', 'id');
  return $count_task_in_projects;
}

// проверить - существует ли выбраный id проекта
function check_is_correct_project_id($projects, $active_project_id)
{
  $result = true;
  $CODE_NOT_FOUND = 404;
  // получить id проектов
  $id_projects = array_column($projects, 'id');
  // если id выбранного проекта существует
  if (!in_array($active_project_id, $id_projects)) {
    // вернуть код ответа 404
    http_response_code($CODE_NOT_FOUND);
    $result = false;
  }
  return $result;
}

// получить задачи
function get_tasks($link, $is_set_id, $show_complete_tasks, $current_user_id) {
  // если в параметрах запроса задан id проекта
  if ($is_set_id) {
    $active_project_id = $_GET['id'];
    // получить задачи для выбранного проекта
    $res_tasks = get_res_stmt($link, get_sql_tasks_in_project($show_complete_tasks), [$active_project_id]);
    // если id проекта не задан
  } else {
    // получить все задачи пользователя
    $res_tasks = get_res_stmt($link, get_sql_tasks_all($show_complete_tasks), [$current_user_id]);
  }
  $tasks = get_rows($link, $res_tasks);
  return $tasks;
}

// сохранить задачу
function add_task($link, $values, $script_name) {
  $sql = 'INSERT INTO task (date_create, name, date_deadline, project_id, account_id) VALUES (now(), ?, ?, ?, ?)';
  $id_task = 0;
  $res = is_res_stmt($link, $sql, $values);
  if ($res) {
    $id_task = mysqli_insert_id($link);
    header('Location: ' . $script_name);
  }
  else {
    $error = mysqli_error($link);
    show_error_content($error);
  }
  return $id_task;
}