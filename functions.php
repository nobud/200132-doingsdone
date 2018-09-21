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

// получить количество суток, оставшихся до дедлайна
function get_days_left($date_deadline) {
  $secs_in_day = 60*60*24;
  $deadline = strtotime($date_deadline);
  $now = time();
  $days_left = floor(($deadline - $now)/$secs_in_day) + 1;
  return $days_left;
}

// возвращает true, если до даты дедлайна осталось менее суток (менее 24ч)
function is_important_date($date) {
  $days_left = get_days_left($date);
  return $days_left == 0;
}


