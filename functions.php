<?php
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

//функция подсчета задач для заданного проекта
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

