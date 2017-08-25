CREATE TABLE IF NOT EXISTS `plugin_pagerating_rating` (
  `pageId` int(10) unsigned NOT NULL,
  `ratingValue` decimal(4,2) unsigned DEFAULT '0.00',
  `ratingCount` int(10) unsigned NOT NULL,
  `totalPoints` decimal(8,2) unsigned NOT NULL,
  PRIMARY KEY (`pageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `plugin_pagerating_review` (
  `pageId` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ratingValue` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `datePublished` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `plugin_pagerating_configuration` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/* Insertion default values */
INSERT INTO `plugin_pagerating_configuration` (`name`, `value`) VALUES ('reviewCaptcha', 0);