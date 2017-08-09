-- version: 2.2.0

-- 29/09/2014
-- version: 2.2.1
UPDATE `page_option` SET `option_usage`='once' WHERE `page_option`.`id` = 'option_newsindex';

-- 21/11/2014
-- version: 2.2.2
UPDATE `template_type` SET `title` = 'News listing' WHERE `id` = 'type_news_list';

-- 06/08/2015
-- version: 2.2.3
INSERT INTO `page_types` (`page_type_id`, `page_type_name`) VALUES ('3', 'news');
UPDATE `page` SET `page_type` = 3 WHERE `id` IN (SELECT `page_id` from `plugin_newslog_news`);

-- 21/08/2015
-- version: 2.2.4
UPDATE `plugin` SET `tags`='feed' WHERE `name` = 'newslog';

-- 11/25/2015
-- version: 2.2.5
ALTER TABLE `plugin_newslog_news`
ADD `event` int(10) unsigned NOT NULL DEFAULT '0' AFTER `type`,
ADD `event_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `event`,
ADD `event_location` varchar(255) COLLATE utf8_unicode_ci NOT NULL AFTER `event_date`;

-- These alters are always the latest and updated version of the database
UPDATE `plugin` SET `version`='2.2.6' WHERE `name`='newslog';
SELECT version FROM `plugin` WHERE `name` = 'newslog';

