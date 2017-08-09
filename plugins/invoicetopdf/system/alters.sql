-- 29/01/2016
-- version: 2.2.0
INSERT INTO `email_triggers` (`enabled`, `trigger_name`, `observer`)
VALUES ('1', 'invoice_send', 'Tools_InvoicetopdfMailWatchdog');


-- These alters are always the latest and updated version of the database
UPDATE `plugin` SET `version`='2.5.0' WHERE `name`='invoicetopdf';
SELECT version FROM `plugin` WHERE `name` = 'invoicetopdf';

