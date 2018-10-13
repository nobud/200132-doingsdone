<?php
require_once 'data-bd.php';
require_once 'functions.php';
require_once 'init.php';

session_start();

try {
  $fields_with_error = [];

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // проверка заполнения обязательных полей
    $fields_with_error = check_fields_required(['email', 'password']);

    $email = $_POST['email'];
    // проверка валидности email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $fields_with_error['email'] = 'Некорректный email';
    }

    if (empty($fields_with_error)) {
      // получить данные пользователя по его email
      $user = get_user_data($link, $sql_user_for_email, $email);
      if ($user) {
        if (password_verify($_POST['password'], $user['password'])) {
          $_SESSION['user'] = $user;
          header('Location: ' . $script_name);
          exit();
        } else {
          $fields_with_error['password'] = 'Неверный пароль';
        }
      } else {
        $fields_with_error['email'] = 'Пользователь с email ' . $email . ' не найден';
      }
    }
  } else {
    if (isset($_SESSION['user'])) {
      header('Location: ' . $script_name);
      exit();
    }
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
}
catch(Exception $e) {
  show_error_content($e->getMessage());
}
