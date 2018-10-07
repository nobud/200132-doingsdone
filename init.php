<?php

$CODE_NOT_FOUND = 404;

$db = [
  'host' => 'localhost',
  'user' => 'root',
  'password' => '',
  'database' => 'doingsdone'];

// установить подключение
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
if ($link) {
  mysqli_set_charset($link, 'utf8');
};
