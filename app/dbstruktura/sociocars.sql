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


DROP TABLE IF EXISTS `entries`;
CREATE TABLE `entries` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `routes_id` int(11) NOT NULL,
  `location` point NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `vehicles_id` int(11) DEFAULT NULL,
  `users_id` int(11) NOT NULL,
  `name` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `secret_key` int(11) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `duration` time DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
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
  `status` enum('ready','in use','not ready') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
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


-- 2013-12-18 12:01:59
