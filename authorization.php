<?php
require_once 'data-bd.php';
require_once 'functions.php';
require_once 'init.php';

$fields_with_error = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {



}

$page_content = include_template('form-authorization.php', ['errors' => $fields_with_error]);

$side_content = include_template ('side-user.php', []);

$layout_content = include_template('layout.php', [
'main_content' => $page_content,
'side_content' => $side_content,
'title' => $title,
'add_task' => false,
'is_authorization' => false
]);

echo $layout_content;