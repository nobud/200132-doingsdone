<?php
require_once 'functions.php';

$title = 'Дела в порядке';

$script_name = 'index.php';

$db = [
  'host' => 'localhost',
  'user' => 'root',
  'password' => '',
  'database' => 'doingsdone'];

$current_user_id = 1;
$show_complete_tasks = rand(0, 1);

// установить подключение
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

// если подключение не установлено
if (!$link) {
  // показать сообщение об ошибке
  $error = mysqli_connect_error();
  show_error_content($error);
}

mysqli_set_charset($link, 'utf8');