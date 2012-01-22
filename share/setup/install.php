<?php

$db_share = $db_x.'share';

$db->query("
	CREATE TABLE IF NOT EXISTS $db_share (
	  `realm` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	  `key` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	  `owner` int unsigned NOT NULL,
	  `user` int unsigned NOT NULL DEFAULT '0',
	  `rights` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
	  `expires` int unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`realm`,`key`,`owner`,`user`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
");

?>