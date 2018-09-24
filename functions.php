<?php
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

// функция подсчета задач для заданного проекта
function get_count_tasks($tasks, $project) {
  $count_tasks = 0;
  foreach($tasks as $key => $val) {
    if ($val['project_category'] == $project) {
      $count_tasks++;
    };
  };
  return $count_tasks;
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



