<?php
require_once 'init.php';

session_start();

// закрыть сессию
$_SESSION = [];

header('Location: ' . $script_name);