-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.38-log - MySQL Community Server (GPL)
-- Операционная система:         Win32
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.card: ~0 rows (приблизительно)
DELETE FROM `card`;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.device: ~1 rows (приблизительно)
DELETE FROM `device`;
/*!40000 ALTER TABLE `device` DISABLE KEYS */;
INSERT INTO `device` (`id`, `type`, `snum`, `fware`, `conn_fw`, `image`, `text`, `address`, `is_active`, `mode`, `zone_id`, `created_at`, `updated_at`) VALUES
	(6, 'Z5RWEB', '44374', 'a.a', '1.0.120', '/images/noimage.jpg', '', '127.0.0.1', 1, 0, 2, '2018-03-09 22:32:20', '2018-03-12 20:03:46'),
	(9, 'ggggg', '1313', '2423', '452', '/images/gallery/55168aece115.jpg', '', '', 1, 0, 1, '2018-03-10 21:26:15', '2018-03-10 21:26:44');
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
  `card_id` int(11) NOT NULL,
  `flag` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_time` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_event_device` (`device_id`),
  KEY `FK_event_idcard` (`card_id`),
  CONSTRAINT `FK_event_device` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`),
  CONSTRAINT `FK_event_idcard` FOREIGN KEY (`card_id`) REFERENCES `card` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.event: ~0 rows (приблизительно)
DELETE FROM `event`;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.eventlog: ~15 rows (приблизительно)
DELETE FROM `eventlog`;
/*!40000 ALTER TABLE `eventlog` DISABLE KEYS */;
INSERT INTO `eventlog` (`id`, `user_id`, `user_ip`, `type`, `msg`, `is_read`, `created_at`, `updated_at`) VALUES
	(17, 1, '127.0.0.1', 'error', 'Команда <strong>power_on</strong> не принята контроллером Z5RWEB(sn 44374) 09-03-2018 22:08:00', 0, '2018-03-09 22:08:00', '2018-03-09 22:08:00'),
	(18, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 09-03-2018 22:28:44', 0, '2018-03-09 22:28:44', '2018-03-09 22:28:44'),
	(19, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 09-03-2018 22:29:20', 0, '2018-03-09 22:29:20', '2018-03-09 22:29:20'),
	(20, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 09-03-2018 22:30:41', 0, '2018-03-09 22:30:41', '2018-03-09 22:30:41'),
	(21, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 09-03-2018 22:30:54', 0, '2018-03-09 22:30:54', '2018-03-09 22:30:54'),
	(22, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 09-03-2018 22:31:10', 0, '2018-03-09 22:31:10', '2018-03-09 22:31:10'),
	(23, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 09-03-2018 22:31:27', 0, '2018-03-09 22:31:27', '2018-03-09 22:31:27'),
	(24, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 09-03-2018 22:32:16', 0, '2018-03-09 22:32:17', '2018-03-09 22:32:17'),
	(25, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 09-03-2018 22:32:28', 0, '2018-03-09 22:32:28', '2018-03-09 22:32:28'),
	(26, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 09-03-2018 22:33:14', 0, '2018-03-09 22:33:14', '2018-03-09 22:33:14'),
	(27, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 09-03-2018 22:33:40', 0, '2018-03-09 22:33:40', '2018-03-09 22:33:40'),
	(28, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 09-03-2018 22:34:43', 0, '2018-03-09 22:34:43', '2018-03-09 22:34:43'),
	(29, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 09-03-2018 22:35:13', 0, '2018-03-09 22:35:13', '2018-03-09 22:35:13'),
	(30, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 09-03-2018 22:38:02', 0, '2018-03-09 22:38:02', '2018-03-09 22:38:02'),
	(31, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 10-03-2018 18:48:08', 0, '2018-03-10 18:48:08', '2018-03-10 18:48:08'),
	(32, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 10-03-2018 21:50:18', 0, '2018-03-10 21:50:18', '2018-03-10 21:50:18'),
	(33, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 11-03-2018 11:59:29', 0, '2018-03-11 11:59:29', '2018-03-11 11:59:29'),
	(34, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 11-03-2018 21:19:53', 0, '2018-03-11 21:19:53', '2018-03-11 21:19:53'),
	(35, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вошел в систему 12-03-2018 19:22:21', 0, '2018-03-12 19:22:21', '2018-03-12 19:22:21'),
	(36, 1, '127.0.0.1', 'access', 'Пользователь <strong>ircut</strong> вышел из системы 12-03-2018 21:30:14', 0, '2018-03-12 21:30:14', '2018-03-12 21:30:14');
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

-- Дамп данных таблицы skud.time_zone: ~1 rows (приблизительно)
DELETE FROM `time_zone`;
/*!40000 ALTER TABLE `time_zone` DISABLE KEYS */;
INSERT INTO `time_zone` (`id`, `zone`, `begin`, `end`, `days`, `text`, `created_at`, `updated_at`) VALUES
	(1, 0, '00:01:00', '23:59:00', '01111111', 'test', '0000-00-00 00:00:00', '2018-02-14 15:51:25'),
	(2, 1, '00:00:00', '08:00:00', '1000100', 'test zona', '2018-03-12 19:31:20', '2018-03-12 19:31:20');
/*!40000 ALTER TABLE `time_zone` ENABLE KEYS */;

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
  `is_worker` tinyint(1) NOT NULL DEFAULT '0',
  `fname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `renter_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `card_id` int(11) NOT NULL,
  `car_id` int(11) DEFAULT NULL,
  `car_num` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doc_type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doc_series` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doc_num` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_visitor_idcard` (`card_id`),
  KEY `FK_visitor_renter` (`renter_id`),
  CONSTRAINT `FK_visitor_idcard` FOREIGN KEY (`card_id`) REFERENCES `card` (`id`),
  CONSTRAINT `FK_visitor_renter` FOREIGN KEY (`renter_id`) REFERENCES `renter` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы skud.visitor: ~0 rows (приблизительно)
DELETE FROM `visitor`;
/*!40000 ALTER TABLE `visitor` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitor` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
