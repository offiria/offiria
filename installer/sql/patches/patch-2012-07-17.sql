CREATE TABLE IF NOT EXISTS `%%PREFIX%%stream_customlist` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(255) NOT NULL,
 `user_id` int(11) NOT NULL,
 `filter` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 

ALTER table %%PREFIX%%stream_links add timestamp timestamp DEFAULT '0000-00-00 00:00:00';

UPDATE %%PREFIX%%stream_links t1
JOIN %%PREFIX%%stream_links t2 ON t1.id=t2.id
SET t2.timestamp=NOW()
WHERE t2.params='{"ignore":true}';
