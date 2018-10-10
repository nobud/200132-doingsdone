<?php
require_once 'mysql_helper.php';
require_once 'queries.php';

// функция шаблонизатор
function include_template($name, $data) {
  $name = 'templates/' . $name;
  $result = '';
  if (!file_exists($name)) {
    return $result;
  }
  ob_start();
  extract($data);
  require $name;
  $result = ob_get_clean();
  return $result;
};

function esc($str) {
  $text = htmlspecialchars($str);
  return $text;
}

// функция определения важности задачи
// возвращает true, если до даты дедлайна осталось менее 24ч
function is_important($datetime_deadline) {
  $result = false;
  $secs_in_hour = 3600;
  $condition = 24; //условие важности задачи - количество часов, оставшихся до даты дедлайна
  $deadline = strtotime($datetime_deadline); //при невозможности конвертации возвращает false
  if ($deadline) {
    $now = time();
    $hours_left = ($deadline - $now)/$secs_in_hour;
    $result = $hours_left <= $condition;
  }
  return $result;
}

// показать сообщение об ошибке
function show_error_content($error) {
  $error_content = include_template('error.php', ['error' => $error]);
  exit($error_content);
}

// получить результат запроса в виде строк из объекта результата запроса
// $link - ресурс соединения
// $res - объект результата запроса
function get_rows($link, $res) {
  $rows = [];
  if ($res) {
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
  } else {
    $error = mysqli_error($link);
    show_error_content($error);
  }
  return $rows;
}

// получить результат запроса в виде одной строки из объекта результата запроса
// $link - ресурс соединения
// $res - объект результата запроса
function get_row($link, $res) {
  $row = [];
  if ($res) {
    $row = mysqli_fetch_assoc($res);
  } else {
    $error = mysqli_error($link);
    show_error_content($error);
  }
  return $row;
}

// получить объект результата после выполнения подготовленного выражения
// $link ресурс соединения
// SQL запрос с плейсхолдерами вместо значений
// данные для вставки на место плейсхолдеров
function get_res_stmt($link, $sql, $data = []) {
  $stmt = db_get_prepare_stmt($link, $sql, $data);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  return $res;
}

// сформировать адрес ссылки с учетом заданных параметров запроса и имени скрипта
function set_url($params, $scriptname) {
  $query = http_build_query($params);
  $url = "/" . $scriptname . "?" . $query;
  return $url;
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

// проверить - существует ли выбраный id проекта
function check_is_correct_project_id($projects, $active_project_id)
{
  $CODE_NOT_FOUND = 404;
  // если в параметрах запроса задан id проекта
  // получить id проектов
  $id_projects = array_column($projects, 'id');
  // если id выбранного проекта существует
  if (!in_array($active_project_id, $id_projects)) {
    // вернуть код ответа 404
    http_response_code($CODE_NOT_FOUND);
    show_error_content('Проект с id=' . $active_project_id . ' не найден');
  }
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

// получить количество задач в проектах
function get_count_tasks($link, $show_complete_tasks, $current_user_id) {
  $res_count_tasks_in_projects = get_res_stmt($link, get_sql_count_task_in_project($show_complete_tasks), [$current_user_id]);
  $count_tasks_in_all_projects = get_rows($link, $res_count_tasks_in_projects);
  $count_task_in_projects = array_column($count_tasks_in_all_projects, 'count_tasks', 'id');
  return $count_task_in_projects;
}


