CREATE TABLE IF NOT EXISTS `plugin_paypal_settings` (
`id` int(10) NOT NULL,
`email` varchar(255) NOT NULL,
`apiSignature` varchar(255) NOT NULL,
`apiUser` varchar(255) NOT NULL,
`apiPassword` varchar(255) NOT NULL,
`useSandbox` tinyint(1) NOT NULL,
 Primary key (`id`)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO `plugin_paypal_settings`
VALUES ('1', '', '', '', '', '0')
ON DUPLICATE KEY UPDATE id=1;

CREATE TABLE IF NOT EXISTS `plugin_paypal_transactions` (
`id` INT NOT NULL AUTO_INCREMENT ,
`txnId` VARCHAR( 200 ) NULL ,
`payerId` VARCHAR( 200 ) NULL ,
`payerMail` VARCHAR( 150 ) NULL ,
`amount` VARCHAR( 25 ) NULL ,
`shippingAmount` VARCHAR( 25 ) NULL ,
`tax` VARCHAR( 25 ) NULL,
`currency` TEXT NULL ,
`paymentStatus` TEXT NULL ,
`status` TEXT NULL ,
`paymentType` TEXT NULL ,
`paymentId` INT NULL ,
`paymentDate` TIMESTAMP NULL DEFAULT NULL ,
`pFirstName` VARCHAR( 50 ) NULL ,
`pLastName` VARCHAR( 50 ) NULL ,
`pCountry` VARCHAR( 60 ) NULL ,
`pCountryCode` TEXT NULL ,
`pAddressState` VARCHAR( 60 ) NULL ,
`pAddressCity` VARCHAR( 60 ) NULL ,
`pAddressZip` TEXT NULL ,
`pAddressName` VARCHAR( 150 ) NULL ,
`cartId` int(10) NULL,
`pendingReason` VARCHAR( 50 ) NULL ,
`subscribeStatus` VARCHAR(60) DEFAULT NULL,
`subscribePeriod` INT(10) DEFAULT NULL,
`subscribePeriodType` VARCHAR(50) DEFAULT NULL,
`subscribeQuantity` INT(10) DEFAULT NULL,
`subscribeAmount` DECIMAL(10,4) DEFAULT NULL,
`subscribeDate` VARCHAR(60) DEFAULT NULL,
`subscriptionId` VARCHAR(255) DEFAULT NULL,
`subscriptionDatePayed` VARCHAR(60) DEFAULT NULL,
`subscriptionAmountPayed` VARCHAR(60) DEFAULT NULL,
`emailSent` ENUM('0','1') DEFAULT '0',
`customerEmailSent` ENUM('0','1') DEFAULT '0',
`refundTransactionId` varchar(128) COLLATE 'utf8_unicode_ci' NULL,
`refundReason` varchar(255) COLLATE 'utf8_unicode_ci' NULL,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

UPDATE `plugin` SET `version`='2.3.0' WHERE `name`='paypal';