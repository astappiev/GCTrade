SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База даних: `gctrade`
--

-- --------------------------------------------------------

--
-- Структура таблиці `tg_auction_bid`
--

CREATE TABLE IF NOT EXISTS `tg_auction_bid` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lot_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `cost` int(11) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`user_id`),
  KEY `lot_id` (`lot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_auction_lot`
--

CREATE TABLE IF NOT EXISTS `tg_auction_lot` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `type_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `metadata` text,
  `description` text,
  `price_min` int(11) unsigned NOT NULL DEFAULT '1',
  `price_step` int(11) unsigned DEFAULT NULL,
  `price_blitz` int(11) unsigned DEFAULT NULL,
  `time_bid` int(10) unsigned NOT NULL DEFAULT '172800',
  `time_elapsed` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_item`
--

CREATE TABLE IF NOT EXISTS `tg_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_primary` int(11) unsigned NOT NULL,
  `id_meta` int(11) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(10) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_item_alias`
--

CREATE TABLE IF NOT EXISTS `tg_item_alias` (
  `item_id` int(11) unsigned NOT NULL,
  `subname` varchar(255) NOT NULL,
  PRIMARY KEY (`subname`),
  KEY `id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_see`
--

CREATE TABLE IF NOT EXISTS `tg_see` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `login` varchar(60) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_send` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_shop`
--

CREATE TABLE IF NOT EXISTS `tg_shop` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `status` tinyint(4) unsigned NOT NULL,
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(60) NOT NULL,
  `name` varchar(100) NOT NULL,
  `about` varchar(255) NOT NULL,
  `description` text,
  `subway` varchar(100) DEFAULT NULL,
  `x_cord` int(5) DEFAULT NULL,
  `z_cord` int(5) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `owner` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_shop_book`
--

CREATE TABLE IF NOT EXISTS `tg_shop_book` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` varchar(128) NOT NULL,
  `description` text,
  `price_sell` int(11) unsigned DEFAULT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_shop_good`
--

CREATE TABLE IF NOT EXISTS `tg_shop_good` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `price_sell` int(11) unsigned DEFAULT NULL,
  `price_buy` int(11) unsigned DEFAULT NULL,
  `stuck` int(11) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_shop` (`shop_id`),
  KEY `id_item` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_user`
--

CREATE TABLE IF NOT EXISTS `tg_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role` tinyint(4) unsigned NOT NULL,
  `status` tinyint(4) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `new_email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) NOT NULL,
  `access_token` varchar(360) DEFAULT NULL,
  `auth_key` varchar(255) NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_user_message`
--

CREATE TABLE IF NOT EXISTS `tg_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) DEFAULT '1',
  `user_sender` int(11) unsigned DEFAULT NULL,
  `user_recipient` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_sender` (`user_sender`),
  KEY `user_recipient` (`user_recipient`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_user_role`
--

CREATE TABLE IF NOT EXISTS `tg_user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `can_admin` tinyint(4) DEFAULT '0',
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tg_user_setting`
--

CREATE TABLE IF NOT EXISTS `tg_user_setting` (
  `user_id` int(11) unsigned NOT NULL,
  `mail_delivery` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `mail_see_leave` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `tg_auction_bid`
--
ALTER TABLE `tg_auction_bid`
  ADD CONSTRAINT `auction_bids_to_auction_lot` FOREIGN KEY (`lot_id`) REFERENCES `tg_auction_lot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auction_bids_to_user` FOREIGN KEY (`user_id`) REFERENCES `tg_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tg_auction_lot`
--
ALTER TABLE `tg_auction_lot`
  ADD CONSTRAINT `auction_lot_to_user` FOREIGN KEY (`user_id`) REFERENCES `tg_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tg_item_alias`
--
ALTER TABLE `tg_item_alias`
  ADD CONSTRAINT `item_aliases_to_item` FOREIGN KEY (`item_id`) REFERENCES `tg_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tg_see`
--
ALTER TABLE `tg_see`
  ADD CONSTRAINT `see_to_user` FOREIGN KEY (`user_id`) REFERENCES `tg_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tg_shop`
--
ALTER TABLE `tg_shop`
  ADD CONSTRAINT `shop_to_user` FOREIGN KEY (`user_id`) REFERENCES `tg_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tg_shop_book`
--
ALTER TABLE `tg_shop_book`
  ADD CONSTRAINT `shop_books_to_item` FOREIGN KEY (`item_id`) REFERENCES `tg_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_books_to_shop` FOREIGN KEY (`shop_id`) REFERENCES `tg_shop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tg_shop_good`
--
ALTER TABLE `tg_shop_good`
  ADD CONSTRAINT `shop_goods_to_item` FOREIGN KEY (`item_id`) REFERENCES `tg_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_goods_to_shop` FOREIGN KEY (`shop_id`) REFERENCES `tg_shop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tg_user_message`
--
ALTER TABLE `tg_user_message`
  ADD CONSTRAINT `user_message_to_user_recipient` FOREIGN KEY (`user_recipient`) REFERENCES `tg_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_message_to_user_sender` FOREIGN KEY (`user_sender`) REFERENCES `tg_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tg_user_setting`
--
ALTER TABLE `tg_user_setting`
  ADD CONSTRAINT `user_to_user_settings` FOREIGN KEY (`user_id`) REFERENCES `tg_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
