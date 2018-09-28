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
    <?php if (!$val['status'] || $show_complete_tasks): ?>
      <tr class="tasks__item task <?php if ($val['status']): ?>task--completed<?php endif; ?>
      <?php if (is_important($val['date_deadline'])): ?>task--important<?php endif; ?>" >
        <td class="task__select">
          <label class="checkbox task__checkbox">
            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
            <span class="checkbox__text"><?=esc($val['name']); ?></span>
          </label>
        </td>

        <td class="task__file">
          <?php if (!is_null($val['attached'])): ?><a class="download-link" href="#"><?=esc($val['attached']); ?></a><?php endif; ?>
        </td>

        <td class="task__date">
          <?php if (!is_null($val['date_deadline'])): ?><?=esc($val['date_deadline']); ?>
          <?php else: ?>нет<?php endif; ?>
        </td>
      </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>