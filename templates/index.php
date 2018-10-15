<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="get">
  <input class="search-form__input" type="text" name="query" value="" placeholder="Поиск по задачам">

  <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
  <nav class="tasks-switch">
    <a href="<?=set_url(array_merge($_GET, ['filter' => '']), $scriptname); ?>" class="tasks-switch__item
    <?php if(isset($_GET['filter']) && (empty($_GET['filter']))): ?>tasks-switch__item--active<?php endif; ?>">Все задачи</a>
    <a href="<?=set_url(array_merge($_GET, ['filter' => 'today']), $scriptname); ?>" class="tasks-switch__item
    <?php if(isset($_GET['filter']) && ($_GET['filter'] == 'today')): ?>tasks-switch__item--active<?php endif; ?>">Повестка дня</a>
    <a href="<?=set_url(array_merge($_GET, ['filter' => 'tomorrow']), $scriptname) ;?>" class="tasks-switch__item
    <?php if(isset($_GET['filter']) && ($_GET['filter'] == 'tomorrow')): ?>tasks-switch__item--active<?php endif; ?>">Завтра</a>
    <a href="<?=set_url(array_merge($_GET, ['filter' => 'expire']), $scriptname); ?>" class="tasks-switch__item
    <?php if(isset($_GET['filter']) && ($_GET['filter'] == 'expire')): ?>tasks-switch__item--active<?php endif; ?>">Просроченные</a>
  </nav>

  <label class="checkbox">
    <input class="checkbox__input visually-hidden show_completed" value="<?=$show_complete_tasks; ?>"
    <?php if($show_complete_tasks): ?> checked <?php endif; ?> type="checkbox">
    <span class="checkbox__text">Показывать выполненные</span>
  </label>
</div>

<table class="tasks">
  <?php foreach ($tasks as $key => $val): ?>

      <tr class="tasks__item task <?php if ($val['status']): ?>task--completed<?php endif; ?>
      <?php if (is_important($val['date_deadline'])): ?>task--important<?php endif; ?>" >
        <td class="task__select">
          <label class="checkbox task__checkbox">
            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?=$val['id']; ?>"
               <?php if ($val['status']): ?>checked<?php endif; ?>>
            <span class="checkbox__text"><?=esc($val['name']); ?></span>
          </label>
        </td>

        <td class="task__file">
          <?php if (!is_null($val['attached'])): ?><a class="download-link" href="<?=$directory_upload_file . $val['attached']; ?>">файл</a><?php endif; ?>
        </td>

        <td class="task__date">
          <?php if (!is_null($val['date_deadline'])): ?><?=esc(format_date($val['date_deadline'])); ?>
          <?php else: ?>нет<?php endif; ?>
        </td>
      </tr>

  <?php endforeach; ?>
</table>