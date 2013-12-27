-- Adminer 3.7.1 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+01:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `wall_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `wall_id` (`wall_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`wall_id`) REFERENCES `wall` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comments` (`id`, `user_id`, `wall_id`, `date`, `content`) VALUES
(1, 2,  1,  '2013-12-26 19:59:12',  'fdgdgd');

DROP TABLE IF EXISTS `entries`;
CREATE TABLE `entries` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `routes_id` int(11) NOT NULL,
  `location` point NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `event` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `odometer` double NOT NULL,
  `velocity` double NOT NULL,
  `consumption` double NOT NULL,
  `fuel_remaining` double NOT NULL,
  `altitude` double NOT NULL,
  `engine_temp` double NOT NULL,
  `engine_rpm` double NOT NULL,
  `throttle` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `routes_id` (`routes_id`),
  CONSTRAINT `entries_ibfk_1` FOREIGN KEY (`routes_id`) REFERENCES `routes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `entries` (`id`, `routes_id`, `location`, `timestamp`, `event`, `user_id`, `odometer`, `velocity`, `consumption`, `fuel_remaining`, `altitude`, `engine_temp`, `engine_rpm`, `throttle`) VALUES
(1, 1,  GeomFromText('POINT(14.42067 50.07598)'), '2013-12-27 01:26:13',  'UNLOCKED', 123,  23549.75, 0,  0,  35.46,  201.46, 25.3, 0,  0),
(2, 1,  GeomFromText('POINT(14.42065 50.07599)'), '2013-12-27 01:27:43',  '', 123,  23550,  23.631, 5.4,  35.46,  202.76, 42.3, 2103.5, 23.1),
(3, 2,  GeomFromText('POINT(40.737102 -73.990318)'),  '2013-12-27 04:51:47',  'UNLOCKED', 123,  23549.75, 0,  0,  35.46,  201.46, 25.3, 0,  0),
(4, 2,  GeomFromText('POINT(40.749825 -73.987963)'),  '2013-12-27 04:52:48',  '', 123,  23549.75, 0,  0,  35.46,  201.46, 25.3, 0,  0),
(5, 2,  GeomFromText('POINT(40.752946 -73.987384)'),  '2013-12-27 04:52:48',  '', 123,  23549.75, 0,  0,  35.46,  201.46, 25.3, 0,  0),
(6, 2,  GeomFromText('POINT(40.755823 -73.986397)'),  '2013-12-27 04:52:48',  '', 123,  23549.75, 0,  0,  35.46,  201.46, 25.3, 0,  0);

DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `accepted` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `user_from_user_to` (`from`,`to`),
  KEY `user_to` (`to`),
  CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`from`) REFERENCES `users` (`id`),
  CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`to`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `friends` (`from`, `to`, `accepted`) VALUES
(1, 2,  1),
(4, 2,  1),
(5, 2,  1),
(6, 2,  1);

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`id`, `user_id`, `name`, `desc`) VALUES
(1, 2,  'fffffffffffff',  'd');

DROP TABLE IF EXISTS `groups_comments`;
CREATE TABLE `groups_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `groups_wall_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `groups_wall_id` (`groups_wall_id`),
  CONSTRAINT `groups_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `groups_comments_ibfk_2` FOREIGN KEY (`groups_wall_id`) REFERENCES `groups_wall` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups_comments` (`id`, `user_id`, `groups_wall_id`, `date`, `content`) VALUES
(1, 1,  1,  '2013-12-26 20:00:08',  'dsssssss');

DROP TABLE IF EXISTS `groups_members`;
CREATE TABLE `groups_members` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `groups_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `groups_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups_members` (`group_id`, `user_id`) VALUES
(1, 2),
(1, 1);

DROP TABLE IF EXISTS `groups_wall`;
CREATE TABLE `groups_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `groups_wall_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `groups_wall_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups_wall` (`id`, `group_id`, `user_id`, `date`, `content`) VALUES
(1, 1,  2,  '2013-12-26 19:59:36',  'ccccccc'),
(2, 1,  1,  '2013-12-26 20:00:11',  'ddddddd');

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `link` varchar(255) NOT NULL,
  `link_params` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attr` text NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `notifications` (`id`, `users_id`, `time`, `link`, `link_params`, `message`, `attr`, `seen`) VALUES
(1, 2,  '2013-12-24 22:46:17',  'User:',  '{\"id\":1}', 'Uživatel {person} váš požádat o přátelství', '[1]',  1),
(2, 1,  '2013-12-24 22:47:48',  'User:',  '{\"id\":2}', 'Uživatel {person} potvrdil vaší žádost o přátelství',  '[2]',  1),
(3, 2,  '2013-12-27 11:16:39',  'User:',  '{\"id\":4}', 'Uživatel {person} váš požádat o přátelství', '[4]',  1),
(4, 2,  '2013-12-27 11:16:48',  'User:',  '{\"id\":5}', 'Uživatel {person} váš požádat o přátelství', '[5]',  1),
(5, 2,  '2013-12-27 11:16:52',  'User:',  '{\"id\":6}', 'Uživatel {person} váš požádat o přátelství', '[6]',  1),
(6, 4,  '2013-12-27 11:17:02',  'User:',  '{\"id\":2}', 'Uživatel {person} potvrdil vaší žádost o přátelství',  '[2]',  0),
(7, 5,  '2013-12-27 11:17:08',  'User:',  '{\"id\":2}', 'Uživatel {person} potvrdil vaší žádost o přátelství',  '[2]',  0),
(8, 6,  '2013-12-27 11:17:13',  'User:',  '{\"id\":2}', 'Uživatel {person} potvrdil vaší žádost o přátelství',  '[2]',  0);

