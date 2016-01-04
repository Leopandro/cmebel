-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.5.41-log - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры базы данных cmebel
CREATE DATABASE IF NOT EXISTS `cmebel` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `cmebel`;


-- Дамп структуры для таблица cmebel.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(63) NOT NULL,
  `email` varchar(127) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='клиенты магазина';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.migration
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_opencart_id` int(10) unsigned NOT NULL COMMENT 'id заказа из opencart',
  `version` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'версия',
  `client_id` int(10) unsigned NOT NULL COMMENT 'клиент',
  `status_id` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `total` decimal(10,2) unsigned NOT NULL COMMENT 'итого',
  `date_added` datetime NOT NULL COMMENT 'дата добавления',
  `date_modified` datetime NOT NULL COMMENT 'дата изменения',
  `last_version` tinyint(1) NOT NULL DEFAULT '1',
  `comment` varchar(128) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_address_1` varchar(255) NOT NULL,
  `payment_address_2` varchar(255) NOT NULL,
  `payment_city` varchar(255) NOT NULL,
  `payment_country` varchar(255) NOT NULL,
  `payment_postcode` varchar(10) NOT NULL,
  `payment_zone` varchar(128) NOT NULL,
  `version_name` varchar(128) NOT NULL,
  `version_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_opencart_id` (`order_opencart_id`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='заказы';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.order_files
CREATE TABLE IF NOT EXISTS `order_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT 'название файла',
  `order_id` int(10) unsigned NOT NULL COMMENT 'id заказа',
  `file` varchar(255) NOT NULL COMMENT 'путь к файлу',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`,`file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='файлы к заказам';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.order_product
CREATE TABLE IF NOT EXISTS `order_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL COMMENT 'id заказа',
  `product_id` int(10) unsigned NOT NULL COMMENT 'id товара',
  `quantity` smallint(5) unsigned NOT NULL COMMENT 'количество',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='товары заказов';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.order_status
CREATE TABLE IF NOT EXISTS `order_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='статусы заказов';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.profile
CREATE TABLE IF NOT EXISTS `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.shop_categories
CREATE TABLE IF NOT EXISTS `shop_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opencart_id` int(10) unsigned DEFAULT NULL COMMENT 'ID из интернет-магазина',
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.shop_products
CREATE TABLE IF NOT EXISTS `shop_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opencart_id` int(10) unsigned DEFAULT NULL COMMENT 'ID из интернет-магазина',
  `name` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL COMMENT 'название модели',
  `category_id` int(10) unsigned NOT NULL COMMENT 'категория',
  `price` decimal(10,2) NOT NULL COMMENT 'цена',
  `quantity` int(10) unsigned NOT NULL COMMENT 'количество',
  `image` varchar(255) DEFAULT NULL COMMENT 'фото',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL COMMENT 'дата добавления',
  `date_modified` datetime NOT NULL COMMENT 'дата редактирования',
  `sku` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `opencart_id` (`opencart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='товары';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.social_account
CREATE TABLE IF NOT EXISTS `social_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_unique` (`provider`,`client_id`),
  UNIQUE KEY `account_unique_code` (`code`),
  KEY `fk_user_account` (`user_id`),
  CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.tasks
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` smallint(5) unsigned NOT NULL COMMENT 'исполнитель',
  `order_opencart_id` int(10) unsigned NOT NULL COMMENT 'id заказа из opencart',
  `serial_number` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'порядковый номер в рамках заказа',
  `text` text NOT NULL COMMENT 'формулировка',
  `comment` text NOT NULL COMMENT 'комментарий',
  `date_added` datetime NOT NULL COMMENT 'дата добавления',
  `date_start` datetime NOT NULL COMMENT 'дата начала',
  `date_end` datetime NOT NULL COMMENT 'дата окончания',
  `date_closed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`order_opencart_id`,`serial_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='задачи';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.task_files
CREATE TABLE IF NOT EXISTS `task_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT 'название файла',
  `task_id` int(10) unsigned NOT NULL COMMENT 'id задачи',
  `file` varchar(255) NOT NULL COMMENT 'путь к файлу',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`task_id`,`file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='файлы к задачам';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.token
CREATE TABLE IF NOT EXISTS `token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  UNIQUE KEY `token_unique` (`user_id`,`code`,`type`),
  CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица cmebel.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `corporate_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_unique_email` (`email`),
  UNIQUE KEY `user_unique_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Экспортируемые данные не выделены.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
