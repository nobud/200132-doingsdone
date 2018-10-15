<?php
$name = $_POST['name'] ?? '';
$date = $_POST['date'] ?? '';
$attached = $_FILES['preview']['name'] ?? '';
if (isset($_POST['project'])) {
    $id_project = $_POST['project'];
} else {
    $id_project = $_GET['id'] ?? 0;
}
?>

<h2 class="content__main-heading">Добавление задачи</h2>
<form class="form" action="add-task.php" method="post" enctype="multipart/form-data">
  <div class="form__row">
    <label class="form__label" for="name">Название <sup>*</sup></label>

    <input class="form__input
    <?php if (isset($errors['name'])): ?>form__input--error<?php endif; ?>"
           type="text" name="name" id="name" value="<?=esc($name); ?>" placeholder="Введите название">
    <?php if (isset($errors['name'])): ?>
        <p class="form__message">
            <span class="error-message"><?=$errors['name']; ?></span>
        </p>
    <?php endif; ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="project">Проект <sup>*</sup></label>
    <select class="form__input form__input--select <?php if (isset($errors['project'])): ?>form__input--error<?php endif; ?>"
            name="project" id="project">
      <?php foreach($projects as $project): ?>
        <option value="<?= $project['id'] ?>"
          <?php if($project['id'] == $id_project): ?>
            selected
          <?php endif; ?>>
        <?=esc($project['name']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?php if (isset($errors['project'])): ?>
        <p class="form__message">
            <span class="error-message"><?=$errors['project']; ?></span>
        </p>
    <?php endif; ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="date">Дата выполнения</label>

    <input class="form__input form__input--date <?php if (isset($errors['date'])): ?>form__input--error<?php endif; ?>"
           type="date" name="date" id="date" value="<?=$date; ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
    <?php if (isset($errors['date'])): ?>
        <p class="form__message">
            <span class="error-message"><?=$errors['date']; ?></span>
        </p>
    <?php endif; ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="preview">Файл</label>

    <div class="form__input-file <?php if (isset($errors['preview'])): ?>form__input--error<?php endif; ?>">
      <input class="visually-hidden" type="file" name="preview" id="preview" value="<?=esc($attached); ?>">

      <label class="button button--transparent" for="preview">
          <span>Выберите файл</span>
      </label>
    </div>
    <?php if (isset($errors['preview'])): ?>
        <p class="form__message">
            <span class="error-message"><?=$errors['attached']; ?></span>
        </p>
    <?php endif; ?>
  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">
  </div>
</form>





