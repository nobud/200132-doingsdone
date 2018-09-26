CREATE DATABASE IF NOT EXISTS doingsdone
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE doingsdone;

CREATE TABLE IF NOT EXISTS project (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(150) NOT NULL,
  account_id int NOT NULL,
  UNIQUE KEY name (account_id,name),
  KEY account_id (account_id)
);

CREATE TABLE IF NOT EXISTS task (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date_create datetime NOT NULL,
  date_complete datetime NOT NULL,
  date_deadline datetime DEFAULT NULL,
  status tinyint(1) unsigned NOT NULL DEFAULT 0,
  name varchar(300) NOT NULL,
  attached blob,
  account_id int NOT NULL,
  project_id int NOT NULL,
  KEY date_deadline (account_id,date_deadline),
  KEY status (account_id,status),
  KEY name (account_id,name),
  KEY project_id (account_id,project_id),
  KEY account_id (account_id)
);

CREATE TABLE IF NOT EXISTS account (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date_reg datetime NOT NULL,
  email varchar(60) NOT NULL,
  name varchar(40) NOT NULL,
  password varchar(30) NOT NULL,
  contacts varchar(300) DEFAULT NULL,
  UNIQUE KEY email (email),
  UNIQUE KEY name (name)
);

