-- 17/09/2015
-- version: 2.2.0
CREATE TABLE IF NOT EXISTS `plugin_cartstatusemail_subscribe`(
  `user_id` INT(10) UNSIGNED NOT NULL,
  `code` CHAR(40) NOT NULL COMMENT 'Hash for unsubscribe link',
  `status` ENUM('subscribed', 'unsubscribed') NOT NULL DEFAULT 'subscribed',
  PRIMARY KEY(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `plugin_cartstatusemail_queue` MODIFY `status` ENUM('0', '1', '2') NOT NULL DEFAULT '0';

-- 17/09/2015
-- version: 2.3.0
ALTER TABLE `plugin_cartstatusemail_queue` ADD COLUMN `sentAt` DATETIME DEFAULT NULL;

-- 06/10/2015
-- version: 2.3.1
CREATE TABLE IF NOT EXISTS `plugin_cartstatusemail_restored_cart`(
`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
`cart_id` INT(10) UNSIGNED NOT NULL COMMENT 'Restored cart id',
`sent_at` DATETIME NOT NULL COMMENT 'sent link date',
`restored_at` DATETIME NOT NULL COMMENT 'restored cart date',
`code` CHAR(40) NOT NULL COMMENT 'Hash for restore cart link',
`user_id` INT(10) UNSIGNED NOT NULL COMMENT 'System user id',
`cart_status` VARCHAR(255) NOT NULL COMMENT 'Cart status',
PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

UPDATE `plugin` SET `version`='2.3.2' WHERE `name`='cartstatusemail';
SELECT version FROM `plugin` WHERE `name` = 'cartstatusemail';

