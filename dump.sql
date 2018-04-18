-- --------------------------------------------------------
-- Хост:                         93.157.171.45
-- Версия сервера:               5.7.21-0ubuntu0.16.04.1 - (Ubuntu)
-- Операционная система:         Linux
-- HeidiSQL Версия:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица skud.auth_assignment
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assignment_user_id_idx` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.auth_assignment: ~0 rows (приблизительно)
DELETE FROM `auth_assignment`;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
	('admin', '1', 1520416955);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;

-- Дамп структуры для таблица skud.auth_item
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.auth_item: ~6 rows (приблизительно)
DELETE FROM `auth_item`;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
	('admin', 1, NULL, NULL, NULL, 1520416955, 1520416955),
	('adminTask', 2, 'Задачи администратора', NULL, NULL, 1520416955, 1520416955),
	('operator', 1, NULL, NULL, NULL, 1520416955, 1520416955),
	('operatorTask', 2, 'Задачи оператора', NULL, NULL, 1520416955, 1520416955),
	('security', 1, NULL, NULL, NULL, 1520416955, 1520416955),
	('securityTask', 2, 'Задачи начальника охраны', NULL, NULL, 1520416955, 1520416955);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;

-- Дамп структуры для таблица skud.auth_item_child
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.auth_item_child: ~5 rows (приблизительно)
DELETE FROM `auth_item_child`;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
	('admin', 'adminTask'),
	('admin', 'operator'),
	('security', 'operator'),
	('admin', 'security'),
	('security', 'securityTask');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;

-- Дамп структуры для таблица skud.auth_rule
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.auth_rule: ~0 rows (приблизительно)
DELETE FROM `auth_rule`;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;

-- Дамп структуры для таблица skud.car
DROP TABLE IF EXISTS `car`;
CREATE TABLE IF NOT EXISTS `car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `text` (`text`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.car: ~2 rows (приблизительно)
DELETE FROM `car`;
/*!40000 ALTER TABLE `car` DISABLE KEYS */;
INSERT INTO `car` (`id`, `text`, `created_at`, `updated_at`) VALUES
	(1, 'СКАНИЯ', '2018-02-14 12:31:32', '2018-02-14 12:31:32'),
	(2, 'ГАЗЕЛЬ', '2018-02-14 12:31:58', '2018-02-14 12:31:58');
/*!40000 ALTER TABLE `car` ENABLE KEYS */;

-- Дамп структуры для таблица skud.card
DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `granted` tinyint(1) NOT NULL DEFAULT '0',
  `flags` smallint(6) DEFAULT NULL,
  `zone` smallint(6) NOT NULL,
  `share` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.card: ~2 rows (приблизительно)
DELETE FROM `card`;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;
INSERT INTO `card` (`id`, `code`, `granted`, `flags`, `zone`, `share`, `created_at`, `updated_at`) VALUES
	(3, '2720454', 0, NULL, 255, 0, '2018-04-11 12:48:14', '2018-04-11 12:48:14'),
	(4, '2723355', 0, NULL, 255, 0, '2018-04-11 13:59:08', '2018-04-11 13:59:08');
/*!40000 ALTER TABLE `card` ENABLE KEYS */;

-- Дамп структуры для таблица skud.device
DROP TABLE IF EXISTS `device`;
CREATE TABLE IF NOT EXISTS `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `snum` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `fware` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `conn_fw` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `mode` smallint(6) NOT NULL DEFAULT '0',
  `zone_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `snum` (`snum`),
  KEY `FK_device_time_zone` (`zone_id`),
  CONSTRAINT `FK_device_time_zone` FOREIGN KEY (`zone_id`) REFERENCES `time_zone` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.device: ~0 rows (приблизительно)
DELETE FROM `device`;
/*!40000 ALTER TABLE `device` DISABLE KEYS */;
INSERT INTO `device` (`id`, `type`, `snum`, `fware`, `conn_fw`, `image`, `text`, `address`, `is_active`, `mode`, `zone_id`, `created_at`, `updated_at`) VALUES
	(10, 'Z5RWEB', '44374', '3.23', '1.0.123', '/images/noimage.jpg', '', '192.168.8.9', 0, 8, 1, '2018-03-13 14:36:34', '2018-04-12 10:09:08');
/*!40000 ALTER TABLE `device` ENABLE KEYS */;

-- Дамп структуры для таблица skud.doc_type
DROP TABLE IF EXISTS `doc_type`;
CREATE TABLE IF NOT EXISTS `doc_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `text` (`text`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.doc_type: ~5 rows (приблизительно)
DELETE FROM `doc_type`;
/*!40000 ALTER TABLE `doc_type` DISABLE KEYS */;
INSERT INTO `doc_type` (`id`, `text`, `created_at`, `updated_at`) VALUES
	(1, 'Паспорт гражданина РФ', '2018-02-14 13:02:22', '2018-02-14 13:02:22'),
	(2, 'Загранпаспорт', '2018-02-14 13:44:58', '2018-02-14 13:44:58'),
	(3, 'Водительские права', '2018-02-14 13:45:16', '2018-02-14 13:45:16'),
	(4, 'Военный билет', '2018-02-14 13:45:30', '2018-02-14 13:45:30'),
	(5, 'Удостоверение личности', '2018-02-14 13:45:54', '2018-02-14 13:45:54');
/*!40000 ALTER TABLE `doc_type` ENABLE KEYS */;

-- Дамп структуры для таблица skud.event
DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `event_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `card` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `flag` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_time` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_event_device` (`device_id`),
  CONSTRAINT `FK_event_device` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5467 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.event: ~42 rows (приблизительно)
DELETE FROM `event`;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` (`id`, `device_id`, `event_type`, `card`, `flag`, `event_time`, `created_at`, `updated_at`) VALUES
	(5402, 10, '17', '2713962', '0', '2018-04-05 15:27:25', '2018-04-05 15:28:41', '2018-04-05 15:28:41'),
	(5426, 10, '2', '2723355', '0', '2018-04-10 14:36:03', '2018-04-10 14:36:05', '2018-04-10 14:36:05'),
	(5427, 10, '3', '2723355', '0', '2018-04-10 14:36:06', '2018-04-10 14:36:08', '2018-04-10 14:36:08'),
	(5428, 10, '2', '2717462', '0', '2018-04-10 15:05:17', '2018-04-10 15:07:29', '2018-04-10 15:07:29'),
	(5429, 10, '3', '2717462', '0', '2018-04-10 15:05:19', '2018-04-10 15:07:29', '2018-04-10 15:07:29'),
	(5430, 10, '2', '2720454', '0', '2018-04-11 12:44:00', '2018-04-11 12:44:01', '2018-04-11 12:44:01'),
	(5431, 10, '3', '2720454', '0', '2018-04-11 12:44:02', '2018-04-11 12:44:04', '2018-04-11 12:44:04'),
	(5432, 10, '4', '2720454', '0', '2018-04-11 12:55:21', '2018-04-11 12:55:23', '2018-04-11 12:55:23'),
	(5433, 10, '16', '2720454', '0', '2018-04-11 12:55:21', '2018-04-11 12:55:23', '2018-04-11 12:55:23'),
	(5434, 10, '5', '2720454', '0', '2018-04-11 12:55:26', '2018-04-11 12:55:27', '2018-04-11 12:55:27'),
	(5435, 10, '17', '2720454', '0', '2018-04-11 12:55:26', '2018-04-11 12:55:27', '2018-04-11 12:55:27'),
	(5436, 10, '5', '2723355', '0', '2018-04-11 14:17:02', '2018-04-11 14:17:08', '2018-04-11 14:17:08'),
	(5437, 10, '17', '2723355', '0', '2018-04-11 14:17:02', '2018-04-11 14:17:08', '2018-04-11 14:17:08'),
	(5438, 10, '4', '2720454', '0', '2018-04-11 14:20:41', '2018-04-11 14:22:37', '2018-04-11 14:22:37'),
	(5439, 10, '16', '2720454', '0', '2018-04-11 14:20:41', '2018-04-11 14:22:37', '2018-04-11 14:22:37'),
	(5440, 10, '4', '2723355', '0', '2018-04-11 14:20:46', '2018-04-11 14:22:37', '2018-04-11 14:22:37'),
	(5441, 10, '16', '2723355', '0', '2018-04-11 14:20:46', '2018-04-11 14:22:37', '2018-04-11 14:22:37'),
	(5442, 10, '5', '2723355', '0', '2018-04-11 14:26:13', '2018-04-11 14:26:15', '2018-04-11 14:26:15'),
	(5443, 10, '17', '2723355', '0', '2018-04-11 14:26:13', '2018-04-11 14:26:15', '2018-04-11 14:26:15'),
	(5444, 10, '5', '2720454', '0', '2018-04-11 14:26:18', '2018-04-11 14:26:20', '2018-04-11 14:26:20'),
	(5445, 10, '17', '2720454', '0', '2018-04-11 14:26:18', '2018-04-11 14:26:20', '2018-04-11 14:26:20'),
	(5446, 10, '4', '2720454', '0', '2018-04-12 10:01:30', '2018-04-12 10:01:32', '2018-04-12 10:01:32'),
	(5447, 10, '16', '2720454', '0', '2018-04-12 10:01:30', '2018-04-12 10:01:32', '2018-04-12 10:01:32'),
	(5448, 10, '16', '2720454', '0', '2018-04-12 10:07:58', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5449, 10, '5', '2720454', '0', '2018-04-12 10:08:02', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5450, 10, '17', '2720454', '0', '2018-04-12 10:08:02', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5451, 10, '4', '2723355', '0', '2018-04-12 10:08:50', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5452, 10, '16', '2723355', '0', '2018-04-12 10:08:50', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5453, 10, '5', '2723355', '0', '2018-04-12 10:08:54', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5454, 10, '17', '2723355', '0', '2018-04-12 10:08:54', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5455, 10, '4', '2720454', '0', '2018-04-12 10:09:53', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5456, 10, '16', '2720454', '0', '2018-04-12 10:09:53', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5457, 10, '5', '2720454', '0', '2018-04-12 10:09:57', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5458, 10, '17', '2720454', '0', '2018-04-12 10:09:57', '2018-04-12 10:10:10', '2018-04-12 10:10:10'),
	(5459, 10, '2', '2717462', '0', '2018-04-12 10:39:14', '2018-04-12 10:39:14', '2018-04-12 10:39:14'),
	(5460, 10, '3', '2717462', '0', '2018-04-12 10:39:18', '2018-04-12 10:39:18', '2018-04-12 10:39:18'),
	(5461, 10, '4', '2720454', '0', '2018-04-12 10:40:10', '2018-04-12 10:40:10', '2018-04-12 10:40:10'),
	(5462, 10, '16', '2720454', '0', '2018-04-12 10:40:10', '2018-04-12 10:40:10', '2018-04-12 10:40:10'),
	(5463, 10, '5', '2720454', '0', '2018-04-12 10:40:20', '2018-04-12 10:40:20', '2018-04-12 10:40:20'),
	(5464, 10, '17', '2720454', '0', '2018-04-12 10:40:20', '2018-04-12 10:40:20', '2018-04-12 10:40:20'),
	(5465, 10, '2', '1367783', '0', '2018-04-12 13:22:13', '2018-04-12 13:22:12', '2018-04-12 13:22:12'),
	(5466, 10, '3', '1367783', '0', '2018-04-12 13:22:15', '2018-04-12 13:22:14', '2018-04-12 13:22:14');
/*!40000 ALTER TABLE `event` ENABLE KEYS */;

-- Дамп структуры для таблица skud.eventlog
DROP TABLE IF EXISTS `eventlog`;
CREATE TABLE IF NOT EXISTS `eventlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `msg` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_read` smallint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.eventlog: ~10 rows (приблизительно)
DELETE FROM `eventlog`;
/*!40000 ALTER TABLE `eventlog` DISABLE KEYS */;
INSERT INTO `eventlog` (`id`, `user_id`, `user_ip`, `type`, `msg`, `is_read`, `created_at`, `updated_at`) VALUES
	(53, 1, '192.168.7.2', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 17-03-2018 20:54:14', 0, '2018-03-17 20:54:14', '2018-03-17 20:54:14'),
	(54, 1, '192.168.7.2', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 20-03-2018 20:13:54', 0, '2018-03-20 20:13:54', '2018-03-20 20:13:54'),
	(55, 1, '192.168.8.150', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 27-03-2018 14:00:43', 0, '2018-03-27 14:00:43', '2018-03-27 14:00:43'),
	(56, 1, '192.168.8.150', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 05-04-2018 12:09:05', 0, '2018-04-05 12:09:05', '2018-04-05 12:09:05'),
	(57, 1, '192.168.8.150', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 10-04-2018 10:27:58', 0, '2018-04-10 10:27:58', '2018-04-10 10:27:58'),
	(58, 1, '192.168.8.150', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 10-04-2018 14:55:51', 0, '2018-04-10 14:55:51', '2018-04-10 14:55:51'),
	(59, 1, '192.168.8.150', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 11-04-2018 12:47:22', 0, '2018-04-11 12:47:22', '2018-04-11 12:47:22'),
	(60, 1, '192.168.8.150', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 11-04-2018 13:56:56', 0, '2018-04-11 13:56:56', '2018-04-11 13:56:56'),
	(61, 1, '192.168.8.150', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 11-04-2018 15:57:58', 0, '2018-04-11 15:57:58', '2018-04-11 15:57:58'),
	(62, 1, '192.168.8.150', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 12-04-2018 10:03:05', 0, '2018-04-12 10:03:05', '2018-04-12 10:03:05');
/*!40000 ALTER TABLE `eventlog` ENABLE KEYS */;

-- Дамп структуры для таблица skud.event_type
DROP TABLE IF EXISTS `event_type`;
CREATE TABLE IF NOT EXISTS `event_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `code` smallint(6) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.event_type: ~37 rows (приблизительно)
DELETE FROM `event_type`;
/*!40000 ALTER TABLE `event_type` DISABLE KEYS */;
INSERT INTO `event_type` (`id`, `text`, `code`, `created_at`, `updated_at`) VALUES
	(1, 'Открыто кнопкой изнутри (вход)', 0, '2018-02-15 15:29:26', '2018-02-15 15:29:26'),
	(2, 'Открыто кнопкой изнутри (выход)', 1, '2018-02-15 15:36:03', '2018-02-15 15:36:03'),
	(3, 'Ключ не найден в банке ключей (вход)', 2, '2018-02-15 15:36:38', '2018-02-15 15:36:38'),
	(4, 'Ключ не найден в банке ключей (выход)', 3, '2018-02-15 15:36:58', '2018-02-15 15:36:58'),
	(5, 'Ключ найден, дверь открыта (вход)', 4, '2018-02-15 15:38:39', '2018-02-15 15:38:39'),
	(6, 'Ключ найден, дверь открыта (выход)', 5, '2018-02-15 15:38:55', '2018-02-15 15:38:55'),
	(7, 'Ключ найден, доступ не разрешен (вход)', 6, '2018-02-15 15:39:45', '2018-02-15 15:39:45'),
	(8, 'Ключ найден, доступ не разрешен (выход)', 7, '2018-02-15 15:40:01', '2018-02-15 15:40:01'),
	(9, 'Открыто оператором по сети (вход)', 8, '2018-02-15 15:40:34', '2018-02-15 15:40:34'),
	(10, 'Открыто оператором по сети (выход)', 9, '2018-02-15 15:40:52', '2018-02-15 15:40:52'),
	(11, 'Ключ найден, дверь заблокирована (вход)', 10, '2018-02-15 15:44:40', '2018-02-15 15:44:40'),
	(12, 'Ключ найден, дверь заблокирована (выход)', 11, '2018-02-15 15:44:56', '2018-02-15 15:44:56'),
	(13, 'Дверь взломана (вход)', 12, '2018-02-15 15:45:22', '2018-02-15 15:45:22'),
	(14, 'Дверь взломана (выход)', 13, '2018-02-15 15:45:38', '2018-02-15 15:45:38'),
	(15, 'Дверь оставлена открытой (вход)', 14, '2018-02-15 15:46:21', '2018-02-15 15:46:21'),
	(16, 'Дверь оставлена открытой (выход)', 15, '2018-02-15 15:46:38', '2018-02-15 15:46:38'),
	(17, 'Проход состоялся (вход)', 16, '2018-02-15 15:47:14', '2018-02-15 15:47:14'),
	(18, 'Проход состоялся (выход)', 17, '2018-02-15 15:47:28', '2018-02-15 15:47:28'),
	(19, 'Перезагрузка контроллера', 20, '2018-02-15 15:48:03', '2018-02-15 15:48:03'),
	(20, 'Питание', 21, '2018-02-15 15:48:25', '2018-02-15 15:48:25'),
	(21, 'Дверь открыта (вход)', 32, '2018-02-15 15:48:56', '2018-02-15 15:48:56'),
	(22, 'Дверь открыта (выход)', 33, '2018-02-15 15:49:12', '2018-02-15 15:49:12'),
	(23, 'Дверь закрыта (вход)', 34, '2018-02-15 15:49:36', '2018-02-15 15:49:36'),
	(24, 'Дверь закрыта (выход)', 35, '2018-02-15 15:49:53', '2018-02-15 15:49:53'),
	(25, 'Переключение режимов работы', 37, '2018-02-15 15:50:22', '2018-02-15 15:50:22'),
	(26, 'Пожарные события', 38, '2018-02-15 15:50:45', '2018-02-15 15:50:45'),
	(27, 'Охранные события', 39, '2018-02-15 15:51:08', '2018-02-15 15:51:08'),
	(28, 'Проход не завершен за заданное время (вход)', 40, '2018-02-15 15:51:38', '2018-02-15 15:51:38'),
	(29, 'Проход не завершен за заданное время (выход)', 41, '2018-02-15 15:51:52', '2018-02-15 15:51:52'),
	(30, 'Совершен вход в шлюз (вход)', 48, '2018-02-15 15:52:20', '2018-02-15 15:52:20'),
	(31, 'Совершен вход в шлюз (выход)', 49, '2018-02-15 15:52:34', '2018-02-15 15:52:34'),
	(32, 'Заблокирован вход в шлюз (вход)', 50, '2018-02-15 15:53:40', '2018-02-15 15:53:40'),
	(33, 'Заблокирован вход в шлюз (выход)', 51, '2018-02-15 15:54:01', '2018-02-15 15:54:01'),
	(34, 'Разрешен вход в шлюз (вход)', 52, '2018-02-15 15:54:26', '2018-02-15 15:54:26'),
	(35, 'Разрешен вход в шлюз (выход)', 53, '2018-02-15 15:54:41', '2018-02-15 15:54:41'),
	(36, 'Проход заблокирован (вход)', 54, '2018-02-15 15:55:09', '2018-02-15 15:55:09'),
	(37, 'Проход заблокирован (выход)', 55, '2018-02-15 15:55:24', '2018-02-15 15:55:24');
/*!40000 ALTER TABLE `event_type` ENABLE KEYS */;

-- Дамп структуры для таблица skud.migration
DROP TABLE IF EXISTS `migration`;
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы skud.migration: ~15 rows (приблизительно)
DELETE FROM `migration`;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` (`version`, `apply_time`) VALUES
	('m000000_000000_base', 1519994415),
	('m140506_102106_rbac_init', 1520415780),
	('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1520415780),
	('m180302_123041_create_device_table', 1519994418),
	('m180302_123107_create_idcard_table', 1519994418),
	('m180302_123137_create_event_table', 1519994418),
	('m180302_123157_create_visitor_table', 1519994418),
	('m180302_123220_create_time_zone_table', 1519994419),
	('m180302_123253_create_event_type_table', 1519994419),
	('m180302_123323_create_car_table', 1519994419),
	('m180302_123348_create_doc_type_table', 1519994419),
	('m180302_125112_create_renter_table', 1519995231),
	('m180305_171744_create_user_table', 1520415381),
	('m180305_172236_create_role_table', 1520415381),
	('m180305_175002_create_eventlog_table', 1520415381);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;

-- Дамп структуры для таблица skud.renter
DROP TABLE IF EXISTS `renter`;
CREATE TABLE IF NOT EXISTS `renter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `area` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `agent` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.renter: ~0 rows (приблизительно)
DELETE FROM `renter`;
/*!40000 ALTER TABLE `renter` DISABLE KEYS */;
INSERT INTO `renter` (`id`, `title`, `area`, `agent`, `phone`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'ИП "Коняхин Роман Александрович"', 'БВЗ', 'Коняхин Роман Александрович', '(495) 215-20-51', 1, '2018-03-09 12:48:12', '2018-03-09 12:48:59');
/*!40000 ALTER TABLE `renter` ENABLE KEYS */;

-- Дамп структуры для таблица skud.role
DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы skud.role: ~3 rows (приблизительно)
DELETE FROM `role`;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`id`, `name`, `alias`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'Администратор', '2018-03-08 11:56:50', '2018-03-08 11:56:50'),
	(2, 'security', 'Бюро пропусков', '2018-03-08 11:55:31', '2018-03-08 11:55:31'),
	(3, 'operator', 'Оператор', '2018-03-08 11:56:08', '2018-03-08 11:56:08');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- Дамп структуры для таблица skud.task
DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `snum` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `json` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.task: ~0 rows (приблизительно)
DELETE FROM `task`;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
/*!40000 ALTER TABLE `task` ENABLE KEYS */;

-- Дамп структуры для таблица skud.time_zone
DROP TABLE IF EXISTS `time_zone`;
CREATE TABLE IF NOT EXISTS `time_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone` smallint(3) NOT NULL,
  `begin` time NOT NULL,
  `end` time NOT NULL,
  `days` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zone` (`zone`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.time_zone: ~2 rows (приблизительно)
DELETE FROM `time_zone`;
/*!40000 ALTER TABLE `time_zone` DISABLE KEYS */;
INSERT INTO `time_zone` (`id`, `zone`, `begin`, `end`, `days`, `text`, `created_at`, `updated_at`) VALUES
	(1, 0, '00:01:00', '23:59:00', '01111111', 'test', '0000-00-00 00:00:00', '2018-02-14 15:51:25'),
	(2, 1, '00:00:00', '08:00:00', '1000100', 'test zona', '2018-03-12 19:31:20', '2018-03-12 19:31:20');
/*!40000 ALTER TABLE `time_zone` ENABLE KEYS */;

-- Дамп структуры для таблица skud.tracelog
DROP TABLE IF EXISTS `tracelog`;
CREATE TABLE IF NOT EXISTS `tracelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `msg` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3259 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.tracelog: ~0 rows (приблизительно)
DELETE FROM `tracelog`;
/*!40000 ALTER TABLE `tracelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tracelog` ENABLE KEYS */;

