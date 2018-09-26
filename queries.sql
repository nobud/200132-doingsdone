USE doingsdone;

-- Добавление пользователей
INSERT INTO account
SET name = 'syndi', email = 'syndi@yandex.ru', password = 'Qty45fg_%', date_reg = '2018-09-25';

INSERT INTO account
SET name = 'isa', email = 'isa@yandex.ru', password = 'dfg456SA$', date_reg = '2018-09-25';

-- Добавление проектов (для одного из пользователей)
INSERT INTO project
SET name = 'Входящие', account_id = 1;

INSERT INTO project
SET name = 'Учеба', account_id = 1;

INSERT INTO project
SET name = 'Работа', account_id = 1;

INSERT INTO project
SET name = 'Домашние дела', account_id = 1;

INSERT INTO project
SET name = 'Авто', account_id = 1;

-- Добавление задач
INSERT INTO task
SET date_create = now(),
  date_deadline = '2018-12-01',
  name = 'Собеседование в IT компании',
  account_id = 1,
  project_id = 3;
  
INSERT INTO task
SET date_create = now(),
  date_deadline = '2018-12-25',
  name = 'Выполнить тестовое задание',
  account_id = 1,
  project_id = 3;
  
INSERT INTO task
SET date_deadline = '2018-12-21',
  date_create = now(),
  name = 'Сделать задание первого раздела',
  account_id = 1,
  project_id = 2;
  
INSERT INTO task
SET date_deadline = '2018-12-22',
  date_create = now(),
  name = 'Встреча с другом',
  account_id = 1,
  project_id = 1;
  
INSERT INTO task
SET 
  date_create = now(),
  name = 'Купить корм для кота',
  account_id = 1,
  project_id = 4;
  
INSERT INTO task
SET 
  date_create = now(),
  name = 'Заказать корм для кота',
  account_id = 1,
  project_id = 4;
  
-- Изменить статус задачи
UPDATE task SET status = !status
WHERE id = 3;

-- Получить список из всех проектов для одного пользователя
SELECT name 
FROM project 
WHERE account_id = 1;

-- Получить список из всех задач для одного проекта
SELECT name, date_deadline 
FROM task 
WHERE project_id = 3;

-- Установить одной из задач дату дедлайна на завтра
UPDATE task
SET date_deadline = (NOW() + INTERVAL 1 DAY)
WHERE id = 5;

-- Получить все задачи для завтрашнего дня
SELECT name, date_deadline
FROM task 
WHERE account_id = 1 and DATE(date_deadline) = DATE(NOW() + INTERVAL 1 DAY) and status = 0;

-- Обновить название задачи по её идентификатору
UPDATE task SET name = 'Сделать задания из второго раздела'
WHERE id = 3;

