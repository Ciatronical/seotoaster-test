-- 20/05/2015
-- version: 2.2.0
INSERT IGNORE INTO `config` (`name`, `value`) VALUES ('youtubeApiKey', 'AIzaSyB19HwS1I35vzAh-AnNnNyGelnozEbJp-w');

-- These alters are always the latest and updated version of the database
UPDATE `plugin` SET `version`='2.2.1' WHERE `name`='webbuilder';
SELECT version FROM `plugin` WHERE `name` = 'webbuilder';

