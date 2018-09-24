<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
  <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

  <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
  <nav class="tasks-switch">
    <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
    <a href="/" class="tasks-switch__item">Повестка дня</a>
    <a href="/" class="tasks-switch__item">Завтра</a>
    <a href="/" class="tasks-switch__item">Просроченные</a>
  </nav>

  <label class="checkbox">
    <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
    <input class="checkbox__input visually-hidden show_completed"
           <?php if ($show_complete_tasks): ?>checked<?php endif; ?>
           type="checkbox">
    <span class="checkbox__text">Показывать выполненные</span>
  </label>
</div>

<table class="tasks">
  <?php foreach ($tasks as $key => $val): ?>
    <?php if (!$val['is_completed'] || $show_complete_tasks): ?>
      <tr class="tasks__item task <?php if ($val['is_completed']): ?>task--completed<?php endif; ?>
      <?php if (is_important($val['date'])): ?>task--important<?php endif; ?>" >
        <td class="task__select">
          <label class="checkbox task__checkbox">
            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
            <span class="checkbox__text"><?=esc($val['name']); ?></span>
          </label>
        </td>

        <td class="task__file">
          <a class="download-link" href="#">Home.psd</a>
        </td>

        <td class="task__date"><?=esc($val['date']); ?></td>
      </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>