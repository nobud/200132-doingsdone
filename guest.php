<?php
require_once 'data-bd.php';
require_once 'functions.php';
require_once 'init.php';

$fields_with_error = [];

$page_content = include_template('guest.php', []);

$layout_content = include_template('layout.php', [
  'main_content' => $page_content,
  'side_content' => null,
  'title' => $title,
  'user' => $_SESSION['user'] ?? []
]);

echo $layout_content;