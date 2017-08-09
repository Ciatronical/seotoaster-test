DROP TABLE IF EXISTS `plugin_apps_settings`;

DROP TABLE IF EXISTS `plugin_apps_system_form`;

DROP TABLE IF EXISTS `plugin_apps_crm`;

DELETE FROM `observers_queue` WHERE `observer` = 'Tools_MailServiceFormWatchdog';

DELETE FROM `observers_queue` WHERE `observer` = 'Tools_SmsServiceFormWatchdog';

DELETE FROM `observers_queue` WHERE `observer` = 'Tools_CrmServiceFormWatchdog';

DELETE FROM `email_triggers` WHERE `trigger_name` = 'store_neworder' AND `observer` = 'store_neworder';

DELETE FROM `email_triggers` WHERE `trigger_name` = 'store_trackingnumber' AND `observer` = 'Tools_AppsSmsWatchdog';

DELETE FROM `email_triggers_actions` WHERE `service` = 'sms' AND `trigger` = 'store_neworder';

DELETE FROM `email_triggers_actions` WHERE `service` = 'sms' AND `trigger` = 'store_trackingnumber';
