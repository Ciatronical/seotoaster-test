-- 11/11/2014
-- version: 2.2.0

-- These alters are always the latest and updated version of the database
UPDATE `plugin` SET `version`='2.2.0' WHERE `name`='toastauth';
SELECT version FROM `plugin` WHERE `name` = 'toastauth';