-- Дамп структуры для таблица skud.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '0',
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL,
  `image` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.user: ~0 rows (приблизительно)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `active`, `username`, `fname`, `lname`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `role_id`, `image`, `created_at`, `updated_at`) VALUES
	(1, 0, 'ircut', 'Администратор', 'системы', 'ctFKTDvbVrNjaZaQHEzV2vtDC-pzqRm9', '$2y$13$sh4owb0Z9AlaLmekrElDBOWAsMJtDskHlNzO/tATX6ZWQhXWRN1oq', NULL, 'admin@mail.com', 1, 1, NULL, '2018-03-07 12:38:39', '2018-03-07 12:38:39');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Дамп структуры для таблица skud.visitor
DROP TABLE IF EXISTS `visitor`;
CREATE TABLE IF NOT EXISTS `visitor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `renter_id` int(11) NOT NULL,
  `card` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `car_id` int(11) DEFAULT NULL,
  `car_num` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doc_id` int(11) NOT NULL,
  `doc_series` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doc_num` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `card` (`card`),
  KEY `FK_visitor_renter` (`renter_id`),
  KEY `FK_visitor_car` (`car_id`),
  KEY `FK_visitor_doc_type` (`doc_id`),
  CONSTRAINT `FK_visitor_car` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`),
  CONSTRAINT `FK_visitor_doc_type` FOREIGN KEY (`doc_id`) REFERENCES `doc_type` (`id`),
  CONSTRAINT `FK_visitor_renter` FOREIGN KEY (`renter_id`) REFERENCES `renter` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.visitor: ~0 rows (приблизительно)
DELETE FROM `visitor`;
/*!40000 ALTER TABLE `visitor` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitor` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