DROP TABLE IF EXISTS `routes`;
CREATE TABLE `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicles_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `unit_id` int(11) NOT NULL,
  `secret_key` int(11) NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `duration` time DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicles_id` (`vehicles_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`vehicles_id`) REFERENCES `vehicles` (`id`),
  CONSTRAINT `routes_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `routes` (`id`, `vehicles_id`, `users_id`, `name`, `unit_id`, `secret_key`, `start_time`, `duration`, `length`) VALUES
(1, 1,  1,  NULL, 12345,  0,  '2012-10-24 01:00:27',  '00:00:03', 1),
(2, 3,  2,  'n8zev trasy',  12345,  0,  '2013-12-27 11:54:38',  '00:01:10', 5);

DROP TABLE IF EXISTS `routeUsers`;
CREATE TABLE `routeUsers` (
  `routes_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  KEY `routes_id` (`routes_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `routeUsers_ibfk_1` FOREIGN KEY (`routes_id`) REFERENCES `routes` (`id`),
  CONSTRAINT `routeUsers_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `sex` enum('male','female') DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `profilpic` varchar(255) DEFAULT NULL,
  `filled_data` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `user_id`, `email`, `password`, `salt`, `name`, `surname`, `sex`, `city`, `profilpic`, `filled_data`) VALUES
(1, 123,  'User1@user.cz',  '6d81e05d4fc918f3e498dafb75aa01eef1866ad0', '39abf06276ad0bdc34d857ccc18f10668f19a7ca', 'sdaaaaaaaaaaa',  'w',  NULL, '', '1-554215-3380704090795-1371072190-n.jpg',  1),
(2, NULL, 'User2@user.cz',  'ae374c280287c8929ec6ab3c462599a337026883', 'fa37856b78eec6c763e34ce85f535284891caa53', 'sssssss',  NULL, NULL, NULL, '2-554215-3380704090795-1371072190-n.jpg',  0),
(3, NULL, 'erwerew@dfr.cz', '2fe29c37379f8b779c3ec67f1e18367eca769c57', 'a72f1301f0cf5c58209b43c147d3009739388707', NULL, NULL, NULL, NULL, NULL, 0),
(4, NULL, 'User3@user.cz',  'ae374c280287c8929ec6ab3c462599a337026883', 'fa37856b78eec6c763e34ce85f535284891caa53', 'sssssss',  NULL, NULL, NULL, '2-554215-3380704090795-1371072190-n.jpg',  0),
(5, NULL, 'User4@user.cz',  'ae374c280287c8929ec6ab3c462599a337026883', 'fa37856b78eec6c763e34ce85f535284891caa53', 'sssssss',  NULL, NULL, NULL, '2-554215-3380704090795-1371072190-n.jpg',  0),
(6, NULL, 'User5@user.cz',  'ae374c280287c8929ec6ab3c462599a337026883', 'fa37856b78eec6c763e34ce85f535284891caa53', 'sssssss',  NULL, NULL, NULL, '2-554215-3380704090795-1371072190-n.jpg',  0);

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `profilpic` varchar(255) DEFAULT NULL,
  `info` longtext,
  `registration_number` varchar(7) DEFAULT NULL,
  `type` enum('car','motorcycle','quad','tricycle','scooter','motor bike','other') NOT NULL,
  `mileage` int(11) NOT NULL DEFAULT '0',
  `status` enum('ready','in use','not ready') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vehicles` (`id`, `users_id`, `unit_id`, `name`, `profilpic`, `info`, `registration_number`, `type`, `mileage`, `status`) VALUES
(1, 1,  12345,  'dfgd', '1-554215-3380704090795-1371072190-n.jpg',  '', '', 'car',  0,  'ready'),
(2, 2,  NULL, 'vozidlo1', '2-554215-3380704090795-1371072190-n.jpg',  '', '', 'motorcycle', 0,  'not ready'),
(3, 2,  NULL, 'sssssssssss',  NULL, '', '', 'tricycle', 0,  'ready'),
(4, 2,  NULL, 'ewweew', '4-554215-3380704090795-1371072190-n.jpg',  '', '', 'quad', 0,  'ready'),
(5, 2,  NULL, 'eewew',  NULL, '', '', 'quad', 0,  'in use'),
(6, 2,  NULL, 'eew',  NULL, '', '', 'tricycle', 0,  'in use'),
(7, 2,  NULL, 'edfdsdfds',  NULL, '', '', 'tricycle', 0,  'ready');

DROP TABLE IF EXISTS `wall`;
CREATE TABLE `wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `content` text NOT NULL,
  `privacy` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `wall_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `wall` (`id`, `user_id`, `date`, `content`, `privacy`) VALUES
(1, 2,  '2013-12-26 16:09:38',  'sedfsdfdssd',  0),
(2, 2,  '2013-12-26 19:59:15',  'dffffffffffffff',  0);

-- 2013-12-27 11:58:46
