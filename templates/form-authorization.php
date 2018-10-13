<?php
if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $email = '';
    }
}
?>

<h2 class="content__main-heading">Вход на сайт</h2>

<form class="form" action="authorization.php" method="post">
  <div class="form__row">
    <label class="form__label" for="email">E-mail <sup>*</sup></label>

    <input class="form__input <?php if (isset($errors['email'])): ?>form__input--error<?php endif; ?>"
           type="text" name="email" id="email" value="<?=esc($email); ?>" placeholder="Введите e-mail">

    <?php if (isset($errors['email'])): ?>
        <p class="form__message">
            <span class="error-message"><?=$errors['email']; ?></span>
        </p>
    <?php endif; ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>

    <input class="form__input <?php if (isset($errors['password'])): ?>form__input--error<?php endif; ?>"
           type="password" name="password" id="password" value="" placeholder="Введите пароль">
    <?php if (isset($errors['password'])): ?>
        <p class="form__message">
            <span class="error-message"><?=$errors['password']; ?></span>
        </p>
    <?php endif; ?>
  </div>

  <div class="form__row form__row--controls">
    <?php if (!empty($errors)): ?>
        <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
    <?php endif; ?>
    <input class="button" type="submit" name="" value="Войти">
  </div>
</form>