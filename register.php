<?php
require_once 'data-bd.php';
require_once 'functions.php';
require_once 'init.php';

try {
  $fields_with_error = [];

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // проверка заполнения обязательных полей
    $fields_with_error = check_fields_required(['email', 'name', 'password']);

    $user['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!isset($fields_with_error['email'])) {
      // проверка корректности email
      if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        $fields_with_error['email'] = 'Некорректный email';
      } else {
        // проверка существования пользователя с таким же email
        if (is_user_exist($link, $sql_user_for_email, $user['email'])) {
          $fields_with_error['email'] = 'Пользователь с таким email уже зарегистрирован';
        }
      }
    }

    $user['name'] = $_POST['name'];
    if (!isset($fields_with_error['email'])) {
      // проверка существования пользователя с таким же name
      if (is_user_exist($link,$sql_user_for_name, $user['name'])) {
        $fields_with_error['name'] = 'Пользователь с таким именем уже зарегистрирован';
      }
    }

    // если нет ошибок валидации - сохранить данные
    if (empty($fields_with_error)) {
      trans_begin($link);
      $user['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $id_user = add_user($link, [$user['name'], $user['email'], $user['password']]);
      if ($id_user) {
        trans_commit($link);
        $user['id'] = $id_user;
        session_start();
        $_SESSION['user'] = $user;
        header('Location: '  . set_url(['email' => $user['email']], 'authorization.php'));
        exit();
      } else {
        $error = mysqli_error($link);
        trans_rollback($link);
        show_error_content($error);
      }
    }
  }

  $page_content = include_template('form-register.php', ['errors' => $fields_with_error]);
  $side_content = include_template ('side-user.php', []);
  $layout_content = include_template('layout.php', [
    'main_content' => $page_content,
    'side_content' => $side_content,
    'title' => $title,
    'user' => $_SESSION['user'] ?? []
  ]);

  echo $layout_content;
}
catch(Exception $e) {
  show_error_content($e->getMessage());
}