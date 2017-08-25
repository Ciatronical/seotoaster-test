DROP TABLE IF EXISTS `plugin_pagerating_rating`;
DROP TABLE IF EXISTS `plugin_pagerating_review`;
DROP TABLE IF EXISTS `plugin_pagerating_configuration`;
DELETE FROM `email_triggers_actions` WHERE `trigger`='newreview';
DELETE FROM `email_triggers` WHERE `trigger_name`='newreview';