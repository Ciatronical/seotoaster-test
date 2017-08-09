CREATE TABLE IF NOT EXISTS `plugin_cartstatusemail_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cartStatus` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `periodHours` int(4) unsigned NOT NULL,
  `productsIds` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `emailTemplate` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `emailFrom` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `productsRule` ENUM('all', 'any', 'without') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'all',
  `emailMessage` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `plugin_cartstatusemail_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cartStatusId` int(10) NOT NULL,
  `status` ENUM('0', '1', '2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `cartStatus` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `cartId` int(10) NOT NULL,
  `userEmail` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `userFullName` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `emailMessage` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `emailTemplate` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `emailFrom` VARCHAR(255) DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `sentAt` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `observers_queue` (`observable`, `observer`) VALUES
('Models_Model_CartSession', 'Cartstatusemail_Tools_CartStatusObserver');

INSERT INTO `email_triggers` (`enabled`, `trigger_name`, `observer`) VALUES
('1', 'cartstatusemail_abandoned',        'Cartstatusemail_Tools_CartstatusemailMailWatchdog'),
('1', 'cartstatusemail_newquote',         'Cartstatusemail_Tools_CartstatusemailMailWatchdog'),
('1', 'cartstatusemail_quotesent',        'Cartstatusemail_Tools_CartstatusemailMailWatchdog'),
('1', 'cartstatusemail_paymentreceived',  'Cartstatusemail_Tools_CartstatusemailMailWatchdog'),
('1', 'cartstatusemail_itemsshipped',     'Cartstatusemail_Tools_CartstatusemailMailWatchdog'),
('1', 'cartstatusemail_itemsdelivered',   'Cartstatusemail_Tools_CartstatusemailMailWatchdog'),
('1', 'cartstatusemail_refundedpurchase', 'Cartstatusemail_Tools_CartstatusemailMailWatchdog'),
('1', 'cartstatusemail_actionrequire',    'Cartstatusemail_Tools_CartstatusemailMailWatchdog'),
('1', 'cartstatusemail_technicalprocessing', 'Cartstatusemail_Tools_CartstatusemailMailWatchdog'),
('1', 'cartstatusemail_lostopportunity',  'Cartstatusemail_Tools_CartstatusemailMailWatchdog');

INSERT INTO `email_triggers_actions` (`trigger`, `template`, `recipient`, `message`, `from`, `subject`) VALUES
('cartstatusemail_abandoned', 'default', 'customer', 'You’ve left something rather lovely in your shopping basket. {cart:basket} Ready to make it yours? Click here {cart:recovery}', 'admin@example.com', 'What’s that in your shopping basket? Do you see what we see?'),
('cartstatusemail_quotesent', 'default', 'customer', 'Don’t forget, you left something behind. To help you make up your mind, enjoy 15% off your quote when you call us at XXX XXX XXXX with coupon # Click here to view your private quote {cart:recovery} Hurry this offer ends on XX/XX/XXXX', 'admin@example.com', 'A little something to sweeten your day'),
('cartstatusemail_paymentreceived', 'default', 'customer', 'We wanted to thank you for your business with a free shipping voucher code for your next purchase. Next time you shop with us, please enter XXXXX in the promotional code field on the checkout page.', 'admin@example.com', 'Thank you for your payment');

CREATE TABLE IF NOT EXISTS `plugin_cartstatusemail_subscribe`(
  `user_id` INT(10) UNSIGNED NOT NULL,
  `code` CHAR(40) NOT NULL COMMENT 'Hash for unsubscribe link',
  `status` ENUM('subscribed', 'unsubscribed') NOT NULL DEFAULT 'subscribed',
  PRIMARY KEY(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

UPDATE `plugin` SET `tags`='ecommerce,merchandising' WHERE `name` = 'cartstatusemail';
UPDATE `plugin` SET `version`='2.3.2' WHERE `name` = 'cartstatusemail';





