-- version: 2.2.0

-- 29/09/2014
-- version: 2.2.1
ALTER TABLE `plugin_paypal_transactions` ADD `subscribeStatus` VARCHAR(60) DEFAULT NULL;
ALTER TABLE `plugin_paypal_transactions` ADD `subscribePeriod` INT(10) DEFAULT NULL;
ALTER TABLE `plugin_paypal_transactions` ADD `subscribePeriodType` VARCHAR(50) DEFAULT NULL;
ALTER TABLE `plugin_paypal_transactions` ADD `subscribeQuantity` INT(10) DEFAULT NULL;
ALTER TABLE `plugin_paypal_transactions` ADD `subscribeAmount` DECIMAL(10,4) DEFAULT NULL;
ALTER TABLE `plugin_paypal_transactions` ADD `subscribeDate` VARCHAR(60) DEFAULT NULL;
ALTER TABLE `plugin_paypal_transactions` ADD `subscriptionId` VARCHAR(255) DEFAULT NULL;
ALTER TABLE `plugin_paypal_transactions` ADD `subscriptionDatePayed` VARCHAR(60) DEFAULT NULL;
ALTER TABLE `plugin_paypal_transactions` ADD `subscriptionAmountPayed` VARCHAR(60) DEFAULT NULL;

-- 12/02/2015
-- version: 2.2.2
ALTER TABLE `plugin_paypal_transactions` ADD `emailSent` ENUM('0','1') DEFAULT '0';

-- 12/02/2015
-- version: 2.2.3
ALTER TABLE `plugin_paypal_transactions` ADD `customerEmailSent` ENUM('0','1') DEFAULT '0';

-- 18/12/2015
-- version: 2.2.4
ALTER TABLE `plugin_paypal_transactions`
ADD `refundTransactionId` varchar(128) COLLATE 'utf8_unicode_ci' NULL,
ADD `refundReason` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `refundTransactionId`;

-- These alters are always the latest and updated version of the database
UPDATE `plugin` SET `version`='2.3.0' WHERE `name`='paypal';
SELECT version FROM `plugin` WHERE `name` = 'paypal';

