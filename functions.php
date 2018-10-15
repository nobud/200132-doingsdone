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

// количество часов до даты дедлайна
function get_hours_left($datetime_deadline) {
  $secs_in_hour = 3600;
  $now = time();
  $deadline = strtotime($datetime_deadline); //при невозможности конвертации возвращает false
  $hours_left = ($deadline - $now)/$secs_in_hour;
  return $hours_left;
}

// функция определения важности задачи
// возвращает true, если до даты дедлайна осталось менее 24ч
function get_timestatus_task($datetime_deadline) {
  if ($datetime_deadline) {
    $hours_left = get_hours_left($datetime_deadline);
    switch ($hours_left) {
      case ($hours_left < 0): {
        return 'expire';
      }
      case ($hours_left <= 24 && $hours_left >=0): {
        return 'today';
      }
      case ($hours_left <= 48 && $hours_left >=0): {
        return 'tomorrow';
      }
      default: {
        return 'unknown';
      }
    }
  }
}

// функция определения важности задачи
// возвращает true, если до даты дедлайна осталось менее суток или задача просрочена
function is_important($datetime_deadline) {
  $result = false;
  $condition = 24; //условие важности задачи - количество часов, оставшихся до даты дедлайна
  if ($datetime_deadline) {
    $hours_left = get_hours_left($datetime_deadline);
    $result = $hours_left <= $condition;
  }
  return $result;
}

// возвращает true, если до даты дедлайна осталось 24ч или менее, но задача не просрочена
function today($value) {
  return get_timestatus_task($value['date_deadline']) == 'today';
}

// возвращает true, если до даты дедлайна осталось 48ч или менее, но задача не просрочена
function tomorrow($value) {
  return get_timestatus_task($value['date_deadline']) == 'tomorrow';
}

// возвращает true, если задача просрочена
function expire($value) {
  return get_timestatus_task($value['date_deadline']) == 'expire';
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

// получить объект результата для запроса SELECT после выполнения подготовленного выражения
// $link ресурс соединения
// SQL запрос с плейсхолдерами вместо значений
// данные для вставки на место плейсхолдеров
function get_res_stmt($link, $sql, $data = []) {
  $stmt = db_get_prepare_stmt($link, $sql, $data);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  return $res;
}

// проверить объект результата для запросов UPDATE и INSERT после выполнения подготовленного выражения
// $link ресурс соединения
// SQL запрос с плейсхолдерами вместо значений
// данные для вставки на место плейсхолдеров
function is_res_stmt($link, $sql, $data = []) {
  $stmt = db_get_prepare_stmt($link, $sql, $data);
  $res = mysqli_stmt_execute($stmt);
  return $res;
}

// начать транзакцию
function trans_begin($link){
  mysqli_query($link, "BEGIN");
}

// завершить транзакцию
function trans_commit($link){
  mysqli_query($link, "COMMIT");
}

// откатить транзакцию
function trans_rollback($link){
  mysqli_query($link, "ROLLBACK");
}

// вывод даты в заданном формате
function format_date($date_str, $format='d.m.Y H:i:s') {
  $date = date_create($date_str);
  return date_format($date, $format);
}

// проверка валидности формата даты
function is_valid_date_format($date_str, $format = 'd.m.Y')
{
  $date = date_create($date_str);
  return $date && $date->format($format) == $date_str;
}

// проверка валидности формата даты
function is_correct_date($date_str)
{
  $result = false;
  $date_parts = get_date_parts($date_str);
  if ($date_parts) {
    $result = checkdate($date_parts['m'], $date_parts['d'], $date_parts['y']);
  }
  return $result;
}

// получить из даты день, месяц, год в виде ассоциативного массива
function get_date_parts($date_str) {
  $date_parts = [];
  if (!empty($date_str)) {
    $date = date_create($date_str);
    if ($date) {
      $date_parts = ['d' => $date->format('d'),
        'm' => $date->format('m'),
        'y' => $date->format('Y')];
    }
  }
  return $date_parts;
}

// сформировать адрес ссылки с учетом заданных параметров запроса и имени скрипта
function set_url($params, $scriptname) {
  $query = http_build_query($params);
  $url = "/" . $scriptname . "?" . $query;
  return $url;
}

// проверить заполнения обязательных полей
// возвращает массив со списком ошибок с ключом по полю
function check_fields_required($fields_required) {
  $fields_with_error = [];
  foreach ($fields_required as $field) {
    if (empty($_POST[$field])) {
      $fields_with_error[$field] = 'Поле не заполнено';
    }
  }
  return $fields_with_error;
}







