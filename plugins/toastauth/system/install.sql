DROP TABLE IF EXISTS `plugin_toastauth_settings`;
CREATE TABLE `plugin_toastauth_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `plugin_toastauth_settings` (`id`, `name`, `settings`, `status`) VALUES
  (null,	'facebook',	'a:6:{s:9:\"client_id\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:12:\"redirect_uri\";s:0:\"\";s:5:\"scope\";s:5:\"email\";s:8:\"auth_url\";s:37:\"https://www.facebook.com/dialog/oauth\";s:9:\"token_url\";s:45:\"https://graph.facebook.com/oauth/access_token\";}',	1),
  (null,	'google',	'a:8:{s:9:\"client_id\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:12:\"redirect_uri\";s:0:\"\";s:5:\"scope\";s:5:\"email\";s:8:\"auth_url\";s:41:\"https://accounts.google.com/o/oauth2/auth\";s:9:\"token_url\";s:42:\"https://accounts.google.com/o/oauth2/token\";s:10:\"grant_type\";s:18:\"authorization_code\";s:13:\"response_type\";s:4:\"code\";}',	1),
  (null,	'linkedin',	'a:8:{s:13:\"response_type\";s:4:\"code\";s:9:\"client_id\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:5:\"scope\";s:29:\"r_basicprofile r_emailaddress\";s:12:\"redirect_uri\";s:60:\"http://auth.com/plugin/toastauth/run/login/provider/linkedin\";s:10:\"grant_type\";s:18:\"authorization_code\";s:9:\"token_url\";s:47:\"https://www.linkedin.com/uas/oauth2/accessToken\";s:8:\"auth_url\";s:49:\"https://www.linkedin.com/uas/oauth2/authorization\";}',	1);
