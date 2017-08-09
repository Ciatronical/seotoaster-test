DROP TABLE `plugin_newslog_configuration`;
DROP TABLE `plugin_newslog_pingservice`;
DELETE FROM `container` WHERE `page_id` IN (select `page_id` from `plugin_newslog_news`);
DELETE FROM `page` WHERE `id` IN (select `page_id` from `plugin_newslog_news`);
DROP TABLE `plugin_newslog_news_has_tag`;
DROP TABLE `plugin_newslog_tag`;
DROP TABLE `plugin_newslog_news`;
DELETE FROM `page_has_option` WHERE `option_id` = 'option_newsindex';
DELETE FROM `page_has_option` WHERE `option_id` = 'option_newspage';
DELETE FROM `page_option` WHERE `id` = 'option_newsindex';
DELETE FROM `page_option` WHERE `id` = 'option_newspage';
DELETE FROM `template_type` WHERE `id` = 'type_news';
DELETE FROM `template_type` WHERE `id` = 'type_news_list';
DELETE FROM `observers_queue` WHERE `observer` = 'Newslog_Tools_Watchdog_Page';

