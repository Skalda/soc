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
(1,	3,	1,	'2013-12-06 04:19:18',	'ssssssss'),
(2,	2,	5,	'2013-12-11 00:43:31',	'q'),
(3,	2,	5,	'2013-12-11 00:43:38',	'q'),
(4,	2,	5,	'2013-12-11 00:43:42',	'q');

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

INSERT INTO `notifications` (`id`, `users_id`, `time`, `link`, `link_params`, `message`, `attr`, `seen`) VALUES
(1,	2,	'2013-12-06 02:45:04',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'',	'[]',	1),
(2,	2,	'2013-12-06 02:45:07',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'',	'[]',	1),
(3,	2,	'2013-12-06 02:45:08',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'',	'[]',	1),
(4,	1,	'2013-12-11 01:07:03',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'',	'[]',	0),
(5,	1,	'2013-12-11 01:07:06',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'',	'[]',	0),
(6,	1,	'2013-12-11 01:07:29',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'',	'[]',	0),
(7,	1,	'2013-12-11 01:07:46',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'',	'[]',	0),
(8,	1,	'2013-12-11 01:08:22',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'',	'[]',	0),
(9,	1,	'2013-12-11 01:08:29',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'j',	'[]',	0),
(10,	1,	'2013-12-11 01:08:42',	'',	'{\"id\":5,\"name\":\"stranka\"}',	'j',	'[]',	0),
(11,	1,	'2013-12-11 01:08:46',	's',	'{\"id\":5,\"name\":\"stranka\"}',	'j',	'[]',	0);

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
(3,	'User2@user.cz',	'8fb688d7dfe7a4ed9d66b8a1aa91208102a27fb8',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	0),
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
  `registration_number` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `production_year` int(4) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `type` enum('ground','water','air') DEFAULT NULL,
  `motor_count` int(11) DEFAULT NULL,
  `wheel_count` int(11) DEFAULT NULL,
  `propeller_count` int(11) DEFAULT NULL,
  `seats_count` int(11) DEFAULT NULL,
  `window_count` int(11) DEFAULT NULL,
  `fuel_type` varchar(255) DEFAULT NULL,
  `engine_capacity` varchar(255) DEFAULT NULL,
  `engine_power` varchar(255) DEFAULT NULL,
  `mileage` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vehicles` (`id`, `users_id`, `name`, `profilpic`, `info`, `registration_number`, `brand`, `model`, `production_year`, `color`, `type`, `motor_count`, `wheel_count`, `propeller_count`, `seats_count`, `window_count`, `fuel_type`, `engine_capacity`, `engine_power`, `mileage`, `status`) VALUES
(1,	2,	'addVehicleForm',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(2,	2,	'auto',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(3,	2,	'sss',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL);

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

-- 2013-12-11 04:20:05
