CREATE DATABASE `Guestbook`
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE `Guestbook`;

CREATE TABLE IF NOT EXISTS `Users` (
  id SERIAL,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL, -- http://php.net/manual/de/function.password-hash.php
  mail VARCHAR(80),
  role VARCHAR(30) NOT NULL DEFAULT 'user',
  active TINYINT(1) NOT NULL DEFAULT 1,
  deleted TINYINT(1) NOT NULL DEFAULT 1,

  PRIMARY KEY (id)
) ENGINE INNODB CHECKSUM 1;