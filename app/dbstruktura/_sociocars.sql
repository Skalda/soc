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


DROP TABLE IF EXISTS `routes`;
CREATE TABLE `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicles_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `name` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `secret_key` int(11) DEFAULT NULL,
  `measured` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `uploaded` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `length` int(11) DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `start_pos` point DEFAULT NULL,
  `end_pos` point DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicles_id` (`vehicles_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`vehicles_id`) REFERENCES `vehicles` (`id`),
  CONSTRAINT `routes_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `routeUser`;
CREATE TABLE `routeUser` (
  `routes_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  KEY `routes_id` (`routes_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `routeUser_ibfk_1` FOREIGN KEY (`routes_id`) REFERENCES `routes` (`id`),
  CONSTRAINT `routeUser_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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

INSERT INTO `users` (`id`, `email`, `password`, `salt`, `name`, `surname`, `sex`, `city`, `profilpic`, `filled_data`) VALUES
(1,	'a@a.cz',	'a',	'',	NULL,	NULL,	'male',	NULL,	NULL,	0),
(2,	'User1@user.cz',	'b00de2e55ea82ad9fc322e61db74a9720f3b80dd',	'da39a3ee5e6b4b0d3255bfef95601890afd80709',	NULL,	NULL,	NULL,	NULL,	NULL,	0),
(3,	'User2@user.cz',	'8fb688d7dfe7a4ed9d66b8a1aa91208102a27fb8',	'',	NULL,	NULL,	NULL,	NULL,	'3-SNFM.jpg',	0),
(4,	'User3@user.cz',	'8fb688d7dfe7a4ed9d66b8a1aa91208102a27fb8',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	0),
(5,	'User4@user.cz',	'8fb688d7dfe7a4ed9d66b8a1aa91208102a27fb8',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	0),
(6,	'User5@user.cz',	'8fb688d7dfe7a4ed9d66b8a1aa91208102a27fb8',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	0),
(7,	'User6@user.cz',	'8fb688d7dfe7a4ed9d66b8a1aa91208102a27fb8',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	0);

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `profilpic` varchar(255) DEFAULT NULL,
  `info` longtext,
  `registration_number` varchar(7) DEFAULT NULL,
  `type` enum('car','motorcycle','quad','tricycle','scooter','motor bike','other') NOT NULL,
  `mileage` int(11) NOT NULL DEFAULT '0',
  `status` enum('ready','in use','not ready') NOT NULL DEFAULT 'not ready',
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vehicles` (`id`, `users_id`, `name`, `profilpic`, `info`, `registration_number`, `type`, `mileage`, `status`) VALUES
(1,	2,	'addVehicleForm',	NULL,	NULL,	NULL,	'car',	0,	'ready'),
(2,	2,	'auto',	NULL,	NULL,	NULL,	'car',	0,	'ready'),
(3,	2,	'sss',	'-SNFM.jpg',	NULL,	NULL,	'car',	0,	'ready'),
(4,	2,	'dalsi',	NULL,	NULL,	NULL,	'car',	0,	'ready'),
(5,	2,	'ftyft',	NULL,	NULL,	NULL,	'car',	0,	'ready'),
(6,	3,	'hgjhg',	NULL,	NULL,	NULL,	'car',	0,	'ready'),
(7,	2,	'dalsi',	NULL,	NULL,	NULL,	'car',	0,	'ready');

DROP TABLE IF EXISTS `vehicleUser`;
CREATE TABLE `vehicleUser` (
  `vehicles_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  KEY `vehicles_id` (`vehicles_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `vehicleUser_ibfk_1` FOREIGN KEY (`vehicles_id`) REFERENCES `vehicles` (`id`),
  CONSTRAINT `vehicleUser_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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
(1,	3,	'2013-12-06 04:19:13',	'sddsd',	0),
(2,	2,	'2013-12-10 21:16:35',	'sss',	0),
(3,	2,	'2013-12-11 00:18:30',	'sa',	0),
(4,	2,	'2013-12-11 00:18:40',	'c',	1),
(5,	2,	'2013-12-11 00:18:45',	'c',	0);

-- 2013-12-18 04:18:20
