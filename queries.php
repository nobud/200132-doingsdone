<?php

// Имя пользователя по его id
$sql_user = 'SELECT name FROM account WHERE id = ?';

// Список проектов у текущего пользователя
$sql_projects = 'SELECT * FROM project WHERE account_id = ?';

// Список задач у текущего пользователя
$sql_tasks = 'SELECT * FROM task WHERE account_id = ?';