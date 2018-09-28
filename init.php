<?php
$db = [
  'host' => 'localhost',
  'user' => 'root',
  'password' => '',
  'database' => 'doingsdone'];

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
if ($link) {
  mysqli_set_charset($link, 'utf8');
};
