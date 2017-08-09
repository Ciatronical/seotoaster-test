-- 26/12/2014
-- version: 2.2.1
INSERT IGNORE INTO `observers_queue` (`observable`, `observer`)
SELECT CONCAT('Application_Model_Models_Form'), CONCAT('Tools_SmsServiceFormWatchdog') FROM observers_queue WHERE
NOT EXISTS (SELECT `observable`, `observer` FROM `observers_queue`
WHERE `observable` = 'Application_Model_Models_Form' AND `observer` = 'Tools_SmsServiceFormWatchdog') LIMIT 1;

INSERT IGNORE INTO `observers_queue` (`observable`, `observer`)
SELECT CONCAT('Application_Model_Models_Form'), CONCAT('Tools_MailServiceFormWatchdog') FROM observers_queue WHERE
NOT EXISTS (SELECT `observable`, `observer` FROM `observers_queue`
WHERE `observable` = 'Application_Model_Models_Form' AND `observer` = 'Tools_MailServiceFormWatchdog') LIMIT 1;

INSERT IGNORE INTO `email_triggers` (`id`, `enabled`, `trigger_name`, `observer`)
SELECT CONCAT(NULL), CONCAT('1'), CONCAT('store_neworder'), CONCAT('Tools_AppsSmsWatchdog') FROM email_triggers WHERE
NOT EXISTS (SELECT `id`, `enabled`, `trigger_name`, `observer` FROM `email_triggers`
WHERE `enabled` = '1' AND `trigger_name` = 'store_neworder' AND `observer` = 'Tools_AppsSmsWatchdog')
AND EXISTS (SELECT name FROM `plugin` where `name` = 'shopping') LIMIT 1;

INSERT IGNORE INTO `email_triggers` (`id`, `enabled`, `trigger_name`, `observer`)
SELECT CONCAT(NULL), CONCAT('1'), CONCAT('store_trackingnumber'), CONCAT('Tools_AppsSmsWatchdog') FROM email_triggers WHERE
NOT EXISTS (SELECT `id`, `enabled`, `trigger_name`, `observer` FROM `email_triggers`
WHERE `enabled` = '1' AND `trigger_name` = 'store_trackingnumber' AND `observer` = 'Tools_AppsSmsWatchdog')
AND EXISTS (SELECT name FROM `plugin` where `name` = 'shopping') LIMIT 1;

INSERT IGNORE INTO `email_triggers_actions` (`id`, `service`, `trigger`, `template`, `recipient`, `message`, `from`, `subject`)
SELECT NULL, CONCAT('sms'), CONCAT('store_neworder'), NULL, CONCAT('customer') , CONCAT('Hello {customer:fullname},
this message is from your favorite store {company:name}.
We received your order on {order:createdat} date for {order:total}.
Your order status is {order:status} and will ship to {order:shippingaddress}.
Thanks for your business.'), NULL,  NULL
FROM email_triggers_actions WHERE
NOT EXISTS (SELECT `id`, `service`, `trigger`, `template`, `recipient`, `message`, `from`, `subject` FROM `email_triggers_actions`
WHERE `service` = 'sms' AND `recipient` = 'customer' AND `trigger` = 'store_neworder')
AND EXISTS (SELECT name FROM `plugin` where `name` = 'shopping') LIMIT 1;

INSERT IGNORE INTO `email_triggers_actions` (`id`, `service`, `trigger`, `template`, `recipient`, `message`, `from`, `subject`)
SELECT NULL, CONCAT('sms'), CONCAT('store_trackingnumber'), NULL, CONCAT('customer') , CONCAT('Hello {customer:fullname},
this message is from your favorite store {company:name}.
Your order {order:shippingtrackingid} for {order:total} placed on {order:createdat} is now {order:status}.
The shipping address for this order is {order:shippingaddress}
Thanks for your business.'), NULL,  NULL
FROM email_triggers_actions WHERE
NOT EXISTS (SELECT `id`, `service`, `trigger`, `template`, `recipient`, `message`, `from`, `subject` FROM `email_triggers_actions`
WHERE `service` = 'sms' AND `recipient` = 'customer' AND `trigger` = 'store_trackingnumber')
AND EXISTS (SELECT name FROM `plugin` where `name` = 'shopping') LIMIT 1;

-- 23/06/2015
-- version: 2.2.2
INSERT IGNORE INTO `observers_queue` (`observable`, `observer`)
SELECT CONCAT('Application_Model_Models_Form'), CONCAT('Tools_CrmServiceFormWatchdog') FROM observers_queue WHERE
NOT EXISTS (SELECT `observable`, `observer` FROM `observers_queue`
WHERE `observable` = 'Application_Model_Models_Form' AND `observer` = 'Tools_CrmServiceFormWatchdog') LIMIT 1;

CREATE TABLE IF NOT EXISTS `plugin_apps_crm` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dataType` VARCHAR(255) NOT NULL,
  `lists` VARCHAR(255) NOT NULL,
  `service` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
  PRIMARY KEY ( `id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 14/09/2015
-- version: 2.2.3
ALTER TABLE `plugin_apps_system_form` ADD COLUMN `additional_list` VARCHAR(255) AFTER `lists`;
ALTER TABLE `plugin_apps_crm` ADD COLUMN `additional_list` VARCHAR(255) AFTER `lists`;

-- These alters are always the latest and updated version of the database
UPDATE `plugin` SET `version`='2.2.4' WHERE `name`='apps';
SELECT version FROM `plugin` WHERE `name` = 'apps';

