<?php
require_once 'mysql_helper.php';

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

// получить результат запроса в виде строк из объекта результата запроса
// $link - ресурс соединения
// $res - объект результата запроса
function get_rows($link, $res) {
  $rows = [];
  $error_content = '';
  if ($res) {
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
  } else {
    $error = mysqli_error($link);
    $error_content = include_template('error.php', ['error' => $error]);
  }
  return ['values' => $rows, 'error_content' => $error_content];
}

// получить результат запроса в виде одной строки из объекта результата запроса
// $link - ресурс соединения
// $res - объект результата запроса
function get_row($link, $res) {
  $row = [];
  $error_content = '';
  if ($res) {
    $row = mysqli_fetch_assoc($res);
  } else {
    $error = mysqli_error($link);
    $error_content = include_template('error.php', ['error' => $error]);
  }
  return ['value' => $row, 'error_content' => $error_content];
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





