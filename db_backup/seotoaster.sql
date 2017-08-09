-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: seotoaster
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.17.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES ('currentTheme','default'),('imgSmall','300'),('imgMedium','450'),('imgLarge','500'),('useSmtp','0'),('smtpHost',''),('smtpLogin',''),('smtpPassword',''),('language','us'),('teaserSize','450'),('smtpPort',''),('memPagesInMenu','1'),('mediaServers','0'),('smtpSsl','0'),('codeEnabled','0'),('inlineEditor','0'),('recaptchaPublicKey','6LcaJdASAAAAADyAWIdBYytJMmYPEykb3Otz4pp6'),('recaptchaPrivateKey','6LcaJdASAAAAAH-e1dWpk96PACf3BQG1OGGvh5hK'),('enableMobileTemplates','1'),('adminEmail','thomas@inter-data.de'),('wicCorporateLogo','plugins/widcard/system/userdata/CorporateLogo.png'),('wicOrganizationDescription','We design and sell urban bikes, along with accessories to make riding more enjoyable, practical, and chic. They come in single-speed and multi speeds in all sizes.'),('version','2.5.0'),('wicOrganizationName','Mission Bicycle Company'),('wicOrganizationCountry','US'),('wicAddress1','2140 S Dupont Highway, Camden'),('wicAddress2','DE 19934'),('wicCity','San Francisco'),('wicCountryState','CA'),('wicZip',''),('wicPhone','212-254-2135'),('wicEmail','website@seotoaster.com'),('landingPage','1'),('canonicalScheme','http'),('recapthaPublicKey','6LcaJdASAAAAADyAWIdBYytJMmYPEykb3Otz4pp6'),('recapthaPrivateKey','6LcaJdASAAAAAH-e1dWpk96PACf3BQG1OGGvh5hK'),('enableMinify','0'),('enableDeveloperMode','0'),('controlPanelStatus','0'),('youtubeApiKey','AIzaSyB19HwS1I35vzAh-AnNnNyGelnozEbJp-w');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `container`
--

DROP TABLE IF EXISTS `container`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `container` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `container_type` int(10) unsigned NOT NULL,
  `page_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '1',
  `publishing_date` date DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indPublished` (`published`),
  KEY `indContainerType` (`container_type`),
  KEY `indPageId` (`page_id`),
  CONSTRAINT `container_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container`
--

