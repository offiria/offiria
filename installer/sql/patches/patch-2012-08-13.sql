CREATE TABLE IF NOT EXISTS `%%PREFIX%%msg` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`from` int(11) NOT NULL,
`parent` int(11) NOT NULL,
`deleted` tinyint(4) NOT NULL DEFAULT '0',
`from_name` varchar(45) NOT NULL,
`posted_on` datetime NOT NULL,
`subject` tinytext NOT NULL,
`body` text NOT NULL,
`attachment` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%%PREFIX%%msg_recepient` (
`msg_id` int(11) NOT NULL,
`msg_parent` int(11) NOT NULL DEFAULT '0',
`msg_from` int(11) NOT NULL,
`to` int(11) NOT NULL,
`bcc` tinyint(4) NOT NULL DEFAULT '0',
`is_read` tinyint(4) NOT NULL DEFAULT '0',
`deleted` tinyint(4) NOT NULL DEFAULT '0',
UNIQUE KEY `un` (`msg_id`,`to`),
KEY `msg_id` (`msg_id`),
KEY `to` (`to`),
KEY `idx_isread_to_deleted` (`is_read`,`to`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%%PREFIX%%msg_files` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`msg_id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`created` datetime NOT NULL,
`filename` varchar(1024) NOT NULL,
`mimetype` varchar(255) NOT NULL,
`filesize` int(11) NOT NULL,
`path` varchar(1024) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `%%PREFIX%%extensions` (`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`) VALUES
('com_messaging', 'component', 'com_messaging', '', 1, 1, 1, 1, '', '', '', '', 0, '0000-00-00 00:00:00', 0, 0);