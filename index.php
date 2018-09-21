<?php
require_once 'functions.php';
require_once 'data.php';

$page_content = include_template ('index.php', [
  'projects' => $projects,
  'tasks' => $tasks,
  'show_complete_tasks' => rand(0, 1)
]);

$side_content = include_template ('side.php', [
  'projects' => $projects,
  'tasks' => $tasks
]);

$layout_content = include_template('layout.php', [
  'main_content' => $page_content,
  'side_content' => $side_content,
  'user_name' => $user_name,
  'title' => $title
]);

echo $layout_content;