LOCK TABLES `container` WRITE;
/*!40000 ALTER TABLE `container` DISABLE KEYS */;
INSERT INTO `container` VALUES (3,3,1,'top','1','0000-00-00','Beautifully hand-crafted San Francisco bycicles'),(4,1,1,'top','1','0000-00-00','<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus alias consequuntur corporis distinctio eligendi esse et eum ipsum laborum magnam magni, molestiae nisi nobis sed sequi ullam, voluptas voluptatum? Unde.</p>'),(5,1,1,'wb_60b5155efc1c6f5774ee20d1','1','0000-00-00','homepage:3:260'),(6,4,NULL,'footer1','1','0000-00-00','Our contacts'),(7,4,NULL,'footer3','1','0000-00-00','Last News'),(14,3,1,'slide_1','1','0000-00-00','Welcome to a Critical Mass San Francisco'),(15,1,1,'slide_1','1','0000-00-00','<p><span>Critical Mass is a mass bicycle ride that takes place on the last Friday of each month in cities around the world. Everyone is invited! No one is in charge! Bring your <a href=\"{$website:url}canyon-canyon-speedmax-al-19.html\" title=\"Canyon Speedmax AL\" >bike</a>!</span></p>'),(17,4,NULL,'footer2','1','0000-00-00','Information'),(18,1,4,'content11','1','0000-00-00','<p>{$productlist:product list grid:tagnames-Mountain bikes}</p>'),(20,1,1,'content1','1','0000-00-00','<p>{$productlist:product list grid:tagnames-Mountain bikes:3}</p>'),(25,1,1,'slogan','1','0000-00-00','<p> </p>\n<p> </p>'),(26,1,1,'wb_70f7dc6c9fa6857fcc2cfca9','1','0000-00-00','homepage:3:250:img'),(31,3,1,'tab1','1','0000-00-00','Biking in San Francisco'),(32,3,1,'tab2','1','0000-00-00','Best Sellers'),(33,3,1,'tab3','1','0000-00-00','New Products'),(34,1,1,'tab2','1','0000-00-00','<div class=\"product-list\">{$productlist:product list grid:brands-Santa Cruz}</div>'),(35,1,1,'wb_11c2dea993521b7e7f718915','1','0000-00-00','{\"link\":\"http:\\/\\/vimeo.com\\/57462308\",\"width\":\"627\",\"height\":\"408\"}'),(36,2,NULL,'right2','1','0000-00-00','<p>{$form:contact:recaptcha:custom}</p>'),(37,4,NULL,'right2','1','0000-00-00','Ask the pros'),(41,1,1,'right1','1','0000-00-00','<div> </div>'),(42,3,1,'right1','1','0000-00-00','Ask the pros'),(43,3,20,'right1','1','0000-00-00','Related Products'),(44,3,20,'right2','1','0000-00-00','Same tags'),(50,1,20,'wb_823916db43491899712b94b9_20','1','0000-00-00','wb_823916db43491899712b94b9_20.jpg'),(51,1,20,'wb_3710ebdd4a33ed56868676eb_20','1','0000-00-00','wb_3710ebdd4a33ed56868676eb_20.jpg'),(52,1,20,'wb_f269141e79c28a9549f8f2cf_20','1','0000-00-00','wb_f269141e79c28a9549f8f2cf_20.jpg'),(53,1,20,'wb_5f28b6ff1c9bf1109c12668f_20','1','0000-00-00','wb_5f28b6ff1c9bf1109c12668f_20.jpg'),(54,1,20,'wb_5dc2571555793ceb92fa18ce_20','1','0000-00-00','wb_5dc2571555793ceb92fa18ce_20.jpg'),(56,3,1,'right2','1','0000-00-00','San Francisco bicycle as a passion'),(57,2,NULL,'footer-info','1','0000-00-00','<p>{$plugin:widcard:BizOrgName} | {$plugin:widcard:BizAddress1}, {$plugin:widcard:BizCity}, {$plugin:widcard:BizState} {$plugin:widcard:BizZip}</p>'),(58,4,NULL,'tab1','1','0000-00-00','Related Products'),(59,4,NULL,'tab2','1','0000-00-00','Same tags'),(60,1,1,'tab3','1','0000-00-00','<div class=\"product-list\">{$productlist:product list default:tagnames-Triatlon Bikes}</div>'),(61,1,14,'right1','1','0000-00-00','\n'),(62,4,NULL,'toastercart','1','0000-00-00','Your cart'),(63,4,NULL,'button','1','0000-00-00','Back to home'),(64,4,NULL,'tab3','1','0000-00-00','Contact Us'),(65,4,NULL,'product-tab1','1','0000-00-00','Details'),(66,4,NULL,'product-tab2','1','0000-00-00','Options'),(67,4,NULL,'product-quote','1','0000-00-00','Send me a quote'),(68,1,46,'user_orders','1','0000-00-00','{$user:grid}'),(69,1,46,'user_ Edit Account','1','0000-00-00','{$user:account}'),(70,1,14,'nothingfound','1','0000-00-00','<p>{$gal:People-on-bikes:200:1:0}</p>\n<p>&nbsp;</p>'),(71,1,14,'wb_b0dcbbd3907ffc431c376a2a','1','0000-00-00','Mountain-bikes:145:1:0'),(73,1,49,'wb_ae9829dd7059807904e443fe_49','1','0000-00-00','wb_ae9829dd7059807904e443fe_49.png'),(74,1,49,'wb_36a6e38a84f2a2abd8eb2a71_49','1','0000-00-00','wb_36a6e38a84f2a2abd8eb2a71_49.png'),(75,1,27,'nothingfound','1','0000-00-00','<div>{$productlist:product list grid:tagnames-Downhill bikes}</div>'),(76,1,18,'nothingfound','1','0000-00-00','<div>{$featured:area:product:5:200:img}</div>'),(77,1,5,'nothingfound','1','0000-00-00','<div>{$productlist:product list default:tagnames-Triatlon Bikes}</div>'),(78,1,12,'nothingfound','1','0000-00-00','<ul class=\"ulist\">\n<li><strong>TOTAL QUALITY</strong> in the way the company is managed, striving for excellence in all areas.</li>\n<li>The importance of developing our activities with respect to the <strong>ENVIRONMENT</strong> and within the context of continuous improvement and pollution prevention.</li>\n<li>That the management of <strong>SAFETY AND HEALTH</strong> has strategic priority for the benefit of the members of our team and for the continued success of the business.</li>\n</ul>\n<p>Other goals for our Management System of Occupational Health and Safety will be:</p>\n<ul class=\"ulist\">\n<li>To ensure compliance with current legislation on the prevention of occupational risks and with the internal regulations of our Joint Prevention System.</li>\n<li>To ensure that all employees and their representatives are consulted, trained and encouraged to actively participate in its development.</li>\n<li>To be transparent, with clear objectives that are quantified and communicated directly.</li>\n<li>To pursue continuous improvement in labor, health, and safety performance.</li>\n</ul>'),(80,1,37,'nothingfound','1','0000-00-00','<div>{$store:clientlogin}</div>'),(81,1,38,'nothingfound','1','0000-00-00','<div>{$member:signup}</div>'),(82,4,NULL,'qu-title','1','0000-00-00','Quote updated!'),(83,4,NULL,'qu-hello','1','0000-00-00','Dear our client!'),(84,4,NULL,'qu-header-3','1','0000-00-00','Have a great day!'),(85,4,NULL,'spr-title','1','0000-00-00','Congratulations!'),(86,4,NULL,'spr-subtitle','1','0000-00-00','Your order confirmed'),(87,4,NULL,'spr-hello','1','0000-00-00','Dear, {customer:fullname}'),(88,4,NULL,'spr-order','1','0000-00-00','Your order ID: {order:id}'),(89,2,NULL,'spr-content','1','0000-00-00','<p><span>Thanks for being a customer at the San Francisco Bikes website. A detailed summary of your invoice is bellow.</span></p>'),(90,4,NULL,'spr-overfooter-1','1','0000-00-00','If you have questions, we\'re happy to help. Please <a style=\"color: #0051a3;\" href=\"{$website:url}contact-us.html\">contact us</a>'),(91,4,NULL,'spr-overfooter-2','1','0000-00-00','Thank you for your purchase.  Please come again'),(92,4,NULL,'snsn-title','1','0000-00-00','Hello!'),(93,4,NULL,'snsn-subtitle','1','0000-00-00','New order received'),(94,4,NULL,'snsn-hello','1','0000-00-00','Hi, admin'),(95,2,NULL,'snsn-content','1','0000-00-00','<p>You have a new purchase in the store. It made a certain {customer:fullname}. <br /> Below you can see the details of the purchase:</p>'),(96,4,NULL,'snsn-overfooter-1','1','0000-00-00','You can see the details of all orders and purchases at <a style=\"color: #0000ff;\" href=\"{$website:url}dashboard/\">dashboard</a>'),(97,4,NULL,'qc-title','1','0000-00-00','New quote!'),(98,4,NULL,'qc-hello','1','0000-00-00','Dear our client!'),(99,2,NULL,'qc-content','1','0000-00-00','<p>We have just generated a new quote <strong> {quote:id} </strong> for you. <br />Go ahead and <a href=\"{$website:url}{quote:id}.html\">check out your quote</a></p>'),(100,4,NULL,'qc-header-3','1','0000-00-00','Have a great day!'),(101,4,NULL,'nui-title','1','0000-00-00','You are connected!'),(102,4,NULL,'nui-subtitle','1','0000-00-00','Welcome to San Francisco Bikes'),(103,4,NULL,'nui-hello','1','0000-00-00','Hello.'),(105,2,NULL,'nui-acessinfo','1','0000-00-00','<h2 class=\"h4\"> </h2>\n<p class=\"h4\"><strong>Login</strong></p>\n<p>{user:email}</p>\n<p class=\"h4\"><strong>Password</strong></p>\n<p>Use the password you\'ve set during the registration or go to the {user:passwordLink} to change it.</p>\n'),(106,2,NULL,'nui-link','1','0000-00-00','<p><a href=\"{$website:url}sign-in.html\" style=\"background: none repeat scroll 0% 0% #5279eb; color: #ffffff; font-size: 20px; text-decoration: none; padding: 10px 20px;\">Click here to log in</a></p>'),(107,4,NULL,'NC-emailmessage','1','0000-00-00','Dear {customer:fullname}'),(108,4,NULL,'NC-acessinfo','1','0000-00-00','Access info:'),(109,2,NULL,'acessinfo','1','0000-00-00','<p class=\"h4\"><strong>Login</strong></p>\n<p>{customer:email}</p>\n<p class=\"h4\"><strong>Password</strong></p>\n<p>Use the password you\'ve set during the registration or go to the {customer:passwordLink} to change it.</p>\n'),(110,4,NULL,'NC-title','1','0000-00-00','You are connected!'),(111,4,NULL,'NC-subtitle','1','0000-00-00','Welcome to {$plugin:widcard:BizOrgName:notag}'),(114,4,NULL,'fr-title','1','0000-00-00','Message received'),(115,4,NULL,'fr-subtitle','1','0000-00-00','Thank you for contacting'),(116,4,NULL,'frs-hello','1','0000-00-00','Thank you for taking the time to contact us.'),(117,2,NULL,'frs-content','1','0000-00-00','<p><span style=\"color: #444444; font-family: Arial, Verdana, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 21px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; display: inline !important; float: none;\">You should receive a reply from a representative of our web site by the end of the next business day. Occasionally we receive a very large number of messages, and your response may take a little longer. If this occurs, we appreciate your patience, and we assure you that you will receive a response.</span></p>'),(118,4,NULL,'shtc-title','1','0000-00-00','Congratulations!'),(119,4,NULL,'shtc-subtitle','1','0000-00-00','Your order has shipped'),(120,4,NULL,'shtc-hello','1','0000-00-00','Dear, {customer:fullname}'),(121,2,NULL,'shtc-content','1','0000-00-00','<p><span style=\"color: #444444; font-family: Arial, Verdana, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; font-size: 17px; font-weight: bold;\">Your order ID: {order:id}<span class=\"Apple-converted-space\"> </span></span><span style=\"color: #444444; font-family: Arial, Verdana, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 19.600000381469727px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; display: inline !important; float: none;\">has shipped via {order:shippingservice}.</span></p>'),(122,4,NULL,'shtc-header-2','1','0000-00-00','You can track it now here:'),(123,1,11,'nothingfound','1','0000-00-00','{$sitemap}'),(129,1,11,'content31','1','0000-00-00','{$plugin:sitemaptree:width:height:teaser_text:teaser_image}'),(132,1,7,'content31','1','0000-00-00','<p>{$member:login}</p>\n<p>{$store:clientlogin}</p>\n<p>{$member:signup}</p>'),(134,1,7,'content21','1','0000-00-00','<p>{$plugin:youtubeupload:tree,forest:Comedy}</p>'),(137,3,1,'slide_2','1','0000-00-00','Bike route in the Bay area'),(138,1,1,'slide_2','1','0000-00-00','<p>If you’re willing to give your legs a little workout, the San Francisco <a href=\"{$website:url}canyon-canyon-speedmax-al-19.html\" title=\"Canyon Speedmax AL\" >bike</a> paths will reward you with breathtaking views you can’t see anywhere else. With new <a href=\"{$website:url}canyon-canyon-speedmax-al-19.html\" title=\"Canyon Speedmax AL\" >bike</a> paths popping up to an entire music festival run on <a href=\"{$website:url}canyon-canyon-speedmax-al-19.html\" title=\"Canyon Speedmax AL\" >bike</a> power.</p>'),(139,3,1,'slide_3','1','0000-00-00','“Ride and Style” Fixed Gear Cycling event'),(140,1,1,'slide_3','1','0000-00-00','<p><span>Red Bull Ride + Style returns to San Francisco on Saturday, May 4th. Stay tuned for an updated list of confirmed riders for both freestyle and race competitions - as well as the four artists who will bring the course to life.</span></p>'),(141,3,1,'slide_4','1','0000-00-00','Pelizzoli Leggenda FOR3 exciting blue'),(142,1,1,'slide_4','1','0000-00-00','<p><span>Well I bit the bullet and after thinking for a long time decided on the Pelizzoli frame. Went for this mostly for the custom fit and the finish on the frames I have seen up to now is amazing.</span></p>'),(143,1,1,'wb_f7052fec8e9d223071c544fc','1','0000-00-00','{\"folder\":\"slider\",\"image\":\"sf-slide-02.jpg\",\"description\":\"\",\"linkedTo\":\"nothing\",\"externalUrl\":\"\"}'),(144,1,1,'wb_4d9ae22cd72741b265f8f8e3','1','0000-00-00','{\"folder\":\"slider\",\"image\":\"sf-slide-05.jpg\",\"description\":\"\",\"linkedTo\":\"nothing\",\"externalUrl\":\"\"}'),(145,1,1,'wb_7dba09208a602364b79291e5','1','0000-00-00','{\"folder\":\"slider\",\"image\":\"sf-slide-06.jpg\",\"description\":\"\",\"linkedTo\":\"nothing\",\"externalUrl\":\"\"}'),(146,1,1,'wb_359553bce05cfe7128aa5fe7','1','0000-00-00','{\"folder\":\"slider\",\"image\":\"sf-slide-07.jpg\",\"description\":\"\",\"linkedTo\":\"nothing\",\"externalUrl\":\"\"}'),(150,1,36,'nothingfound','1','0000-00-00','<p>{$featured:area:Email:50:100:img:0}</p>'),(152,1,77,'content11','1','0000-00-00','<p><a class=\"_lbox\" href=\"{$website:url}media/news/original/13953336779_af7e3b9ba3_h.jpg\" title=\"13953336779_af7e3b9ba3_h\"><img src=\"{$website:url}media/news/large/13953336779_af7e3b9ba3_h.jpg\" alt=\"13953336779_af7e3b9ba3_h\" width=\"601\" height=\"398\" /></a></p>\n<p>Yesterday the Bay Area celebrated the 20th Anniversary of Bike to Work Day with an impressive amount of bikers hitting the road. One major San Francisco thoroughfare tallied that nearly 76% of the trips made on it yesterday were done by bike. Well done, San Francisco!</p>\n<p>The month of May is National Bike Month and we’re happy to see so many people participating in Bike to Work Day. Of course, we think everyday should be Bike to Work Day for anyone who works less than 5 miles from home. In the Bay Area alone, more than one million Bay Area residents live within five miles of their workplace.</p>\n<p>If you’re considering biking to daily as part of your commute, but not sure where to start, check out the San Francisco Bike Coalition for maps and tips and more. If you’re a bike-to-work regular or just getting into biking, drop us a line and let us know how your bike commute went yesterday</p>\n<p>Imagine how less congested our streets would be and how much healthier and happier people would be if more people made the choice to bicycle, walk, or take public transit to work.</p>\n<p>We’re lucky to live in San Francisco where bicycling is a mainstream activity and the majority of our local elected officials recognize the value of bicycling. This year, 9 out of 11 local elected Board of Supervisors, our Mayor, and our District Attorney all participated in Bike to Work Day with thousands of other residents. Bike to Work Day helps remind these elected officials to fully fund and prioritize initiatives like Connecting the City which creates safe and accessible bikeways for anyone from 8 to 80 years old.</p>\n<p><a class=\"_lbox\" href=\"{$website:url}media/news/original/13953451818_45d9ff2b81_h.jpg\" title=\"13953451818_45d9ff2b81_h\"><img src=\"{$website:url}media/news/large/13953451818_45d9ff2b81_h.jpg\" alt=\"13953451818_45d9ff2b81_h\" width=\"500\" height=\"331\" /></a></p>\n<p>We know cities can get more people to bicycle if they create separated bikeways like this new one on Polk Street near City Hall. It takes political will and funding to make these changes happen on our public streets.</p>\n<p>We encourage you to find out more about your local Bike to Work Day activities – and support your local and statewide bicycle advocacy organizations working to make bicycling better for all of us.</p>\n'),(153,1,78,'content11','1','0000-00-00','<p><img title=\"Canyon Speedmax AL\" src=\"{$website:url}media/news/original/drewnew.png\" alt=\"drewnew\" width=\"550\" height=\"367\" /></p>\n<p>Bicycling is booming in San Francisco. From the increase in ridership (96% since 2006) to the influx of new bike shops (three new ones on Market Street alone) to the plethora of bike startups launching from our City by the Bay, one thing is clear: San Francisco loves biking.</p>\n<p>We sat down to talk with one of these new bike startups,&nbsp;Revolights, about the tie-in between new bike companies and bicycle advocacy. Here&rsquo;s what San Francisco native and Revolights&rsquo; Chief Marketing Officer, Drew Ocon, had to say about the combo. Revolights is one of the panelists in&nbsp;tonight&rsquo;s Bike Month event, Crowdfunding the Bike Industry.&nbsp;</p>\n<p><strong>An incredible number of bike companies have launched in San Francisco and the Bay Area. Why do you think our area is ripe for such new bike innovation?<br /></strong>It is amazing! Last time I checked, about 25% of all bicycle related Kickstarter projects are based here in California and mainly in the Bay Area, which goes to show that both entrepreneurship and cycling run deep in our blood. Back in the early 1900&prime;s, the Golden Gate Park Polo Fields were home to the first west coast velodrome, and in the &rsquo;70&prime;s, you&rsquo;d see pioneers like Joe Breeze, Gary Fisher and Tom Ritchey riding bikes down Mount Tam.</p>\n<p><strong>Why does your company support the work of the SF Bicycle Coalition?<br /></strong>We have always admired and respected what the SFBC has done to lead the way in bicycle safety and infrastructure. We share an affinity for bike safety and legitimizing bicycle commuting as a form of daily transportation.</p>\n<p><strong>Thanks for the set of lights you gave to the winner of our Bike Commuter of the Year competition! The 2014 winner is&nbsp;Bao-Tran Ausman, a mom who pedals with her kids all around SF. Why do you think it&rsquo;s exciting to see more parents and kids biking?<br /></strong>There are so many amazing aspects of this situation (1) its great to see that the modern day minivan could be a cargo bike. Love to see families are able to remove cars from their lives and rely on bicycle transportation. (2) Teaching children the rules of the road in an urban setting makes for a more conscientious generation. (3) It just shows how far the SFBC has pushed the standards for infrastructure because if city cycling wasn&rsquo;t perceived safe, parents wouldn&rsquo;t feel comfortable toting their kids around town. All are great signs for our community.</p>\n<p><strong>When did you start riding in SF, and what&rsquo;s the biggest change you&rsquo;ve seen in SF biking since you started riding here?<br /></strong>Well I was born here in SF (yep, real San Franciscans do exist) and raised in the Outer Mission and then out to Walnut Creek so I have watched this biking community grow for the past 20 years. My earliest memories are learning to ride in Golden Gate Park when JFK Dr was closed to cars on Sundays &ndash; now a weekly staple in SF bike culture. The biggest change that has affected my daily life are the upgrades to&nbsp;Fell and Oak. Connecting The Wiggle to the Panhandle was a major win for the cycling community. Moving forward, I am excited to see SFBC&rsquo;s &ldquo;Vision Zero Goal&nbsp;(no cyclist or pedestrian deaths in five years) become a reality.</p>'),(155,1,7,'content11','1','0000-00-00','<p><img src=\"{$website:url}media/slider/original/sf-slide-07.jpg\" alt=\"sf&nbsp;slide&nbsp;07\" width=\"941\" height=\"300\" /></p>\n<p><span data-mce-mark=\"1\">{$gal:Champions:200:1:0}</span></p>\n<p><span data-mce-mark=\"1\"><span data-mce-mark=\"1\">We design and sell urban bikes, along with accessories to make riding more enjoyable, practical, and chic. Our European-inspired bikes ride like butter. They come in single-speed and multi-speeds in all sizes.</span></span></p>\n<p><span data-mce-mark=\"1\"><span data-mce-mark=\"1\"> You can dress in casual or business attire, and wear pumps, tennis shoes, or flip flops - just about anything - while riding our bikes. And we have baskets, bags and other gear to go along with them. These bikes will make you feel like a kid again, and this is every bit as important as anything else. </span></span></p>\n<p><span data-mce-mark=\"1\"><span data-mce-mark=\"1\">You can read about us in numerous national press publications or hear from our customers directly.</span></span></p>'),(156,1,13,'team','1','0000-00-00','<p>{$gal:Champions:250:94x94:0}</p>'),(158,2,NULL,'tab3','1','0000-00-00','<p>{$form:contact:recaptcha:custom}</p>'),(169,1,3,'nothingfound','1','0000-00-00','<p>{$productlist:product list default:tagnames-Road bikes}</p>'),(175,1,48,'wb_16b89c41d194d2a8b066279b_48','1','0000-00-00','wb_16b89c41d194d2a8b066279b_48.jpg'),(176,1,48,'wb_efe28df2fd88ae65889d2750_48','1','0000-00-00','wb_efe28df2fd88ae65889d2750_48.jpg'),(177,1,48,'wb_634d134ad326ceb055a2befa_48','1','0000-00-00','wb_634d134ad326ceb055a2befa_48.jpg'),(178,1,48,'wb_95963d2a5776675bd607afe5_48','1','0000-00-00','wb_95963d2a5776675bd607afe5_48.jpg'),(180,1,48,'wb_e5f0169a3995d79c287d9d0a_48','1','0000-00-00','wb_e5f0169a3995d79c287d9d0a_48.jpg'),(187,1,1,'right2','1','0000-00-00','<p>We design and sell urban bikes, along with accessories to make riding more enjoyable, practical, and chic. Our European-inspired bikes ride like butter. They come in single-speed and multi-speeds in all sizes. You can dress in casual or business attire, and wear pumps, tennis shoes, or flip flops - just about anything - while riding our bikes. And we have baskets, bags and other gear to go along with them. These bikes will make you feel like a kid again, and this is every bit as important as anything else. You can read about us in numerous national press publications or hear from our customers directly.</p>'),(198,1,13,'contact1','1','0000-00-00','<p>{$form:contact:recaptcha:custom:Send}</p>'),(208,6,7,'XYZ','1','0000-00-00','val'),(209,6,18,'XYZ','1','0000-00-00','val'),(212,6,7,'sample_widget_name','1','0000-00-00','top'),(221,1,7,'nothingfound','1','0000-00-00','{$newslist:News list:tags:Bicycle,Electric,Work Day,Bike Month:asc:2:pgr}'),(222,2,NULL,'wb_a2ca0e283d7168c6dd0ae6f8','1','0000-00-00','homepage:5:0:img'),(223,1,94,'nothingfound','1','0000-00-00','{$store:postpurchasereport}'),(224,1,1,'content21','1','0000-00-00','<ul class=\"news-list list-unstyled\">{$newslist:News list:tags:Bicycle:asc:2:pgr}</ul>'),(225,1,39,'nothingfound','1','0000-00-00','Good try ! However the search index is not functional with demo pages and product catalogue. Please create first your own pages and product catalogue to make the search engine work on your website.');
/*!40000 ALTER TABLE `container` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deeplink`
--

DROP TABLE IF EXISTS `deeplink`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deeplink` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('int','ext') COLLATE utf8_unicode_ci DEFAULT 'int',
  `ban` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `nofollow` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indName` (`name`),
  KEY `indType` (`type`),
  KEY `indUrl` (`url`),
  KEY `indDplPageId` (`page_id`),
  CONSTRAINT `deeplink_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deeplink`
--

LOCK TABLES `deeplink` WRITE;
/*!40000 ALTER TABLE `deeplink` DISABLE KEYS */;
/*!40000 ALTER TABLE `deeplink` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_triggers`
--

DROP TABLE IF EXISTS `email_triggers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_triggers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `enabled` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `trigger_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `observer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trigger_name_2` (`trigger_name`,`observer`),
  KEY `trigger_name` (`trigger_name`),
  KEY `observer` (`observer`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_triggers`
--

LOCK TABLES `email_triggers` WRITE;
/*!40000 ALTER TABLE `email_triggers` DISABLE KEYS */;
INSERT INTO `email_triggers` VALUES (1,'1','t_feedbackform','Tools_Mail_SystemMailWatchdog'),(2,'1','t_passwordreset','Tools_Mail_SystemMailWatchdog'),(3,'1','t_passwordchange','Tools_Mail_SystemMailWatchdog'),(4,'1','t_membersignup','Tools_Mail_SystemMailWatchdog'),(5,'1','t_systemnotification','Tools_Mail_SystemMailWatchdog'),(6,'1','store_newcustomer','Tools_StoreMailWatchdog'),(7,'1','store_neworder','Tools_StoreMailWatchdog'),(8,'1','store_trackingnumber','Tools_StoreMailWatchdog'),(12,'1','store_newuseraccount','Tools_StoreMailWatchdog'),(13,'1','quote_created','Quote_Tools_QuoteMailWatchdog'),(14,'1','quote_updated','Quote_Tools_QuoteMailWatchdog'),(16,'1','store_neworder','Tools_AppsSmsWatchdog'),(17,'1','store_trackingnumber','Tools_AppsSmsWatchdog'),(26,'1','cartstatusemail_abandoned','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(27,'1','cartstatusemail_newquote','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(28,'1','cartstatusemail_quotesent','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(29,'1','cartstatusemail_paymentreceived','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(30,'1','cartstatusemail_itemsshipped','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(31,'1','cartstatusemail_itemsdelivered','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(32,'1','cartstatusemail_refundedpurchase','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(33,'1','cartstatusemail_actionrequire','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(34,'1','cartstatusemail_technicalprocessing','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(35,'1','cartstatusemail_lostopportunity','Cartstatusemail_Tools_CartstatusemailMailWatchdog'),(36,'1','store_refund','Tools_StoreMailWatchdog'),(37,'1','invoice_send','Tools_InvoicetopdfMailWatchdog');
/*!40000 ALTER TABLE `email_triggers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_triggers_actions`
--

DROP TABLE IF EXISTS `email_triggers_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_triggers_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service` enum('email','sms') COLLATE utf8_unicode_ci DEFAULT NULL,
  `trigger` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recipient` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `from` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'can be used in the From field of e-mail',
  `subject` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'can be used in the "Subject" field of e-mail',
  PRIMARY KEY (`id`),
  KEY `trigger` (`trigger`),
  KEY `template` (`template`),
  KEY `recipient` (`recipient`),
  CONSTRAINT `email_triggers_actions_ibfk_1` FOREIGN KEY (`trigger`) REFERENCES `email_triggers` (`trigger_name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `email_triggers_actions_ibfk_2` FOREIGN KEY (`recipient`) REFERENCES `email_triggers_recipient` (`recipient`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `email_triggers_actions_ibfk_3` FOREIGN KEY (`template`) REFERENCES `template` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_triggers_actions`
--

LOCK TABLES `email_triggers_actions` WRITE;
/*!40000 ALTER TABLE `email_triggers_actions` DISABLE KEYS */;
INSERT INTO `email_triggers_actions` VALUES (2,'email','t_membersignup','Toaster - new user info','member','Thank you for your registration!','no-reply@example.com','Thank you!'),(3,'email','t_membersignup','email','customer','Thank you for your registration!','no-reply@example.com','Thank you!'),(4,'email','t_passwordreset','Toaster Reset link','member','Password reset request','no-reply@example.com','Password reminder'),(7,'sms','store_neworder','email','sales person','<p>Hi there, </p>\r\n \r\n<p>We received an order on {order:createdat}  from {customer:fullname} placed from IP {order:ipaddress}. This order came through {order:referer}</p>\r\n \r\n<p>Their order total is {order:currency}{order:total}.<br />\r\nWe are shipping via {order:shippingservice}  to {order:shippingaddress}.</p>\r\n \r\n<p>This client originally registered with our site {customer:regdate} on {customer:ipaddress}</p>\r\n \r\n<p>{$store:postpurchasereport:mailreport}</p>\r\n \r\n<p>Please log into {$website:url} to process this order. </p>\r\n','store@example.com','New order placed subject'),(8,'sms','store_neworder','email','customer','Hello {customer:fullname},\r\nthis message is from your favorite store {company:name}.\r\nWe received your order on {order:createdat} date for {order:currency}{order:total}.\r\nYour order status is {order:status} and will ship to {order:shippingaddress}.\r\nThanks for your business.','',''),(9,'sms','store_trackingnumber','email','customer','Hello {customer:fullname},\r\nthis message is from your favorite store {company:name}.\r\nYour order {order:shippingtrackingid} for {order:currency}{order:total} placed on {order:createdat} is now {order:status}.\r\nThe shipping address for this order is {order:shippingaddress}\r\nThanks for your business.','',''),(12,'email','store_neworder','Store - new sale notification','admin','Hi there {customer:fullname}! Thank you for purchasing with us.','store@example.com','New order placed subject'),(13,'email','store_neworder','Store - purchase receipt','customer','Hi {customer:fullname}! Thank you for purchasing with us.','store@example.com','New order placed subject'),(14,'email','store_newcustomer','email','sales person','Hi you have new customer. ','store@example.com','New customer registered subject'),(15,'email','store_newcustomer','email','admin','Hi you have new customer. ','store@example.com','New customer registered subject'),(16,'email','store_newcustomer','Store - new customer','customer','Dear {customer:fullname}! Thank you for your registration.','store@example.com','New customer registered subject'),(17,'email','newcomment','email','admin','Someone left a new comment on your website. Look at it here: {$page:url}','no-reply@example.com','New comment posted'),(18,'email','cartstatusemail_abandoned','email','customer','You’ve left something rather lovely in your shopping basket. {cart:basket} Ready to make it yours? Click here {cart:recovery}','admin@example.com','What’s that in your shopping basket? Do you see what we see?'),(19,'email','cartstatusemail_quotesent','default','customer','Don’t forget, you left something behind. To help you make up your mind, enjoy 15% off your quote when you call us at XXX XXX XXXX with coupon # Click here to view your private quote {cart:recovery} Hurry this offer ends on XX/XX/XXXX','admin@example.com','A little something to sweeten your day'),(20,'email','cartstatusemail_paymentreceived','default','customer','We wanted to thank you for your business with a free shipping voucher code for your next purchase. Next time you shop with us, please enter XXXXX in the promotional code field on the checkout page.','admin@example.com','Thank you for your payment'),(21,'email','quote_created','Store -quote created','customer','Hi there! We just generated a new quote ({quote:id}) for you. Go ahead and <a href=\'{$website:url}{quote:id}.html\'> check out your quote</a>','admin@example.com','New quote subject'),(22,'email','quote_updated','Store - quote updated','customer','Hi there! Your quote[{quote:id}] has been updated. Go ahead and <a href=\'{$website:url}{quote:id}.html\'> check out your quote</a>','admin@example.com','Your quote has been updated'),(23,'email','t_passwordchange','email','admin','Your password was changed successfully','no-reply@example.com','Password was changed'),(24,'email','store_trackingnumber','Store - tracking code','customer','Your order #{order:id} status shipping tracking code: {order:shippingtrackingid}	','store@example.com','Track your order'),(25,'email','store_newuseraccount','Store - new customer','admin','','store@example.com','new user account information'),(26,'email','store_newuseraccount','Store - new customer','sales person','','store@example.com','new user account information'),(27,'email','quote_created','email','admin','Hi there! We just generated a new quote ({quote:id}) for you. Go ahead and <a href=\'{$website:url}{quote:id}.html\'> check out your quote</a>','admin@example.com','New quote subject');
/*!40000 ALTER TABLE `email_triggers_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_triggers_recipient`
--

DROP TABLE IF EXISTS `email_triggers_recipient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_triggers_recipient` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recipient` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Recipient Name',
  PRIMARY KEY (`id`),
  KEY `recipient` (`recipient`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_triggers_recipient`
--

LOCK TABLES `email_triggers_recipient` WRITE;
/*!40000 ALTER TABLE `email_triggers_recipient` DISABLE KEYS */;
INSERT INTO `email_triggers_recipient` VALUES (4,'admin'),(3,'copywriter'),(6,'customer'),(1,'guest'),(2,'member'),(7,'sales person'),(5,'superadmin');
/*!40000 ALTER TABLE `email_triggers_recipient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `featured_area`
--

DROP TABLE IF EXISTS `featured_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `featured_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(164) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indName` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `featured_area`
--

LOCK TABLES `featured_area` WRITE;
/*!40000 ALTER TABLE `featured_area` DISABLE KEYS */;
INSERT INTO `featured_area` VALUES (8,'bikes'),(3,'Email'),(1,'homepage'),(7,'Members only'),(2,'product');
/*!40000 ALTER TABLE `featured_area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form`
--

DROP TABLE IF EXISTS `form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `code` text COLLATE utf8_unicode_ci NOT NULL,
  `contact_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message_success` text COLLATE utf8_unicode_ci NOT NULL,
  `message_error` text COLLATE utf8_unicode_ci NOT NULL,
  `reply_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reply_mail_template` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reply_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reply_from_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reply_text` text COLLATE utf8_unicode_ci,
  `captcha` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable_sms` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form`
--

LOCK TABLES `form` WRITE;
/*!40000 ALTER TABLE `form` DISABLE KEYS */;
INSERT INTO `form` VALUES (1,'contact','<input class=\"grid_6 alpha mb10px\" type=\"text\" placeholder=\"Full Name\" name=\"name\">\r\n<input class=\"grid_6 omega mb10px\" type=\"text\" placeholder=\"Last Name\" name=\"Last name\">\r\n<input class=\"grid_12 alpha omega mb10px\" type=\"text\" placeholder=\"Email\" name=\"email\">\r\n<input class=\"grid_12 alpha omega mb10px\" type=\"text\" placeholder=\"Phone number\" name=\"mobile\">\r\n<textarea class=\"grid_12 alpha omega mb10px\" name=\"Message\"></textarea>','contact@seotoaster.com','Success','Error','Thank you for your request','Toaster - form rempy','contact@seotoaster.com','San Francinsco Bikes','Test message here','1','','1');
/*!40000 ALTER TABLE `form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_page_conversion`
--

DROP TABLE IF EXISTS `form_page_conversion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_page_conversion` (
  `page_id` int(10) unsigned NOT NULL,
  `form_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `conversion_code` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`page_id`,`form_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_page_conversion`
--

LOCK TABLES `form_page_conversion` WRITE;
/*!40000 ALTER TABLE `form_page_conversion` DISABLE KEYS */;
INSERT INTO `form_page_conversion` VALUES (1,'contact',''),(3,'contact',''),(7,'contact',''),(13,'contact','');
/*!40000 ALTER TABLE `form_page_conversion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_container`
--

DROP TABLE IF EXISTS `link_container`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_container` (
  `id_container` int(10) unsigned NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_container`,`link`),
  KEY `indContainerId` (`id_container`),
  KEY `indLink` (`link`),
  CONSTRAINT `FK_link_container` FOREIGN KEY (`id_container`) REFERENCES `container` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_container`
--

LOCK TABLES `link_container` WRITE;
/*!40000 ALTER TABLE `link_container` DISABLE KEYS */;
INSERT INTO `link_container` VALUES (15,'{$website:url}canyon-canyon-speedmax-al-19.html'),(90,'{$website:url}contact-us.html'),(96,'{$website:url}dashboard/'),(99,'{$website:url}{quote:id}.html'),(106,'{$website:url}sign-in.html'),(138,'{$website:url}canyon-canyon-speedmax-al-19.html'),(152,'{$website:url}media/news/original/13953336779_af7e3b9ba3_h.jpg'),(152,'{$website:url}media/news/original/13953451818_45d9ff2b81_h.jpg'),(152,'{$website:url}media/news/original/13953458449_ae1eb3b6a7_h.jpg'),(152,'{$website:url}media/news/original/14116855226_bc43f15ae8_h.jpg'),(152,'{$website:url}media/news/original/14137159232_422b2bd426_b.jpg'),(152,'{$website:url}media/news/original/14231993635_e6f8755d67_h.jpg');
/*!40000 ALTER TABLE `link_container` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `observers_queue`
--

DROP TABLE IF EXISTS `observers_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `observers_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `observable` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Observable Class Name',
  `observer` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Observer Class Name',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observers_queue`
--

LOCK TABLES `observers_queue` WRITE;
/*!40000 ALTER TABLE `observers_queue` DISABLE KEYS */;
INSERT INTO `observers_queue` VALUES (1,'Application_Model_Models_Page','Api_Tools_PageObserver'),(2,'Application_Model_Models_Page','Newslog_Tools_Watchdog_Page'),(3,'Models_Model_Product','PromoObserver'),(4,'Application_Model_Models_Form','Tools_MailServiceFormWatchdog'),(5,'Application_Model_Models_Form','Tools_SmsServiceFormWatchdog'),(6,'Models_Model_CartSession','Cartstatusemail_Tools_CartStatusObserver'),(7,'Application_Model_Models_Form','Tools_CrmServiceFormWatchdog');
/*!40000 ALTER TABLE `observers_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `optimized`
--

DROP TABLE IF EXISTS `optimized`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `optimized` (
  `page_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to page table',
  `url` tinytext COLLATE utf8_unicode_ci,
  `h1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_title` text COLLATE utf8_unicode_ci,
  `nav_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `targeted_key_phrase` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `meta_keywords` text COLLATE utf8_unicode_ci,
  `teaser_text` text COLLATE utf8_unicode_ci,
  `seo_intro` text COLLATE utf8_unicode_ci,
  `seo_intro_target` text COLLATE utf8_unicode_ci,
  `status` enum('tweaked','on') COLLATE utf8_unicode_ci DEFAULT NULL,
  `seo_rule_id` int(10) DEFAULT NULL,
  `url_rule_id` int(10) DEFAULT NULL,
  UNIQUE KEY `page_id` (`page_id`),
  KEY `h1` (`h1`),
  KEY `status` (`status`),
  KEY `nav_name` (`nav_name`),
  KEY `url` (`url`(30)),
  CONSTRAINT `optimized_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `optimized`
--

LOCK TABLES `optimized` WRITE;
/*!40000 ALTER TABLE `optimized` DISABLE KEYS */;
/*!40000 ALTER TABLE `optimized` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `nav_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `meta_keywords` text COLLATE utf8_unicode_ci,
  `header_title` text COLLATE utf8_unicode_ci,
  `h1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `teaser_text` text COLLATE utf8_unicode_ci,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_404page` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `show_in_menu` enum('0','1','2') COLLATE utf8_unicode_ci DEFAULT '0',
  `order` int(10) unsigned DEFAULT NULL,
  `weight` tinyint(3) unsigned DEFAULT '0',
  `silo_id` int(10) unsigned DEFAULT NULL,
  `targeted_key_phrase` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `protected` enum('0','1') CHARACTER SET utf8 DEFAULT '0',
  `system` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `draft` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `publish_at` date DEFAULT NULL,
  `news` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `err_login_landing` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `mem_landing` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `signup_landing` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `checkout` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `preview_image` text COLLATE utf8_unicode_ci COMMENT 'Page Preview Image',
  `external_link_status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `external_link` text COLLATE utf8_unicode_ci,
  `page_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indParentId` (`parent_id`),
  KEY `indUrl` (`url`),
  KEY `indMenu` (`show_in_menu`),
  KEY `indOrder` (`order`),
  KEY `indProtected` (`protected`),
  KEY `draft` (`draft`),
  KEY `news` (`news`),
  KEY `nav_name` (`nav_name`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES (1,'index',0,'Home','','','Home','Home','index.html','','2014-05-26 06:05:20','0','1',0,0,NULL,'Home','0','0','0',NULL,'0','0','0','0','0',NULL,'0',NULL,1),(3,'default',18,'Road Bikes','We design and sell urban bikes in San Francisco, California along with accessories to make riding more enjoyable, practical, and chic. They come in single-speed and multi speeds in all sizes.','Road Bikes, Road Bikes in San Francisco, California, Mission Bicycle Company, buy Road Bikes online, Road Bikes with free shipping','Road Bikes in San Francisco, California | Buy Online','Road Bikes in San Francisco','road-bikes-store-san-francisco-california.html','designed for speed and competition in road cycling. They have a light frame and components with minimal accessories, low driving, to provide a powerful and aerodynamic fit, narrow high-pressure tires for minimal rolling resistance and multiple gears. Road bikes have a relatively narrow range of programs, which typically ranges from medium to very high ratios, distributed over 18, 20, 27 or 30 gears. Closer gear ratios allow drivers to choose the gear that will allow them to go at the optimum frequency for maximum pedaling efficiency.','2014-10-08 14:20:49','0','1',4,0,0,'Road Bikes in San Francisco','0','0','0',NULL,'0','0','0','0','0','road-bikes-store-san-francisco-california.jpg','0',NULL,1),(4,'default',18,'Mountain Bikes','We design and sell urban bikes in San Francisco, California along with accessories to make riding more enjoyable, practical, and chic. They come in single-speed and multi speeds in all sizes.','Mountain Bikes, Mountain Bikes in San Francisco, California, Mission Bicycle Company, buy Mountain Bikes online, Mountain Bikes with free shipping','Mountain Bikes in San Francisco, California | Buy Online','Mountain Bikes in San Francisco','mountain-bikes-store-san-francisco-california.html','designed for off-road riding, and include other types of off-road bikes, such as cross-country (for example, \"CS\"), downhill, freeride. All mountain bikes have a very strong frame and wheels, wide tires with high tread, and reinforced wheel to help rider resist sudden jolts. Typically, mountain bikes have shock absorbers of different types (or pneumatic spring or gas spring), and hydraulic or mechanical disc brakes. Speed ​​range of mountain biking is very wide, from very low to average ratio, and, as a rule, from 16 to 28 gears.','2014-10-08 14:20:37','0','1',6,0,0,'Mountain Bikes in San Francisco','0','0','0','2014-06-01','0','0','0','0','0','mountain-bikes-store-san-francisco-california.jpg','0',NULL,1),(5,'default',18,'Triathlon Bikes','We design and sell urban bikes in San Francisco, California along with accessories to make riding more enjoyable, practical, and chic. They come in single-speed and multi speeds in all sizes.','Triathlon Bikes, Triathlon Bikes in San Francisco, California, Mission Bicycle Company, buy Triathlon Bikes online, Triathlon Bikes with free shipping','Triathlon Bikes in San Francisco, California | Buy Online','Triathlon Bikes in San Francisco','triathlon-bikes-store-san-francisco-california.html','designed for driving on the open or closed cycle track, exceptionally easy compared to road bikes. They have one gear, fixed transmission (ie, without the clutch), no brakes, and a minimal number of other components, which otherwise would have absented bike to the motor road.','2014-09-22 06:11:25','0','1',5,0,0,'Triathlon Bikes','0','0','0',NULL,'0','0','0','0','0','triathlon-bikes-store-san-francisco-california.jpg','0',NULL,1),(6,'checkout',-1,'Checkout','','','Checkout','Checkout','checkout.html','','2014-09-22 06:11:25','0','0',55,0,0,'Checkout','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(7,'default',0,'About Us','','','About Us','About Us','about-us.html','','2014-06-03 06:28:01','0','1',7,0,0,'About Us','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(11,'default',-1,'Site map','','','Site map','Site map','site-map.html','','2014-03-27 04:15:22','0','2',40,0,0,'Site map','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(12,'default',-1,'Privacy Policy','','','Privacy Policy','Privacy Policy','privacy-policy.html','','2014-03-27 04:15:22','0','2',41,0,0,'Privacy Policy','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(13,'contact',0,'Contact Us','','','Contact Us','Contact Us','contact-us.html','','2014-06-02 05:31:39','0','1',18,0,0,'Contact Us','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(14,'default',0,'Gallery','','','Gallery','Gallery','gallery.html','','2014-03-27 04:16:21','0','1',1,0,0,'Gallery','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(15,'news room',0,'News','','','News','News','news.html','','2014-06-02 05:31:39','0','1',17,0,0,'News','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(18,'default',0,'Bikes','We design and sell urban bikes in San Francisco, California along with accessories to make riding more enjoyable, practical, and chic. They come in single-speed and multi speeds in all sizes.','Mission Bicycle Company, best bicycle online store in San Francisco, California, buy bicycle online, buy bicycle in SF, bicycle free shipping, buy bicycle in San Francisco,','Bikes in San Francisco, California | Buy Bikes Online','Bikes in San Francisco, California','bike-store-in-san-francisco-california.html','','2014-09-22 06:11:25','0','1',2,0,0,'Bike','0','0','0',NULL,'0','0','0','0','0','bike-store-in-san-francisco-california','0',NULL,1),(19,'default',0,'Product Pages','','','Product Pages','Product Pages','product-pages.html','','2014-06-03 02:32:13','0','0',19,0,0,'','0','0','1',NULL,'0','0','0','0','0',NULL,'0',NULL,1),(20,'product',19,'Santa Cruz Tallboy LT Carbon - Santa Cruz','The Santa Cruz Tallboy LT Carbon XTR AM 29er Full Suspension Mountain Bike 2013 offers all the same features as the Tallboy LT but with an added touch of carbon fibre.','','Santa Cruz Santa Cruz Tallboy LT Carbon','Santa Cruz Tallboy LT Carbon','santa-cruz-santa-cruz-tallboy-lt-carbon-1.html','The Santa Cruz Tallboy LT Carbon XTR AM 29er Full Suspension Mountain Bike 2013 offers all the same features as the Tallboy LT but with an added touch of carbon fibre.','2016-02-04 15:02:33','0','1',20,0,0,'Santa Cruz Tallboy LT Carbon','0','0','0',NULL,'0','0','0','0','0','santa-cruz-santa-cruz-tallboy-lt-carbon-1.jpg','0',NULL,2),(23,'product',19,'Santa Cruz Superlight XT XC - Santa Cruz','The Santa Cruz Superlight XT XC Full Suspension Mountain Bike 2013 was created with performance and value aspects in mind. The Superlight was built to offer a winning blend of nimble geometry, confidence inspiring handling, and elegantly simple yet effective suspension, to make one of the most sought-after bikes on the market','','Santa Cruz Santa Cruz Superlight XT XC','Santa Cruz Superlight XT XC','santa-cruz-santa-cruz-superlight-xt-xc-2.html','The Santa Cruz Superlight XT XC Full Suspension Mountain Bike 2013 was created with performance and value aspects in mind. The Superlight was built to offer a winning blend of nimble geometry, confidence inspiring handling, and elegantly simple yet effective suspension, to make one of the most sought-after bikes on the market','2016-02-04 15:02:33','0','1',21,0,0,'Santa Cruz Superlight XT XC','0','0','0',NULL,'0','0','0','0','0','santa-cruz-santa-cruz-superlight-xt-xc-2.jpg','0',NULL,2),(24,'Product Quote',19,'Santa Cruz Superlight 29er XT XC - Santa Cruz','The Santa Cruz Superlight 29er XT XC Full Suspension Mountain Bike 2013 combines the proven value and performance of the legendary Superlight with the smooth rolling stability of bigger 29\" wheels. Santa Cruz have made a 29er that\'s super light, made from custom hydroformed aluminium tubing, a reliable, efficient, responsive single pivot suspension design that offers 100mm of travel, terrain eating 29\" wheels and kickass value for money.','','Santa Cruz Santa Cruz Superlight 29er XT XC','Santa Cruz Superlight 29er XT XC','santa-cruz-santa-cruz-superlight-29er-xt-xc-3.html','The Santa Cruz Superlight 29er XT XC Full Suspension Mountain Bike 2013 combines the proven value and performance of the legendary Superlight with the smooth rolling stability of bigger 29\" wheels. Santa Cruz have made a 29er that\'s super light, made from custom hydroformed aluminium tubing, a reliable, efficient, responsive single pivot suspension design that offers 100mm of travel, terrain eating 29\" wheels and kickass value for money.','2016-02-04 15:02:33','0','1',22,0,0,'Santa Cruz Superlight 29er XT XC','0','0','0',NULL,'0','0','0','0','0','santa-cruz-santa-cruz-superlight-29er-xt-xc-3.jpg','0',NULL,2),(25,'product',19,'Ragley M74 Hardtail - Ragley','New for 2013 Ragley introduce the M74 complete bike. Following the success of our highly acclaimed steel hardtail bikes we have delved into the world of aluminium tubed framesets to produce the M74. With a spec list that includes RockShox Recon forks, Avid Elixir brakes, SRAM X5 shifting, Ragley bars, stem and post with a single ring and chainguide set up the M74 is ready to tackle any trail situation','','Ragley Ragley M74 Hardtail','Ragley M74 Hardtail','ragley-ragley-m74-hardtail-4.html','New for 2013 Ragley introduce the M74 complete bike. Following the success of our highly acclaimed steel hardtail bikes we have delved into the world of aluminium tubed framesets to produce the M74. With a spec list that includes RockShox Recon forks, Avid Elixir brakes, SRAM X5 shifting, Ragley bars, stem and post with a single ring and chainguide set up the M74 is ready to tackle any trail situation','2016-02-04 15:02:33','0','1',23,0,0,'Ragley M74 Hardtail','0','0','0',NULL,'0','0','0','0','0','ragley-ragley-m74-hardtail-4.jpg','0',NULL,2),(26,'product',19,'Santa Cruz Highball R XC 29er - Santa Cruz','The Santa Cruz Highball R XC 29er Hardtail Mountain Bike 2013 takes all the swift, light elements from the 29\" wheeled Carbon Highball, and crafts them together in an aluminium frame. This bike has a number of other features too, a couple of the most notable being versatility and value. The proprietary aluminum tubing allows for a strong, respectably light frame, and features the same responsive geometry as our carbon race bike.','','Santa Cruz Santa Cruz Highball R XC 29er','Santa Cruz Highball R XC 29er','santa-cruz-santa-cruz-highball-r-xc-29er-5.html','The Santa Cruz Highball R XC 29er Hardtail Mountain Bike 2013 takes all the swift, light elements from the 29\" wheeled Carbon Highball, and crafts them together in an aluminium frame. This bike has a number of other features too, a couple of the most notable being versatility and value. The proprietary aluminum tubing allows for a strong, respectably light frame, and features the same responsive geometry as our carbon race bike.','2016-02-04 15:02:33','0','1',24,0,0,'Santa Cruz Highball R XC 29er','0','0','0',NULL,'0','0','0','0','0','santa-cruz-santa-cruz-highball-r-xc-29er-5.jpg','0',NULL,2),(27,'default',18,'Downhill Bikes','We design and sell urban bikes in San Francisco, California along with accessories to make riding more enjoyable, practical, and chic. They come in single-speed and multi speeds in all sizes.','Downhill Bikes, Downhill Bikes in San Francisco, California, Mission Bicycle Company, buy Downhill Bikes online, Downhill Bikes with free shipping','Downhill Bikes in San Francisco, California | Buy Online','Downhill Bikes in San Francisco','downhill-bikes-store-san-francisco-california.html','Downhill mountain biking (DH) is a time trial event held on a steep, rough terrain that often features jumps, rock gardens and other obstacles','2014-10-08 14:19:11','0','1',3,0,0,'Downhill Bikes in San Francisco','0','0','0',NULL,'0','0','0','0','0','downhill-bikes-store-san-francisco-california.jpg','0',NULL,1),(36,'default',0,'E-mails','','','E-mails','E-mails','e-mails.html','','2014-06-03 06:55:39','0','1',8,0,0,'E-mails','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(37,'2 columns',-1,'Sign In','','','Sign In','Sign In','sign-in.html','','2014-03-27 04:15:22','0','2',42,0,0,'Sign In','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(38,'2 columns',-1,'Registration','','','Registration','Registration','registration.html','','2014-03-27 04:15:22','0','2',43,0,0,'Registration','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(39,'default',-1,'Search Results','','','Search Results','Search Results','search-results.html','','2014-09-22 06:11:26','0','0',56,0,0,'Search Results','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(40,'Toaster - new user info',36,'Toaster - new user info','','','Toaster - new user info','Toaster - new user info','toaster-new-user-info.html','','2014-06-03 06:55:39','0','1',9,0,0,'Toaster - new user info','0','0','0',NULL,'0','0','0','0','0','toaster-new-user-info.jpg','0',NULL,1),(41,'Store -quote created',36,'Store - quote created','','','Store - quote created','Store - quote created','store-quote-created.html','','2014-06-03 06:55:39','0','1',10,0,0,'Store - quote created','0','0','0',NULL,'0','0','0','0','0','store-quote-created.jpg','0',NULL,1),(42,'Store - quote updated',36,'Store - quote updated','','','Store - quote updated','Store - quote updated','store-quote-updated.html','','2014-06-03 06:21:46','0','1',11,0,0,'Store - quote updated','0','0','0',NULL,'0','0','0','0','0','store-quote-updated.jpg','0',NULL,1),(43,'Store - purchase receipt',36,'Store - purchace receipt','','','Store - purchace receipt','Store - purchace receipt','store-purchace-receipt.html','','2014-06-03 06:21:46','0','1',12,0,0,'Store - purchace receipt','0','0','0',NULL,'0','0','0','0','0','store-purchace-receipt.jpg','0',NULL,1),(44,'Store - new sale notification',36,'Store - new sale notification','','','Store - new sale notification','Store - new sale notification','store-new-sale-notification.html','','2014-06-03 06:21:46','0','1',13,0,0,'Store - new sale notification','0','0','0',NULL,'0','0','0','0','0','store-new-sale-notification.jpg','0',NULL,1),(45,'404 page',-1,'404 page','','','404 page','404 page','404-page.html','','2014-09-22 06:11:26','0','0',57,0,0,'404 page','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(46,'User landing',-1,'User landing','','','User landing','User landing','user-landing.html','','2014-09-22 06:11:26','0','0',58,0,0,'User landing','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(47,'product',19,'Orbea Orca M22 - Orca','With industry leading lightness, the SRAM Red 22 components are a perfect match for the newest version of our lightweight, carbon fiber Orca bicycle.','','Orca Orbea Orca M22','Orbea Orca M22','orca-orbea-orca-m22-6.html','With industry leading lightness, the SRAM Red 22 components are a perfect match for the newest version of our lightweight, carbon fiber Orca bicycle.','2016-02-04 15:02:33','0','1',25,0,0,'Orbea Orca M22','0','0','0',NULL,'0','0','0','0','0','orca-orbea-orca-m22-6.jpg','0',NULL,2),(48,'product',19,'Orbea Orca M10 - Orca','The Orca Bronze M10 is our top-notch Orca Bronze bicycle','','Orca Orbea Orca M10','Orbea Orca M10','orca-orbea-orca-m10-7.html','The Orca Bronze M10 is our top-notch Orca Bronze bicycle','2016-02-04 15:02:33','0','1',26,0,0,'Orbea Orca M10','0','0','0',NULL,'0','0','0','0','0','orca-orbea-orca-m10-7.jpg','0',NULL,2),(49,'product',19,'Canyon ULTIMATE CF SLX 7.0 - Canyon','A perfect blend of sportiness, stiffness and light weight – this frame, weighing just 790 g, will set pulses racing with its integrated cables and conical head tube.','','Canyon Canyon ULTIMATE CF SLX 7.0','Canyon ULTIMATE CF SLX 7.0','canyon-canyon-ultimate-cf-slx-7-0-8.html','A perfect blend of sportiness, stiffness and light weight – this frame, weighing just 790 g, will set pulses racing with its integrated cables and conical head tube.','2016-02-04 15:02:33','0','1',27,0,0,'Canyon ULTIMATE CF SLX 7.0','0','0','0',NULL,'0','0','0','0','0','canyon-canyon-ultimate-cf-slx-7-0-8.png','0',NULL,2),(52,'product',19,'Canyon Torque-DHX - Canyon','For years the Torque FRX combined the ultimate in downhill performance with maximum versatility.','','Canyon Canyon Torque-DHX','Canyon Torque-DHX','canyon-canyon-torque-dhx-11.html','For years the Torque FRX combined the ultimate in downhill performance with maximum versatility.','2016-02-04 15:02:33','0','1',30,0,0,'Canyon Torque-DHX','0','0','0',NULL,'0','0','0','0','0','canyon-canyon-torque-dhx-11.png','0',NULL,2),(54,'product',19,'Giant Glory 0 - Giant','At the core of this competition-oriented downhill flyer is an impressively light, strong and agile frameset','','Giant Giant Glory 0','Giant Glory 0','giant-giant-glory-0-13.html','At the core of this competition-oriented downhill flyer is an impressively light, strong and agile frameset','2016-02-04 15:02:33','0','1',32,0,0,'Giant Glory 0','0','0','0',NULL,'0','0','0','0','0',NULL,'0',NULL,2),(55,'product',19,'Canyon Glory 2 - Canyon','At the core of this competition-oriented downhill flyer is an impressively light, strong and agile frameset. The radically hydroformed tubeset utilizes the latest, lightweight co-pivot design to shed grams while maintaining outstanding stiffness and durability','','Canyon Canyon Glory 2','Canyon Glory 2','canyon-canyon-glory-2-14.html','At the core of this competition-oriented downhill flyer is an impressively light, strong and agile frameset. The radically hydroformed tubeset utilizes the latest, lightweight co-pivot design to shed grams while maintaining outstanding stiffness and durability','2016-02-04 15:02:33','0','1',33,0,0,'Canyon Glory 2','0','0','0',NULL,'0','0','0','0','0','canyon-canyon-glory-2-14.jpg','0',NULL,2),(56,'product',19,'Orbea Occam 29 H20 - Orca','Our Occam 29 H20 is purpose built for adventure.','','Orca Orbea Occam 29 H20','Orbea Occam 29 H20','orca-orbea-occam-29-h20-15.html','Our Occam 29 H20 is purpose built for adventure.','2016-02-04 15:02:33','0','1',34,0,0,'Orbea Occam 29 H20','0','0','0',NULL,'0','0','0','0','0','orca-orbea-occam-29-h20-15.jpg','0',NULL,2),(57,'product',19,'Orbea Ordu M-ltd - Orca','The Ordu is the bike of choice for the Euskaltel-Euskadi pros, and it’s the bike that Andrew Starykowicz uses to punish his triathlon rivals. In fact, it’s the bike he used to set the current world records for Ironman and Ironman 70.3 bike splits.','','Orca Orbea Ordu M-ltd','Orbea Ordu M-ltd','orca-orbea-ordu-m-ltd-16.html','The Ordu is the bike of choice for the Euskaltel-Euskadi pros, and it’s the bike that Andrew Starykowicz uses to punish his triathlon rivals. In fact, it’s the bike he used to set the current world records for Ironman and Ironman 70.3 bike splits.','2016-02-04 15:02:33','0','1',35,0,0,'Orbea Ordu M-ltd','0','0','0',NULL,'0','0','0','0','0','orca-orbea-ordu-m-ltd-16.jpg','0',NULL,2),(58,'product',19,'Orbea Ordu M30 - Orca','Sometimes brutal simplicity is what you need for success. ','','Orca Orbea Ordu M30','Orbea Ordu M30','orca-orbea-ordu-m30-17.html','Sometimes brutal simplicity is what you need for success. ','2016-02-04 15:02:33','0','1',36,0,0,'Orbea Ordu M30','0','0','0',NULL,'0','0','0','0','0','orca-orbea-ordu-m30-17.jpg','0',NULL,2),(59,'product',19,'Canyon Speedmax CF - Canyon','100% triathlon: The special triathlon stem is significantly higher than the time trial version, and spacers can be used to raise it up to 38 mm. ','','Canyon Canyon Speedmax CF','Canyon Speedmax CF','canyon-canyon-speedmax-cf-18.html','100% triathlon: The special triathlon stem is significantly higher than the time trial version, and spacers can be used to raise it up to 38 mm. ','2016-02-04 15:02:33','0','1',37,0,0,'Canyon Speedmax CF','0','0','0',NULL,'0','0','0','0','0','canyon-canyon-speedmax-cf-18.png','0',NULL,2),(60,'product',19,'Canyon Speedmax AL - Canyon','This year you can pound your fastest races times into the tarmac with the aluminium model – for less than EUR 1500.','','Canyon Canyon Speedmax AL','Canyon Speedmax AL','canyon-canyon-speedmax-al-19.html','This year you can pound your fastest races times into the tarmac with the aluminium model – for less than EUR 1500.','2016-02-04 15:02:33','0','1',38,0,0,'Canyon Speedmax AL','0','0','0',NULL,'0','0','0','0','0','canyon-canyon-speedmax-al-19.png','0',NULL,2),(61,'Store - new customer',36,'Store - new customer','','','Store - new customer','Store - new customer','store-new-customer.html','','2014-06-03 06:21:46','0','1',14,0,0,'Store - new customer','0','0','0',NULL,'0','0','0','0','0','store-new-customer.jpg','0',NULL,1),(62,'Toaster - form rempy',36,'Toaster - form reply','','','Toaster - form reply','Toaster - form reply','toaster-form-reply.html','','2014-06-03 06:21:46','0','1',15,0,0,'Toaster - form reply','0','0','0',NULL,'0','0','0','0','0','toaster-form-reply.jpg','0',NULL,1),(63,'Store - tracking code',36,'Store - tracking code','','','Store - tracking code','Store - tracking code','store-tracking-code.html','','2014-06-03 06:21:46','0','1',0,0,0,'Store - tracking code','0','0','0',NULL,'0','0','0','0','0','store-tracking-code.jpg','0',NULL,1),(77,'news',-1,'Bike To Work Bay Area','Yesterday the Bay Area celebrated the 20th Anniversary of Bike to Work Day with an impressive amount of bikers hitting the road. One major San Francisco thoroughfare tallied that nearly 76% of the trips made on it yesterday were done by bike. Well done, San Francisco!','Bike To Work Bay Area','Bike To Work Bay Area','Bike To Work Bay Area','bike-to-work-bay-area-1400840135.html','Yesterday the Bay Area celebrated the 20th Anniversary of Bike to Work Day with an impressive amount of bikers hitting the road. One major San Francisco thoroughfare tallied that nearly 76% of the trips made on it yesterday were done by bike. Well done, San Francisco!','2016-02-04 15:01:28','0','0',49,0,0,'Bike To Work Bay Area','0','1','0',NULL,'1','0','0','0','0','bike-to-work-bay-area-1400840135.jpg','0',NULL,3),(78,'news',-1,'WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY','Bicycling is booming in San Francisco. From the increase in ridership (96% since 2006) to the influx of new bike shops (three new ones on Market Street alone) to the plethora of bike startups launching from our City by the Bay, one thing is clear: San Francisco loves biking.','WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY','WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY','WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY','why-local-company-revolights-supports-bicycle-advocacy-1400840939.html','Bicycling is booming in San Francisco. From the increase in ridership (96% since 2006) to the influx of new bike shops (three new ones on Market Street alone) to the plethora of bike startups launching from our City by the Bay, one thing is clear: San Francisco loves biking.','2016-02-04 15:01:28','0','0',50,0,0,'WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY','0','1','0',NULL,'1','0','0','0','0','why-local-company-revolights-supports-bicycle-advocacy-1400840939.png','0',NULL,3),(88,'default',-1,'Promoting cycling in San Francisco Bay Area','everyday transportation','san francisco bicycle coalition, san francisco bicycles,sf bikes','Promoting cycling in San Francisco Bay Area','Promoting cycling in San Francisco Bay Area','promoting-cycling-in-san-francisco-bay-area.html','everyday transportation','2014-09-22 06:11:25','0','0',48,0,0,'Promoting cycling in San Francisco Bay Area','0','0','0',NULL,'0','0','0','0','0','promoting-cycling-in-san-francisco-bay-area.jpg','0',NULL,1),(92,'default',-1,'Paris','','','Paris','Paris','paris.html','','2014-09-22 06:11:25','0','0',49,0,0,'Paris','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(94,'default',-1,'Thank you','','','Thank you','Thank you','thank-you.html','','2014-09-22 06:11:25','0','0',50,0,0,'Thank you','0','0','0',NULL,'0','0','0','0','0','','0',NULL,1),(100,'default',-3,'dashboard','','','dashboard','dashboard','dashboarddash99be11aabafcbc8ac35f0d3896f76e1e.html','','2014-10-06 10:24:34','0','0',0,0,0,'dashboard','0','1','0',NULL,'0','0','0','0','0',NULL,'0',NULL,1);
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_fa`
--

DROP TABLE IF EXISTS `page_fa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_fa` (
  `page_id` int(10) unsigned NOT NULL,
  `fa_id` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`page_id`,`fa_id`),
  KEY `indPageId` (`page_id`),
  KEY `indFaId` (`fa_id`),
  KEY `indOrder` (`order`),
  CONSTRAINT `FK_page_fa` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE,
  CONSTRAINT `page_fa_ibfk_1` FOREIGN KEY (`fa_id`) REFERENCES `featured_area` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_fa`
--

LOCK TABLES `page_fa` WRITE;
/*!40000 ALTER TABLE `page_fa` DISABLE KEYS */;
INSERT INTO `page_fa` VALUES (3,1,0),(3,2,0),(40,3,0),(88,8,0),(4,1,1),(4,2,1),(41,3,1),(92,8,1),(27,2,2),(42,3,2),(5,2,3),(27,1,3),(43,3,3),(44,3,4),(61,3,5),(62,3,6),(63,3,7);
/*!40000 ALTER TABLE `page_fa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_has_option`
--

DROP TABLE IF EXISTS `page_has_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_has_option` (
  `page_id` int(10) unsigned NOT NULL,
  `option_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`page_id`,`option_id`),
  KEY `option_id` (`option_id`),
  CONSTRAINT `page_has_option_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE,
  CONSTRAINT `page_has_option_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `page_option` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_has_option`
--

LOCK TABLES `page_has_option` WRITE;
/*!40000 ALTER TABLE `page_has_option` DISABLE KEYS */;
INSERT INTO `page_has_option` VALUES (11,'option_404page'),(45,'option_404page'),(6,'option_checkout'),(15,'option_newsindex'),(84,'option_newspage'),(85,'option_newspage'),(88,'option_protected'),(39,'option_search'),(46,'option_storeclientlogin'),(94,'option_storethankyou');
/*!40000 ALTER TABLE `page_has_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_option`
--

DROP TABLE IF EXISTS `page_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_option` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `context` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'In which context this option is used. E.g. option_newsindex used in News system context',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `option_usage` enum('once','many') COLLATE utf8_unicode_ci DEFAULT 'many',
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_option`
--

LOCK TABLES `page_option` WRITE;
/*!40000 ALTER TABLE `page_option` DISABLE KEYS */;
INSERT INTO `page_option` VALUES ('option_404page','Our error 404 \"Not found\" page','Seotoaster pages',1,'many'),('option_checkout','The cart checkout page','Cart and checkout',1,'once'),('option_member_landing','Where members land after logging-in','Seotoaster membership',1,'once'),('option_member_loginerror','Our membership login error page','Seotoaster membership',1,'once'),('option_member_signuplanding','Where members land after signed-up','Seotoaster membership',1,'once'),('option_newsindex','News index page','News system',1,'once'),('option_newspage','News page','News system',1,'many'),('option_protected','Accessible only to logged-in members','Seotoaster pages',1,'many'),('option_quotepage','Quote page','Quote system',1,'many'),('option_search','Search landing page','Seotoaster pages',1,'once'),('option_storeclientlogin','Store client landing page','Cart and checkout',1,'once'),('option_storeshippingterms','Shipping terms and conditions','Cart and checkout',1,'once'),('option_storethankyou','Post purchase \"Thank you\" page','Cart and checkout',1,'once');
/*!40000 ALTER TABLE `page_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_types`
--

DROP TABLE IF EXISTS `page_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_types` (
  `page_type_id` tinyint(3) unsigned NOT NULL,
  `page_type_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`page_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_types`
--

LOCK TABLES `page_types` WRITE;
/*!40000 ALTER TABLE `page_types` DISABLE KEYS */;
INSERT INTO `page_types` VALUES (1,'page'),(2,'product'),(3,'news'),(4,'quote');
/*!40000 ALTER TABLE `page_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_log`
--

DROP TABLE IF EXISTS `password_reset_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token_hash` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Password reset token. Unique hash string.',
  `user_id` int(10) unsigned NOT NULL,
  `status` enum('new','used','expired') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new' COMMENT 'Recovery link status',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expired_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_hash` (`token_hash`),
  KEY `status` (`status`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_log`
--

LOCK TABLES `password_reset_log` WRITE;
/*!40000 ALTER TABLE `password_reset_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin`
--

DROP TABLE IF EXISTS `plugin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('enabled','disabled') COLLATE utf8_unicode_ci DEFAULT 'disabled',
  `tags` text COLLATE utf8_unicode_ci COMMENT 'comma separated words',
  `license` blob,
  `version` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indName` (`name`),
  KEY `indStatus` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin`
--

LOCK TABLES `plugin` WRITE;
/*!40000 ALTER TABLE `plugin` DISABLE KEYS */;
INSERT INTO `plugin` VALUES (1,'api','enabled','','','2.2.0'),(2,'newslog','enabled','feed','','2.2.6'),(3,'cart','enabled','','','2.2.0'),(4,'invoicetopdf','enabled','ecommerce','','2.5.0'),(5,'shopping','enabled','','','2.5.0'),(6,'netcontent','enabled','','','2.2.0'),(7,'promo','enabled','ecommerce,merchandising','','2.2.0'),(8,'quote','enabled','ecommerce','','2.2.5'),(9,'socialposter','enabled','','','2.2.0'),(10,'toasterstats','enabled','','','2.2.0'),(12,'webbuilder','enabled','','','2.2.1'),(13,'widcard','enabled','','','2.2.0'),(14,'dashboard','enabled','ecommerce','','2.2.0'),(20,'apps','enabled','','','2.2.4'),(21,'cartstatusemail','enabled','ecommerce,merchandising','','2.3.2'),(22,'flatrateshipping','enabled','','','2.2.0'),(23,'productonly','enabled','','','2.2.0'),(24,'delivery','enabled','','','2.2.0'),(25,'toastauth','enabled','','','2.2.0'),(26,'paypal','enabled','','','2.3.0');
/*!40000 ALTER TABLE `plugin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_apps_crm`
--

DROP TABLE IF EXISTS `plugin_apps_crm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_apps_crm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lists` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `additional_list` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `service` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_apps_crm`
--

LOCK TABLES `plugin_apps_crm` WRITE;
/*!40000 ALTER TABLE `plugin_apps_crm` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_apps_crm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_apps_settings`
--

DROP TABLE IF EXISTS `plugin_apps_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_apps_settings` (
  `service_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`service_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_apps_settings`
--

LOCK TABLES `plugin_apps_settings` WRITE;
/*!40000 ALTER TABLE `plugin_apps_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_apps_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_apps_system_form`
--

DROP TABLE IF EXISTS `plugin_apps_system_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_apps_system_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lists` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `additional_list` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `service` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_apps_system_form`
--

LOCK TABLES `plugin_apps_system_form` WRITE;
/*!40000 ALTER TABLE `plugin_apps_system_form` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_apps_system_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_cartstatusemail_queue`
--

DROP TABLE IF EXISTS `plugin_cartstatusemail_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_cartstatusemail_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cartStatusId` int(10) NOT NULL,
  `status` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `cartStatus` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cartId` int(10) NOT NULL,
  `userEmail` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `userFullName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailMessage` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailTemplate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailFrom` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sentAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_cartstatusemail_queue`
--

LOCK TABLES `plugin_cartstatusemail_queue` WRITE;
/*!40000 ALTER TABLE `plugin_cartstatusemail_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_cartstatusemail_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_cartstatusemail_restored_cart`
--

DROP TABLE IF EXISTS `plugin_cartstatusemail_restored_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_cartstatusemail_restored_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int(10) unsigned NOT NULL COMMENT 'Restored cart id',
  `sent_at` datetime NOT NULL COMMENT 'sent link date',
  `restored_at` datetime NOT NULL COMMENT 'restored cart date',
  `code` char(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Hash for restore cart link',
  `user_id` int(10) unsigned NOT NULL COMMENT 'System user id',
  `cart_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cart status',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_cartstatusemail_restored_cart`
--

LOCK TABLES `plugin_cartstatusemail_restored_cart` WRITE;
/*!40000 ALTER TABLE `plugin_cartstatusemail_restored_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_cartstatusemail_restored_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_cartstatusemail_settings`
--

DROP TABLE IF EXISTS `plugin_cartstatusemail_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_cartstatusemail_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cartStatus` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `periodHours` int(4) unsigned NOT NULL,
  `productsIds` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailTemplate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailFrom` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `productsRule` enum('all','any','without') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'all',
  `emailMessage` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_cartstatusemail_settings`
--

LOCK TABLES `plugin_cartstatusemail_settings` WRITE;
/*!40000 ALTER TABLE `plugin_cartstatusemail_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_cartstatusemail_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_cartstatusemail_subscribe`
--

DROP TABLE IF EXISTS `plugin_cartstatusemail_subscribe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_cartstatusemail_subscribe` (
  `user_id` int(10) unsigned NOT NULL,
  `code` char(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Hash for unsubscribe link',
  `status` enum('subscribed','unsubscribed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'subscribed',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_cartstatusemail_subscribe`
--

LOCK TABLES `plugin_cartstatusemail_subscribe` WRITE;
/*!40000 ALTER TABLE `plugin_cartstatusemail_subscribe` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_cartstatusemail_subscribe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_dashboard_theme`
--

DROP TABLE IF EXISTS `plugin_dashboard_theme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_dashboard_theme` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_dashboard_theme`
--

LOCK TABLES `plugin_dashboard_theme` WRITE;
/*!40000 ALTER TABLE `plugin_dashboard_theme` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_dashboard_theme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_flatrateshipping_config`
--

DROP TABLE IF EXISTS `plugin_flatrateshipping_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_flatrateshipping_config` (
  `id` int(10) unsigned NOT NULL,
  `amount_type_limit` enum('up to','over') COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount_limit` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_flatrateshipping_config`
--

LOCK TABLES `plugin_flatrateshipping_config` WRITE;
/*!40000 ALTER TABLE `plugin_flatrateshipping_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_flatrateshipping_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_flatrateshipping_zones_config`
--

DROP TABLE IF EXISTS `plugin_flatrateshipping_zones_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_flatrateshipping_zones_config` (
  `config_id` int(10) unsigned NOT NULL,
  `flatrate_zone_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount_zone` decimal(10,2) DEFAULT NULL,
  `config_zone_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`config_id`,`config_zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_flatrateshipping_zones_config`
--

LOCK TABLES `plugin_flatrateshipping_zones_config` WRITE;
/*!40000 ALTER TABLE `plugin_flatrateshipping_zones_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_flatrateshipping_zones_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_invoicetopdf_settings`
--

DROP TABLE IF EXISTS `plugin_invoicetopdf_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_invoicetopdf_settings` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_invoicetopdf_settings`
--

LOCK TABLES `plugin_invoicetopdf_settings` WRITE;
/*!40000 ALTER TABLE `plugin_invoicetopdf_settings` DISABLE KEYS */;
INSERT INTO `plugin_invoicetopdf_settings` VALUES ('action','fireaction'),('controller','backend_plugin'),('invoiceTemplate','invoice'),('name','invoicetopdf'),('packingTemplate','packing'),('run','config');
/*!40000 ALTER TABLE `plugin_invoicetopdf_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_netcontent_widget`
--

DROP TABLE IF EXISTS `plugin_netcontent_widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_netcontent_widget` (
  `widget_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `content` text COLLATE utf8_unicode_ci,
  `publish` tinyint(1) DEFAULT NULL,
  `p2p` tinyint(1) NOT NULL DEFAULT '0',
  `modify_date` date DEFAULT NULL,
  PRIMARY KEY (`widget_name`,`p2p`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_netcontent_widget`
--

LOCK TABLES `plugin_netcontent_widget` WRITE;
/*!40000 ALTER TABLE `plugin_netcontent_widget` DISABLE KEYS */;
INSERT INTO `plugin_netcontent_widget` VALUES ('seoBottom',NULL,1,0,NULL),('seoHead',NULL,1,0,NULL),('seoTop',NULL,1,0,NULL);
/*!40000 ALTER TABLE `plugin_netcontent_widget` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_newslog_configuration`
--

DROP TABLE IF EXISTS `plugin_newslog_configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_newslog_configuration` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_newslog_configuration`
--

LOCK TABLES `plugin_newslog_configuration` WRITE;
/*!40000 ALTER TABLE `plugin_newslog_configuration` DISABLE KEYS */;
INSERT INTO `plugin_newslog_configuration` VALUES ('folder','news'),('gplusProfile','');
/*!40000 ALTER TABLE `plugin_newslog_configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_newslog_news`
--

DROP TABLE IF EXISTS `plugin_newslog_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_newslog_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned DEFAULT NULL,
  `metaData` text COLLATE utf8_unicode_ci NOT NULL,
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `teaser` text COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci,
  `broadcast` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('internal','external') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'internal',
  `event` int(10) unsigned NOT NULL DEFAULT '0',
  `event_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event_location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `external_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id` (`page_id`),
  KEY `type` (`type`),
  KEY `external_id` (`external_id`),
  KEY `index_created_ad` (`created_at`),
  KEY `index_id_created_at` (`id`,`created_at`),
  CONSTRAINT `plugin_newslog_news_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Local author id';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_newslog_news`
--

LOCK TABLES `plugin_newslog_news` WRITE;
/*!40000 ALTER TABLE `plugin_newslog_news` DISABLE KEYS */;
INSERT INTO `plugin_newslog_news` VALUES (16,77,'{\"h1\":\"Bike To Work Bay Area\",\"title\":\"Bike To Work Bay Area\",\"navName\":\"Bike To Work Bay Area\",\"url\":\"bike-to-work-bay-area-1400840135.html\",\"teaserText\":\"Yesterday the Bay Area celebrated the 20th Anniversary of Bike to Work Day with an impressive amount of bikers hitting the road. One major San Francisco thoroughfare tallied that nearly 76% of the trips made on it yesterday were done by bike. Well done, San Francisco!\",\"metaKeywords\":\"Bike To Work Bay Area\",\"template\":\"news\",\"image\":\"bike-to-work-bay-area-1400840135.jpg\"}','Bike To Work Bay Area','Yesterday the Bay Area celebrated the 20th Anniversary of Bike to Work Day with an impressive amount of bikers hitting the road. One major San Francisco thoroughfare tallied that nearly 76% of the trips made on it yesterday were done by bike. Well done, San Francisco!','\n                    <p><a class=\"_lbox\" href=\"{$website:url}media/news/original/13953336779_af7e3b9ba3_h.jpg\" title=\"13953336779_af7e3b9ba3_h\"><img src=\"{$website:url}media/news/large/13953336779_af7e3b9ba3_h.jpg\" alt=\"13953336779_af7e3b9ba3_h\" width=\"601\" height=\"398\" /></a></p>\n<p>Yesterday the Bay Area celebrated the 20th Anniversary of Bike to Work Day with an impressive amount of bikers hitting the road. One major San Francisco thoroughfare tallied that nearly 76% of the trips made on it yesterday were done by bike. Well done, San Francisco!</p>\n<p>The month of May is National Bike Month and we’re happy to see so many people participating in Bike to Work Day. Of course, we think everyday should be Bike to Work Day for anyone who works less than 5 miles from home. In the Bay Area alone, more than one million Bay Area residents live within five miles of their workplace.</p>\n<p>If you’re considering biking to daily as part of your commute, but not sure where to start, check out the San Francisco Bike Coalition for maps and tips and more. If you’re a bike-to-work regular or just getting into biking, drop us a line and let us know how your bike commute went yesterday</p>\n<p>Imagine how less congested our streets would be and how much healthier and happier people would be if more people made the choice to bicycle, walk, or take public transit to work.</p>\n<p>We’re lucky to live in San Francisco where bicycling is a mainstream activity and the majority of our local elected officials recognize the value of bicycling. This year, 9 out of 11 local elected Board of Supervisors, our Mayor, and our District Attorney all participated in Bike to Work Day with thousands of other residents. Bike to Work Day helps remind these elected officials to fully fund and prioritize initiatives like Connecting the City which creates safe and accessible bikeways for anyone from 8 to 80 years old.</p>\n<p><a class=\"_lbox\" href=\"{$website:url}media/news/original/13953451818_45d9ff2b81_h.jpg\" title=\"13953451818_45d9ff2b81_h\"><img src=\"{$website:url}media/news/large/13953451818_45d9ff2b81_h.jpg\" alt=\"13953451818_45d9ff2b81_h\" width=\"500\" height=\"331\" /></a></p>\n<p>We know cities can get more people to bicycle if they create separated bikeways like this new one on Polk Street near City Hall. It takes political will and funding to make these changes happen on our public streets.</p>\n<p>We encourage you to find out more about your local Bike to Work Day activities – and support your local and statewide bicycle advocacy organizations working to make bicycling better for all of us.</p>\n',0,1,0,0,'internal',0,'0000-00-00 00:00:00','','2014-05-23 03:15:35','2014-09-09 02:03:56',NULL,1),(17,78,'{\"h1\":\"WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY\",\"title\":\"WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY\",\"navName\":\"WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY\",\"url\":\"why-local-company-revolights-supports-bicycle-advocacy-1400840939.html\",\"teaserText\":\"Bicycling is booming in San Francisco. From the increase in ridership (96% since 2006) to the influx of new bike shops (three new ones on Market Street alone) to the plethora of bike startups launching from our City by the Bay, one thing is clear: San Francisco loves biking.\",\"metaKeywords\":\"WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY\",\"template\":\"news\",\"image\":\"why-local-company-revolights-supports-bicycle-advocacy-1400840939.png\"}','WHY LOCAL COMPANY REVOLIGHTS SUPPORTS BICYCLE ADVOCACY','Bicycling is booming in San Francisco. From the increase in ridership (96% since 2006) to the influx of new bike shops (three new ones on Market Street alone) to the plethora of bike startups launching from our City by the Bay, one thing is clear: San Francisco loves biking.','\n                    <p><img title=\"Canyon Speedmax AL\" src=\"{$website:url}media/news/original/drewnew.png\" alt=\"drewnew\" width=\"550\" height=\"367\" /></p>\n<p>Bicycling is booming in San Francisco. From the increase in ridership (96% since 2006) to the influx of new bike shops (three new ones on Market Street alone) to the plethora of bike startups launching from our City by the Bay, one thing is clear: San Francisco loves biking.</p>\n<p>We sat down to talk with one of these new bike startups,&nbsp;Revolights, about the tie-in between new bike companies and bicycle advocacy. Here&rsquo;s what San Francisco native and Revolights&rsquo; Chief Marketing Officer, Drew Ocon, had to say about the combo. Revolights is one of the panelists in&nbsp;tonight&rsquo;s Bike Month event, Crowdfunding the Bike Industry.&nbsp;</p>\n<p><strong>An incredible number of bike companies have launched in San Francisco and the Bay Area. Why do you think our area is ripe for such new bike innovation?<br /></strong>It is amazing! Last time I checked, about 25% of all bicycle related Kickstarter projects are based here in California and mainly in the Bay Area, which goes to show that both entrepreneurship and cycling run deep in our blood. Back in the early 1900&prime;s, the Golden Gate Park Polo Fields were home to the first west coast velodrome, and in the &rsquo;70&prime;s, you&rsquo;d see pioneers like Joe Breeze, Gary Fisher and Tom Ritchey riding bikes down Mount Tam.</p>\n<p><strong>Why does your company support the work of the SF Bicycle Coalition?<br /></strong>We have always admired and respected what the SFBC has done to lead the way in bicycle safety and infrastructure. We share an affinity for bike safety and legitimizing bicycle commuting as a form of daily transportation.</p>\n<p><strong>Thanks for the set of lights you gave to the winner of our Bike Commuter of the Year competition! The 2014 winner is&nbsp;Bao-Tran Ausman, a mom who pedals with her kids all around SF. Why do you think it&rsquo;s exciting to see more parents and kids biking?<br /></strong>There are so many amazing aspects of this situation (1) its great to see that the modern day minivan could be a cargo bike. Love to see families are able to remove cars from their lives and rely on bicycle transportation. (2) Teaching children the rules of the road in an urban setting makes for a more conscientious generation. (3) It just shows how far the SFBC has pushed the standards for infrastructure because if city cycling wasn&rsquo;t perceived safe, parents wouldn&rsquo;t feel comfortable toting their kids around town. All are great signs for our community.</p>\n<p><strong>When did you start riding in SF, and what&rsquo;s the biggest change you&rsquo;ve seen in SF biking since you started riding here?<br /></strong>Well I was born here in SF (yep, real San Franciscans do exist) and raised in the Outer Mission and then out to Walnut Creek so I have watched this biking community grow for the past 20 years. My earliest memories are learning to ride in Golden Gate Park when JFK Dr was closed to cars on Sundays &ndash; now a weekly staple in SF bike culture. The biggest change that has affected my daily life are the upgrades to&nbsp;Fell and Oak. Connecting The Wiggle to the Panhandle was a major win for the cycling community. Moving forward, I am excited to see SFBC&rsquo;s &ldquo;Vision Zero Goal&nbsp;(no cyclist or pedestrian deaths in five years) become a reality.</p><a class=\"tpopup generator-links\" data-pwidth=\"960\" data-pheight=\"560\" title=\"Click to edit  content\" href=\"javascript:;\" data-url=\"http://packet.dev/backend/backend_content/edit/id/153/containerType/1\"><img width=\"26\" height=\"26\" src=\"http://packet.dev/system/images/editadd-content.png\" alt=\"edit  content\" /></a>\n                    <a class=\"tpopup generator-links\" data-pwidth=\"960\" data-pheight=\"560\" title=\"Click to edit  content\" href=\"javascript:;\" data-url=\"http://packet.dev/backend/backend_content/add/containerType/1/containerName/content12/pageId/78\"><img width=\"26\" height=\"26\" src=\"http://packet.dev/system/images/editadd-content.png\" alt=\"edit  content\" /></a>\n                    <h2><span><a class=\"tpopup generator-links\" data-pwidth=\"600\" data-pheight=\"140\" title=\"Click to edit header content\" href=\"javascript:;\" data-url=\"http://packet.dev/backend/backend_content/add/containerType/3/containerName/content2/pageId/78\"><img width=\"26\" height=\"26\" src=\"http://packet.dev/system/images/editadd-header.png\" alt=\"edit header content\" /></a></span></h2>\n                    <a class=\"tpopup generator-links\" data-pwidth=\"960\" data-pheight=\"560\" title=\"Click to edit  content\" href=\"javascript:;\" data-url=\"http://packet.dev/backend/backend_content/add/containerType/1/containerName/content21/pageId/78\"><img width=\"26\" height=\"26\" src=\"http://packet.dev/system/images/editadd-content.png\" alt=\"edit  content\" /></a>\n                    <a class=\"tpopup generator-links\" data-pwidth=\"960\" data-pheight=\"560\" title=\"Click to edit  content\" href=\"javascript:;\" data-url=\"http://packet.dev/backend/backend_content/add/containerType/1/containerName/content22/pageId/78\"><img width=\"26\" height=\"26\" src=\"http://packet.dev/system/images/editadd-content.png\" alt=\"edit  content\" /></a>\n                    <h2><span><a class=\"tpopup generator-links\" data-pwidth=\"600\" data-pheight=\"140\" title=\"Click to edit header content\" href=\"javascript:;\" data-url=\"http://packet.dev/backend/backend_content/add/containerType/3/containerName/content3/pageId/78\"><img width=\"26\" height=\"26\" src=\"http://packet.dev/system/images/editadd-header.png\" alt=\"edit header content\" /></a></span></h2>\n                    <a class=\"tpopup generator-links\" data-pwidth=\"960\" data-pheight=\"560\" title=\"Click to edit  content\" href=\"javascript:;\" data-url=\"http://packet.dev/backend/backend_content/add/containerType/1/containerName/content31/pageId/78\"><img width=\"26\" height=\"26\" src=\"http://packet.dev/system/images/editadd-content.png\" alt=\"edit  content\" /></a>\n                    <a class=\"tpopup generator-links\" data-pwidth=\"960\" data-pheight=\"560\" title=\"Click to edit  content\" href=\"javascript:;\" data-url=\"http://packet.dev/backend/backend_content/add/containerType/1/containerName/content32/pageId/78\"><img width=\"26\" height=\"26\" src=\"http://packet.dev/system/images/editadd-content.png\" alt=\"edit  content\" /></a>\n                ',0,1,0,0,'internal',0,'0000-00-00 00:00:00','','2014-05-23 03:28:59','2014-10-08 14:19:24',NULL,1);
/*!40000 ALTER TABLE `plugin_newslog_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_newslog_news_has_tag`
--

DROP TABLE IF EXISTS `plugin_newslog_news_has_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_newslog_news_has_tag` (
  `news_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`news_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `plugin_newslog_news_has_tag_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `plugin_newslog_news` (`id`) ON DELETE CASCADE,
  CONSTRAINT `plugin_newslog_news_has_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `plugin_newslog_tag` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_newslog_news_has_tag`
--

LOCK TABLES `plugin_newslog_news_has_tag` WRITE;
/*!40000 ALTER TABLE `plugin_newslog_news_has_tag` DISABLE KEYS */;
INSERT INTO `plugin_newslog_news_has_tag` VALUES (16,11),(17,11),(16,17),(16,18),(17,19),(17,20);
/*!40000 ALTER TABLE `plugin_newslog_news_has_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_newslog_pingservice`
--

DROP TABLE IF EXISTS `plugin_newslog_pingservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_newslog_pingservice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('enabled','disabled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'disabled',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `default` (`is_default`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_newslog_pingservice`
--

LOCK TABLES `plugin_newslog_pingservice` WRITE;
/*!40000 ALTER TABLE `plugin_newslog_pingservice` DISABLE KEYS */;
INSERT INTO `plugin_newslog_pingservice` VALUES (3,'http://rpc.weblogs.com/RPC2','enabled',1),(4,'http://ping.blo.gs/','enabled',1),(5,'http://rpc.pingomatic.com/RPC2','enabled',1);
/*!40000 ALTER TABLE `plugin_newslog_pingservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_newslog_tag`
--

DROP TABLE IF EXISTS `plugin_newslog_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_newslog_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_newslog_tag`
--

LOCK TABLES `plugin_newslog_tag` WRITE;
/*!40000 ALTER TABLE `plugin_newslog_tag` DISABLE KEYS */;
INSERT INTO `plugin_newslog_tag` VALUES (11,'Bicycle','2014-05-23 08:52:20','2014-05-23 09:01:35'),(12,'Bianchi','2014-05-23 08:52:42','2014-05-23 09:01:57'),(13,'PUBLIC','2014-05-23 09:28:50','2014-05-23 09:38:05'),(14,'The Sleeper Bike','2014-05-23 09:44:05','2014-05-23 09:53:20'),(15,'Electric','2014-05-23 09:44:22','2014-05-23 09:53:37'),(17,'Bay Area','2014-05-23 10:15:12','2014-05-23 10:24:28'),(18,'Work Day','2014-05-23 10:15:26','2014-05-23 10:24:41'),(19,'Revolights','2014-05-23 10:28:40','2014-05-23 10:37:55'),(20,'Bike Month','2014-05-23 10:28:56','2014-05-23 10:38:11');
/*!40000 ALTER TABLE `plugin_newslog_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_paypal_settings`
--

DROP TABLE IF EXISTS `plugin_paypal_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_paypal_settings` (
  `id` int(10) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apiSignature` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apiUser` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apiPassword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `useSandbox` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_paypal_settings`
--

LOCK TABLES `plugin_paypal_settings` WRITE;
/*!40000 ALTER TABLE `plugin_paypal_settings` DISABLE KEYS */;
INSERT INTO `plugin_paypal_settings` VALUES (1,'','','','',0);
/*!40000 ALTER TABLE `plugin_paypal_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_paypal_transactions`
--

DROP TABLE IF EXISTS `plugin_paypal_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_paypal_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txnId` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payerId` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payerMail` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shippingAmount` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` text COLLATE utf8_unicode_ci,
  `paymentStatus` text COLLATE utf8_unicode_ci,
  `status` text COLLATE utf8_unicode_ci,
  `paymentType` text COLLATE utf8_unicode_ci,
  `paymentId` int(11) DEFAULT NULL,
  `paymentDate` timestamp NULL DEFAULT NULL,
  `pFirstName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pLastName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pCountry` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pCountryCode` text COLLATE utf8_unicode_ci,
  `pAddressState` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pAddressCity` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pAddressZip` text COLLATE utf8_unicode_ci,
  `pAddressName` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cartId` int(10) DEFAULT NULL,
  `pendingReason` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscribeStatus` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscribePeriod` int(10) DEFAULT NULL,
  `subscribePeriodType` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscribeQuantity` int(10) DEFAULT NULL,
  `subscribeAmount` decimal(10,4) DEFAULT NULL,
  `subscribeDate` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscriptionId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscriptionDatePayed` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscriptionAmountPayed` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailSent` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `customerEmailSent` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `refundTransactionId` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refundReason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_paypal_transactions`
--

LOCK TABLES `plugin_paypal_transactions` WRITE;
/*!40000 ALTER TABLE `plugin_paypal_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugin_paypal_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_promo`
--

DROP TABLE IF EXISTS `plugin_promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_promo` (
  `product_id` int(10) unsigned NOT NULL,
  `promo_price` decimal(10,2) NOT NULL,
  `promo_from` date DEFAULT NULL,
  `promo_due` date DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  CONSTRAINT `plugin_promo_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `shopping_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_promo`
--

LOCK TABLES `plugin_promo` WRITE;
/*!40000 ALTER TABLE `plugin_promo` DISABLE KEYS */;
INSERT INTO `plugin_promo` VALUES (1,3100.00,'2013-12-01','2018-12-17');
/*!40000 ALTER TABLE `plugin_promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugin_toastauth_settings`
--

DROP TABLE IF EXISTS `plugin_toastauth_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin_toastauth_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugin_toastauth_settings`
--

LOCK TABLES `plugin_toastauth_settings` WRITE;
/*!40000 ALTER TABLE `plugin_toastauth_settings` DISABLE KEYS */;
INSERT INTO `plugin_toastauth_settings` VALUES (1,'facebook','a:6:{s:9:\"client_id\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:12:\"redirect_uri\";s:0:\"\";s:5:\"scope\";s:5:\"email\";s:8:\"auth_url\";s:37:\"https://www.facebook.com/dialog/oauth\";s:9:\"token_url\";s:45:\"https://graph.facebook.com/oauth/access_token\";}',0),(2,'google','a:8:{s:9:\"client_id\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:12:\"redirect_uri\";s:0:\"\";s:5:\"scope\";s:5:\"email\";s:8:\"auth_url\";s:41:\"https://accounts.google.com/o/oauth2/auth\";s:9:\"token_url\";s:42:\"https://accounts.google.com/o/oauth2/token\";s:10:\"grant_type\";s:18:\"authorization_code\";s:13:\"response_type\";s:4:\"code\";}',0),(3,'linkedin','a:8:{s:13:\"response_type\";s:4:\"code\";s:9:\"client_id\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:5:\"scope\";s:29:\"r_basicprofile r_emailaddress\";s:12:\"redirect_uri\";s:60:\"http://auth.com/plugin/toastauth/run/login/provider/linkedin\";s:10:\"grant_type\";s:18:\"authorization_code\";s:9:\"token_url\";s:47:\"https://www.linkedin.com/uas/oauth2/accessToken\";s:8:\"auth_url\";s:49:\"https://www.linkedin.com/uas/oauth2/authorization\";}',0);
/*!40000 ALTER TABLE `plugin_toastauth_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `redirect`
--

DROP TABLE IF EXISTS `redirect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `redirect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned DEFAULT NULL,
  `from_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `domain_to` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `domain_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indPageId` (`page_id`),
  KEY `indFromUrl` (`from_url`),
  KEY `indToUrl` (`to_url`),
  CONSTRAINT `FK_redirect` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `redirect`
--

LOCK TABLES `redirect` WRITE;
/*!40000 ALTER TABLE `redirect` DISABLE KEYS */;
/*!40000 ALTER TABLE `redirect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_data`
--

DROP TABLE IF EXISTS `seo_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `seo_top` longtext COLLATE utf8_unicode_ci,
  `seo_bottom` longtext COLLATE utf8_unicode_ci,
  `seo_head` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_data`
--

LOCK TABLES `seo_data` WRITE;
/*!40000 ALTER TABLE `seo_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `seo_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_brands`
--

DROP TABLE IF EXISTS `shopping_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_brands`
--

LOCK TABLES `shopping_brands` WRITE;
/*!40000 ALTER TABLE `shopping_brands` DISABLE KEYS */;
INSERT INTO `shopping_brands` VALUES (1,'Santa Cruz'),(2,'Ragley'),(3,'Orca'),(4,'Canyon'),(5,'Giant');
/*!40000 ALTER TABLE `shopping_brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_cart_session`
--

DROP TABLE IF EXISTS `shopping_cart_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_cart_session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `referer` tinytext COLLATE utf8_unicode_ci COMMENT 'Referer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) unsigned DEFAULT NULL,
  `shipping_address_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_address_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_price` decimal(10,2) DEFAULT NULL,
  `shipping_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_tracking_id` tinytext COLLATE utf8_unicode_ci COMMENT 'Shipping Tracking ID',
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gateway` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount_tax_rate` enum('0','1','2','3') COLLATE utf8_unicode_ci DEFAULT '0',
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Sub Total',
  `shipping_tax` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Shipping Tax',
  `discount_tax` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Discount Tax',
  `sub_total_tax` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Sub total Tax',
  `total_tax` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Total Tax',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Sub Total + Total Tax + Shipping',
  `notes` text COLLATE utf8_unicode_ci COMMENT 'Comment for order',
  `discount` decimal(10,2) DEFAULT NULL COMMENT 'Order discount',
  `free_cart` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `refund_amount` decimal(10,2) DEFAULT NULL COMMENT 'Partial or full refund amount',
  `refund_notes` text COLLATE utf8_unicode_ci COMMENT 'Refund info',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `shipping_address_id` (`shipping_address_id`),
  KEY `billing_address_id` (`billing_address_id`),
  CONSTRAINT `shopping_cart_session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_cart_session`
--

LOCK TABLES `shopping_cart_session` WRITE;
/*!40000 ALTER TABLE `shopping_cart_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_cart_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_cart_session_content`
--

DROP TABLE IF EXISTS `shopping_cart_session_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_cart_session_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int(10) unsigned DEFAULT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  `options` text COLLATE utf8_unicode_ci,
  `price` decimal(10,4) DEFAULT NULL COMMENT 'Price w/o Tax',
  `qty` int(10) unsigned DEFAULT NULL,
  `tax` decimal(10,4) DEFAULT NULL COMMENT 'Tax Price',
  `tax_price` decimal(10,4) DEFAULT NULL COMMENT 'Price + Tax',
  `freebies` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`,`product_id`),
  CONSTRAINT `shopping_cart_session_content_ibfk_2` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart_session` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_cart_session_content`
--

LOCK TABLES `shopping_cart_session_content` WRITE;
/*!40000 ALTER TABLE `shopping_cart_session_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_cart_session_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_cart_session_has_recurring`
--

DROP TABLE IF EXISTS `shopping_cart_session_has_recurring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_cart_session_has_recurring` (
  `recurring_cart_id` int(10) unsigned NOT NULL COMMENT 'recurrent payment id',
  `cart_id` int(10) unsigned NOT NULL COMMENT 'dependent cart id to recurring payment',
  PRIMARY KEY (`recurring_cart_id`,`cart_id`),
  KEY `shopping_cart_session_has_recurring_ibfk_3` (`cart_id`),
  CONSTRAINT `shopping_cart_session_has_recurring_ibfk_2` FOREIGN KEY (`recurring_cart_id`) REFERENCES `shopping_cart_session` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `shopping_cart_session_has_recurring_ibfk_3` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart_session` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_cart_session_has_recurring`
--

LOCK TABLES `shopping_cart_session_has_recurring` WRITE;
/*!40000 ALTER TABLE `shopping_cart_session_has_recurring` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_cart_session_has_recurring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_config`
--

DROP TABLE IF EXISTS `shopping_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_config` (
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_config`
--

LOCK TABLES `shopping_config` WRITE;
/*!40000 ALTER TABLE `shopping_config` DISABLE KEYS */;
INSERT INTO `shopping_config` VALUES ('action','fireaction'),('address1','827 Shrader St.'),('address2','Suite 400'),('autoQuote','0'),('cartPlugin','cart'),('city','San Francisco'),('company','Demo Store'),('controller','backend_plugin'),('country','US'),('currency','USD'),('email','contact@seotoaster.com'),('expirationDelay',''),('forceSSLCheckout','0'),('name','quote'),('noZeroPrice','1'),('phone','415 899 3455'),('quoteTemplate','quote'),('run','settings'),('showPriceIncTax','1'),('state','5'),('version','2.5.0'),('weightUnit','kg'),('zip','94117');
/*!40000 ALTER TABLE `shopping_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_coupon`
--

DROP TABLE IF EXISTS `shopping_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Coupon ID',
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Coupon code',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'discount' COMMENT 'Coupon discount type',
  `scope` enum('order','client') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Coupon usage scope',
  `startDate` date DEFAULT NULL COMMENT 'Coupon start date',
  `endDate` date DEFAULT NULL COMMENT 'Coupon expire date',
  `allowCombination` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Allow combination with other coupons',
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `type` (`type`),
  CONSTRAINT `shopping_coupon_ibfk_1` FOREIGN KEY (`type`) REFERENCES `shopping_coupon_type` (`type`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_coupon`
--

LOCK TABLES `shopping_coupon` WRITE;
/*!40000 ALTER TABLE `shopping_coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_coupon_discount`
--

DROP TABLE IF EXISTS `shopping_coupon_discount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_coupon_discount` (
  `coupon_id` int(10) unsigned NOT NULL COMMENT 'Coupon ID',
  `minOrderAmount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Allow combination with other coupons',
  `discountAmount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Coupon discount amount',
  `discountUnits` enum('unit','percent') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unit' COMMENT 'Coupon discount units',
  PRIMARY KEY (`coupon_id`),
  CONSTRAINT `shopping_coupon_discount_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `shopping_coupon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_coupon_discount`
--

LOCK TABLES `shopping_coupon_discount` WRITE;
/*!40000 ALTER TABLE `shopping_coupon_discount` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_coupon_discount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_coupon_freeshipping`
--

DROP TABLE IF EXISTS `shopping_coupon_freeshipping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_coupon_freeshipping` (
  `coupon_id` int(10) unsigned NOT NULL COMMENT 'Coupon ID',
  `minOrderAmount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Minimal order amount',
  PRIMARY KEY (`coupon_id`),
  CONSTRAINT `shopping_coupon_freeshipping_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `shopping_coupon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_coupon_freeshipping`
--

LOCK TABLES `shopping_coupon_freeshipping` WRITE;
/*!40000 ALTER TABLE `shopping_coupon_freeshipping` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_coupon_freeshipping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_coupon_product`
--

DROP TABLE IF EXISTS `shopping_coupon_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_coupon_product` (
  `coupon_id` int(10) unsigned NOT NULL COMMENT 'Coupon ID',
  `product_id` int(10) unsigned NOT NULL COMMENT 'Product ID',
  PRIMARY KEY (`coupon_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `shopping_coupon_product_ibfk_3` FOREIGN KEY (`coupon_id`) REFERENCES `shopping_coupon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shopping_coupon_product_ibfk_4` FOREIGN KEY (`product_id`) REFERENCES `shopping_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_coupon_product`
--

LOCK TABLES `shopping_coupon_product` WRITE;
/*!40000 ALTER TABLE `shopping_coupon_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_coupon_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_coupon_sales`
--

DROP TABLE IF EXISTS `shopping_coupon_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_coupon_sales` (
  `coupon_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Coupon code',
  `cart_id` int(10) unsigned NOT NULL COMMENT 'Cart Id',
  PRIMARY KEY (`coupon_code`,`cart_id`),
  KEY `cart_id` (`cart_id`),
  CONSTRAINT `shopping_coupon_sales_ibfk_3` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart_session` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_coupon_sales`
--

LOCK TABLES `shopping_coupon_sales` WRITE;
/*!40000 ALTER TABLE `shopping_coupon_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_coupon_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_coupon_type`
--

DROP TABLE IF EXISTS `shopping_coupon_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_coupon_type` (
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `label` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_coupon_type`
--

LOCK TABLES `shopping_coupon_type` WRITE;
/*!40000 ALTER TABLE `shopping_coupon_type` DISABLE KEYS */;
INSERT INTO `shopping_coupon_type` VALUES ('discount','Discount with min. order'),('freeshipping','Free shipping with min. order');
/*!40000 ALTER TABLE `shopping_coupon_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_coupon_usage`
--

DROP TABLE IF EXISTS `shopping_coupon_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_coupon_usage` (
  `coupon_id` int(10) unsigned NOT NULL COMMENT 'Coupon ID',
  `cart_id` int(10) unsigned NOT NULL COMMENT 'Customer ID',
  PRIMARY KEY (`coupon_id`,`cart_id`),
  KEY `cart_id` (`cart_id`),
  CONSTRAINT `shopping_coupon_usage_ibfk_4` FOREIGN KEY (`coupon_id`) REFERENCES `shopping_coupon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shopping_coupon_usage_ibfk_5` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_coupon_usage`
--

LOCK TABLES `shopping_coupon_usage` WRITE;
/*!40000 ALTER TABLE `shopping_coupon_usage` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_coupon_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_customer_address`
--

DROP TABLE IF EXISTS `shopping_customer_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_customer_address` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `address_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobilecountrycode` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Contains mobile phone country code',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `state` (`state`),
  CONSTRAINT `shopping_customer_address_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_customer_address`
--

LOCK TABLES `shopping_customer_address` WRITE;
/*!40000 ALTER TABLE `shopping_customer_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_customer_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_customer_info`
--

DROP TABLE IF EXISTS `shopping_customer_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_customer_info` (
  `user_id` int(10) unsigned NOT NULL,
  `default_shipping_address_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_billing_address_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `shopping_customer_info_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_customer_info`
--

LOCK TABLES `shopping_customer_info` WRITE;
/*!40000 ALTER TABLE `shopping_customer_info` DISABLE KEYS */;
INSERT INTO `shopping_customer_info` VALUES (9,NULL,NULL,NULL),(10,NULL,NULL,NULL),(11,NULL,NULL,NULL),(12,NULL,NULL,NULL);
/*!40000 ALTER TABLE `shopping_customer_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_filtering_attributes`
--

DROP TABLE IF EXISTS `shopping_filtering_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_filtering_attributes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Attribute ID',
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Attribute Name',
  `label` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Attribute Label',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_filtering_attributes`
--

LOCK TABLES `shopping_filtering_attributes` WRITE;
/*!40000 ALTER TABLE `shopping_filtering_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_filtering_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_filtering_tags_has_attributes`
--

DROP TABLE IF EXISTS `shopping_filtering_tags_has_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_filtering_tags_has_attributes` (
  `tag_id` int(10) unsigned NOT NULL,
  `attribute_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tag_id`,`attribute_id`),
  KEY `attribute_id` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_filtering_tags_has_attributes`
--

LOCK TABLES `shopping_filtering_tags_has_attributes` WRITE;
/*!40000 ALTER TABLE `shopping_filtering_tags_has_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_filtering_tags_has_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_filtering_values`
--

DROP TABLE IF EXISTS `shopping_filtering_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_filtering_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL COMMENT 'Product ID',
  `attribute_id` int(10) unsigned NOT NULL COMMENT 'Attribute ID',
  `value` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Attribute Value',
  PRIMARY KEY (`id`),
  UNIQUE KEY `attribute_id_2` (`attribute_id`,`product_id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_filtering_values`
--

LOCK TABLES `shopping_filtering_values` WRITE;
/*!40000 ALTER TABLE `shopping_filtering_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_filtering_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_filtering_widget_settings`
--

DROP TABLE IF EXISTS `shopping_filtering_widget_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_filtering_widget_settings` (
  `filter_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Filter ID',
  `settings` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Widget Settings',
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_filtering_widget_settings`
--

LOCK TABLES `shopping_filtering_widget_settings` WRITE;
/*!40000 ALTER TABLE `shopping_filtering_widget_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_filtering_widget_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_group`
--

DROP TABLE IF EXISTS `shopping_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priceSign` enum('plus','minus') COLLATE utf8_unicode_ci DEFAULT NULL,
  `priceType` enum('percent','unit') COLLATE utf8_unicode_ci DEFAULT NULL,
  `priceValue` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_group`
--

LOCK TABLES `shopping_group` WRITE;
/*!40000 ALTER TABLE `shopping_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_group_price`
--

DROP TABLE IF EXISTS `shopping_group_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_group_price` (
  `groupId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `priceValue` decimal(10,2) DEFAULT NULL,
  `priceSign` enum('plus','minus') COLLATE utf8_unicode_ci DEFAULT NULL,
  `priceType` enum('percent','unit') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`groupId`,`productId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_group_price`
--

LOCK TABLES `shopping_group_price` WRITE;
/*!40000 ALTER TABLE `shopping_group_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_group_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_import_orders`
--

DROP TABLE IF EXISTS `shopping_import_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_import_orders` (
  `real_order_id` int(10) unsigned NOT NULL,
  `import_order_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`real_order_id`,`import_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_import_orders`
--

LOCK TABLES `shopping_import_orders` WRITE;
/*!40000 ALTER TABLE `shopping_import_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_import_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_list_country`
--

DROP TABLE IF EXISTS `shopping_list_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_list_country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_list_country`
--

LOCK TABLES `shopping_list_country` WRITE;
/*!40000 ALTER TABLE `shopping_list_country` DISABLE KEYS */;
INSERT INTO `shopping_list_country` VALUES (1,'AD'),(2,'AE'),(3,'AF'),(4,'AG'),(5,'AI'),(6,'AL'),(7,'AM'),(8,'AN'),(9,'AO'),(10,'AQ'),(11,'AR'),(12,'AS'),(13,'AT'),(14,'AU'),(15,'AW'),(16,'AX'),(17,'AZ'),(18,'BA'),(19,'BB'),(20,'BD'),(21,'BE'),(22,'BF'),(23,'BG'),(24,'BH'),(25,'BI'),(26,'BJ'),(27,'BL'),(28,'BM'),(29,'BN'),(30,'BO'),(31,'BQ'),(32,'BR'),(33,'BS'),(34,'BT'),(35,'BV'),(36,'BW'),(37,'BY'),(38,'BZ'),(39,'CA'),(40,'CC'),(41,'CD'),(42,'CF'),(43,'CG'),(44,'CH'),(45,'CI'),(46,'CK'),(47,'CL'),(48,'CM'),(49,'CN'),(50,'CO'),(51,'CR'),(52,'CS'),(53,'CT'),(54,'CU'),(55,'CV'),(56,'CX'),(57,'CY'),(58,'CZ'),(59,'DD'),(60,'DE'),(61,'DJ'),(62,'DK'),(63,'DM'),(64,'DO'),(65,'DZ'),(66,'EC'),(67,'EE'),(68,'EG'),(69,'EH'),(70,'ER'),(71,'ES'),(72,'ET'),(73,'FI'),(74,'FJ'),(75,'FK'),(76,'FM'),(77,'FO'),(78,'FQ'),(79,'FR'),(80,'FX'),(81,'GA'),(82,'GB'),(83,'GD'),(84,'GE'),(85,'GF'),(86,'GG'),(87,'GH'),(88,'GI'),(89,'GL'),(90,'GM'),(91,'GN'),(92,'GP'),(93,'GQ'),(94,'GR'),(95,'GS'),(96,'GT'),(97,'GU'),(98,'GW'),(99,'GY'),(100,'HK'),(101,'HM'),(102,'HN'),(103,'HR'),(104,'HT'),(105,'HU'),(106,'ID'),(107,'IE'),(108,'IL'),(109,'IM'),(110,'IN'),(111,'IO'),(112,'IQ'),(113,'IR'),(114,'IS'),(115,'IT'),(116,'JE'),(117,'JM'),(118,'JO'),(119,'JP'),(120,'JT'),(121,'KE'),(122,'KG'),(123,'KH'),(124,'KI'),(125,'KM'),(126,'KN'),(127,'KP'),(128,'KR'),(129,'KW'),(130,'KY'),(131,'KZ'),(132,'LA'),(133,'LB'),(134,'LC'),(135,'LI'),(136,'LK'),(137,'LR'),(138,'LS'),(139,'LT'),(140,'LU'),(141,'LV'),(142,'LY'),(143,'MA'),(144,'MC'),(145,'MD'),(146,'ME'),(147,'MF'),(148,'MG'),(149,'MH'),(150,'MI'),(151,'MK'),(152,'ML'),(153,'MM'),(154,'MN'),(155,'MO'),(156,'MP'),(157,'MQ'),(158,'MR'),(159,'MS'),(160,'MT'),(161,'MU'),(162,'MV'),(163,'MW'),(164,'MX'),(165,'MY'),(166,'MZ'),(167,'NA'),(168,'NC'),(169,'NE'),(170,'NF'),(171,'NG'),(172,'NI'),(173,'NL'),(174,'NO'),(175,'NP'),(176,'NQ'),(177,'NR'),(178,'NT'),(179,'NU'),(180,'NZ'),(181,'OM'),(182,'PA'),(183,'PC'),(184,'PE'),(185,'PF'),(186,'PG'),(187,'PH'),(188,'PK'),(189,'PL'),(190,'PM'),(191,'PN'),(192,'PR'),(193,'PS'),(194,'PT'),(195,'PU'),(196,'PW'),(197,'PY'),(198,'PZ'),(199,'QA'),(200,'RE'),(201,'RO'),(202,'RS'),(203,'RU'),(204,'RW'),(205,'SA'),(206,'SB'),(207,'SC'),(208,'SD'),(209,'SE'),(210,'SG'),(211,'SH'),(212,'SI'),(213,'SJ'),(214,'SK'),(215,'SL'),(216,'SM'),(217,'SN'),(218,'SO'),(219,'SR'),(220,'ST'),(221,'SV'),(222,'SY'),(223,'SZ'),(224,'TC'),(225,'TD'),(226,'TF'),(227,'TG'),(228,'TH'),(229,'TJ'),(230,'TK'),(231,'TL'),(232,'TM'),(233,'TN'),(234,'TO'),(235,'TR'),(236,'TT'),(237,'TV'),(238,'TW'),(239,'TZ'),(240,'UA'),(241,'UG'),(242,'UM'),(243,'US'),(244,'UY'),(245,'UZ'),(246,'VA'),(247,'VC'),(248,'VD'),(249,'VE'),(250,'VG'),(251,'VI'),(252,'VN'),(253,'VU'),(254,'WF'),(255,'WK'),(256,'WS'),(257,'YD'),(258,'YE'),(259,'YT'),(260,'ZA'),(261,'ZM'),(262,'ZW');
/*!40000 ALTER TABLE `shopping_list_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_list_state`
--

DROP TABLE IF EXISTS `shopping_list_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_list_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_list_state`
--

LOCK TABLES `shopping_list_state` WRITE;
/*!40000 ALTER TABLE `shopping_list_state` DISABLE KEYS */;
INSERT INTO `shopping_list_state` VALUES (1,'US','AL','Alabama'),(2,'US','AK','Alaska'),(3,'US','AZ','Arizona'),(4,'US','AR','Arkansas'),(5,'US','CA','California'),(6,'US','CO','Colorado'),(7,'US','CT','Connecticut'),(8,'US','DE','Delaware'),(9,'US','DC','District Of Columbia'),(10,'US','FL','Florida'),(11,'US','GA','Georgia'),(12,'US','HI','Hawaii'),(13,'US','ID','Idaho'),(14,'US','IL','Illinois'),(15,'US','IN','Indiana'),(16,'US','IA','Iowa'),(17,'US','KS','Kansas'),(18,'US','KY','Kentucky'),(19,'US','LA','Louisiana'),(20,'US','ME','Maine'),(21,'US','MD','Maryland'),(22,'US','MA','Massachusetts'),(23,'US','MI','Michigan'),(24,'US','MN','Minnesota'),(25,'US','MS','Mississippi'),(26,'US','MO','Missouri'),(27,'US','MT','Montana'),(28,'US','NE','Nebraska'),(29,'US','NV','Nevada'),(30,'US','NH','New Hampshire'),(31,'US','NJ','New Jersey'),(32,'US','NM','New Mexico'),(33,'US','NY','New York'),(34,'US','NC','North Carolina'),(35,'US','ND','North Dakota'),(36,'US','OH','Ohio'),(37,'US','OK','Oklahoma'),(38,'US','OR','Oregon'),(39,'US','PA','Pennsylvania'),(40,'US','RI','Rhode Island'),(41,'US','SC','South Carolina'),(42,'US','SD','South Dakota'),(43,'US','TN','Tennessee'),(44,'US','TX','Texas'),(45,'US','UT','Utah'),(46,'US','VT','Vermont'),(47,'US','VA','Virginia'),(48,'US','WA','Washington'),(49,'US','WV','West Virginia'),(50,'US','WI','Wisconsin'),(51,'US','WY','Wyoming'),(52,'CA','AB','Alberta'),(53,'CA','BC','British Columbia'),(54,'CA','MB','Manitoba'),(55,'CA','NB','New Brunswick'),(56,'CA','NF','Newfoundland and Labrador'),(57,'CA','NT','Northwest Territories'),(58,'CA','NS','Nova Scotia'),(59,'CA','NU','Nunavut'),(60,'CA','ON','Ontario'),(61,'CA','PE','Prince Edward Island'),(62,'CA','QC','Quebec'),(63,'CA','SK','Saskatchewan'),(64,'CA','YT','Yukon Territory'),(65,'AU','ACT','Australian Capital Territory'),(66,'AU','NSW','New South Wales'),(67,'AU','NT','Northern Territory'),(68,'AU','QLD','Queensland'),(69,'AU','SA','South Australia'),(70,'AU','TAS','Tasmania'),(71,'AU','VIC','Victoria'),(72,'AU','WA','Western Australia');
/*!40000 ALTER TABLE `shopping_list_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_pickup_location`
--

DROP TABLE IF EXISTS `shopping_pickup_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_pickup_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `working_hours` text COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `location_category_id` int(10) unsigned NOT NULL,
  `lat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lng` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `weight` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country` (`country`),
  KEY `city` (`city`),
  KEY `country_city` (`city`,`country`),
  KEY `location_category_id` (`location_category_id`),
  CONSTRAINT `shopping_pickup_location_ibfk_1` FOREIGN KEY (`location_category_id`) REFERENCES `shopping_pickup_location_category` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_pickup_location`
--

LOCK TABLES `shopping_pickup_location` WRITE;
/*!40000 ALTER TABLE `shopping_pickup_location` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_pickup_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_pickup_location_cart`
--

DROP TABLE IF EXISTS `shopping_pickup_location_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_pickup_location_cart` (
  `cart_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `working_hours` text COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `location_category_id` int(10) unsigned NOT NULL,
  `lat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lng` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_pickup_location_cart`
--

LOCK TABLES `shopping_pickup_location_cart` WRITE;
/*!40000 ALTER TABLE `shopping_pickup_location_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_pickup_location_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_pickup_location_category`
--

DROP TABLE IF EXISTS `shopping_pickup_location_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_pickup_location_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `external_category` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_pickup_location_category`
--

LOCK TABLES `shopping_pickup_location_category` WRITE;
/*!40000 ALTER TABLE `shopping_pickup_location_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_pickup_location_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_pickup_location_config`
--

DROP TABLE IF EXISTS `shopping_pickup_location_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_pickup_location_config` (
  `id` int(10) unsigned NOT NULL,
  `amount_type_limit` enum('up to','over','eachover') COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount_limit` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_pickup_location_config`
--

LOCK TABLES `shopping_pickup_location_config` WRITE;
/*!40000 ALTER TABLE `shopping_pickup_location_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_pickup_location_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_pickup_location_zones`
--

DROP TABLE IF EXISTS `shopping_pickup_location_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_pickup_location_zones` (
  `config_id` int(10) unsigned NOT NULL,
  `pickup_location_category_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount_location_category` decimal(10,2) DEFAULT NULL,
  `config_zone_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`config_id`,`config_zone_id`),
  CONSTRAINT `shopping_pickup_location_zones_ibfk_1` FOREIGN KEY (`config_id`) REFERENCES `shopping_pickup_location_config` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_pickup_location_zones`
--

LOCK TABLES `shopping_pickup_location_zones` WRITE;
/*!40000 ALTER TABLE `shopping_pickup_location_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_pickup_location_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product`
--

DROP TABLE IF EXISTS `shopping_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `page_id` int(10) unsigned DEFAULT NULL,
  `enabled` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `sku` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `mpn` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` decimal(8,3) DEFAULT NULL,
  `brand_id` int(10) unsigned DEFAULT NULL,
  `photo` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `short_description` mediumtext COLLATE utf8_unicode_ci,
  `full_description` text COLLATE utf8_unicode_ci,
  `price` decimal(10,4) DEFAULT NULL,
  `tax_class` enum('0','1','2','3') COLLATE utf8_unicode_ci DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `base_price` decimal(10,2) DEFAULT NULL,
  `inventory` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `free_shipping` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `page_id` (`page_id`),
  KEY `brand_id` (`brand_id`),
  CONSTRAINT `shopping_product_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product`
--

LOCK TABLES `shopping_product` WRITE;
/*!40000 ALTER TABLE `shopping_product` DISABLE KEYS */;
INSERT INTO `shopping_product` VALUES (1,NULL,20,'1','1','Santa Cruz Tallboy LT Carbon test','Tallboy LT',1.250,1,'Mountain-bikes/Santa Cruz Tallboy LT Carbon.jpg','The Santa Cruz Tallboy LT Carbon XTR AM 29er Full Suspension Mountain Bike 2013 offers all the same features as the Tallboy LT but with an added touch of carbon fibre.','The Santa Cruz Tallboy LT Carbon XTR AM 29er Full Suspension Mountain Bike 2013 offers all the same features as the Tallboy LT but with an added touch of carbon fibre. The results are phenomenal as this frame comes equipped with a Fox Float CTD rear shock, 135mm travel, VPP suspension, 29\" wheels, 142x12mm rear axle, ISCG05 mounts, plenty of beef where is counts, and it only weighs 5.35lbs. That is not a typo, just a insanely lightweight frame that every rider can appreciate.',3300.0000,'1','2013-12-24 13:11:10','2014-07-08 10:45:48',NULL,NULL,'1'),(2,NULL,23,'1','2','Santa Cruz Superlight XT XC','XT XC',NULL,1,'Mountain-bikes/Santa Cruz Superlight XT XC.jpg','The Santa Cruz Superlight XT XC Full Suspension Mountain Bike 2013 was created with performance and value aspects in mind. The Superlight was built to offer a winning blend of nimble geometry, confidence inspiring handling, and elegantly simple yet effective suspension, to make one of the most sought-after bikes on the market','The Santa Cruz Superlight XT XC Full Suspension Mountain Bike 2013 was created with performance and value aspects in mind. The Superlight was built to offer a winning blend of nimble geometry, confidence inspiring handling, and elegantly simple yet effective suspension, to make one of the most sought-after bikes on the market',3150.7500,'1','2013-12-24 16:04:33','2014-05-26 04:21:19',NULL,NULL,'0'),(3,NULL,24,'1','3','Santa Cruz Superlight 29er XT XC','XT XC',NULL,1,'Mountain-bikes/Santa Cruz Superlight 29er XT XC.jpg','The Santa Cruz Superlight 29er XT XC Full Suspension Mountain Bike 2013 combines the proven value and performance of the legendary Superlight with the smooth rolling stability of bigger 29\" wheels. Santa Cruz have made a 29er that\'s super light, made from custom hydroformed aluminium tubing, a reliable, efficient, responsive single pivot suspension design that offers 100mm of travel, terrain eating 29\" wheels and kickass value for money.','The Santa Cruz Superlight 29er XT XC Full Suspension Mountain Bike 2013 combines the proven value and performance of the legendary Superlight with the smooth rolling stability of bigger 29\" wheels. Santa Cruz have made a 29er that\'s super light, made from custom hydroformed aluminium tubing, a reliable, efficient, responsive single pivot suspension design that offers 100mm of travel, terrain eating 29\" wheels and kickass value for money.',0.0000,'1','2013-12-24 16:05:22','2014-05-26 04:24:07',NULL,NULL,'1'),(4,NULL,25,'1','4','Ragley M74 Hardtail','M74 Hardtail',NULL,2,'Mountain-bikes/Ragley M74 Hardtail.jpg','New for 2013 Ragley introduce the M74 complete bike. Following the success of our highly acclaimed steel hardtail bikes we have delved into the world of aluminium tubed framesets to produce the M74. With a spec list that includes RockShox Recon forks, Avid Elixir brakes, SRAM X5 shifting, Ragley bars, stem and post with a single ring and chainguide set up the M74 is ready to tackle any trail situation','New for 2013 Ragley introduce the M74 complete bike. Following the success of our highly acclaimed steel hardtail bikes we have delved into the world of aluminium tubed framesets to produce the M74. With a spec list that includes RockShox Recon forks, Avid Elixir brakes, SRAM X5 shifting, Ragley bars, stem and post with a single ring and chainguide set up the M74 is ready to tackle any trail situation',2000.2000,'1','2013-12-25 11:15:06','2014-05-22 11:15:51',NULL,NULL,'0'),(5,NULL,26,'1','5','Santa Cruz Highball R XC 29er','R XC 29er',NULL,1,'Mountain-bikes/Santa Cruz Highball R XC 29er.jpg','The Santa Cruz Highball R XC 29er Hardtail Mountain Bike 2013 takes all the swift, light elements from the 29\" wheeled Carbon Highball, and crafts them together in an aluminium frame. This bike has a number of other features too, a couple of the most notable being versatility and value. The proprietary aluminum tubing allows for a strong, respectably light frame, and features the same responsive geometry as our carbon race bike.','The Santa Cruz Highball R XC 29er Hardtail Mountain Bike 2013 takes all the swift, light elements from the 29\" wheeled Carbon Highball, and crafts them together in an aluminium frame. This bike has a number of other features too, a couple of the most notable being versatility and value. The proprietary aluminum tubing allows for a strong, respectably light frame, and features the same responsive geometry as our carbon race bike.',2500.6400,'1','2013-12-25 11:15:46','2014-05-22 11:17:10',NULL,NULL,'0'),(6,NULL,47,'1','6','Orbea Orca M22','6',5.300,3,'Orbea/b111ttcc-p3-side-orcam22-1453-20130718164035.jpg','With industry leading lightness, the SRAM Red 22 components are a perfect match for the newest version of our lightweight, carbon fiber Orca bicycle.','Together they form a race-worthy package that ensures top performance and quick shifts in any situation. We were among the first bike companies to offer complete SRAM road groups, and their latest efforts are nothing less than superb. The Orca M22 is a bike that is bred for road racing. It has the lightness you want in the mountains, the aerodynamic efficiency you need in a breakaway, and the durability for heavy training. Treat yourself to the very best.',10000.0000,'1','2014-03-19 09:23:32','2014-03-19 09:23:50',NULL,'5','1'),(7,NULL,48,'1','7','Orbea Orca M10','7',5.200,3,'Orbea/b107ttcc-xt-side-orcabm10-20130822093522.jpg','The Orca Bronze M10 is our top-notch Orca Bronze bicycle','Part of its magic is Shimano’s Ultegra Di2 shifting components. However, the Bronze level carbon fiber frame shares more than good looks with the Orca OMR and OMP road bikes; the ride is superb and tames coarse roads for more comfort. And as we’ve said before, the feel and function of the Ultegra Di2 components are so closely matched to their Dura-Ace counterparts that they give away nothing in terms of performance. We finish the Orca Bronze M10 with a smart blend of components that balance affordability, performance, and durability.',4500.0000,'1','2014-03-19 09:32:19','2014-03-19 09:32:32',NULL,'7','1'),(8,NULL,49,'1','8','Canyon ULTIMATE CF SLX 7.0','8',6.000,4,'Canyon/ultimate-cf-slx-7_c1024.png','A perfect blend of sportiness, stiffness and light weight – this frame, weighing just 790 g, will set pulses racing with its integrated cables and conical head tube.','The Ultimate CF SLX has incredible frame stiffness to unleash the thrust, and its stiff 100% carbon fibre One One Four SLX race fork delivers direct, razor sharp steering.. You will always be able to rely on Shimano\'s next-generation Ultegra groupset.',3199.0000,'1','2014-03-19 09:48:33','2014-03-19 09:48:48',NULL,'6','0'),(11,NULL,52,'1','11','Canyon Torque-DHX','11',15.200,4,'Canyon/ultimate-cf-slx-7_c1024.png','For years the Torque FRX combined the ultimate in downhill performance with maximum versatility.',' Its successor, the Torque DHX, is a bike for the new model year and has enhanced kinematics for more efficient power transfer, more responsiveness and more travel, making it ideal for all-day downhill and bike park action. The Torque DHX frame has been completely revised. New tube shapes and the deep drawn top tube ensure more standover height and therefore more rider manoeuvrability.',3599.0000,'1','2014-03-19 10:40:29','2014-03-20 05:23:34',NULL,NULL,'0'),(13,NULL,54,'1','13','Giant Glory 0','13',13.000,5,'Canyon/glory0.jpg','At the core of this competition-oriented downhill flyer is an impressively light, strong and agile frameset','The radically hydroformed tubeset utilizes the latest, lightweight co-pivot design to shed grams while maintaining outstanding stiffness and durability',6200.0000,'1','2014-03-19 10:57:55','2014-03-19 11:11:29',NULL,NULL,'0'),(14,NULL,55,'1','14','Canyon Glory 2','14',16.000,4,'Canyon/glory_2.jpg','At the core of this competition-oriented downhill flyer is an impressively light, strong and agile frameset. The radically hydroformed tubeset utilizes the latest, lightweight co-pivot design to shed grams while maintaining outstanding stiffness and durability','At the core of this competition-oriented downhill flyer is an impressively light, strong and agile frameset. The radically hydroformed tubeset utilizes the latest, lightweight co-pivot design to shed grams while maintaining outstanding stiffness and durability',6300.0000,'1','2014-03-19 11:14:16','2014-03-19 11:14:36',NULL,NULL,'0'),(15,NULL,56,'1','15','Orbea Occam 29 H20','15',13.000,3,'Orbea/b256ttcc-d8-side-occam29h20.jpg','Our Occam 29 H20 is purpose built for adventure.','The tubes in the Orbea Hydroformed aluminum frame are shaped to deliver strength and stiffness in a lightweight, high performing package, and we pair the frame with a full complement of Shimano drivetrain components. The SLX shifter/derailleur combination gets an upgrade to an XT Shadow Plus rear derailleur for quick and predictable shifts as well as greater security for the chain over rough trails. Speaking of control, the H20 is outfitted with Fox CTD Trail Adjust dampers at both ends to smooth out the bumps in the trail. The Occam 29 H20 is an essential tool for your adventure.',4199.0000,'1','2014-03-19 11:18:12','2014-03-19 11:18:22',NULL,NULL,'0'),(16,NULL,57,'1','16','Orbea Ordu M-ltd','16',5.000,3,'Orbea/b130ttcc-a1-side-ordumltd-14m.jpg','The Ordu is the bike of choice for the Euskaltel-Euskadi pros, and it’s the bike that Andrew Starykowicz uses to punish his triathlon rivals. In fact, it’s the bike he used to set the current world records for Ironman and Ironman 70.3 bike splits.','With its striking aesthetic and sleek aerodynamic shape, it’s best when paired with Shimano’s Dura-Ace Di2 electronic group. The Ordu M LTD features shifter buttons at the brake levers and at the aero extensions. You’ll have the ability to make split second gear changes, no matter your position or the terrain. This is an advantage you’ll get with our top level Ordu. Are you ready for some new PRs this season?',15000.2990,'1','2014-03-19 11:42:32','2014-05-22 11:18:20',NULL,NULL,'0'),(17,NULL,58,'1','17','Orbea Ordu M30','17',NULL,3,'Orbea/b127ttcc-a2-side-ordum30-14m.jpg','Sometimes brutal simplicity is what you need for success. ','That’s the undercurrent for the build on the Ordu M30 – simple and effective, like a good sharp knife. We pair the high modulus carbon fiber Ordu OMR frameset with Shimano Ultegra 11-speed components. Ultegra is the self-sponsored racer’s choice, and it’s been that way for years. The cabling for the shifting and rear brake is run completely inside the frame, for a clean aesthetic without disturbing the sleek aerodynamic tube shaping. It looks fast, and it is. Andrew Starykowicz used the Ordu to set the current bike split world records for Ironman and Ironman 70.3 distances.',6099.0000,'1','2014-03-19 11:46:10','2014-03-19 11:46:19',NULL,NULL,'0'),(18,NULL,59,'1','18','Canyon Speedmax CF','18',5.200,4,'Canyon/speedmax-cf-tria-1.png','100% triathlon: The special triathlon stem is significantly higher than the time trial version, and spacers can be used to raise it up to 38 mm. ','The \"flat\" handlebar makes it easy to find a comfortable position on the lower bar even in extended training sessions, and the L-bend extensions put the wrists into a comfortable position for long distances. The exceptionally clear and distinctive design has also been a hit in the design world, and the Speedmax CF has won many prestigious awards – the red dot award \"best of the best\", the \"Designpreis der Bundesrepublik Deutschland\", the Eurobike Award, the good design award and the German Design Award in the category \"public & transportation\". ',5899.0000,'1','2014-03-19 11:51:13','2014-03-19 11:51:23',NULL,NULL,'0'),(19,NULL,60,'1','19','Canyon Speedmax AL','19',NULL,4,'Canyon/speedmax-al-01-perspective.png','This year you can pound your fastest races times into the tarmac with the aluminium model – for less than EUR 1500.','The design of the Speedmax AL frame is all about reducing air resistance: the wind does not stand a chance against the flat tube profiles, lowered rear triangle, smoothed weld seams and a narrow front. As you would expect, the cables are concealed within the frame. One aerodynamic highlight is the Aero Fork CF with carbon fibre blades, specially developed for the Speedmax AL. The narrow fork legs are the first point of contact with the wind, consistently directing the air flow into the overall system and making an important contribution to the outstanding aerodynamic characteristics.',2499.0000,'1','2014-03-19 11:53:39','2014-03-19 11:53:49',NULL,NULL,'0');
/*!40000 ALTER TABLE `shopping_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product_freebies_settings`
--

DROP TABLE IF EXISTS `shopping_product_freebies_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product_freebies_settings` (
  `prod_id` int(10) unsigned NOT NULL,
  `price_value` decimal(10,4) DEFAULT '0.0000',
  `quantity` int(4) unsigned DEFAULT '0',
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product_freebies_settings`
--

LOCK TABLES `shopping_product_freebies_settings` WRITE;
/*!40000 ALTER TABLE `shopping_product_freebies_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_product_freebies_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product_has_freebies`
--

DROP TABLE IF EXISTS `shopping_product_has_freebies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product_has_freebies` (
  `product_id` int(10) unsigned NOT NULL,
  `freebies_id` int(10) unsigned NOT NULL,
  `freebies_quantity` int(4) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`freebies_id`),
  KEY `freebies_id` (`freebies_id`),
  CONSTRAINT `shopping_product_has_freebies_ibfk_1` FOREIGN KEY (`freebies_id`) REFERENCES `shopping_product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product_has_freebies`
--

LOCK TABLES `shopping_product_has_freebies` WRITE;
/*!40000 ALTER TABLE `shopping_product_has_freebies` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_product_has_freebies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product_has_option`
--

DROP TABLE IF EXISTS `shopping_product_has_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product_has_option` (
  `product_id` int(10) unsigned NOT NULL,
  `option_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`option_id`),
  KEY `fk_shopping_product_has_shopping_product_option_shopping_prod2` (`option_id`),
  CONSTRAINT `shopping_product_has_option_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `shopping_product_option` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `shopping_product_has_option_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `shopping_product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product_has_option`
--

LOCK TABLES `shopping_product_has_option` WRITE;
/*!40000 ALTER TABLE `shopping_product_has_option` DISABLE KEYS */;
INSERT INTO `shopping_product_has_option` VALUES (1,1),(1,2),(1,3),(2,6),(2,8),(3,10),(3,11);
/*!40000 ALTER TABLE `shopping_product_has_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product_has_part`
--

DROP TABLE IF EXISTS `shopping_product_has_part`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product_has_part` (
  `product_id` int(10) unsigned NOT NULL,
  `part_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`part_id`),
  CONSTRAINT `shopping_product_has_part_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `shopping_product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product_has_part`
--

LOCK TABLES `shopping_product_has_part` WRITE;
/*!40000 ALTER TABLE `shopping_product_has_part` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_product_has_part` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product_has_related`
--

DROP TABLE IF EXISTS `shopping_product_has_related`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product_has_related` (
  `product_id` int(10) unsigned NOT NULL,
  `related_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`related_id`),
  KEY `fk_shopping_product1` (`related_id`),
  KEY `fk_shopping_product2` (`product_id`),
  CONSTRAINT `shopping_product_has_related_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `shopping_product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product_has_related`
--

LOCK TABLES `shopping_product_has_related` WRITE;
/*!40000 ALTER TABLE `shopping_product_has_related` DISABLE KEYS */;
INSERT INTO `shopping_product_has_related` VALUES (1,1),(2,1),(3,1),(1,2),(1,3),(2,3),(1,4),(2,4),(3,4),(1,5),(3,5);
/*!40000 ALTER TABLE `shopping_product_has_related` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product_has_tag`
--

DROP TABLE IF EXISTS `shopping_product_has_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product_has_tag` (
  `product_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `shopping_product_has_tag_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `shopping_product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `shopping_product_has_tag_ibfk_3` FOREIGN KEY (`tag_id`) REFERENCES `shopping_tags` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product_has_tag`
--

LOCK TABLES `shopping_product_has_tag` WRITE;
/*!40000 ALTER TABLE `shopping_product_has_tag` DISABLE KEYS */;
INSERT INTO `shopping_product_has_tag` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,2),(7,2),(8,2),(11,3),(13,3),(14,3),(15,3),(16,4),(17,4),(18,4),(19,4);
/*!40000 ALTER TABLE `shopping_product_has_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product_option`
--

DROP TABLE IF EXISTS `shopping_product_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentId` int(10) unsigned DEFAULT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('dropdown','radio','text','date','file') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indTitle` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product_option`
--

LOCK TABLES `shopping_product_option` WRITE;
/*!40000 ALTER TABLE `shopping_product_option` DISABLE KEYS */;
INSERT INTO `shopping_product_option` VALUES (1,NULL,'Color','dropdown'),(2,NULL,'Speed','dropdown'),(3,NULL,'Rims','dropdown'),(4,0,'Color','dropdown'),(5,4,'Color','dropdown'),(6,4,'Color','dropdown'),(7,0,'Size','dropdown'),(8,7,'Size','dropdown'),(9,0,'template-Color','dropdown'),(10,9,'Color','dropdown'),(11,7,'Size','dropdown');
/*!40000 ALTER TABLE `shopping_product_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product_option_selection`
--

DROP TABLE IF EXISTS `shopping_product_option_selection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product_option_selection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `option_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priceSign` enum('+','-') COLLATE utf8_unicode_ci DEFAULT NULL,
  `priceValue` decimal(10,4) DEFAULT NULL,
  `priceType` enum('percent','unit') COLLATE utf8_unicode_ci DEFAULT NULL,
  `weightSign` enum('+','-') COLLATE utf8_unicode_ci DEFAULT NULL,
  `weightValue` decimal(8,3) DEFAULT NULL,
  `isDefault` enum('1','0') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indTitle` (`title`),
  KEY `fk_shopping_product_option_selection_shopping_product_option1` (`option_id`),
  CONSTRAINT `fk_shopping_product_option_selection_shopping_product_option1` FOREIGN KEY (`option_id`) REFERENCES `shopping_product_option` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product_option_selection`
--

LOCK TABLES `shopping_product_option_selection` WRITE;
/*!40000 ALTER TABLE `shopping_product_option_selection` DISABLE KEYS */;
INSERT INTO `shopping_product_option_selection` VALUES (2,1,'Green','+',0.0000,'percent','+',0.000,'1'),(3,1,'Red','+',0.0000,'percent','+',0.000,'0'),(4,1,'Blue','+',0.0000,'percent','+',0.000,'0'),(6,2,'12','+',0.0000,'percent','+',0.000,'1'),(7,2,'18','+',20.0000,'unit','+',0.000,'0'),(8,2,'21','+',35.0000,'unit','+',0.000,'0'),(9,3,'Single','+',0.0000,'percent','+',0.000,'1'),(10,3,'Duble','+',12.0000,'unit','+',0.000,'0'),(11,4,'','+',0.0000,'percent','+',0.000,'1'),(12,5,'','+',0.0000,'percent','+',0.000,'1'),(13,6,'Green','+',0.0000,'percent','+',0.000,'1'),(14,6,'Red','+',0.0000,'percent','+',0.000,'0'),(15,6,'Blue','+',0.0000,'percent','+',0.000,'0'),(16,7,'16','+',0.0000,'percent','+',0.000,'1'),(17,7,'18','+',0.0000,'percent','+',0.000,'0'),(18,7,'18.5','+',0.0000,'percent','+',0.000,'0'),(19,7,'19','+',0.0000,'percent','+',0.000,'0'),(20,7,'20','+',0.0000,'percent','+',0.000,'0'),(21,8,'16','+',0.0000,'percent','+',0.000,'1'),(22,8,'18','+',0.0000,'percent','+',0.000,'0'),(23,8,'18.5','+',0.0000,'percent','+',0.000,'0'),(24,8,'19','+',0.0000,'percent','+',0.000,'0'),(25,8,'20','+',0.0000,'percent','+',0.000,'0'),(26,9,'Green','+',0.0000,'percent','+',0.000,'1'),(27,9,'Red','+',0.0000,'percent','+',0.000,'0'),(28,9,'White','+',0.0000,'percent','+',0.000,'0'),(29,9,'Blue','+',0.0000,'percent','+',0.000,'0'),(30,10,'Green','+',0.0000,'percent','+',0.000,'1'),(31,10,'Red','+',0.0000,'percent','+',0.000,'0'),(32,10,'White','+',0.0000,'percent','+',0.000,'0'),(33,10,'Blue','+',0.0000,'percent','+',0.000,'0'),(34,11,'16','+',0.0000,'percent','+',0.000,'1'),(35,11,'18','+',0.0000,'percent','+',0.000,'0'),(36,11,'18.5','+',0.0000,'percent','+',0.000,'0'),(37,11,'19','+',0.0000,'percent','+',0.000,'0'),(38,11,'20','+',0.0000,'percent','+',0.000,'0');
/*!40000 ALTER TABLE `shopping_product_option_selection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_product_set_settings`
--

DROP TABLE IF EXISTS `shopping_product_set_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_product_set_settings` (
  `productId` int(10) unsigned NOT NULL,
  `autoCalculatePrice` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`productId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_product_set_settings`
--

LOCK TABLES `shopping_product_set_settings` WRITE;
/*!40000 ALTER TABLE `shopping_product_set_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_product_set_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_quote`
--

DROP TABLE IF EXISTS `shopping_quote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_quote` (
  `id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('new','sent','sold','lost') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `disclaimer` text COLLATE utf8_unicode_ci,
  `internal_note` text COLLATE utf8_unicode_ci,
  `discount_tax_rate` enum('0','1','2','3') COLLATE utf8_unicode_ci DEFAULT '1',
  `delivery_type` tinytext COLLATE utf8_unicode_ci,
  `cart_id` int(10) unsigned DEFAULT NULL,
  `edited_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creator_id` int(10) unsigned DEFAULT '0',
  `expires_at` timestamp NULL DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `status` (`status`),
  KEY `edited_by` (`edited_by`),
  KEY `cart_id` (`cart_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `shopping_quote_ibfk_2` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart_session` (`id`) ON DELETE SET NULL,
  CONSTRAINT `shopping_quote_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_quote`
--

LOCK TABLES `shopping_quote` WRITE;
/*!40000 ALTER TABLE `shopping_quote` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_quote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_recurring_payment`
--

DROP TABLE IF EXISTS `shopping_recurring_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_recurring_payment` (
  `cart_id` int(10) unsigned NOT NULL COMMENT 'Cart id',
  `subscription_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Subscription id',
  `ipn_tracking_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Ipn number',
  `gateway_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Payment gateway name',
  `payment_period` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Frequency of recurring payment',
  `recurring_times` smallint(5) unsigned NOT NULL COMMENT 'Amount of payments',
  `subscription_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Subscription date',
  `payment_cycle_amount` decimal(10,4) DEFAULT NULL COMMENT 'Amount for each recurring cycle',
  `total_amount_paid` decimal(10,4) DEFAULT NULL COMMENT 'Amount paid',
  `last_payment_date` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Last payment date',
  `next_payment_date` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Next payment date',
  `recurring_status` enum('new','active','pending','expired','suspended','canceled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new' COMMENT 'Recurring payment status',
  `accept_changing_next_billing_date` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0' COMMENT 'Flag for change next payment date',
  `accept_changing_shipping_address` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0' COMMENT 'Flag for change shipping address',
  `free_transaction_cycle` tinyint(3) unsigned DEFAULT NULL COMMENT 'Free transaction cycle quantity',
  `transactions_quantity` smallint(5) unsigned DEFAULT NULL COMMENT 'Transaction total quantity',
  `custom_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Additional information for payment',
  PRIMARY KEY (`cart_id`),
  CONSTRAINT `shopping_recurring_payment_ibfk_2` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart_session` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_recurring_payment`
--

LOCK TABLES `shopping_recurring_payment` WRITE;
/*!40000 ALTER TABLE `shopping_recurring_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_recurring_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_shipping_config`
--

DROP TABLE IF EXISTS `shopping_shipping_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_shipping_config` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Shipping plugin name',
  `enabled` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `config` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`name`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_shipping_config`
--

LOCK TABLES `shopping_shipping_config` WRITE;
/*!40000 ALTER TABLE `shopping_shipping_config` DISABLE KEYS */;
INSERT INTO `shopping_shipping_config` VALUES ('freeshipping','1','a:2:{s:10:\"cartamount\";s:1:\"0\";s:11:\"destination\";s:4:\"both\";}'),('pickup','1',NULL);
/*!40000 ALTER TABLE `shopping_shipping_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_tags`
--

DROP TABLE IF EXISTS `shopping_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_tags`
--

LOCK TABLES `shopping_tags` WRITE;
/*!40000 ALTER TABLE `shopping_tags` DISABLE KEYS */;
INSERT INTO `shopping_tags` VALUES (3,'Downhill bikes'),(1,'Mountain bikes'),(2,'Road bikes'),(4,'Triatlon Bikes');
/*!40000 ALTER TABLE `shopping_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_tax`
--

DROP TABLE IF EXISTS `shopping_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_tax` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zoneId` int(10) unsigned NOT NULL,
  `rate1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rate2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rate3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `isDefault` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `zoneId` (`zoneId`),
  CONSTRAINT `shopping_tax_ibfk_1` FOREIGN KEY (`zoneId`) REFERENCES `shopping_zone` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_tax`
--

LOCK TABLES `shopping_tax` WRITE;
/*!40000 ALTER TABLE `shopping_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_zone`
--

DROP TABLE IF EXISTS `shopping_zone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_zone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_zone`
--

LOCK TABLES `shopping_zone` WRITE;
/*!40000 ALTER TABLE `shopping_zone` DISABLE KEYS */;
INSERT INTO `shopping_zone` VALUES (1,'US'),(2,'CA'),(3,'EU');
/*!40000 ALTER TABLE `shopping_zone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_zone_country`
--

DROP TABLE IF EXISTS `shopping_zone_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_zone_country` (
  `zone_id` int(11) unsigned NOT NULL,
  `country_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`zone_id`,`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_zone_country`
--

LOCK TABLES `shopping_zone_country` WRITE;
/*!40000 ALTER TABLE `shopping_zone_country` DISABLE KEYS */;
INSERT INTO `shopping_zone_country` VALUES (1,243),(2,39),(3,13),(3,21),(3,23),(3,57),(3,58),(3,60),(3,62),(3,67),(3,71),(3,73),(3,79),(3,82),(3,94),(3,105),(3,107),(3,115),(3,139),(3,140),(3,141),(3,160),(3,173),(3,189),(3,194),(3,201),(3,209),(3,212),(3,214);
/*!40000 ALTER TABLE `shopping_zone_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_zone_state`
--

DROP TABLE IF EXISTS `shopping_zone_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_zone_state` (
  `zone_id` int(10) unsigned NOT NULL,
  `state_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`zone_id`,`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_zone_state`
--

LOCK TABLES `shopping_zone_state` WRITE;
/*!40000 ALTER TABLE `shopping_zone_state` DISABLE KEYS */;
INSERT INTO `shopping_zone_state` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(1,42),(1,43),(1,44),(1,45),(1,46),(1,47),(1,48),(1,49),(1,50),(1,51),(2,52),(2,53),(2,54),(2,55),(2,56),(2,57),(2,58),(2,59),(2,60),(2,61),(2,62),(2,63),(2,64);
/*!40000 ALTER TABLE `shopping_zone_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_zone_zip`
--

DROP TABLE IF EXISTS `shopping_zone_zip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopping_zone_zip` (
  `zone_id` int(11) NOT NULL,
  `zip` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`zone_id`,`zip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_zone_zip`
--

LOCK TABLES `shopping_zone_zip` WRITE;
/*!40000 ALTER TABLE `shopping_zone_zip` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_zone_zip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `silo`
--

DROP TABLE IF EXISTS `silo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `silo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indName` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `silo`
--

LOCK TABLES `silo` WRITE;
/*!40000 ALTER TABLE `silo` DISABLE KEYS */;
/*!40000 ALTER TABLE `silo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template`
--

DROP TABLE IF EXISTS `template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template` (
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`),
  KEY `type` (`type`),
  CONSTRAINT `template_ibfk_1` FOREIGN KEY (`type`) REFERENCES `template_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template`
--

LOCK TABLES `template` WRITE;
/*!40000 ALTER TABLE `template` DISABLE KEYS */;
INSERT INTO `template` VALUES ('2 columns','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n            \n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <div class=\"logo h1 grid_5 alpha t-hide m-hide\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n            \n            <h1 class=\"grid_12 h2 page-header\">{$page:h1}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_6 productchangebox\">\n                {$search:results}\n                {$content:content11}\n                {$content:content12}\n                <h2>{$header:content2}</h2>\n                {$content:content21}\n                {$content:content22}\n                <h2>{$header:content3}</h2>\n                {$content:content31}\n                {$content:content32}\n            </div>\n\n            <!-- Right box -->\n            <div id=\"right\" class=\"grid_6\">\n                <ul class=\"list-unstyled toaster-cart-box\">{toastercart} <li class=\"item\"> <div class=\"grid_3 alpha product-image\">{$cartitem:photo}</div> <div class=\"grid_8 h4 omega product-name\">{$cartitem:name}</div> <div class=\"grid_1 omega text-right  product-remove\">{$cartitem:remove}</div> <div class=\"grid_9 omega product-price\">{$cartitem:price}</div> </li>{/toastercart}</ul>\n                <div class=\"toaster-cart-header\">{$header:toastercart:static}</div>\n                <!--div class=\"right-box\">\n                    <h3>{$h eader:right1}</h3>\n                    {$c ontent:right1}\n                </div-->\n                <div class=\"right-box\">\n                    <h3>{$header:right2:static}</h3>\n                    {$content:right2:static}\n                </div>\n                <div class=\"right-box\">\n                    <h3>{$header:right3:static}</h3>\n                    {$content:right3:static}\n                </div>\n            </div>\n            \n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script>\n            $(function () {\n            });\n        </script>\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typeregular'),('404 page','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n            \n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <div class=\"logo h1 grid_5 alpha t-hide m-hide\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n            \n            <h1 class=\"grid_12 h2 page-header\">{$page:h1}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_12 page404\" role=\"main\">\n                <div class=\"grid_6 alpha\">404</div>\n                <div class=\"grid_6 omega\">\n                    {$content:404:static}<br>\n                    <a class=\"btn inverse large\" href=\"{$website:url}\" title=\"{$header:button:readonly}\">{$header:button:static}</a>\n                </div>\n            </div>\n\n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typeregular'),('category','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n            \n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <div class=\"logo h1 grid_5 alpha t-hide m-hide\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n\n            <h1 class=\"grid_12 h2 page-header\">{$page:h1}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_8 productchangebox\">\n                {$search:results}\n                {$content:content11}\n                {$content:content12}\n                <h2>{$header:content2}</h2>\n                {$content:content21}\n                {$content:content22}\n                <h2>{$header:content3}</h2>\n                {$content:content31}\n                {$content:content32}\n            </div>\n\n            <!-- Right box -->\n            <aside id=\"right\" class=\"grid_4 sidebar\">\n                <ul class=\"list-unstyled toaster-cart-box\">\n                    {toastercart}\n                    <li class=\"item\">\n                        <div class=\"grid_3 alpha product-image\">\n                            {$cartitem:photo:crop}\n                        </div>\n                        <div class=\"grid_8 h4 omega product-name\">\n                            {$cartitem:name}\n                        </div>\n                        <div class=\"grid_1 omega text-right  product-remove\">\n                            {$cartitem:remove}\n                        </div>\n                        <div class=\"grid_9 omega product-price\">\n                            {$cartitem:price}\n                        </div>\n                    </li>\n                    {/toastercart}\n                </ul>\n                <div class=\"toaster-cart-header\">{$header:toastercart:static}</div>\n                <div class=\"right-box\">\n                    <h3>{$header:right2:static}</h3>\n                    {$content:right2:static}\n                </div>\n                <div class=\"right-box\">\n                    <h3>{$header:right3:static}</h3>\n                    {$content:right3:static}\n                </div>\n            </aside>\n\n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typeregular'),('checkout','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n            \n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <div class=\"logo h1 grid_5 alpha t-hide m-hide suffix_7\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n            \n            <h1 class=\"grid_12 h2 page-header\">{$page:h1}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_12\">\n                <!-- Table products to order -->\n                <table id=\"checkoutitemlist\" class=\"checkout-cart-table mb30px\">\n                    <thead class=\"t-hide m-hide\">\n                        <tr>\n                            <th class=\"product-info\" colspan=\"2\">Product</th>\n                            <!--th class=\"description\">Description</th-->\n                            <th class=\"product-unit-price\">Unit price</th>\n                            <th class=\"product-qty\">Qty</th>\n                            <th class=\"product-total\">Total</th>\n                            <th class=\"product-remove\">Remove</th>\n                        </tr>\n                    </thead>\n                    <tbody>\n                    {toastercart}\n                        <tr>\n                            <td class=\"product-img\"> {$cartitem:photo} </td>\n                            <td class=\"product-info\">\n                                {$cartitem:name}\n                                \n                                <p class=\"itemID\">\n                                    <span>Item ID: </span>{$cartitem:sku}\n                                </p>\n                                <div class=\"options-list\">\n                                    {$cartitem:options}\n                                </div>\n                            </td>\n                            <td class=\"product-unit-price\">{$cartitem:price:unit}</td>\n                            <td class=\"product-qty\">{$cartitem:qty}</td>\n                            <td class=\"product-total\">{$cartitem:price}</td>\n                            <td class=\"product-remove\">{$cartitem:remove}</td>\n                        </tr>\n                    {/toastercart}\n                    </tbody>\n                </table>\n                <!-- end table products to order -->\n                <div class=\"grid_9 alpha\">\n                    <!-- Checkout widget -->\n                    {$store:checkout}\n                    <!-- Select the required payment gateways-->\n                    {paymentgateways}\n                        {$plugin:paypal:button}\n                        <h2>Request a free quote, pay with EFT or by check:</h2>\n                        {$quote:form:firstname:lastname:company:email*:address1:address2:country:city:zip:phone:disclaimer}\n                    {/paymentgateways}\n                </div>\n                <div id=\"checkoutsummary\" class=\"grid_3 checkoutsummary omega\">\n                    {$store:cartsummary}\n                    {$store:buyersummary}\n                </div>\n            </div>\n        </div>\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typecheckout'),('contact','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n\n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <div class=\"logo h1 grid_5 alpha t-hide m-hide\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n\n            <h1 class=\"grid_12 h2 page-header\">{$page:h1}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_12 contact\" role=\"main\">\n                {$plugin:gmaps:bizlocation:1200:300}\n                <div class=\"grid_6 prefix_1 suffix_1 alpha mt30px\">\n                    <h2>{$header:contact1}</h2>\n                    {$content:contact1}\n                </div>\n                <div class=\"grid_4 omega mt30px\">\n                    <h3>{$header:contact2}</h3>\n                    <p class=\"fs12\">\n                        <strong>Address: </strong>{$plugin:widcard:BizAddress1}  {$plugin:widcard:BizAddress2}, {$plugin:widcard:BizCity}, {$plugin:widcard:BizState} {$plugin:widcard:BizZip}<br />\n                        <strong>Phone: </strong>{$plugin:widcard:BizTelephone}<br />\n                        <strong>Email: </strong><a href=\"mailto:{$plugin:widcard:BizEmail:notag}\">{$plugin:widcard:BizEmail}</a>\n                    </p>\n                    <h3>{$header:contact3}</h3>\n                    {$content:contact2}\n                </div>\n            </div>\n\n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typeregular'),('default','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n            \n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <div class=\"logo h1 grid_5 alpha t-hide m-hide\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n\n            <h1 class=\"grid_12 h2 page-header\">{$page:h1}</h1>\n            \n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_8 productchangebox\">\n                <div class=\"h2 mb10px\">{$header:gallery1}</div>\n                {$search:results}\n                <div class=\"h2 mb10px\">{$header:gallery2}</div>\n                {$content:content11}\n            </div>\n\n            <!-- Right box -->\n            <aside id=\"right\" class=\"grid_4 sidebar\">\n                <ul class=\"list-unstyled toaster-cart-box\">\n                    {toastercart}\n                    <li class=\"item\">\n                        <div class=\"grid_3 alpha product-image\">\n                            {$cartitem:photo}\n                        </div>\n                        <div class=\"grid_8 h4 omega product-name\">\n                            {$cartitem:name}\n                        </div>\n                        <div class=\"grid_1 omega text-right  product-remove\">\n                            {$cartitem:remove}\n                        </div>\n                        <div class=\"grid_9 omega product-price\">\n                            {$cartitem:price}\n                        </div>\n                    </li>\n                    {/toastercart}\n                </ul>\n                <div class=\"toaster-cart-header\">{$header:toastercart:static}</div>\n                <div class=\"right-box\">\n                    <h3>{$header:right2:static}</h3>\n                    {$content:right2:static}\n                </div>\n                <div class=\"right-box\">\n                    <h3>{$header:right3:static}</h3>\n                    {$content:right3:static}\n                </div>\n            </aside>\n\n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typeregular'),('email','<html>\n<head>\n  <title>{$plugin:widcard:BizOrgName:notag}</title>\n</head>\n<body style=\"margin:0;padding:0;\">\n	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"font-family:Arial, Helvetica, sans-serif; color:#000; font-size:14px; text-align:left;\">\n		<tr>\n			<td bgcolor=\"\" style=\"\" align=\"center\">\n				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\" bgcolor=\"\" style=\"margin: 0px auto; max-width:100%;\">\n\n					<!-- Header, LOGO -->\n					<tr style=\"\" bgcolor=\"\">\n						<td style=\"padding:15px;border-bottom:3px solid #fff;\">\n							<a target=\"_blank\" href=\"{$website:url}\" style=\"text-decoration:none; font-size:50px; color:#999;\">\n								<img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\" height=\"75\" style=\"vertical-align:middle;\">\n							</a>\n						</td>\n					</tr>\n\n					<!-- Content -->\n					<tr style=\"\">\n						<td style=\"padding:15px;\" valign=\"top\">\n                        {$header:header:static}\n							{emailmessage}\n						</td>\n					</tr>\n\n					<!-- Footer -->\n					<tr style=\"\" bgcolor=\"\">\n						<td style=\"padding:15px 0; font-size:12px;text-align:center; border-top:3px solid #fff; color:#999;\">\n							<address>{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag} | Tel : {$plugin:widcard:BizTelephone:notag}</address>\n						</td>\n					</tr>\n				</table>\n			</td>\n		</tr>\n	</table>\n</body>\n</html>','typemail'),('index','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]><link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n            \n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <h1 class=\"logo grid_5 alpha t-hide m-hide\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\">\n                    </a>\n                </h1>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n\n            <!--Slider -->\n         \n\n            <div class=\"grid_12 t-grid_6 mt30px mb30px text-center slogan-box\">\n                <h2 class=\"text-center light h1 mb10px\">{$header:slogan}</h2>\n                {$content:slogan}\n            </div>\n\n            <div class=\"grid_12 mt20px mb20px presentable\">\n                <!--h2 class=\"text-center light mb20px\">{$header:top}</h2-->\n                {$featuredonly:top}\n            </div>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_8 t-grid_6 m-grid_4\">\n                <div class=\"tabs\">\n                    <ul class=\"list-unstyled list-inline\">\n                        <li class=\"t-hide m-hide\"><a href=\"#tabs-1\">{$header:tab1}</a></li>\n                        <li><a href=\"#tabs-2\">{$header:tab2}</a></li>\n                        <li><a href=\"#tabs-3\">{$header:tab3}</a></li>\n                    </ul>\n                    <div id=\"tabs-1\" class=\"t-hide m-hide\">\n                        <div class=\"responsive-video\">\n                            {$videolink:tab1}\n                        </div>\n                    </div>\n                    <div id=\"tabs-2\">\n                        {$content:tab2}\n                        {$content:tab2}\n                    </div>\n                    <div id=\"tabs-3\">\n                        {$content:tab3}\n                    </div>\n                </div>\n                <div class=\"h2 mb10px\">{$header:content1}</div>\n                {$content:content21}\n                {$content:content22}\n                {$content:content23}\n            </div>\n\n            <!-- Right box -->\n            <aside id=\"right\" class=\"grid_4\" role=\"complementary\">\n                <div class=\"aside-box\">\n                    <div class=\"h2 mb10px\">{$header:right1}</div>\n                    {$content:right1}\n                </div>\n                <div class=\"aside-box\">\n                    <div class=\"h2 mb10px\">{$header:right2}</div>\n                    {$content:right2}\n                </div>\n                <div class=\"aside-box\">\n                    <div class=\"h2 mb10px\">{$header:right3}</div>\n                    {$content:right3}\n                </div>\n            </aside>\n\n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typeregular'),('invoice','<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n<html>\n    <head>\n     <title>{$plugin:widcard:BizOrgName:notag}</title>\n  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n\n  <style type=\"text/css\">\n   * { padding: 0; margin: 0; }\n   body {\n    background-color: #FFFFFF;\n    font: 16px Arial, Verdana, Sans-serif;\n   }\n   h1 { margin:0; float: left; width: 50%; }\n   a.logo {\n    color: #454545;\n    display: block;\n    font-size: 30px; font-weight: normal;\n    text-decoration: none;\n   }\n   a.logo img {\n    width: 70px;\n    vertical-align: middle;\n   }\n\n   #address {\n    color: #999999;\n    float: right;\n    width: 50%;\n    text-align: right;\n   }\n   #address h3 {\n    margin: 0; padding: 0;\n    color: #151515;\n   }\n   #address p {\n    margin: 0;\n    font-size: 14px;\n   }\n\n   table.cart-content {\n    border: none;\n    margin-bottom: 5px; margin-top: 15px;\n    width: 100%;\n    overflow: hidden;\n    float: left;\n    background-color: none;\n    border-collapse: collapse;\n   }\n   table.cart-content td, table.cart-content th {\n    text-align: left;\n   }\n   table.cart-content th {\n    border-bottom: 2px solid #000;\n   }\n   table.cart-content td {\n    border-bottom: 1px dashed #EEE;\n    padding: 10px 0;\n   }\n   .post-purchase {\n    font-size: 13px;\n   }\n   .post-purchase h3.post-purchase-report-customer-order-id {\n    border-bottom: 1px dashed #CCCCCC;\n    color: #444444;\n    font-size: 19px;\n    font-weight: bold;\n    margin: 0 0 10px;\n    padding: 0;\n    padding-bottom:7px;\n   }\n   .post-purchase .post-purchase-report-customer-info, \n   .post-purchase .post-purchase-report-shipping, \n   .post-purchase .post-purchase-report-billing {\n    float: left;\n    width: 50%;\n    margin: 0;\n    overflow: hidden;\n   }\n   .post-purchase .post-purchase-report-billing, \n   .post-purchase .post-purchase-report-summary { \n    overflow:hidden; \n   }\n   /* summary */\n   .post-purchase .post-purchase-report-summary div {\n    margin: 10px 0;\n    font-size: 12px;\n   }\n   .post-purchase .post-purchase-report-summary span {\n    display: block;\n    width: 100%;\n   }\n   .post-purchase .post-purchase-report-summary div.cart-total {\n    border-top: 1px solid #151515;\n    padding-top: 5px;\n    font-weight: bold;\n    border: none;\n   }\n   .post-purchase-report-summary {\n    float: right;\n    width: 25%;\n   }\n   .post-purchase-report-summary .summary-title {\n    padding: 0 0 5px; margin-bottom: 5px;\n    border-bottom: 1px solid #151515;\n    font-size: 14px;\n    font-weight: bold;\n    text-transform: uppercase;\n   }\n  </style>\n\n </head>\n <body>\n  <div id=\"container\">\n   <h1>\n    <a class=\"logo\" title=\"{$page:h1}\" href=\"{$website:url}\">\n     <img alt=\"{$plugin:widcard:BizOrgName:notag}\" src=\"{$plugin:widcard:BizLogo:url}\" />\n    </a>\n   </h1>\n   <div id=\"address\">\n    <h3>{$plugin:widcard:BizOrgName:notag}</h3>\n    <p>{$plugin:widcard:BizAddress1:notag}</p>\n    <p>{$plugin:widcard:BizCity:notag} {$plugin:widcard:BizState:notag}</p>\n    <p>{$plugin:widcard:BizZip:notag}</p>\n    <p><strong>Email:</strong> {$plugin:widcard:BizEmail:notag}</p>\n    <p><strong>Phone:</strong> {$plugin:widcard:BizTelephone:notag}</p>\n   </div>\n   <div style=\"clear:both\"></div>\n   <h2>Proforma Invoice #{$plugin:invoicetopdf:InvoiceNumber}</h2>\n   {$store:postpurchasereport::mpn:Code}\n  </div>\n </body>\n</html>','typeinvoice'),('main menu','{ifpages}\n    <label class=\"sub-menu-btn btn large icon icon-list2 fl-right {$mobile:device:desktop=d-hide}\"></label>\n{/ifpages}\n\n<a href=\"{$page:url}\" title=\"{$page:h1}\">{$page:navName}</a>\n\n{submenu}\n    <a class=\"page-title\" href=\"{$page:url}\" title=\"{$page:h1}\">{$page:navName}</a>\n{/submenu}','typemenu'),('news','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n        <div id=\"fb-root\"></div>\n        <script>(function(d, s, id) {\n            var js, fjs = d.getElementsByTagName(s)[0];\n            if (d.getElementById(id)) return;\n            js = d.createElement(s); js.id = id;\n            js.src = \"//connect.facebook.net/en_EN/all.js#xfbml=1\";\n            fjs.parentNode.insertBefore(js, fjs);\n        }(document, \'script\', \'facebook-jssdk\'));</script>\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n\n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\">\n                <div class=\"logo grid_5 alpha t-hide m-hide h1\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n\n            <h1 class=\"grid_12 h2 page-header\">{$page:h1}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_8\">\n                <!-- news content -->\n                {newscontent}\n                    {$content:content11}\n                    {$content:content12}\n                    <h2><span>{$header:content2}</span></h2>\n                    {$content:content21}\n                    {$content:content22}\n                    <h2><span>{$header:content3}</span></h2>\n                    {$content:content31}\n                    {$content:content32}\n                {/newscontent}\n            </div>\n\n            <!-- Right box -->\n            <div id=\"right\" class=\"grid_4\">\n                <a class=\"twitter-timeline\"  href=\"{$plugin:widcard:BizTwitterAccount:notag}\"  data-widget-id=\"353093631638388737\">Tweets by @seotoaster</a>\n    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\"://platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>\n            \n            \n                <div class=\"fb-like-box mt20px\" data-href=\"{$plugin:widcard:BizFbAccount:notag}\" data-width=\"313\" data-height=\"300\" data-colorscheme=\"light\" data-show-faces=\"true\" data-header=\"false\" data-stream=\"false\" data-show-border=\"false\"></div>\n                <div class=\"right-box\">\n                    <h3>{$header:right3}</h3>\n                    {$content:right3}\n                </div>\n            </div>\n            \n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','type_news'),('News list','<li class=\"news-list-item clearfix\">\n    {$news:actions}\n    <time class=\"news-item-date icon-calendar2 small block mb10px\" datetime=\"{$news:date:Y-m-d}\">{$news:date: M d, Y}</time>\n    <a class=\"page-teaser-image text-center grid_3 alpha\" href=\"{$news:url}\">\n        <img src=\"{$news:preview}\" alt=\"{$news:title}\"/>\n    </a>\n    <a class=\"page-title large grid_9 omega\" href=\"{$news:url}\">{$news:title}</a>\n    <p class=\"news-item-description grid_9 omega small mb10px\">{$news:teaser}</p>\n    <p class=\"news-item-tags small cl-both text-right\">{$news:tags}</p>\n</li>','type_news_list'),('news room','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n        <div id=\"fb-root\"></div>\n        <script>(function(d, s, id) {\n            var js, fjs = d.getElementsByTagName(s)[0];\n            if (d.getElementById(id)) return;\n            js = d.createElement(s); js.id = id;\n            js.src = \"//connect.facebook.net/en_EN/all.js#xfbml=1\";\n            fjs.parentNode.insertBefore(js, fjs);\n        }(document, \'script\', \'facebook-jssdk\'));</script>\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n\n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\">\n                <div class=\"logo grid_5 alpha t-hide m-hide h1\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n\n            <h1 class=\"grid_12 h2 page-header\">{$page:h1}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_8\" role=\"main\">\n\n                <!-- news list -->\n                <ul class=\"news-list list-unstyled\">\n                {newslist:content:4:pag}\n                    <li class=\"news-item grid\">\n                        <a class=\"page-teaser-image\" href=\"{$news:url}\">\n                            <img src=\"{$news:preview:crop}\" width=\"200\" alt=\"{$news:title}\"/>\n                        </a>\n                        <a class=\"h3 page-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-date\">{$news:date:M Y}</p>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                {/newslist}\n                </ul>\n            </div>\n\n            <!-- Right box -->\n            <aside id=\"right\" class=\"grid_4\" role=\"complementary\">\n                {device:desktop}\n                <a class=\"twitter-timeline\"  href=\"https://twitter.com/seotoaster\"  data-widget-id=\"353093631638388737\">Tweets by @seotoaster</a>\n    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\"://platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>\n\n\n                <div class=\"fb-like-box mt20px\" data-href=\"{$plugin:widcard:BizFbAccount:notag}\" data-width=\"313\" data-height=\"300\" data-colorscheme=\"light\" data-show-faces=\"true\" data-header=\"false\" data-stream=\"false\" data-show-border=\"false\"></div>\n                {/device}\n                <div class=\"right-box\">\n                    <h3>{$header:right3}</h3>\n                    {$content:right3}\n                </div>\n            </aside>\n\n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typeregular'),('packing','<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n<html>\n    <head>\n     <title>{$plugin:widcard:BizOrgName:notag}</title>\n  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n\n  <style type=\"text/css\">\n   * { padding: 0; margin: 0; }\n   body {\n    background-color: #FFFFFF;\n    font: 16px Arial, Verdana, Sans-serif;\n   }\n   h1 { margin:0; float: left; width: 50%; }\n   a.logo {\n    color: #454545;\n    display: block;\n    font-size: 30px; font-weight: normal;\n    text-decoration: none;\n   }\n   a.logo img {\n    width: 70px;\n    vertical-align: middle;\n   }\n\n   #address {\n    color: #999999;\n    float: right;\n    width: 50%;\n    text-align: right;\n   }\n   #address h3 {\n    margin: 0; padding: 0;\n    color: #151515;\n   }\n   #address p {\n    margin: 0;\n    font-size: 14px;\n   }\n\n   table.cart-content {\n    border: none;\n    margin-bottom: 5px; margin-top: 15px;\n    width: 100%;\n    overflow: hidden;\n    float: left;\n    background-color: none;\n    border-collapse: collapse;\n   }\n   table.cart-content td, table.cart-content th {\n    text-align: left;\n   }\n   table.cart-content th {\n    border-bottom: 2px solid #000;\n   }\n   table.cart-content td {\n    border-bottom: 1px dashed #EEE;\n    padding: 10px 0;\n   }\n   .post-purchase {\n    font-size: 13px;\n   }\n   .post-purchase h3.post-purchase-report-customer-order-id {\n    border-bottom: 1px dashed #CCCCCC;\n    color: #444444;\n    font-size: 19px;\n    font-weight: bold;\n    margin: 0 0 10px;\n    padding: 0;\n    padding-bottom:7px;\n   }\n   .post-purchase .post-purchase-report-customer-info, \n   .post-purchase .post-purchase-report-shipping, \n   .post-purchase .post-purchase-report-billing {\n    float: left;\n    width: 50%;\n    margin: 0;\n    overflow: hidden;\n   }\n   .post-purchase .post-purchase-report-billing, \n   .post-purchase .post-purchase-report-summary { \n    overflow:hidden; \n   }\n   /* summary */\n   .post-purchase .post-purchase-report-summary div {\n    margin: 10px 0;\n    font-size: 12px;\n   }\n   .post-purchase .post-purchase-report-summary span {\n    display: block;\n    width: 100%;\n   }\n   .post-purchase .post-purchase-report-summary div.cart-total {\n    border-top: 1px solid #151515;\n    padding-top: 5px;\n    font-weight: bold;\n    border: none;\n   }\n   .post-purchase-report-summary {\n    float: right;\n    width: 25%;\n   }\n   .post-purchase-report-summary .summary-title {\n    padding: 0 0 5px; margin-bottom: 5px;\n    border-bottom: 1px solid #151515;\n    font-size: 14px;\n    font-weight: bold;\n    text-transform: uppercase;\n   }\n  </style>\n\n </head>\n <body>\n  <div id=\"container\">\n   <h1>\n    <a class=\"logo\" title=\"{$page:h1}\" href=\"{$website:url}\">\n     <img alt=\"{$plugin:widcard:BizOrgName:notag}\" src=\"{$plugin:widcard:BizLogo:url}\" />\n    </a>\n   </h1>\n   <div id=\"address\">\n    <h3>{$plugin:widcard:BizOrgName:notag}</h3>\n    <p>{$plugin:widcard:BizAddress1:notag}</p>\n    <p>{$plugin:widcard:BizCity:notag} {$plugin:widcard:BizState:notag}</p>\n    <p>{$plugin:widcard:BizZip:notag}</p>\n    <p><strong>Email:</strong> {$plugin:widcard:BizEmail:notag}</p>\n    <p><strong>Phone:</strong> {$plugin:widcard:BizTelephone:notag}</p>\n   </div>\n   <div style=\"clear:both\"></div>\n   <h2>Proforma Invoice #{$plugin:invoicetopdf:InvoiceNumber}</h2>\n   {$store:postpurchasereport::mpn:Code}\n  </div>\n </body>\n</html>','typeinvoice'),('product','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix d-hide\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n            \n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <div class=\"logo h1 grid_5 alpha t-hide m-hide\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n\n            <h1 class=\"grid_12 page-header product-name\" itemprop=\"name\">{$product:name}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_12 product\">\n                <div class=\"grid_5 alpha\">\n                    {$product:editproduct}\n                    <a class=\"_lbox product-image\" href=\"{$product:photourl:original}\">\n                        <img itemprop=\"image\" src=\"{$product:photourl:medium}\" alt=\"{$product:name}\"/>\n                    </a>\n                    <div class=\"product-gallery\">\n                        <!--span>{ $header:product-gallery:static}</span>\n                        { $content:product-gallery}-->\n                        {$directupload:product-{$page:id}:product-img{$page:id}-1:90:gallery:crop}\n                        {$directupload:product-{$page:id}:product-img{$page:id}-2:90:gallery:crop}\n                        {$directupload:product-{$page:id}:product-img{$page:id}-3:90:gallery:crop}\n                        {$directupload:product-{$page:id}:product-img{$page:id}-4:90:gallery:crop}\n                        {$directupload:product-{$page:id}:product-img{$page:id}-5:90:gallery:crop}\n                    </div>\n                    <div class=\"grid_12 alpha omega mt30px product-reviews\">{$plugin:pagerating:review}</div>\n                </div>\n                <div class=\"grid_7 omega\" itemprop=\"offers\" itemscope itemtype=\"http://schema.org/Offer\">\n                    <div class=\"grid_12 mb15px alpha omega\">\n                        <p class=\"product-price h1 alpha grid_6 t-grid_2 mb0px\" itemprop=\"price\">{$product:price}</p>\n                        <div class=\"grid_6 t-grid_4 t-alpha m-alpha t-omega m-omega mt5px\">\n                            <input id=\"productquantity\" class=\"grid_5 larger t-alpha m-alpha t-grid_3 m-grid_1\" type=\"text\" name=\"productquantity-{$product:id}\" value=\"1\"/>\n                            <div class=\"grid_7 t-grid_3 m-grid_3 t-omega m-omega omega text-center\">{$store:addtocart:{$product:id}}</div>\n                        </div>\n                    </div>\n                    <div class=\"tabs grid_12\">\n                        <ul class=\"list-unstyled list-inline\">\n                            <li class=\"mini\"><a href=\"#product-tab1\">{$header:product-tab1:static}</a></li>\n                            <li class=\"mini\"><a href=\"#product-tab2\">{$header:product-tab2:static}</a></li>\n                            <!--li class=\"mini\"><a href=\"#product-tab3\">{$header:product-tab3:static}</a></li-->\n                        </ul>\n                        <div id=\"product-tab1\">\n                            <ul class=\"list-unstyled list-bordered product-info\">\n                                <li><span class=\"title\">Weight:</span> {$product:weight}</li>\n                                <li><span class=\"title\">Brand:</span> {$product:brand}</li>\n                                <li><span class=\"title\">SKU code:</span> {$product:sku}</li>\n                                <li><span class=\"title\">MPN code:</span> {$product:mpn}</li>\n                                <li><span class=\"title\">Product tags:</span> {$product:tags}</li>\n                            </ul>\n                            <p class=\"product-description full\">{$product:description:full}</p>\n                        </div>\n                        <div id=\"product-tab2\" class=\"product-options\">\n                            {$product:options}\n                        </div>\n                        <div id=\"product-tab3\"></div>\n                    </div>\n                    <div class=\"tabs grid_12 mt30px alpha omega\">\n                        <ul class=\"list-unstyled list-inline\">\n                            <li><a href=\"#tabs-1\">{$header:tab1:static}</a></li>\n                            <li><a href=\"#tabs-2\">{$header:tab2:static}</a></li>\n                            <li><a href=\"#tabs-3\">{$header:tab3:static}</a></li>\n                        </ul>\n                        <div id=\"tabs-1\">\n                            {$product:related}\n                        </div>\n                        <div id=\"tabs-2\">\n                            {$productlist:product list sametags:sametags}\n                        </div>\n                        <div id=\"tabs-3\">\n                            {$content:tab3:static}\n                        </div>\n                    </div>\n                </div>\n            </div>\n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script>\n            $(function () {\n            });\n        </script>\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typeproduct'),('product list default','<div class=\"product-item list\">\n    <a href=\"{$product:url}\" class=\"product-image grid_4 t-grid_2 alpha\">\n        <img src=\"{$product:photourl:medium}\" alt=\"{$product:name}\" />\n        {$product:freeshipping:Free shipping}\n    </a>\n    <div class=\"grid_5 t-grid_3 omega\">\n        <a class=\"product-title\" href=\"{$product:url}\">{$product:name}</a>\n        <p class=\"product-description\">{$product:description:short}</p>\n    </div>\n    <div class=\"grid_3 t-grid_1 omega text-center b-prices\">\n        <p class=\"product-{onsale:{$product:id}}new-{/onsale}price\" itemprop=\"price\">{$product:price}</p>\n        {onsale:{$product:id}}\n            <p class=\"product-price\">{$product:price:original}</p>\n        {/onsale}\n        {$store:addtocart:{$product:id}}\n    </div>\n</div>','typelisting'),('product list grid','<div class=\"product-item grid\">\n    <a href=\"{$product:url}\" class=\"product-image grid_4 alpha\">\n        <img src=\"{$product:photourl:medium}\" alt=\"{$product:name}\" />\n        {$product:freeshipping:Free shipping}\n    </a>\n    <div class=\"grid_5 omega\">\n        <a class=\"product-title\" href=\"{$product:url}\">{$product:name}</a>\n        <p class=\"product-description\">{$product:description:short}</p>\n    </div>\n    <div class=\"grid_3 omega text-center b-prices\">\n        {onsale:{$product:id}}\n            <p class=\"product-old-price\">{$product:price:original}</p>\n        {/onsale}\n        <p class=\"product-{onsale:{$product:id}}new-{/onsale}price\" itemprop=\"price\">{$product:price}</p>\n        {$store:addtocart:{$product:id}}\n    </div>\n</div>','typelisting'),('Product list related','<div class=\"product-item related\">\n    <a href=\"{$product:url}\" class=\"product-image\">\n        <img src=\"{$product:photourl:medium}\" alt=\"{$product:name}\" />\n    </a>\n    <div>\n        {$product:freeshipping:Free}\n        {onsale:{$product:id}}\n        <p class=\"product-new-price\">{$product:price:original}</p>\n        {/onsale} \n        <a class=\"product-title\" href=\"{$product:url}\">{$product:name}</a>\n        <p class=\"product-price\" itemprop=\"price\">{$product:price}</p>\n        {$store:addtocart:{$product:id}}\n    </div>\n</div>','typelisting'),('Product list sametags','<div class=\"product-item grid sametags\">\n    <a href=\"{$product:url}\" class=\"product-image\">\n        <img src=\"{$product:photourl:medium}\" alt=\"{$product:name}\" />\n        {$product:freeshipping:Free Shipping}\n    </a>\n    <a class=\"product-title\" href=\"{$product:url}\">{$product:name}</a>\n    <div class=\"b-prices text-center\">\n        <p class=\"product-description\">{$product:description:short}</p>\n        {onsale:{$product:id}}\n            <p class=\"product-old-price\">{$product:price:original}</p>\n        {/onsale}\n        <p class=\"product-price\" itemprop=\"price\">{$product:price}</p>\n        {$store:addtocart:{$product:id}}\n    </div>\n</div>','typelisting'),('product quote','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/flexkit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix {$mobile:device:desktop=d-hide}\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n            \n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <div class=\"logo h1 grid_5 alpha t-hide m-hide\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n\n            <h1 class=\"grid_12 page-header product-name\" itemprop=\"name\">{$product:name}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_12\">\n                <div class=\"grid_5 alpha\">\n                    {$product:editproduct}\n                    <a class=\"_lbox product-image\" href=\"{$product:photourl:original}\">\n                        <img  itemprop=\"image\" src=\"{$product:photourl:medium}\" alt=\"{$product:name}\" />\n                    </a>\n                    <div class=\"product-gallery\">\n                        <!--span>{ $header:product-gallery:static}</span>\n                        { $content:product-gallery}-->\n                        {$directupload:product-{$page:id}:product-img{$page:id}-1:90:gallery:crop}\n                        {$directupload:product-{$page:id}:product-img{$page:id}-2:90:gallery:crop}\n                        {$directupload:product-{$page:id}:product-img{$page:id}-3:90:gallery:crop}\n                        {$directupload:product-{$page:id}:product-img{$page:id}-4:90:gallery:crop}\n                        {$directupload:product-{$page:id}:product-img{$page:id}-5:90:gallery:crop}\n                    </div>\n                    <div class=\"grid_12 alpha omega mt30px product-reviews\">{$plugin:pagerating:review}</div>\n                </div>\n                <div class=\"grid_7 omega\" itemprop=\"offers\" itemscope itemtype=\"http://schema.org/Offer\">\n                   <!--p class=\"product-price h1 alpha \" itemprop=\"price\">{$product:price}</p-->\n                    <div class=\"tabs\">\n                        <ul class=\"list-unstyled list-inline\">\n                            <li class=\"mini\"><a href=\"#product-tab1\">{$header:product-tab1:static}</a></li>\n                            <li class=\"mini\"><a href=\"#product-tab2\">{$header:product-tab2:static}</a></li>\n                            <!--li class=\"mini\"><a href=\"#product-tab3\">{$header:product-tab3:static}</a></li-->\n                        </ul>\n                        <div id=\"product-tab1\">\n                            <ul class=\"list-unstyled list-bordered product-info\">\n                                <li><span class=\"title\">Weight:</span> {$product:weight}</li>\n                                <li><span class=\"title\">Brand:</span> {$product:brand}</li>\n                                <li><span class=\"title\">SKU code:</span> {$product:sku}</li>\n                                <li><span class=\"title\">MPN code:</span> {$product:mpn}</li>\n                                <li><span class=\"title\">Product tags:</span> {$product:tags}</li>\n                            </ul>\n                            <p class=\"product-description full\">{$product:description:full}</p>\n                        </div>\n                        <div id=\"product-tab2\" class=\"product-options\">\n                            {$product:options}\n                        </div>\n                        <div id=\"product-tab3\"></div>\n                    </div>\n                    <!--input id=\"productquantity\" class=\"grid_2 prefix_7\" type=\"text\" name=\"productquantity-{$product:id}\" value=\"1\"/>\n                    <div class=\"grid_3 omega\">{$store:addtocart:{$product:id}}</div-->\n                    <hr/>\n                    <div class=\"h2 mt0px mb10px\">{$header:product-quote:static}</div>\n                    {$quote:form}\n                    <div class=\"tabs grid_12 mt30px alpha omega\">\n                        <ul class=\"list-unstyled list-inline\">\n                            <li><a href=\"#tabs-1\">{$header:tab1:static}</a></li>\n                            <li><a href=\"#tabs-2\">{$header:tab2:static}</a></li>\n                            <li><a href=\"#tabs-3\">{$header:tab3:static}</a></li>\n                        </ul>\n                        <div id=\"tabs-1\">\n                            {$product:related}\n                        </div>\n                        <div id=\"tabs-2\">\n                            {$productlist:product list sametags:sametags}\n                        </div>\n                        <div id=\"tabs-3\">\n                            {$content:tab3:static}\n                        </div>\n                    </div>\n                </div>\n            </div>\n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script>\n            $(function () {\n            });\n        </script>\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/forcekit.min.js\"></script>\n    </body>\n</html>','typeproduct'),('products list','<div class=\"product-item\">\n	<a href=\"{$product:url}\" class=\"product-image\">\n		<img src=\"{$product:photourl:small}\" alt=\"{$product:name}\" />\n	</a>\n	<a class=\"product-title\" href=\"{$product:url}\">{$product:name}</a>\n	<span class=\"product-price\">{$product:price}</span>\n	{$store:addtocart:{$product:id}}\n</div>','typelisting'),('quote','<!DOCTYPE html>\n<html>\n    <head>\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>\n        <meta charset=\"utf-8\">\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\"/>\n        <meta name=\"description\" content=\"{$meta:description}\"/>\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n\n        <!-- Favicons -->\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n        <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"/>\n        <link href=\"css/style.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"/>\n        <link href=\"css/content.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"/>\n        <link href=\"css/nav.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"/>\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"/>\n        {/concatcss}\n        <link href=\'http://fonts.googleapis.com/css?family=Asap|Archivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\'>\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]><script src=\"js/system/ie-pack.js\"></script><![endif]-->\n\n    </head>\n    <body>\n        {adminonly}<div class=\"text-center\">{$quote:search}</div>{/adminonly}\n        <div id=\"container\" class=\"container\">\n\n        <!-- Start of the header -->\n          <header class=\"grid_12 mb20px\">\n              <div class=\'grid_8 alpha logo h1\'>\n                  <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n              </div>\n              <div id=\"storeinfo\" class=\"grid_4 omega\">\n                <h1>{$plugin:shopping:company}</h1>\n                <p>{$plugin:shopping:address1}, {$plugin:shopping:address2}</p>\n                <p>{$plugin:shopping:city} {$plugin:shopping:state} {$plugin:shopping:zip}</p>\n                <p>{$plugin:shopping:country}</p>\n                <p><a href=\"mailto:{$plugin:shopping:email}\">{$plugin:shopping:email}</a></p>\n              </div>\n          </header>\n        <!-- end of the header -->\n\n        <!-- Main Column -->\n          <div id=\"content\" class=\"grid_12 form\">\n\n            {$quote:address:billing}\n            {$quote:address:shipping}\n            {customersonly}\n                <div class=\"quote-info\" id=\"quote-billing-info\">\n                    <p class=\"title\">Billing address</p>\n                    <div>\n                        <p>{$quote:address:billing:firstname} {$quote:address:billing:lastname}</p>\n                        <p>{$quote:address:billing:company}</p>            \n                        <p>{$quote:address:billing:address1} {$quote:address:billing:address2}</p>\n                        <p>{$quote:address:billing:city} {$quote:address:billing:state} {$quote:address:billing:zip}</p>\n                        <p>{$quote:address:billing:country}</p>\n                        <p><a href=\"mailto:{$quote:address:billing:email}\">{$quote:address:billing:email}</a></p>\n                        <p>{$quote:address:billing:phone}</p>\n                    </div>\n                </div>\n                \n                <div class=\"quote-info\" id=\"quote-shipping-info\">\n                    <p class=\"title\">Shipping address</p>\n                    <div>\n                        <p>{$quote:address:shipping:firstname} {$quote:address:shipping:lastname}</p>\n                        <p>{$quote:address:shipping:company}</p>\n                        <p>{$quote:address:shipping:address1} {$quote:address:shipping:address2}</p>\n                        <p>{$quote:address:shipping:city} {$quote:address:shipping:state} {$quote:address:shipping:zip}</p>\n                        <p>{$quote:address:shipping:country}</p>\n                        <p><a href=\"mailto:{$quote:address:shipping:email}\">{$quote:address:shipping:email}</a></p>\n                        <p>{$quote:address:shipping:phone}</p>\n                    </div>    \n                </div>\n            {/customersonly}\n\n            <div id=\"quote-details\" class=\"mb20px grid_12\">\n                <h3>{adminonly}Quote title: {/adminonly}{$quote:title}</h3>\n                <div class=\"dates mb5px\">created on: <span>{$quote:date:created}</span></div>\n                <div class=\"dates\">expires: <span>{$quote:date:expires}</span></div>\n            </div>\n            <table id=\"toaster-quote\" class=\"toaster-quote-content cart-content mb20px\">\n                    <thead class=\"t-hide m-hide\">\n                        <tr>\n                            <th class=\"product-info\" colspan=\"2\">Product</th>\n                            <th class=\"product-note\">Note</th>\n                            <th class=\"product-unit-price\">Unit price</th>\n                            <th class=\"product-qty\">Qty</th>\n                            <th class=\"product-total\">Total</th>\n                            <th class=\"product-remove\">Remove</th>\n                        </tr>\n                    </thead>\n                    <tbody>\n                    {toasterquote}\n                    <tr>\n                        <td class=\"product-img\"> {$quote:item:photo} </td>\n                        <td class=\"product-info\">{$quote:item:name}\n                            <p class=\"itemID\"><span>Item ID: </span>{$quote:item:sku}</p>\n                            {$quote:item:options}\n                        </td>\n                        <td class=\"product-note\">{$header:{$quote:item:id}-note}</td>\n                        <td class=\"product-unit-price\">{$quote:item:price:unit}</td>\n                        <td class=\"product-qty\">{$quote:item:qty}</td>\n                        <td class=\"product-total\">{$quote:item:price}</td>\n                        <td class=\"product-remove\">{$quote:item:remove}</td>\n                    </tr>\n                    {/toasterquote}\n                    </tbody>\n                </table>\n                <div id=\"quote-summary\" class=\"grid_3 prefix_9\">\n                    <h3 class=\"quote-widget-title\">Summary</h3>\n                    <div id=\"cart-summary\">\n                        <div><span>Subtotal:</span>{$quote:total:sub}</div>\n                        <div><span>Total Tax:</span>{$quote:total:tax}</div>\n                        <div><span>Shipping:</span>{$quote:shipping}</div>\n                        <div><span>Discount:</span>{$quote:discount}</div>\n                        <div class=\"cart-total\"><span>Total:</span>{$quote:total}</div>\n                        <div class=\"cart-total\"><span>Efective tax:</span>{$quote:total:taxdiscount}</div>\n                    </div>\n                </div>\n          </div>\n\n        </div>\n        {adminonly}\n            <!-- ADMIN PANEL -->\n            <div id=\"quoteControlls\">{$quote:controls}</div>\n        {/adminonly}\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n\n    </body>\n</html>','typequote'),('Store - new customer','<html>\n<head>\n    <title>{$plugin:widcard:BizOrgName:notag}</title>\n            <!-- Favicons -->\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n        <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n</head>\n\n<body style=\"font-size: 14px; font-family: Arial, Verdana, Sans-serif; color: #444; margin: 0;\">\n        <table bgcolor=\"#f5f5f5\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:#f5f5f5; \">\n            <tr>\n				<td>\n					\n					<!-- E-mail tempalte -->\n					<table align=\"center\" width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">\n						<!--tr>\n							<td align=\"center\" style=\"color: #999; font-size: 12px; padding: 5px 0;\">\n								Having trouble viewing this email? <a href=\"#\" style=\"color: #777;\">View it on your browser</a>\n							</td>\n						</tr-->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n    					<!-- header -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background-color: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td rowspan=\"2\" valign=\"middle\" width=\"250\" style=\"padding: 23px 0 20px 20px;\">    				    					                                                                                                                  \n										    <a href=\"{$website:url}\" title=\"Home\" style=\"color: #3498db; text-decoration: none; font-size: 24px;\">San Francisco Bikes</a>    									    \n										</td>\n										<td style=\"color: #444444; font-size: 22px; font-weight: bold; padding: 10px 0 0;\">\n											{$header:nc-title:static}\n										</td>\n										<td width=\"80\" rowspan=\"2\" align=\"center\">\n                        					<a target=\"_blank\" href=\"{$plugin:widcard:BizFbAccount:notag}\"><img src=\"{$website:url}themes/default/images/fb.png\"></a>\n                    						<a target=\"_blank\" href=\"{$plugin:widcard:BizTwitterAccount:notag}\"><img src=\"{$website:url}themes/default/images/tw.png\"></a>\n                    					</td>\n									</tr>\n									<tr>\n										<td style=\"color: #444444; font-size: 20px; padding: 0 0 20px;\">\n											 {$header:nc-subtitle:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- header -->\n						\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n					\n						<!-- content -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 20px;\">\n                                            <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n                                                <tbody>\n                                                <tr>\n                                                <td width=\"50%\" style=\"padding: 0 10px;\">\n                                                <p style=\"font-size: 20px; margin-bottom: 7px; font-weight:700;\">{$header:NC-emailmessage:static}</p>\n                                                {$content:NC-emailmessage:static}\n                                                \n                                                </td>\n                                                <td valign=\"top\" bgcolor=\"#f7f7f7\" width=\"50%\" style=\"padding: 0 10px;\">\n                                                <p style=\"font-size: 20px; margin-bottom: 7px; font-weight:700;\">{$header:NC-acessinfo:static}</p>\n                                                {$content:acessinfo:static}\n                                                \n                                                </td>\n                                                </tr>\n                                                </tbody>\n                                                </table>\n											\n \n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- content -->\n						<!-- footer -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#333333\" style=\"background: #333333;\">\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; padding: 20px 10px 5px;\">\n												{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag}\n										</td>\n									</tr>\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; font-weight: bold; padding: 5px 10px 20px;\">\n												CALL US TOLL FREE:  {$plugin:widcard:BizTelephone:notag}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- footer -->\n\n						<!-- copyright -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td align=\"right\" style=\"font-size: 12px; color: #666; padding: 5px 0 25px;\">\n											<span>{$header:copy:static}</span>\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- copyright -->\n\n					</table>\n					<!-- E-mail tempalte -->\n\n				</td>\n			</tr>\n		</table>\n	</body>    \n</html>','typemail'),('Store - new sale notification','<html>\n    <head>\n    	<title>{$plugin:widcard:BizOrgName:notag}</title>\n        <!-- Favicons -->\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n        <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n	</head>\n	<body style=\"font-size: 14px; font-family: Arial, Verdana, Sans-serif; color: #444444; margin: 0;\">\n		<table bgcolor=\"#f5f5f5\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #F5F5F5;\">\n			<tr>\n				<td>\n					<!-- E-mail tempalte -->\n					<table align=\"center\" width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n						<!-- header -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background-color: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td rowspan=\"2\" valign=\"middle\" width=\"250\" style=\"padding: 23px 0 20px 20px;\">    				    					                                                                                                                  \n										    <a href=\"{$website:url}\" title=\"Home\" style=\"color: #3498db; text-decoration: none; font-size: 24px;\">San Francisco Bikes</a>    									    \n										</td>\n										<td style=\"color: #444444; font-size: 22px; font-weight: bold; padding: 10px 0 0;\">\n											{$header:snsn-title:static}\n										</td>\n										<td width=\"80\" rowspan=\"2\" align=\"center\">\n                        					<a target=\"_blank\" href=\"{$plugin:widcard:BizFbAccount:notag}\"><img src=\"{$website:url}themes/default/images/fb.png\"></a>\n                    						<a target=\"_blank\" href=\"{$plugin:widcard:BizTwitterAccount:notag}\"><img src=\"{$website:url}themes/default/images/tw.png\"></a>\n                    					</td>\n									</tr>\n									<tr>\n										<td style=\"color: #444444; font-size: 20px; padding: 0 0 20px;\">\n											 {$header:snsn-subtitle:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- header -->\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n						<!-- content -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 20px 10px; font-size: 24px; font-family: Arial; font-weight: bold; color: #444444;\">\n											{$header:snsn-hello:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 10px 20px 10px; line-height: 1.4;\">\n                                        {$content:snsn-content:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 0 20px;\">\n											<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  bgcolor=\"#EEEEEE\" style=\"background: #EEEEEE; padding: 10px;\">\n												<tr>\n													<td width=\"15%\" style=\"font-weight: bold; text-align: right; padding: 3px;\">Date:</td>\n													<td width=\"25%\" style=\"padding: 3px;\">{order:createdat}</td>\n													<td width=\"30%\" style=\"font-weight: bold; text-align: right;padding: 3px;\">Total:</td>\n													<td width=\"30%\">{order:total}</td>\n												</tr>\n												<tr>\n													<td width=\"15%\" style=\"font-weight: bold; text-align: right;padding: 3px;\">IP:</td>\n													<td width=\"25%\" style=\"padding: 3px;\">{order:ipaddress}</td>\n													<td rowspan=\"3\" valign=\"top\" width=\"30%\" style=\"font-weight: bold; text-align: right;padding: 3px;\">Shipping Address:</td>\n													<td rowspan+\"3\" width=\"30%\" style=\"padding: 3px;\">{order:shippingaddress}</td>\n												</tr>\n												<tr>\n													<td width=\"15%\" style=\"font-weight: bold; text-align: right; padding: 3px;\">Through:</td>\n													<td width=\"25%\" style=\"padding: 3px;\">{order:referer}</td>\n												</tr>\n											</table> \n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 10px 20px 30px;\">\n											{$store:postpurchasereport:mailreport:mpn:Code}	\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- content -->\n\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF; padding: 20px 20px 0;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 0 20px; border-top: 1px solid #EEEEEE; text-align: center;\">\n                                            {$header:snsn-overfooter-1:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n\n						<!-- footer -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#333333\" style=\"background: #333333;\">\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; padding: 20px 10px 5px;\">\n												{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag}\n										</td>\n									</tr>\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; font-weight: bold; padding: 5px 10px 20px;\">\n												CALL US TOLL FREE:  {$plugin:widcard:BizTelephone:notag}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- footer -->\n						<!-- copyright -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n\n										</td>\n										<td align=\"right\" style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n											Copyright 2013 seotoaster.com\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- copyright -->\n					</table>\n					<!-- E-mail tempalte -->\n				</td>\n			</tr>\n		</table>\n	</body>\n</html>','typemail'),('Store - purchase receipt','<html>\n    <head>\n    	<title>{$plugin:widcard:BizOrgName:notag}</title>\n            <!-- Favicons -->\n    <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n    <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n	</head>\n	<body style=\"font-size: 14px; font-family: Arial, Verdana, Sans-serif; color: #444444; margin: 0;\">\n		<table bgcolor=\"#f5f5f5\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #F5F5F5;\">\n			<tr>\n				<td>\n					<!-- E-mail tempalte -->\n					<table align=\"center\" width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n						<!-- header -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background-color: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td rowspan=\"2\" valign=\"middle\" width=\"250\" style=\"padding: 23px 0 20px 20px;\">    				    					                                                                                                                  \n										    <a href=\"{$website:url}\" title=\"Home\" style=\"color: #3498db; text-decoration: none; font-size: 24px;\">San Francisco Bikes</a>    									    \n										</td>\n										<td style=\"color: #444444; font-size: 22px; font-weight: bold; padding: 10px 0 0;\">\n											{$header:spr-title:static}\n										</td>\n										<td width=\"80\" rowspan=\"2\" align=\"center\">\n                        					<a target=\"_blank\" href=\"{$plugin:widcard:BizFbAccount:notag}\"><img src=\"{$website:url}themes/default/images/fb.png\"></a>\n                    						<a target=\"_blank\" href=\"{$plugin:widcard:BizTwitterAccount:notag}\"><img src=\"{$website:url}themes/default/images/tw.png\"></a>\n                    					</td>\n									</tr>\n									<tr>\n										<td style=\"color: #444444; font-size: 20px; padding: 0 0 20px;\">\n											 {$header:spr-subtitle:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- header -->\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n						<!-- content -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 20px 10px; font-size: 24px; font-family: Arial; font-weight: bold; color: #444444;\">\n											{$header:spr-hello:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 10px 20px 10px; line-height: 1.4;\">\n                                            {$content:spr-content:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 10px 20px 10px; font-size: 17px; font-family: Arial; font-weight: bold; color: #444444;\">\n											{$header:spr-order:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 10px 20px 30px;\">\n											{$store:postpurchasereport:mailreport:mpn:Code}	\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- content -->\n\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF; padding: 20px 20px 0;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 0 0; border-top: 1px solid #EEEEEE; text-align: center;\">\n                                            {$header:spr-overfooter-1:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 25px 0 20px; text-align: center; font-size: 24px; font-weight: bold;\">\n                                            {$header:spr-overfooter-2:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n\n						<!-- footer -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#333333\" style=\"background: #333333;\">\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; padding: 20px 10px 5px;\">\n												{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag}\n										</td>\n									</tr>\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; font-weight: bold; padding: 5px 10px 20px;\">\n												CALL US TOLL FREE:  {$plugin:widcard:BizTelephone:notag}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- footer -->\n						<!-- copyright -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n\n										</td>\n										<td align=\"right\" style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n											Copyright 2013 seotoaster.com\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- copyright -->\n					</table>\n					<!-- E-mail tempalte -->\n				</td>\n			</tr>\n		</table>\n	</body>\n</html>','typemail'),('Store - quote updated','<html>\n    <head>\n        <title>{$plugin:widcard:BizOrgName:notag}</title>\n            <!-- Favicons -->\n    <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n    <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n    </head>\n    <body style=\"font-size: 14px; font-family: Arial, Verdana, Sans-serif; color: #444444; margin: 0;\">\n		<table bgcolor=\"#f5f5f5\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #F5F5F5;\">\n			<tr>\n				<td>\n					<!-- E-mail tempalte -->\n					<table align=\"center\" width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n						<!-- header -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background-color: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td rowspan=\"2\" valign=\"middle\" width=\"250\" style=\"padding: 23px 0 20px 20px;\">					    					                                                                                                                  \n										    <a href=\"{$website:url}\" title=\"Home\" style=\"color: #3498db; text-decoration: none; font-size: 24px;\">San Francisco Bikes</a>    									    \n										</td>\n										<td style=\"color: #444444; font-size: 28px; font-weight: bold;\">\n											{$header:qu-title:static}\n										</td>\n										<td width=\"80\" rowspan=\"2\" align=\"center\">\n                        					<a target=\"_blank\" href=\"{$plugin:widcard:BizFbAccount:notag}\"><img src=\"{$website:url}themes/default/images/fb.png\"></a>\n                    						<a target=\"_blank\" href=\"{$plugin:widcard:BizTwitterAccount:notag}\"><img src=\"{$website:url}themes/default/images/tw.png\"></a>\n                    					</td>\n									</tr>									\n								</table>\n							</td>\n						</tr>\n						<!-- header -->\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n						<!-- content -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 20px 10px; color: #444444; font-size: 24px; font-weight:bold;\">\n											{$header:qu-hello:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 10px 20px 10px; line-height: 1.4; \">\n											{emailmessage}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 30px 0; font-weight: bold; font-size: 24px; text-align: center;\">\n											{$header:qu-header-3:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- content -->\n						<!-- footer -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#333333\" style=\"background: #333333;\">\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; padding: 20px 10px 5px;\">\n												{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag}\n										</td>\n									</tr>\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; font-weight: bold; padding: 5px 10px 20px;\">\n												CALL US TOLL FREE:  {$plugin:widcard:BizTelephone:notag}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- footer -->\n						<!-- copyright -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n\n										</td>\n										<td align=\"right\" style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n											Copyright 2013 seotoaster.com\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- copyright -->\n					</table>\n					<!-- E-mail tempalte -->\n				</td>\n			</tr>\n		</table>\n	</body>\n</html>','typemail'),('Store - tracking code','<html>\n    <head>\n        <title>{$plugin:widcard:BizOrgName:notag}</title>\n                <!-- Favicons -->\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n        <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n	</head>\n	<body style=\"font-size: 14px; font-family: Arial, Verdana, Sans-serif; color: #444444; margin: 0;\">\n		<table bgcolor=\"#f5f5f5\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #F5F5F5;\">\n			<tr>\n				<td>\n					<!-- E-mail tempalte -->\n					<table align=\"center\" width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n        				<!-- header -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background-color: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td rowspan=\"2\" valign=\"middle\" width=\"250\" style=\"padding: 23px 0 20px 20px;\">    				    					                                                                                                                  \n										    <a href=\"{$website:url}\" title=\"Home\" style=\"color: #3498db; text-decoration: none; font-size: 24px;\">San Francisco Bikes</a>    									    \n										</td>\n										<td style=\"color: #444444; font-size: 22px; font-weight: bold; padding: 10px 0 0;\">\n											{$header:shtc-title:static}\n										</td>\n										<td width=\"80\" rowspan=\"2\" align=\"center\">\n                        					<a target=\"_blank\" href=\"{$plugin:widcard:BizFbAccount:notag}\"><img src=\"{$website:url}themes/default/images/fb.png\"></a>\n                    						<a target=\"_blank\" href=\"{$plugin:widcard:BizTwitterAccount:notag}\"><img src=\"{$website:url}themes/default/images/tw.png\"></a>\n                    					</td>\n									</tr>\n									<tr>\n										<td style=\"color: #444444; font-size: 20px; padding: 0 0 20px;\">\n											 {$header:shtc-subtitle:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- header -->\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n						<!-- content -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 20px 10px; color: #444444; font-size: 24px; font-weight:bold;\">\n											{$header:shtc-hello:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 10px 20px 10px; line-height: 1.4; \">\n											{$content:shtc-content:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 0 20px;\">\n											<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  bgcolor=\"#EEEEEE\" style=\"background: #EEEEEE;\">\n												<tr>\n													<td style=\"padding: 10px 10px 5px; font-size: 16px; font-weight: bold;\">\n														{$header:shtc-header-2:static}\n													</td>\n												</tr>\n												<tr>\n													<td style=\"padding: 5px 10px 10px;\">{order:shippingtrackingid}</td>\n												</tr>\n											</table> \n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 10px 0; font-weight: bold; font-size: 24px; text-align: center;\">\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- content -->\n						<!-- footer -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#333333\" style=\"background: #333333;\">\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; padding: 20px 10px 5px;\">\n												{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag}\n										</td>\n									</tr>\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; font-weight: bold; padding: 5px 10px 20px;\">\n												CALL US TOLL FREE:  {$plugin:widcard:BizTelephone:notag}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- footer -->\n						<!-- copyright -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n\n										</td>\n										<td align=\"right\" style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n											Copyright 2013 seotoaster.com\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- copyright -->\n					</table>\n					<!-- E-mail tempalte -->\n				</td>\n			</tr>\n		</table>\n	</body>\n</html>','typemail'),('Store -quote created','<html>\n    <head>\n        <title>{$plugin:widcard:BizOrgName:notag}</title>\n            <!-- Favicons -->\n    <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n    <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n    </head>\n	<body style=\"font-size: 14px; font-family: Arial, Verdana, Sans-serif; color: #444444; margin: 0;\">\n		<table bgcolor=\"#f5f5f5\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #F5F5F5;\">\n			<tr>\n				<td>\n					<!-- E-mail tempalte -->\n					<table align=\"center\" width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n						<!-- header -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background-color: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td rowspan=\"2\" valign=\"middle\" width=\"250\" style=\"padding: 23px 0 20px 20px;\">    				    					                                                                                                                  \n										    <a href=\"{$website:url}\" title=\"Home\" style=\"color: #3498db; text-decoration: none; font-size: 24px;\">San Francisco Bikes</a>    									    \n										</td>\n										<td style=\"color: #444444; font-size: 22px; font-weight: bold; padding: 10px 0 0;\">\n											{$header:qc-title:static}\n										</td>\n										<td width=\"80\" rowspan=\"2\" align=\"center\">\n                        					<a target=\"_blank\" href=\"{$plugin:widcard:BizFbAccount:notag}\"><img src=\"{$website:url}themes/default/images/fb.png\"></a>\n                    						<a target=\"_blank\" href=\"{$plugin:widcard:BizTwitterAccount:notag}\"><img src=\"{$website:url}themes/default/images/tw.png\"></a>\n                    					</td>\n									</tr>\n									<tr>\n										<td style=\"color: #444444; font-size: 20px; padding: 0 0 20px;\">\n											 {$header:qc-subtitle:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- header -->\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n						<!-- content -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 20px 10px; color: #444444; font-size: 24px; font-weight:bold;\">\n											{$header:qc-hello:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 10px 20px 10px; line-height: 1.4; \">\n											{$content:qc-content:static}\n										</td>\n									</tr>\n									<tr>\n										<td style=\"padding: 30px 0; font-weight: bold; font-size: 24px; text-align: center;\">\n											{$header:qc-header-3:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- content -->\n						<!-- footer -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#333333\" style=\"background: #333333;\">\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; padding: 20px 10px 5px;\">\n												{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag}\n										</td>\n									</tr>\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; font-weight: bold; padding: 5px 10px 20px;\">\n												CALL US TOLL FREE:  {$plugin:widcard:BizTelephone:notag}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- footer -->\n						<!-- copyright -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n\n										</td>\n										<td align=\"right\" style=\"font-size: 12px; color: #666666; padding: 5px 0 25px;\">\n											Copyright 2013 seotoaster.com\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- copyright -->\n					</table>\n					<!-- E-mail tempalte -->\n				</td>\n			</tr>\n		</table>\n	</body>\n</html>','typemail'),('Toaster - form rempy','<html>\n<head>\n    <title>{$plugin:widcard:BizOrgName:notag}</title>\n                <!-- Favicons -->\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n        <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n</head>\n\n<body style=\"font-size: 14px; font-family: Arial, Verdana, Sans-serif; color: #444; margin: 0;\">\n        <table bgcolor=\"#f5f5f5\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #f5f5f5;\">\n            <tr>\n				<td>\n					\n					<!-- E-mail tempalte -->\n					<table align=\"center\" width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">\n						<!--tr>\n							<td align=\"center\" style=\"color: #999; font-size: 12px; padding: 5px 0;\">\n								Having trouble viewing this email? <a href=\"#\" style=\"color: #777;\">View it on your browser</a>\n							</td>\n						</tr-->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n        				<!-- header -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background-color: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td rowspan=\"2\" valign=\"middle\" width=\"250\" style=\"padding: 23px 0 20px 20px;\">    				    					                                                                                                                  \n										    <a href=\"{$website:url}\" title=\"Home\" style=\"color: #3498db; text-decoration: none; font-size: 24px;\">San Francisco Bikes</a>    									    \n										</td>\n										<td style=\"color: #444444; font-size: 22px; font-weight: bold; padding: 10px 0 0;\">\n											{$header:fr-title:static}\n										</td>\n										<td width=\"80\" rowspan=\"2\" align=\"center\">\n                        					<a target=\"_blank\" href=\"{$plugin:widcard:BizFbAccount:notag}\"><img src=\"{$website:url}themes/default/images/fb.png\"></a>\n                    						<a target=\"_blank\" href=\"{$plugin:widcard:BizTwitterAccount:notag}\"><img src=\"{$website:url}themes/default/images/tw.png\"></a>\n                    					</td>\n									</tr>\n									<tr>\n										<td style=\"color: #444444; font-size: 20px; padding: 0 0 20px;\">\n											 {$header:fr-subtitle:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- header -->\n						\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n					\n						<!-- content -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									\n										\n                                            <tr>\n                                            <td colspan=\"4\" align=\"center\" style=\"padding:15px 20px 0; font-size:28px; text-align:left;\">\n                                                {$header:frs-hello:static}    \n                                            </td>\n                                        </tr>\n											<tr>\n                                            <td style=\"padding: 0 20px; line-height:1.5em\">\n                                            {$content:frs-content:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- content -->\n                        \n						\n						<!-- footer -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#333333\" style=\"background: #333333;\">\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; padding: 20px 10px 5px;\">\n												{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag}\n										</td>\n									</tr>\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; font-weight: bold; padding: 5px 10px 20px;\">\n												CALL US TOLL FREE:  {$plugin:widcard:BizTelephone:notag}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- footer -->\n\n						<!-- copyright -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td align=\"right\" style=\"font-size: 12px; color: #666; padding: 5px 0 25px;\">\n											<span>Copyright 2013 seotoaster.com</span>\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- copyright -->\n\n					</table>\n					<!-- E-mail tempalte -->\n\n				</td>\n			</tr>\n		</table>\n	</body>    \n</html>','typemail'),('Toaster - new user info','<html>\n<head>\n    <title>{$plugin:widcard:BizOrgName:notag}</title>\n    <!-- Favicons -->\n    <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n    <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n</head>\n\n<body style=\"font-size: 14px; font-family: Arial, Verdana, Sans-serif; color: #444; margin: 0;\">\n        <table bgcolor=\"#f5f5f5\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:#f5f5f5; \">\n            <tr>\n    			<td>\n					\n					<!-- E-mail tempalte -->\n					<table align=\"center\" width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">\n						<!--tr>\n							<td align=\"center\" style=\"color: #999; font-size: 12px; padding: 5px 0;\">\n								Having trouble viewing this email? <a href=\"#\" style=\"color: #777;\">View it on your browser</a>\n							</td>\n						</tr-->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- header -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td rowspan=\"2\" valign=\"middle\" width=\"250\" style=\"padding: 23px 0 20px 20px;\">    				    					                                                                                                                  \n										    <a href=\"{$website:url}\" title=\"Home\" style=\"color: #3498db; text-decoration: none; font-size: 24px;\">San Francisco Bikes</a>    									    \n										</td>\n										<td style=\"color: #444444; font-size: 22px; font-weight: bold; padding: 10px 0 0;\">\n											{$header:nui-title:static}\n										</td>\n										<td width=\"80\" rowspan=\"2\" align=\"center\">\n                        					<a target=\"_blank\" href=\"{$plugin:widcard:BizFbAccount:notag}\"><img	src=\"{$website:url}themes/default/images/fb.png\"></a>\n                    						<a target=\"_blank\" href=\"{$plugin:widcard:BizTwitterAccount:notag}\"><img src=\"{$website:url}themes/default/images/tw.png\"></a>\n                    					</td>\n									</tr>\n									<tr>\n										<td style=\"color: #444444; font-size: 20px; padding: 0 0 20px;\">\n											 {$header:nui-subtitle:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- header -->\n						\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n					\n						<!-- content -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 20px;\">\n                                            <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n                                                <tbody>\n                                                <tr>\n                                                <td style=\"padding: 10px 0;\">\n                                                <p style=\"font-size: 20px; margin-bottom: 0 0 6px; font-weight:700;\">{$header:nui-hello:static}</p>\n                                                {$content:nui-content:static}\n                                                </td>\n                                                </tr>\n                                                <tr>\n                                                <td valign=\"top\" bgcolor=\"#f7f7f7\" style=\"padding: 10px;\">\n                                                <p style=\"font-size: 20px; margin-bottom: 0 0 6px; font-weight:700;\">{$header:nui-acessinfo:static}</p>\n                                                {$content:nui-acessinfo:static}\n                                                </td>\n                                                </tr>\n                                                <tr>\n                                                	<td height=\"20\">\n                                                		\n                                                	</td>\n                                                </tr>\n                                            	<tr>\n                                                <td style=\"padding: 10px 0;\">\n                                                {$content:nui-link:static}\n                                                </td>\n                                                </tr>\n                                                </tbody>\n                                                </table>\n											\n \n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- content -->\n						<!-- footer -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#333333\" style=\"background: #333333;\">\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; padding: 20px 10px 5px;\">\n												{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag}\n										</td>\n									</tr>\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; font-weight: bold; padding: 5px 10px 20px;\">\n												CALL US TOLL FREE:  {$plugin:widcard:BizTelephone:notag}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- footer -->\n\n						<!-- copyright -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td align=\"right\" style=\"font-size: 12px; color: #666; padding: 5px 0 25px;\">\n											<span>{$header:copy:static}</span>\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- copyright -->\n\n					</table>\n					<!-- E-mail tempalte -->\n\n				</td>\n			</tr>\n		</table>\n	</body>    \n</html>','typemail'),('Toaster Reset link','<html>\n<head>\n    <title>{$plugin:widcard:BizOrgName:notag}</title>\n    <!-- Favicons -->\n    <link rel=\"shortcut icon\" href=\"images/favicon.ico\">\n    <link rel=\"apple-touch-icon\" href=\"images/apple-icon.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/apple-icon-72x72.png\">\n    <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/apple-icon-114x114.png\">\n</head>\n\n<body style=\"font-size: 14px; font-family: Arial, Verdana, Sans-serif; color: #444; margin: 0;\">\n        <table bgcolor=\"#f5f5f5\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:#f5f5f5; \">\n            <tr>\n    			<td>\n					\n					<!-- E-mail tempalte -->\n					<table align=\"center\" width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">\n						<!--tr>\n							<td align=\"center\" style=\"color: #999; font-size: 12px; padding: 5px 0;\">\n								Having trouble viewing this email? <a href=\"#\" style=\"color: #777;\">View it on your browser</a>\n							</td>\n						</tr-->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- header -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td rowspan=\"2\" valign=\"middle\" width=\"250\" style=\"padding: 23px 0 20px 20px;\">    				    					                                                                                                                  \n										    <a href=\"{$website:url}\" title=\"Home\" style=\"color: #3498db; text-decoration: none; font-size: 24px;\">San Francisco Bikes</a>    									    \n										</td>\n										<td style=\"color: #444444; font-size: 22px; font-weight: bold; padding: 10px 0 0;\">\n											{$header:nui-title:static}\n										</td>\n										<td width=\"80\" rowspan=\"2\" align=\"center\">\n                        					<a target=\"_blank\" href=\"{$plugin:widcard:BizFbAccount:notag}\"><img	src=\"{$website:url}themes/default/images/fb.png\"></a>\n                    						\n                    						<a target=\"_blank\" href=\"{$plugin:widcard:BizTwitterAccount:notag}\"><img src=\"{$website:url}themes/default/images/tw.png\"></a>\n                    					</td>\n									</tr>\n									<tr>\n										<td style=\"color: #444444; font-size: 20px; padding: 0 0 20px;\">\n											 {$header:nui-subtitle:static}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- header -->\n						\n						<!-- space -->\n						<tr>\n							<td height=\"20\"></td>\n						</tr>\n						<!-- space -->\n					\n						<!-- content -->\n						<tr>\n							<td bgcolor=\"#FFFFFF\" style=\"background: #FFFFFF;\">\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td style=\"padding: 20px 20px;\">\n                                            <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n                                                <tbody>\n                                                <tr>\n                                                <td style=\"padding: 10px 0;\">\n                                                <p style=\"font-size: 20px; margin-bottom: 0 0 6px; font-weight:700;\">{$header:nui-hello:static}</p>\n                                                {$content:nui-content:static}\n                                                </td>\n                                                </tr>\n                                                <tr>\n                                                <td valign=\"top\" bgcolor=\"#f7f7f7\" style=\"padding: 10px;\">\n                                                <p style=\"font-size: 20px; margin-bottom: 0 0 6px; font-weight:700;\">{$header:nui-acessinfo:static}</p>\n                                                {$content:nui-acessinfo:static}\n                                                </td>\n                                                </tr>\n                                                <tr>\n                                                	<td height=\"20\">\n                                                		\n                                                	</td>\n                                                </tr>\n                                            	<tr>\n                                                <td style=\"padding: 10px 0;\">\n                                                {$content:nui-link:static}\n                                                </td>\n                                                </tr>\n                                                </tbody>\n                                                </table>\n											\n \n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- content -->\n						<!-- footer -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#333333\" style=\"background: #333333;\">\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; padding: 20px 10px 5px;\">\n												{$plugin:widcard:BizOrgName:notag} | {$plugin:widcard:BizAddress1:notag}, {$plugin:widcard:BizCity:notag}, {$plugin:widcard:BizState:notag} {$plugin:widcard:BizZip:notag}\n										</td>\n									</tr>\n									<tr>\n										<td align=\"center\" style=\"color: #EEEEEE; font-size: 12px; font-weight: bold; padding: 5px 10px 20px;\">\n												CALL US TOLL FREE:  {$plugin:widcard:BizTelephone:notag}\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- footer -->\n\n						<!-- copyright -->\n						<tr>\n							<td>\n								<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n									<tr>\n										<td align=\"right\" style=\"font-size: 12px; color: #666; padding: 5px 0 25px;\">\n											<span>{$header:copy:static}</span>\n										</td>\n									</tr>\n								</table>\n							</td>\n						</tr>\n						<!-- copyright -->\n\n					</table>\n					<!-- E-mail tempalte -->\n\n				</td>\n			</tr>\n		</table>\n	</body>    \n</html>','typemail'),('user landing','<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=\"utf-8\">\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><![endif]-->\n        <title>{$page:title}</title>\n        <meta name=\"keywords\" content=\"{$meta:keywords}\">\n        <meta name=\"description\" content=\"{$meta:description}\">\n\n        <!-- Mobile Specific Metas -->\n        <meta name=\"HandheldFriendly\" content=\"True\">\n        <meta name=\"MobileOptimized\" content=\"480\">\n        <meta http-equiv=\"cleartype\" content=\"on\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\">\n\n        <!-- Add to homescreen for Chrome on Android -->\n        <meta name=\"mobile-web-app-capable\" content=\"yes\">\n\n        <!-- Add to homescreen for Safari on iOS -->\n        <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n        <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\">\n        <meta name=\"apple-mobile-web-app-title\" content=\"{$page:title}\">\n\n        <!-- Favicons -->\n        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"images/touch/144x144.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"images/touch/114x114.png\">\n        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"images/touch/72x72.png\">\n        <link rel=\"apple-touch-icon\" href=\"images/touch/57x57.png\">\n\n        <!-- Tile icon for Win8 (144x144 + tile color) -->\n        <meta name=\"msapplication-TileImage\" content=\"images/touch/144x144.png\">\n        <meta name=\"msapplication-TileColor\" content=\"#3372DF\">\n\n        <!-- Generic Icon -->\n        <link rel=\"icon\" href=\"images/favicon.ico\">\n        <!--[if IE]>\n        <link rel=\"shortcut icon\" href=\"images/favicon.ico\"><![endif]-->\n\n        <!-- Fonts -->\n        <link href=\'//fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic%7CArchivo+Narrow:400,700\' rel=\'stylesheet\' type=\'text/css\' media=\"screen\">\n\n        <!-- CSS -->\n        {concatcss}\n        <link href=\"css/reset.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/content.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/nav.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/style.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Shopping css -->\n        <link href=\"css/store.css\" rel=\"stylesheet\" media=\"screen\">\n        <!-- Additional css -->\n        <link href=\"css/forcekit.css\" rel=\"stylesheet\" media=\"screen\">\n        <link href=\"css/animation.css\" rel=\"stylesheet\" media=\"screen\">\n        {/concatcss}\n\n        <!-- HTML5shiv and selectivizr.js IE support of HTML5 elements and CSS3 pseudo-classes, attribute selectors -->\n        <!--[if lt IE 9]>\n        <script src=\"js/system/ie-pack.min.js\"></script><![endif]-->\n\n    </head>\n    <body class=\"{$mobile:device}\">\n\n        <!-- Top bar for mobile, with button show/hide menu -->\n        <div class=\"top-block clearfix {$mobile:device:desktop=d-hide}\">\n            <label data-menu=\"#main-menu .main_menu\" data-menu-position=\"left\" class=\"menu-btn fl-left icon-list2 btn large\">Menu</label>\n            \n            <label class=\"dropdown-btn fl-right icon-cart2 btn large\" data-menu=\".toaster-cart\" data-menu-position=\"right\" ></label>\n        </div>\n\n        <!-- Container -->\n        <div id=\"container\" class=\"container\">\n\n            <!-- Header -->\n            <header class=\"grid_12 mb20px\" role=\"banner\">\n                <div class=\"logo h1 grid_5 alpha t-hide m-hide\">\n                    <a href=\"{$website:url}\" title=\"{$page:h1}\">\n                        <img src=\"{$plugin:widcard:bizLogo:url}\" alt=\"{$plugin:widcard:BizOrgName:notag}\">{$plugin:widcard:BizOrgName:notag}\n                    </a>\n                </div>\n                <div class=\"grid_7 omega mt30px\">{$store:cartblock}</div>\n            </header>\n\n            <div class=\"brand-box grid_12\">\n\n                <!-- Main Menu -->\n                <nav id=\"main-menu\" class=\"grid_9 alpha\" role=\"navigation\">\n                    {$menu:main:main menu}\n                </nav>\n\n                <!-- Search -->\n                <div class=\"grid_3 omega\">{$search:form}</div>\n            </div>\n\n            <h1 class=\"grid_12 h2 page-header\">{$page:h1}</h1>\n\n            <!-- Content box -->\n            <div id=\"content\" class=\"grid_8 toaster-widget\">\n                {$user:tabs:orders,Download goods, Edit Account}\n            </div>\n\n            <!-- Right box -->\n            <div id=\"right\" class=\"grid_4\">\n                <ul class=\"right-box list-unstyled profile-user-info\">\n                    <li class=\"larger\">Hello, <strong>{$user:name}</strong></li>\n                    <li><strong>Your e-mail:</strong> {$user:email}</li>\n                    <li><strong>Registration date:</strong> {$user:registration}</li>\n                    <li><strong>Last seen:</strong> {$user:lastlogin}</li>\n                </ul>\n                <div class=\"right-box\">\n                    <h3>{$header:right2}</h3>\n                    {$content:right2}\n                </div>\n                <div class=\"right-box\">\n                    <h3>{$header:right3}</h3>\n                    {$content:right3}\n                </div>\n            </div>\n            \n        </div>\n\n        <!-- Footer -->\n        <footer class=\"mt30px\" itemscope itemtype=\"http://schema.org/LocalBusiness\" role=\"contentinfo\">\n\n            <!-- Container -->\n            <div class=\"container\">\n                <!-- Static menu -->\n                <div class=\"grid_5 mb10px alpha\">{$menu:flat}</div>\n\n                <!-- News-list -->\n                {device:desktop}\n                <ul class=\"grid_7 t-hide omega news-list list-unstyled cycle-slideshow\"\n                        data-cycle-slides=\".news-item\"\n                        data-cycle-fx=\"scrollHorz\"\n                        data-cycle-max-z=\"10\"\n                        data-cycle-pause-on-hover=\"true\">\n                    {newslist:footer}\n                    <li class=\"news-item slide\">\n                        <div class=\"news-date\">{$news:date: d M}</div>\n                        <a class=\"h3 news-title\" href=\"{$news:url}\">{$news:title}</a>\n                        <p class=\"news-description\">{$news:teaser}</p>\n                    </li>\n                    {/newslist}\n                </ul>\n                {/device}\n\n                <!-- Address -->\n                <address class=\"grid_12 t-grid_6 m-grid_4 mt10px text-center\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">\n                    <span itemprop=\"name\">{$plugin:widcard:BizOrgName:notag}</span> |\n                    <span itemprop=\"streetAddress\">{$plugin:widcard:BizAddress1:notag}</span>\n                    <span itemprop=\"addressLocality\">{$plugin:widcard:BizCity:notag}</span>,\n                    <span itemprop=\"postalCode\">{$plugin:widcard:BizZip:notag}</span>\n                    <span itemprop=\"addressRegion\">{$plugin:widcard:BizState:notag}</span>\n                    <meta itemprop=\"addressCountry\" content=\"EN\" />\n                </address>\n\n                <!-- Footer info -->\n                <div class=\"grid_12 t-grid_6 m-grid_4 text-center\">{$content:footer-info:static}</div>\n\n            </div>\n\n        </footer>\n\n        <!-- SCRIPTS ZONE -->\n        <script src=\"js/plugin/jquery.cycle2.min.js\"></script>\n        <script src=\"js/scripts.min.js\"></script>\n        <script src=\"js/system/flexkit.min.js\"></script>\n    </body>\n</html>','typeregular');
/*!40000 ALTER TABLE `template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_type`
--

DROP TABLE IF EXISTS `template_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template_type` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Template type name: For example ''quote'', ''regularpage'', etc...',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Alias for the template "Product listing", etc...',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_type`
--

LOCK TABLES `template_type` WRITE;
/*!40000 ALTER TABLE `template_type` DISABLE KEYS */;
INSERT INTO `template_type` VALUES ('type_news','News'),('type_news_list','News listing'),('type_partial_template','Nested Templates'),('typecheckout','Checkout'),('typeinvoice','Invoices'),('typelisting','Product listing'),('typemail','E-mail'),('typemenu','Menu'),('typemobile','Mobile page'),('typeproduct','Product'),('typequote','Quote'),('typeregular','Regular');
/*!40000 ALTER TABLE `template_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user password',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `full_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ipaddress` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reg_date` timestamp NULL DEFAULT NULL,
  `referer` tinytext COLLATE utf8_unicode_ci,
  `gplus_profile` tinytext COLLATE utf8_unicode_ci,
  `mobile_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `indEmail` (`email`),
  KEY `indPassword` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'superadmin','62f4ff77c4966c0b1fd61e833354b353','thomas@inter-data.de','admin','2017-08-09 12:29:53','127.0.0.1','2017-08-08 09:06:13',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_attributes`
--

DROP TABLE IF EXISTS `user_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_attributes` (
  `user_id` int(10) unsigned NOT NULL,
  `attribute` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`user_id`,`attribute`(20)),
  CONSTRAINT `user_attributes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_attributes`
--

LOCK TABLES `user_attributes` WRITE;
/*!40000 ALTER TABLE `user_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_attributes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-09 14:33:49
