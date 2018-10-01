<?php

// Имя пользователя по его id
$sql_user = 'SELECT name FROM account WHERE id = ?';

// Список проектов у текущего пользователя
$sql_projects = 'SELECT * FROM project WHERE account_id = ?';

// сформировать часть запроса в зависимости от значения флажка "показывать выполненные задачи"
function get_sql_add_check_status($show_complete_task) {
  return $show_complete_task ? '' : ' AND status = 0 ';
}

// Получить текст запроса - Количество задач для каждого проекта
function get_sql_count_task_in_project($show_complete_task) {
  $sql_count_task_in_project = 'SELECT project.id as id, count(project_id) AS count_tasks FROM project ' .
    'LEFT JOIN task ON (project.id = task.project_id' . get_sql_add_check_status($show_complete_task) . ') WHERE project.account_id = ? GROUP BY id, project_id';
  return $sql_count_task_in_project;
}

// Получить текст запроса - Список всех задач у текущего пользователя
function get_sql_tasks_all($show_complete_task) {
  $sql_tasks_all = 'SELECT * FROM task WHERE account_id = ? ' . get_sql_add_check_status($show_complete_task) . ' ORDER BY date_deadline DESC';
  return $sql_tasks_all;
}

function get_sql_tasks_in_project($show_complete_task) {
  // Список задач, относящихся к выбранному проекту
  $sql_tasks_in_project = 'SELECT * FROM task WHERE project_id = ? ' . get_sql_add_check_status($show_complete_task) . ' ORDER BY date_deadline DESC';
  return $sql_tasks_in_project;
}
