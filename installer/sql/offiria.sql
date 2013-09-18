-- MySQL dump 10.13  Distrib 5.5.24, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: of_skeletor
-- ------------------------------------------------------
-- Server version	5.5.24-0ubuntu0.12.04.1

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
-- Table structure for table `%%PREFIX%%ak_acl`
--

DROP TABLE IF EXISTS `%%PREFIX%%ak_acl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%ak_acl` (
  `user_id` bigint(20) unsigned NOT NULL,
  `permissions` mediumtext,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%ak_acl`
--

LOCK TABLES `%%PREFIX%%ak_acl` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%ak_acl` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%ak_acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%ak_profiles`
--

DROP TABLE IF EXISTS `%%PREFIX%%ak_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%ak_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `configuration` longtext,
  `filters` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%ak_profiles`
--

LOCK TABLES `%%PREFIX%%ak_profiles` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%ak_profiles` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%ak_profiles` VALUES (1,'Default Backup Profile','###AES128###ehb64b5hI++uDoWvVqRkz/rLuLLLPtDS/C1OTXxhovyIavA2+p2yAXXtLtFVDeknyw/2AJSLLQb+Q39dmYHQ/JG5lWuaPNxCCK3tDA6rfKDx5wsQI/Ti/uoLeqpVGP7c194ASkl3BoZyMuRrcAWy9qJ/t1rdD33fSDdallR29XZtpPpEhCLv9k6MRu55mcqPMUWho1bd7XJOzu6slmSmW28n6ZWcjUOCQfpy7+dZ6f08aBf4FR+7kuorYhhUGrc/8tle6umcyF24E3g8TpVpIB7JfgemzM3CSUda5pLo6gHEEw3oXttYW8/kA5ZC+s8bq5TR+Cr7DBPzWSOYcgoGuz03aBpCn1UkwW/HWn5Nh7dWUZ8UcGiUzKrk0i5kghNnyrTMyAjup9M/QXTgQGsYQ+4kvcPIThGp4MCK3kb8POe8LnuybnJGPSxNBEPiPU+lpNo13lsrSX4jsat9oTZLNH/2zp6qw8q7M+qm9gmIcCe3IybpRd1ax9S+YVLIgVGWXX11ElfxCDll4N9aD6pPysY4eLcflVsxSkcw5OIiyEYpQU70GloAeLQ0TBpSwNDbnkt8q/uteRi8YA63+3oLbENuS9bu4En/SVgKD/w8nGtF6f+JqToizJ4TuFHk+hWeBcGzD2ZbfizrJXpceqkFkCti9ZqDJftBT7IcB5GbjvdTcKs0lqXWOoJUFhpACmox9E27Q8ZvzKi6IJ5rIxHcNl6XjKOx0s+vE2vskgDcjMW7hAGZ8PO4PkbQdQA2P3R9bBaGSrwQ9Vd6MWBVYuRquOMEUW8jVGIGzErX5JinAqQ8e5BRDwsRUBpZcBvGQ+hNGjDDoG8cw/7Uy3JJSpOYZHvFL+3rK33/XJRqIGO+T/3SVmfEoU7a3DKkuQ6BQ+0c6m5ePiICcMzi4v0AOmzNGs/tV1dWFD0VXJh35yw11p4C7U81Ze4lvKcz0/AcKxlG3cT4UbvW31pnyY++fO5bSK1nWVtmvInEdtBrq9YwyLghadPycUkHNkQ2vfc2EgJ0hKX8yF9ydcnKtixSt4dqmoGMJDDHwQdn9QcN+YrvRYwDve06C9kU2ZiqKEzVDWtT3bVap081cP7HBaZfpcrDGyaXyU557sZRz77QFBzrdOklW5zfDTgtrw0im2itLZzboA0gP/mdxz1TlcTrHe4rCvi8XESBoR6p1LDIpvvw59Fk8UbokGwZrmZkHgi/DlVzuHdbju8rwmfqL+xCisXl4EcUPdbKRURbN63VL7eLEf+0fc2vveNX2NDDELdU3IAFtLvL7wC+MSDhp2Sj5veyBusn9kTyTauVG3BKCV628NLUAVwnFc9LsCKJSWOF4qi8K87p0xkSnPW2YpNHdBrV6U6VOD7TjKk+mbwGMAaXRSI6jq3tIxIuvQjPB2SrR7GUfy09hegIJ3MhtBCp/TJHCCcELD1SHK2CMOFT7PlWOu7kdJTWpY+Y9u1zqpFl4kibjIuzLxySbkBDq44m3A/IdUgPqhV8FwGDdAL93t5Q7rXHKHFounswDWWJ1yRVUl/fx0othZ65oU2uBXCXSXyYGEPpBleNo7sVr1sne3XrGXgYzOysVh/hnUCFUSa9hYi0h0yqT1ZEgRtMhJNcRsIWes5okXPly7xSIlX0tkfWDBQYEVhZPOoai52ac/ZnZZIVHoFqPbMHQAoJvsmQsapHr0/l2UfQws2yGTuMFg9ynxkJhi98lBanKukSBF9Mtlx8EOh/v3xTBWj7Iu4Ix3oMy8ViUgWyNAZ18va3OhDh1mVQy7Q7kk1Ny/lQoURIyVuQo2TpxtI5A0SyE2kMTXyk0sOtMrRbm296pGYUPYMj6yg6wVBK/a0E0lYS45d13l4eSAQhpPKqfF6/7L2oIh7JduBJA1ilGi79HD6bHfDPZtoS8TILcGE9K0NE3gOsi+fNpvgk4wNPJEG9BpAqcnKqWdTw91xUFvsJHLVpNF5Zye7BrbSxKNw8FRKfk6kHsLbqxwUAAA==',''),(2,'offiria','###AES128###ehb64b5hI++uDoWvVqRkz/rLuLLLPtDS/C1OTXxhovxhmc9vDtfghmz/M4hGALU1v8HurDb70oqP6Gof1uwQzeNDXQjSgPUboCJ03IuwxbOJbfZsYWU8JzfAJbOw8oGJ55thI6wx/KED3I0msfubmkN/D754F7ve1xer3mK4OvHMfQFcTbBtPGE5jm0tBDvNgc5yVzzLKH48mGZlrNrEG6MFmVCzCi9nKNwqHIoogprx40EcAhkK6XCjuE4co3tdAjjQeNR/k9lwCbYHrlZYXfhfpG/3CqYcEVuzafxKM29sSlxQcbGdkt1J92KnrAzH8IQZ+voG/GymC1qq9IoV/lNXhF7fQ3Vns/J54NkuRZPkOy1qDLQBiA3dp+8WnNopKtc1cv5/pljwrKN/uVXK8ifJYt9zVekqZh97wupCZUJTzh1JNZbzPOpkRyN6Pxj9U5J2vg748hTRq7ntvRTqViUO28JqcHH965KWQzHLzmwQ+uMtFx3eAtYlQh/wS/spkyKNpvSh3TawrD9+etzEV/Y6ot1LFXS9Vrj7ZA8gCG/xL8czEZN7W2TjRsW/mFfksloQNz2xBhrIVm7y4fPiEc/uwxSTfOGrk7XDpOZncq3IW/+7LFDs7O5z8cbVr7V+rnSQH47TrKELE6jWu/OzvYd2o4qwzedkrrfehc4ORdIYaxtwjOx9dRv8gFA4obDCDlHlIQIerrgcByS34rDhjCvCi8749ldr9WiU+8ikVBwR9bJghafn5XhhMpkPwJDxZrhfyiXtuBoER/NNfDiOHhCuhM/hMU7o+24eb/ejnRHcn880qbL7AJnuGJg9iZ+xONJWfZTXnRE7DvxJ41FBGA9J3IqZzJS0skX64kx8ge05PxIlmq6G/MxZShSOQi0fNQW8/wzxRArBbSVCZvHGpFU10KENUxeFov1SwsbNIoa58FB/7bxoIfC61mX1MOSk2/Juex84xPsJrjfycDzKWe9O/YdNpewnIr01/KZMP7fYCiBshPI6WbnJkFYBGrj7aB+r9fNFCEoz54KHCSSeeb8fhRReVNLPHVN7WxpyPMbGsLA+d6vgcOaoj8xBnVfbKtZp4Q4ZfpRizB4dEXQhEnRzrdWncFd4DrjDuq+ZuRvjxjq06OCYfwoZ+SBxSvpp8JanAZFof3UlK8TniZh7LdHpQXnhojQvl5L/78UshzWnMrdKs0htKt9o0kDVIZVGc67E/Ti/6HBooOsNLMIWb+O+RqQ5dBOKAslme7anaMXCnbjDpXOxHM53rIf5zdPGvoAV4XIQ95/BaixA6PoIVY5F0YoLmt4un/RB8KUuBUaIxhEKCER5hjyzrHGdsYUIMQ8NJMXRxYuuRwDvucWM31Fo0uMKV7+SCTEQzd8g70rBQ0P/OnxEjLfuBSD2fFetTAW0B1HB3yDT88E9jvVu7CptLUjPFuzt9cF6yzKTMfcK/tG4yVARYvOrYGYEPZOrlTuwTEfxeV/0XYhMQNCgfnMfm7SuDX9VCfPSRyHvc3OlVPyf+tSdE5CGnUzj4uXAN4jCeyjq5zJDRwbvBov3JLar6aohxQkXmvEBxtUVvQ1ztJaOOAR9jeN+bmsehFU/0ThG6DsfrZROlZK9v0jH+3JvksFca09r/PZlOq6i+ed1PzKjQbfOspI///nRE3FUKhNjeDNc0N/GfyINI2EOMGlEWVEpjip0mGzjP2sBk4cv/fu5i42UX3vH7es043XehUFa7Xhf0udSEHmAIJuWM7/kRh0m87/xXDIKvp1rdx837hpqFjt7i1L5SWlMofuZeRf1ZjSXkEdY9UECHY2pFPbG56JxX2nrYEQ1T6hWBVqKf5JercdJVKeAPGz/6Eamdjs+nwNxx8vOLdfg/MSBaJHG0sDISqGXVkVP4FpRCKbld3pJc3UCurgG6dAMHSpluaPRTQoU8NFOy2TqWrcEy+MrYD0yZ0t8FbvYqM5+xuvJsEzDhrxctOlOHIJ0jMyWwgUAAA==',NULL);
/*!40000 ALTER TABLE `%%PREFIX%%ak_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%ak_stats`
--

DROP TABLE IF EXISTS `%%PREFIX%%ak_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%ak_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `comment` longtext,
  `backupstart` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `backupend` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('run','fail','complete') NOT NULL DEFAULT 'run',
  `origin` varchar(30) NOT NULL DEFAULT 'backend',
  `type` varchar(30) NOT NULL DEFAULT 'full',
  `profile_id` bigint(20) NOT NULL DEFAULT '1',
  `archivename` longtext,
  `absolute_path` longtext,
  `multipart` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(255) DEFAULT NULL,
  `filesexist` tinyint(3) NOT NULL DEFAULT '1',
  `remote_filename` varchar(1000) DEFAULT NULL,
  `total_size` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_fullstatus` (`filesexist`,`status`),
  KEY `idx_stale` (`status`,`origin`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%ak_stats`
--

LOCK TABLES `%%PREFIX%%ak_stats` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%ak_stats` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%ak_stats` VALUES (2,'Backup taken on Thursday, 22 December 2011 05:20','','2011-12-21 21:20:29','2011-12-21 21:21:53','complete','backend','full',1,'site-offiria.jomsocial.com-20111222-052029.zip','C:/public_html/e20.jomsocial.com/administrator/components/com_akeeba/backup/site-offiria.jomsocial.com-20111222-052029.zip',1,'backend',0,NULL,36657251),(3,'Backup taken on Thursday, 22 December 2011 13:22','','2011-12-22 05:22:39','0000-00-00 00:00:00','complete','backend','full',1,'site-offiria.jomsocial.com-20111222-132239.zip','C:/public_html/e20.jomsocial.com/administrator/components/com_akeeba/backup/site-offiria.jomsocial.com-20111222-132239.zip',0,'backend',0,NULL,0),(4,'Backup taken on Tuesday, 21 February 2012 03:06','','2012-02-20 19:06:54','2012-02-20 19:07:26','complete','backend','full',1,'site-bnm.offiria.com-20120221-030654.zip','/home/www/bnm.offiria.com/public_html/administrator/components/com_akeeba/backup/site-bnm.offiria.com-20120221-030654.zip',1,'backend',0,NULL,62596988);
/*!40000 ALTER TABLE `%%PREFIX%%ak_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%ak_storage`
--

DROP TABLE IF EXISTS `%%PREFIX%%ak_storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%ak_storage` (
  `tag` varchar(255) NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` longtext,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%ak_storage`
--

LOCK TABLES `%%PREFIX%%ak_storage` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%ak_storage` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%ak_storage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%analytics`
--

DROP TABLE IF EXISTS `%%PREFIX%%analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `year` smallint(5) unsigned NOT NULL,
  `month` tinyint(3) unsigned NOT NULL,
  `day` tinyint(3) unsigned NOT NULL,
  `week` tinyint(3) unsigned NOT NULL,
  `hour` tinyint(3) unsigned NOT NULL,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%analytics`
--

LOCK TABLES `%%PREFIX%%analytics` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%analytics` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%analytics` VALUES (1,'company.display',0,0,'2012-04-25 05:52:08',2012,4,25,17,5,''),(2,'company.display',0,0,'2012-04-26 05:49:13',2012,4,26,17,5,'');
/*!40000 ALTER TABLE `%%PREFIX%%analytics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%assets`
--

DROP TABLE IF EXISTS `%%PREFIX%%assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%assets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set parent.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `level` int(10) unsigned NOT NULL COMMENT 'The cached level in the nested tree.',
  `name` varchar(50) NOT NULL COMMENT 'The unique name for the asset.\n',
  `title` varchar(100) NOT NULL COMMENT 'The descriptive title for the asset.',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_asset_name` (`name`),
  KEY `idx_lft_rgt` (`lft`,`rgt`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%assets`
--

LOCK TABLES `%%PREFIX%%assets` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%assets` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%assets` VALUES (1,0,1,420,0,'root.1','Root Asset','{\"core.login.site\":{\"6\":1,\"2\":1},\"core.login.admin\":{\"6\":1},\"core.login.offline\":{\"6\":1},\"core.admin\":{\"8\":1},\"core.manage\":{\"7\":1},\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),(2,1,1,2,1,'com_admin','com_admin','{}'),(3,1,3,6,1,'com_banners','com_banners','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),(4,1,7,8,1,'com_cache','com_cache','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),(5,1,9,10,1,'com_checkin','com_checkin','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),(6,1,11,12,1,'com_config','com_config','{}'),(7,1,13,16,1,'com_contact','com_contact','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),(8,1,17,24,1,'com_content','com_content','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1},\"core.delete\":[],\"core.edit\":{\"4\":1},\"core.edit.state\":{\"5\":1},\"core.edit.own\":[]}'),(9,1,25,26,1,'com_cpanel','com_cpanel','{}'),(10,1,27,28,1,'com_installer','com_installer','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1},\"core.delete\":[],\"core.edit.state\":[]}'),(11,1,29,30,1,'com_languages','com_languages','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),(12,1,31,32,1,'com_login','com_login','{}'),(13,1,33,34,1,'com_mailto','com_mailto','{}'),(14,1,35,36,1,'com_massmail','com_massmail','{}'),(15,1,37,38,1,'com_media','com_media','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1},\"core.delete\":{\"5\":1}}'),(16,1,39,40,1,'com_menus','com_menus','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),(17,1,41,42,1,'com_messages','com_messages','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),(18,1,43,44,1,'com_modules','com_modules','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),(19,1,45,48,1,'com_newsfeeds','com_newsfeeds','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),(20,1,49,50,1,'com_plugins','com_plugins','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.edit\":[],\"core.edit.state\":[]}'),(21,1,51,52,1,'com_redirect','com_redirect','{\"core.admin\":{\"7\":1},\"core.manage\":[]}'),(22,1,53,54,1,'com_search','com_search','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1}}'),(23,1,55,56,1,'com_templates','com_templates','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),(24,1,57,58,1,'com_users','com_users','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":{\"6\":1}}'),(25,1,59,62,1,'com_weblinks','com_weblinks','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1},\"core.delete\":[],\"core.edit\":{\"4\":1},\"core.edit.state\":{\"5\":1},\"core.edit.own\":[]}'),(26,1,63,64,1,'com_wrapper','com_wrapper','{}'),(27,8,18,23,2,'com_content.category.2','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),(28,3,4,5,2,'com_banners.category.3','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),(29,7,14,15,2,'com_contact.category.4','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),(30,19,46,47,2,'com_newsfeeds.category.5','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),(31,25,60,61,2,'com_weblinks.category.6','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),(32,1,418,419,1,'com_akeeba','akeeba','{\"core.admin\":[],\"core.manage\":[],\"akeeba.backup\":[],\"akeeba.configure\":[],\"akeeba.download\":[]}'),(33,27,19,20,3,'com_content.article.1','Term of Service','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),(34,27,21,22,3,'com_content.article.2','Privacy policy','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}');
/*!40000 ALTER TABLE `%%PREFIX%%assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%associations`
--

DROP TABLE IF EXISTS `%%PREFIX%%associations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%associations` (
  `id` varchar(50) NOT NULL COMMENT 'A reference to the associated item.',
  `context` varchar(50) NOT NULL COMMENT 'The context of the associated item.',
  `key` char(32) NOT NULL COMMENT 'The key for the association computed from an md5 on associated ids.',
  PRIMARY KEY (`context`,`id`),
  KEY `idx_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%associations`
--

LOCK TABLES `%%PREFIX%%associations` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%associations` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%associations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%banner_clients`
--

DROP TABLE IF EXISTS `%%PREFIX%%banner_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%banner_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `extrainfo` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `metakey` text NOT NULL,
  `own_prefix` tinyint(4) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%banner_clients`
--

LOCK TABLES `%%PREFIX%%banner_clients` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%banner_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%banner_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%banner_tracks`
--

DROP TABLE IF EXISTS `%%PREFIX%%banner_tracks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%banner_tracks` (
  `track_date` datetime NOT NULL,
  `track_type` int(10) unsigned NOT NULL,
  `banner_id` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`track_date`,`track_type`,`banner_id`),
  KEY `idx_track_date` (`track_date`),
  KEY `idx_track_type` (`track_type`),
  KEY `idx_banner_id` (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%banner_tracks`
--

LOCK TABLES `%%PREFIX%%banner_tracks` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%banner_tracks` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%banner_tracks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%banners`
--

DROP TABLE IF EXISTS `%%PREFIX%%banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `imptotal` int(11) NOT NULL DEFAULT '0',
  `impmade` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `clickurl` varchar(200) NOT NULL DEFAULT '',
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `custombannercode` varchar(2048) NOT NULL,
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `params` text NOT NULL,
  `own_prefix` tinyint(1) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reset` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`),
  KEY `idx_banner_catid` (`catid`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%banners`
--

LOCK TABLES `%%PREFIX%%banners` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%categories`
--

DROP TABLE IF EXISTS `%%PREFIX%%categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `extension` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `metadesc` varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
  `metakey` varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
  `metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`extension`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_path` (`path`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%categories`
--

LOCK TABLES `%%PREFIX%%categories` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%categories` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%categories` VALUES (1,0,0,0,11,0,'','system','ROOT','root','','',1,0,'0000-00-00 00:00:00',1,'{}','','','',0,'2009-10-18 16:07:09',0,'0000-00-00 00:00:00',0,'*'),(2,27,1,1,2,1,'uncategorised','com_content','Uncategorised','uncategorised','','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:26:37',0,'0000-00-00 00:00:00',0,'*'),(3,28,1,3,4,1,'uncategorised','com_banners','Uncategorised','uncategorised','','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\",\"foobar\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:27:35',0,'0000-00-00 00:00:00',0,'*'),(4,29,1,5,6,1,'uncategorised','com_contact','Uncategorised','uncategorised','','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:27:57',0,'0000-00-00 00:00:00',0,'*'),(5,30,1,7,8,1,'uncategorised','com_newsfeeds','Uncategorised','uncategorised','','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:28:15',0,'0000-00-00 00:00:00',0,'*'),(6,31,1,9,10,1,'uncategorised','com_weblinks','Uncategorised','uncategorised','','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:28:33',0,'0000-00-00 00:00:00',0,'*');
/*!40000 ALTER TABLE `%%PREFIX%%categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%contact_details`
--

DROP TABLE IF EXISTS `%%PREFIX%%contact_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `con_position` varchar(255) DEFAULT NULL,
  `address` text,
  `suburb` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `misc` mediumtext,
  `image` varchar(255) DEFAULT NULL,
  `imagepos` varchar(20) DEFAULT NULL,
  `email_to` varchar(255) DEFAULT NULL,
  `default_con` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `webpage` varchar(255) NOT NULL DEFAULT '',
  `sortname1` varchar(255) NOT NULL,
  `sortname2` varchar(255) NOT NULL,
  `sortname3` varchar(255) NOT NULL,
  `language` char(7) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%contact_details`
--

LOCK TABLES `%%PREFIX%%contact_details` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%contact_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%contact_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%content`
--

DROP TABLE IF EXISTS `%%PREFIX%%content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `title_alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'Deprecated in Joomla! 3.0',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `sectionid` int(10) unsigned NOT NULL DEFAULT '0',
  `mask` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` varchar(5120) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `language` char(7) NOT NULL COMMENT 'The language code for the article.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%content`
--

LOCK TABLES `%%PREFIX%%content` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%content` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%content` VALUES (1,33,'Term of Service','term-of-service','','<div style=\"background-color: transparent;\">\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Modifications to the Service and Prices</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria, in its sole discretion, reserves the right at any time and from time to time to modify or discontinue, temporarily or permanently, the Service (or any part thereof) with or without notice. Although we may attempt to notify you via email when major changes are made, you should visit this page periodically to review the terms.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Prices of all Services, including but not limited to monthly premium features of the Service, are subject to change upon 30 days notice from us. Such notice may be provided at any time by posting the changes to the Offiria Site (www.Offiria) or the Service itself.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria shall not be liable to you or to any third party for any modification, price change, suspension or discontinuance of the Service.</span><br /><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Registration and Use</span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /><br class=\"kix-line-break\" />Your Offiria Account Information</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">To join the Service you must provide your legal full name, a valid email address, and any other information requested into your Offiria Account in order to complete the signup process. Provided you have complied with and agreed to the Terms of Service you will be issued with a user name and password.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Accounts registered by \"bots\" or other automated methods are not permitted.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You must keep your user name and password confidential and must not reveal your password to anyone. You are entirely responsible for any and all activities that occur under your Offiria Account. Offiria will not be liable for any losses or damage incurred as a result of the unauthorized use of your user name or password.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You agree to notify us immediately in the event of any unauthorized use of your Offiria Account or any other breach of security. Notification should be made by email to &lt;email address&gt;</span><br /><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Transferability</span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: underline; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Your user account may only be used by one person. A single login shared by multiple people is not permitted. Your Offiria Account, password and user name is personal to you and is not transferable under any circumstances.</span><br /><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Accurate Information</span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: underline; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You may only use the Service for lawful purposes. You may not use the Service for any illegal or unauthorized purpose. You agree that you will use the Service in compliance with all applicable local, state, national, and international laws, rules and regulations, including any laws regarding the transmission of technical data exported from your country of residence. You are also responsible whenever your user id and password is used, and any and all related charges (for instance, charges related to ordering certain services), whether or not authorized by you.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You must provide us with valid and truthful information. In particular you agree that the information provided on the Offiria registration screens, together with any other personal contact details are valid and correct.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You must inform us of any change in the information provided by you on the Offiria registration screens (including but not limited to email address and other personal contact information) as soon as practicable.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You further agree to use reasonable efforts to keep any other information provided up to date. As one of the conditions of your use, you warrant and represent that you are a genuine or bona fide Account Holder and that you are not using the Terms of Service for any other purpose. It is YOUR RESPONSIBILITY to ensure that your use of the Service complies with these Terms of Service and any notices received by you from Offiria.</span><br /></strong></strong>\r\n<h4 dir=\"ltr\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Authentication of Information</span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: underline; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We reserve the right at our discretion to request verification of, or verify on your behalf, any information provided by you, including but not limited to the information provided on the Offiria registration screen. </span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Conditions of Use</span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: underline; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #222222; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">The following are the principal conditions of your use of the Service. We reserve the right to terminate your use of the Service should you violate or fail to comply with any of the following conditions:</span><br /></strong></strong><ol>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you may only apply for and maintain one Offiria account;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">the information you provide on the Offiria registration screens must be your details and must be complete, valid, truthful and correct and you must keep this information up to date by accessing \'My Profile\';</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you must validate your e-mail address by entering the validation code sent to your nominated e-mail address where prompted within a reasonable period of time after registering for the Service;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you may not join nor use this Service on behalf of another person;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you must not use anyone else\'s Offiria Account; and</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you agree to receive Messages either by Internet, cell phone, physical letter or all.</span></li>\r\n</ol><strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Prohibited Uses</span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You agree not to use the Offiria Websites (including, without limitation, any Materials or Services you may obtain through your use of the Offiria Websites): </span></h4>\r\n<ol>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">in a manner that violates any local, state, national, foreign, or international statute, regulation, rule, order, treaty, or other law (each a “Law”); </span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">to impersonate any person or entity or otherwise misrepresent your affiliation with a person or entity; or </span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">to interfere with or disrupt the Offiria Websites or servers or networks connected to the Offiria Websites. </span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you agree not to provide any information that is false, inaccurate, misleading or incomplete; </span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you shall not use, post or transmit any material of any kind which Offiria considers, does or is likely to damage the Business or the reputation of Offiria. You further agree not to (x) use any data miOffiria, robots, or similar data gathering or extraction methods in connection with the Offiria Websites; or (y) attempt to gain unauthorized access to any portion of the Offiria Websites or any other accounts, computer systems, or networks connected to the Offiria Websites, whether through hacking, password miOffiria, or any other means.</span></li>\r\n</ol><strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Your Content</span><span style=\"font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You own all of the content and information you post on the Offiria Websites. </span></h4>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We claim no intellectual property rights over the material you provide.</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">In addition:</span></h4>\r\n<ol>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You are responsible for all content posted by you within any Offiria(s) that your user account is associated with.</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Your profile, uploaded materials, or any content that is created by your use of the Service remain the property of your group and/or its members.</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria does not pre-screen content, but Offiria and its designee have the right (but not the obligation) in their sole discretion to refuse or remove any content that is available via the Service.</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria is not responsible for any loss, theft, or damage of any kind to any User Submissions. Offiria does not guarantee that you will have any recourse through Offiria or any third party to edit or delete any User Submission you have submitted. (we may not want all of this)</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria.com reserves the right at all times to remove or refuse to distribute any content on the Service, such as content which violates the terms of this Agreement.</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria.com reserves the right to access, read, preserve, and disclose any information as it reasonably believes is necessary to (i) satisfy any applicable law, regulation, legal process or governmental request, (ii) enforce this Agreement, including investigation of potential violations hereof, (iii) detect, prevent, or otherwise address fraud, security or technical issues, (iv) respond to user support requests, or (v) protect the rights, property or safety of Offiria.com, its users and the public. Offiria.com will not be responsible or liable for the exercise or non-exercise of its rights under this Agreement.</span></li>\r\n</ol><strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /><br /><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">By using Offiria, you should know:</span></strong></strong><ol>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We collect limited account information and store and maintain your account and profile information on our servers.</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We will never rent, sell or share information that personally identifies you for marketing purposes except to provide products or services you\'ve requested, when we have your permission or under the following circumstances:</span></li>\r\n</ol>\r\n<ul>\r\n<li style=\"list-style-type: disc; font-size: 13px; font-family: Verdana; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We respond to subpoenas, court orders, or legal process, or to establish or exercise our legal rights or defend against legal claims;</span></li>\r\n<li style=\"list-style-type: disc; font-size: 13px; font-family: Verdana; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We believe it is necessary to share information in order to investigate, prevent, or take action regarding illegal activities, suspected fraud, situations involving potential threats to the physical safety of any person, violations of Offiria\'s terms of use, or as otherwise required by law.</span></li>\r\n</ul>\r\n<ol start=\"3\">\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You can update your account information and preferences at any time.</span></li>\r\n</ol><strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /><br /><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">By submitting to our User Submission, you represent and warrant that:</span></strong></strong><ol>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you are at least 13 years old;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you own all rights in your User Submissionsyou have acquired all necessary rights in your User Submissions to enable you to grant to Offiria the rights in your User Submissions described herein;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you are the individual pictured and/or heard in your User Submissions</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">your User Submissions do not infringe the copyright, trademark, patent, trade secret, or other intellectual property rights, privacy rights, or any other legal or moral rights of any third party;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">any information contained in your User Submission is not known by you to be false, inaccurate, or misleading;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">your User Submission does not violate any Law (including, but not limited to, those goverOffiria export control, consumer protection, unfair competition, anti-discrimination, or false advertising);</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">you were not and will not be compensated or granted any consideration by any third party for submitting your User Submission;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">your User Submission does not contain any viruses, worms, spyware, adware, or other potentially damaging programs or files;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">your User Submission does not contain or constitute any unsolicited or unauthorized advertising, promotional materials, junk mail, spam, chain letters, pyramid schemes, or any other form of solicitation.</span></li>\r\n</ol><strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Indemnification</span><span style=\"font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You agree to indemnify, defend, and hold harmless Offiria Parties from and against any and all claims, liabilities, damages, losses, costs, expenses, or fees (including reasonable attorneys’ fees) that such parties may incur as a result of or arising from your (or anyone using your account’s) violation of these Terms. Offiria reserves the right to assume the exclusive defense and control of any matter otherwise subject to indemnification by you and, in such case, you agree to cooperate with Offiria’s defense of such claim.</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Disclaimers</span><span style=\"font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">All content on the Offiria Websites is provided \"as is\" and “with all faults”, as available basis. Offiria expressly disclaims all warranties of any kind, whether express, implied, statutory, with respect to the Offiria Websites (including, but not limited to, any implied or statutory warranties of merchantability, fitness for a particular use or purpose, title, and non-infringement of intellectual property rights). Without limiting the generality of the foregoing, Offiria makes no warranty that the Offiria Websites will meet your requirements or that the Offiria Websites will be uninterrupted, timely, secure, or error free or that defects in the Offiria Websites will be corrected. Offiria makes no warranty as to the results that may be obtained from the use of the Offiria Websites or as to the accuracy or reliability of any information obtained through the Offiria Websites. No advice or information, whether oral or written, obtained by you through the Offiria Websites or from Offiria, or other affiliated companies, or its or their suppliers (or the respective officers, directors, employees, or agents of any such entities) (collectively, “the Offiria parties”) shall create any warranty. Offiria disclaims all equitable indemnities.</span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You are responsible for taking all precautions necessary to ensure that any content you may obtain from the Service is free of viruses and any other potentially destructive computer code.</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Limitation of Liability</span><span style=\"font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">In no event will any of the Offiria parties be liable for </span></h4>\r\n<ol>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">any indirect, special, consequential, punitive, or exemplary damages or </span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">any damages whatsoever,  those resulting from loss of revenues, lost profits, loss of goodwill, loss of use, business interruption, or other intangible losses), arising out of or in connection with the Offiria websites (including, without limitation, use, inability to use, or the results of use of the hp websites), whether such damages are based on warranty, contract, tort, statute, or any other legal theory and even if any Offiria party has been advised (or should have known) of the possibility of such damages.</span></li>\r\n</ol><strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Modifications to these Terms</span><span style=\"font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria may, in its sole and absolute discretion, change these Terms from time to time. Offiria will post notice of such changes on the applicable Site. If you object to any such changes, your sole recourse shall be to cease using the Offiria Websites. Continued use of the Offiria Websites following notice of any such changes shall indicate your acknowledgement of such changes and agreement to be bound by the terms and conditions of such changes. Certain provisions of these Terms may be superseded by expressly-designated legal notices or terms located on particular pages of the Offiria Websites and, in such circumstances, the expressly-designated legal notice or term shall be deemed to be incorporated into these Terms and to supersede the provision(s) of these Terms that are designated as being superseded.</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Termination</span><span style=\"font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria may terminate, suspend, or modify your registration with, or access to, all or part of the Offiria Websites, without notice, at any time and for any reason. You may discontinue your participation in and access to the Offiria Websites at any time. If you breach any of these Terms, your authorization to use the Offiria Websites automatically terminates and you must immediately destroy any downloaded or printed Materials (and any copies thereof).</span></h4>\r\n</div>','',1,0,0,2,'2011-12-27 08:38:17',42,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2011-12-27 08:38:17','0000-00-00 00:00:00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\"}',1,0,1,'','',1,5,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),(2,34,'Privacy policy','privacy-policy','','<div style=\"background-color: transparent;\">\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Coffee</span><span style=\"font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" /></span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Robot cute jellyfish starbucks twisties.</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /></strong></strong>\r\n<p style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria is an online service provider that provides a group communication and networking platform that enable users to create their own networks utilizing our technology platform (the “Offiria Platform”). Offiria is not involved in the management of Networks on the Offiria Platform and is not involved in the decisions relating to the focus of Networks or the Content uploaded or published to Networks using the Offiria Platform. These are your Networks and, as a Network Creator, you are responsible for managing them in all respects (including the actions, conduct, and Content of Your Members) in compliance with the Offiria Terms of Service.</span></p>\r\n<p style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We value your privacy, and we want to help make your experience on the Offiria Platform as satisfying and safe as possible. We have established this Privacy Policy to explain how Personal Information (as defined below) is collected on the Offiria Platform, and how that Personal Information is used and disclosed. “Personal Information” is information that allows a person to directly identify an individual, such as name or email address, and information that we combine directly with such identifying information. This Privacy Policy is incorporated into and is subject to the Offiria Terms of Service. Any capitalized terms not defined herein have the meaOffiria set forth in the Offiria Terms of Service. By using the Offiria Platform, you expressly consent to the information handling practices described in this Privacy Policy.</span></p>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">The Way We Use Information</span><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\"><br class=\"kix-line-break\" />By using Offiria, you agree to the collection and use of your personal and/or group information as described in this policy. You should know:</span></strong></strong><ol>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We collect limited account information and store and maintain your account and profile information on our servers.</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We will never rent, sell or share information that personally identifies you for marketing purposes except to provide products or services you\'ve requested, when we have your permission or under the following circumstances:</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We may share general information to trusted partners who work on behalf of or with Offiria under confidentiality agreements. General information may be used to help Offiria communicate offers to you from Offiria and our marketing partners. Our partners do not have any independent right to share this general information and any offers will include a URL and email address where you can unsubscribe from future mailings.</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We respond to subpoenas, court orders, or legal process, or to establish or exercise our legal rights or defend against legal claims;</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">We believe it is necessary to share information in order to investigate, prevent, or take action regarding illegal activities, suspected fraud, situations involving potential threats to the physical safety of any person, violations of Offiria\'s terms of use, or as otherwise required by law.</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">*Offiria displays targeted advertisements based on personal or group information. Advertisers (including ad serving companies) may assume that people who interact with, view, or click on targeted ads meet the targeting criteria.</span></li>\r\n<li style=\"list-style-type: lower-alpha; font-size: 13px; font-family: \'Times New Roman\'; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You can update your account information and preferences at any time.</span></li>\r\n</ol><strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><br /><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">How You Can Access Or Correct Your Information</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You can access all your personally identifiable information that we collect online and maintain by visiting your account settings page or by visiting the edit profile options on each of the networks that you are a member of.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You can correct factual errors in your personally identifiable information through our account management tools or by sending us a request that credibly shows error.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">To protect your privacy and security, we will also take reasonable steps to verify your identity before granting access or making corrections.</span><br /><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Images of your Offiria or profile in marketing materials</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria reserves the right to include images of your Offiria\'s header graphic or overview Summary page on the Offiria website or in other marketing materials for demonstration and promotional purposes. We also may display additional imageries, this includes the possibility of displaying an image of your member profile, including your photograph. Offiria will take care to present images in a professional manner and without compromising the character of the Groupsite.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">You may specifically request in writing that we not use images of your profile page in this manner by sending a request with the text REMOVE FROM MARKETING MATERIALS in the subject line, and you may also write in writing to by sending us a letter, fax, or by all means of written communication.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria Managers may also specifically request in writing that Offiria not use images of their Offiria\'s header graphic or pages in this manner by contacting us using either of the above described methods.</span><br /><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Changes</span></h4>\r\n<strong style=\"font-weight: normal;\"><strong style=\"font-weight: normal;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Offiria may periodically update this policy. We will notify you about significant changes in the way we treat personal information by sending a notice to the primary email address specified in your Offiria profile or by placing a prominent notice on our site.</span><br /><br /></strong></strong>\r\n<h4 style=\"margin-left: 0.7pt; margin-top: 0pt; margin-bottom: 0pt;\" dir=\"ltr\"><span style=\"font-size: 19px; font-family: Arial; color: #0047ff; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">How to Contact Us</span></h4>\r\n<strong id=\"internal-source-marker_0.654773767106235\" style=\"font-weight: normal;\"><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Should you have other questions or concerns about these privacy policies, please call us at &lt;official contact number&gt; or send us an email at &lt;email address&gt;.</span><br /><span style=\"font-size: 12px; font-family: Arial; color: #000000; background-color: transparent; font-weight: normal; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;\">Our office is located at:<br class=\"kix-line-break\" />&lt;address line 1&gt;<br class=\"kix-line-break\" />&lt;address line 2&gt;<br class=\"kix-line-break\" />&lt;address line 3&gt;</span></strong></div>','',1,0,0,2,'2011-12-27 08:47:11',42,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2011-12-27 08:47:11','0000-00-00 00:00:00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\"}',1,0,0,'','',1,8,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*','');
/*!40000 ALTER TABLE `%%PREFIX%%content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%content_frontpage`
--

DROP TABLE IF EXISTS `%%PREFIX%%content_frontpage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%content_frontpage` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%content_frontpage`
--

LOCK TABLES `%%PREFIX%%content_frontpage` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%content_frontpage` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%content_frontpage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%content_rating`
--

DROP TABLE IF EXISTS `%%PREFIX%%content_rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%content_rating` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `rating_sum` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_count` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%content_rating`
--

LOCK TABLES `%%PREFIX%%content_rating` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%content_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%content_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%core_log_searches`
--

DROP TABLE IF EXISTS `%%PREFIX%%core_log_searches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%core_log_searches` (
  `search_term` varchar(128) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%core_log_searches`
--

LOCK TABLES `%%PREFIX%%core_log_searches` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%core_log_searches` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%core_log_searches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%extensions`
--

DROP TABLE IF EXISTS `%%PREFIX%%extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%extensions` (
  `extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `element` varchar(100) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `client_id` tinyint(3) NOT NULL,
  `enabled` tinyint(3) NOT NULL DEFAULT '1',
  `access` int(10) unsigned NOT NULL DEFAULT '1',
  `protected` tinyint(3) NOT NULL DEFAULT '0',
  `manifest_cache` text NOT NULL,
  `params` text NOT NULL,
  `custom_data` text NOT NULL,
  `system_data` text NOT NULL,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) DEFAULT '0',
  `state` int(11) DEFAULT '0',
  PRIMARY KEY (`extension_id`),
  KEY `element_clientid` (`element`,`client_id`),
  KEY `element_folder_clientid` (`element`,`folder`,`client_id`),
  KEY `extension` (`type`,`element`,`folder`,`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10024 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%extensions`
--

LOCK TABLES `%%PREFIX%%extensions` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%extensions` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%extensions` VALUES (1,'com_mailto','component','com_mailto','',0,1,1,1,'{\"legacy\":false,\"name\":\"com_mailto\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_MAILTO_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(2,'com_wrapper','component','com_wrapper','',0,1,1,1,'{\"legacy\":false,\"name\":\"com_wrapper\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_WRAPPER_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(3,'com_admin','component','com_admin','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_admin\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_ADMIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(4,'com_banners','component','com_banners','',1,1,1,0,'{\"legacy\":false,\"name\":\"com_banners\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_BANNERS_XML_DESCRIPTION\",\"group\":\"\"}','{\"purchase_type\":\"3\",\"track_impressions\":\"0\",\"track_clicks\":\"0\",\"metakey_prefix\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),(5,'com_cache','component','com_cache','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_cache\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_CACHE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(6,'com_categories','component','com_categories','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_categories\",\"type\":\"component\",\"creationDate\":\"December 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_CATEGORIES_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(7,'com_checkin','component','com_checkin','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_checkin\",\"type\":\"component\",\"creationDate\":\"Unknown\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2008 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_CHECKIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(8,'com_contact','component','com_contact','',1,1,1,0,'{\"legacy\":false,\"name\":\"com_contact\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_CONTACT_XML_DESCRIPTION\",\"group\":\"\"}','{\"show_contact_category\":\"hide\",\"show_contact_list\":\"0\",\"presentation_style\":\"sliders\",\"show_name\":\"1\",\"show_position\":\"1\",\"show_email\":\"0\",\"show_street_address\":\"1\",\"show_suburb\":\"1\",\"show_state\":\"1\",\"show_postcode\":\"1\",\"show_country\":\"1\",\"show_telephone\":\"1\",\"show_mobile\":\"1\",\"show_fax\":\"1\",\"show_webpage\":\"1\",\"show_misc\":\"1\",\"show_image\":\"1\",\"image\":\"\",\"allow_vcard\":\"0\",\"show_articles\":\"0\",\"show_profile\":\"0\",\"show_links\":\"0\",\"linka_name\":\"\",\"linkb_name\":\"\",\"linkc_name\":\"\",\"linkd_name\":\"\",\"linke_name\":\"\",\"contact_icons\":\"0\",\"icon_address\":\"\",\"icon_email\":\"\",\"icon_telephone\":\"\",\"icon_mobile\":\"\",\"icon_fax\":\"\",\"icon_misc\":\"\",\"show_headings\":\"1\",\"show_position_headings\":\"1\",\"show_email_headings\":\"0\",\"show_telephone_headings\":\"1\",\"show_mobile_headings\":\"0\",\"show_fax_headings\":\"0\",\"allow_vcard_headings\":\"0\",\"show_suburb_headings\":\"1\",\"show_state_headings\":\"1\",\"show_country_headings\":\"1\",\"show_email_form\":\"1\",\"show_email_copy\":\"1\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"1\",\"custom_reply\":\"0\",\"redirect\":\"\",\"show_category_crumb\":\"0\",\"metakey\":\"\",\"metadesc\":\"\",\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),(9,'com_cpanel','component','com_cpanel','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_cpanel\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_CPANEL_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(10,'com_installer','component','com_installer','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_installer\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_INSTALLER_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(11,'com_languages','component','com_languages','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_languages\",\"type\":\"component\",\"creationDate\":\"2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_LANGUAGES_XML_DESCRIPTION\",\"group\":\"\"}','{\"administrator\":\"en-GB\",\"site\":\"en-GB\"}','','',0,'0000-00-00 00:00:00',0,0),(12,'com_login','component','com_login','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_login\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_LOGIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(13,'com_media','component','com_media','',1,1,0,1,'{\"legacy\":false,\"name\":\"com_media\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_MEDIA_XML_DESCRIPTION\",\"group\":\"\"}','{\"upload_extensions\":\"mp3,bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,swf,txt,accdb,accdc,accdp,ade,adp,doc,docm,docx,grv,mpd,mpp,pps,ppsm,ppsx,ppt,pptm,pptx,prf,pst,vdx,vsd,vss,vst,vsx,vtx,xl,xla,xlam,xls,xlsb,xlsm,xlsx,xlxs,xlw,xsf,xcf,BMP,CSV,DOC,GIF,ICO,JPG,JPEG,ODG,ODP,ODS,ODT,PDF,PNG,SWF,TXT,MP3,ACCDB,ACCDC,ACCDP,ADE,ADP,DOC,DOCM,DOCX,GRV,MPD,MPP,PPS,PPSM,PPSX,PPT,PPTM,PPTX,PRF,PST,VDX,VSD,VSS,VST,VSX,VTX,XL,XLA,XLAM,XLS,XLSB,XLSM,XLSX,XLXS,XLW,XSF,XCF\",\"upload_maxsize\":\"10\",\"file_path\":\"images\",\"image_path\":\"images\",\"restrict_uploads\":\"0\",\"check_mime\":\"0\",\"image_extensions\":\"bmp,gif,jpg,png\",\"ignore_extensions\":\"\",\"upload_mime\":\"image\\/jpeg,image\\/gif,image\\/png,image\\/bmp,application\\/x-shockwave-flash,application\\/msword,application\\/excel,application\\/pdf,application\\/doc,application\\/powerpoint,text\\/plain,application\\/x-zip\",\"upload_mime_illegal\":\"text\\/html\",\"enable_flash\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(14,'com_menus','component','com_menus','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_menus\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_MENUS_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(15,'com_messages','component','com_messages','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_messages\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_MESSAGES_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(16,'com_modules','component','com_modules','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_modules\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_MODULES_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(17,'com_newsfeeds','component','com_newsfeeds','',1,1,1,0,'{\"legacy\":false,\"name\":\"com_newsfeeds\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_NEWSFEEDS_XML_DESCRIPTION\",\"group\":\"\"}','{\"show_feed_image\":\"1\",\"show_feed_description\":\"1\",\"show_item_description\":\"1\",\"feed_word_count\":\"0\",\"show_headings\":\"1\",\"show_name\":\"1\",\"show_articles\":\"0\",\"show_link\":\"1\",\"show_description\":\"1\",\"show_description_image\":\"1\",\"display_num\":\"\",\"show_pagination_limit\":\"1\",\"show_pagination\":\"1\",\"show_pagination_results\":\"1\",\"show_cat_items\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),(18,'com_plugins','component','com_plugins','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_plugins\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_PLUGINS_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(19,'com_search','component','com_search','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_search\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_SEARCH_XML_DESCRIPTION\",\"group\":\"\"}','{\"enabled\":\"0\",\"search_areas\":\"0\",\"show_date\":\"1\",\"opensearch_name\":\"\",\"opensearch_description\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),(20,'com_templates','component','com_templates','',1,1,1,1,'{\"legacy\":false,\"name\":\"com_templates\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_TEMPLATES_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(21,'com_weblinks','component','com_weblinks','',1,1,1,0,'{\"legacy\":false,\"name\":\"com_weblinks\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_WEBLINKS_XML_DESCRIPTION\",\"group\":\"\"}','{\"show_comp_description\":\"1\",\"comp_description\":\"\",\"show_link_hits\":\"1\",\"show_link_description\":\"1\",\"show_other_cats\":\"0\",\"show_headings\":\"0\",\"show_numbers\":\"0\",\"show_report\":\"1\",\"count_clicks\":\"1\",\"target\":\"0\",\"link_icons\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),(22,'com_content','component','com_content','',1,1,0,1,'{\"legacy\":false,\"name\":\"com_content\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_CONTENT_XML_DESCRIPTION\",\"group\":\"\"}','{\"article_layout\":\"_:default\",\"show_title\":\"1\",\"link_titles\":\"1\",\"show_intro\":\"1\",\"show_category\":\"0\",\"link_category\":\"1\",\"show_parent_category\":\"0\",\"link_parent_category\":\"0\",\"show_author\":\"0\",\"link_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"0\",\"show_vote\":\"0\",\"show_readmore\":\"1\",\"show_readmore_title\":\"1\",\"readmore_limit\":\"100\",\"show_icons\":\"1\",\"show_print_icon\":\"1\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"show_noauth\":\"0\",\"category_layout\":\"_:blog\",\"show_category_title\":\"0\",\"show_description\":\"0\",\"show_description_image\":\"0\",\"maxLevel\":\"1\",\"show_empty_categories\":\"0\",\"show_no_articles\":\"1\",\"show_subcat_desc\":\"1\",\"show_cat_num_articles\":\"0\",\"show_base_description\":\"1\",\"maxLevelcat\":\"-1\",\"show_empty_categories_cat\":\"0\",\"show_subcat_desc_cat\":\"1\",\"show_cat_num_articles_cat\":\"1\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"0\",\"show_subcategory_content\":\"0\",\"show_pagination_limit\":\"1\",\"filter_field\":\"hide\",\"show_headings\":\"1\",\"list_show_date\":\"0\",\"date_format\":\"\",\"list_show_hits\":\"1\",\"list_show_author\":\"1\",\"orderby_pri\":\"order\",\"orderby_sec\":\"rdate\",\"order_date\":\"published\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"feed_summary\":\"0\",\"filters\":{\"1\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"6\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"7\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"2\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"3\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"4\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"5\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"8\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"}}}','','',0,'0000-00-00 00:00:00',0,0),(23,'com_config','component','com_config','',1,1,0,1,'{\"legacy\":false,\"name\":\"com_config\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_CONFIG_XML_DESCRIPTION\",\"group\":\"\"}','{\"filters\":{\"1\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"6\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"7\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"2\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"3\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"4\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"5\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"8\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"}}}','','',0,'0000-00-00 00:00:00',0,0),(24,'com_redirect','component','com_redirect','',1,1,0,1,'{\"legacy\":false,\"name\":\"com_redirect\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_REDIRECT_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(25,'com_users','component','com_users','',1,1,0,1,'{\"legacy\":false,\"name\":\"com_users\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_USERS_XML_DESCRIPTION\",\"group\":\"\"}','{\"allowUserRegistration\":\"0\",\"new_usertype\":\"2\",\"guest_usergroup\":\"1\",\"useractivation\":\"0\",\"frontend_userparams\":\"1\",\"site_language\":\"0\",\"mailSubjectPrefix\":\"\",\"mailBodySuffix\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),(27,'com_finder','component','com_finder','',1,1,0,0,'','{\"show_description\":\"1\",\"description_length\":255,\"allow_empty_query\":\"0\",\"show_url\":\"1\",\"show_advanced\":\"1\",\"expand_advanced\":\"0\",\"show_date_filters\":\"0\",\"highlight_terms\":\"1\",\"opensearch_name\":\"\",\"opensearch_description\":\"\",\"batch_size\":\"50\",\"memory_table_limit\":30000,\"title_multiplier\":\"1.7\",\"text_multiplier\":\"0.7\",\"meta_multiplier\":\"1.2\",\"path_multiplier\":\"2.0\",\"misc_multiplier\":\"0.3\",\"stemmer\":\"porter_en\"}','','',0,'0000-00-00 00:00:00',0,0),(28,'com_joomlaupdate','component','com_joomlaupdate','',1,1,0,1,'{\"legacy\":false,\"name\":\"com_joomlaupdate\",\"type\":\"component\",\"creationDate\":\"February 2012\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.2\",\"description\":\"COM_JOOMLAUPDATE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(100,'PHPMailer','library','phpmailer','',0,1,1,1,'{\"legacy\":false,\"name\":\"PHPMailer\",\"type\":\"library\",\"creationDate\":\"2008\",\"author\":\"PHPMailer\",\"copyright\":\"Copyright (C) PHPMailer.\",\"authorEmail\":\"\",\"authorUrl\":\"http:\\/\\/phpmailer.codeworxtech.com\\/\",\"version\":\"2.5.0\",\"description\":\"LIB_PHPMAILER_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(101,'SimplePie','library','simplepie','',0,1,1,1,'{\"legacy\":false,\"name\":\"SimplePie\",\"type\":\"library\",\"creationDate\":\"2008\",\"author\":\"SimplePie\",\"copyright\":\"Copyright (C) 2008 SimplePie\",\"authorEmail\":\"\",\"authorUrl\":\"http:\\/\\/simplepie.org\\/\",\"version\":\"1.0.1\",\"description\":\"LIB_SIMPLEPIE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(102,'phputf8','library','phputf8','',0,1,1,1,'{\"legacy\":false,\"name\":\"phputf8\",\"type\":\"library\",\"creationDate\":\"2008\",\"author\":\"Harry Fuecks\",\"copyright\":\"Copyright various authors\",\"authorEmail\":\"\",\"authorUrl\":\"http:\\/\\/sourceforge.net\\/projects\\/phputf8\",\"version\":\"2.5.0\",\"description\":\"LIB_PHPUTF8_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(103,'Joomla! Web Application Framework','library','joomla','',0,1,1,1,'{\"legacy\":false,\"name\":\"Joomla! Web Application Framework\",\"type\":\"library\",\"creationDate\":\"2008\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"http:\\/\\/www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"LIB_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(200,'mod_articles_archive','module','mod_articles_archive','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_articles_archive\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters.\\n\\t\\tAll rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_ARTICLES_ARCHIVE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(201,'mod_articles_latest','module','mod_articles_latest','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_articles_latest\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_LATEST_NEWS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(202,'mod_articles_popular','module','mod_articles_popular','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_articles_popular\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_POPULAR_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(203,'mod_banners','module','mod_banners','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_banners\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_BANNERS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(204,'mod_breadcrumbs','module','mod_breadcrumbs','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_breadcrumbs\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_BREADCRUMBS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(205,'mod_custom','module','mod_custom','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_custom\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_CUSTOM_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(206,'mod_feed','module','mod_feed','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_feed\",\"type\":\"module\",\"creationDate\":\"July 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_FEED_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(207,'mod_footer','module','mod_footer','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_footer\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_FOOTER_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(208,'mod_login','module','mod_login','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_login\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_LOGIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(209,'mod_menu','module','mod_menu','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_menu\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_MENU_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(210,'mod_articles_news','module','mod_articles_news','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_articles_news\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_ARTICLES_NEWS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(211,'mod_random_image','module','mod_random_image','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_random_image\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_RANDOM_IMAGE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(212,'mod_related_items','module','mod_related_items','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_related_items\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_RELATED_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(213,'mod_search','module','mod_search','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_search\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_SEARCH_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(214,'mod_stats','module','mod_stats','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_stats\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_STATS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(215,'mod_syndicate','module','mod_syndicate','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_syndicate\",\"type\":\"module\",\"creationDate\":\"May 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_SYNDICATE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(216,'mod_users_latest','module','mod_users_latest','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_users_latest\",\"type\":\"module\",\"creationDate\":\"December 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_USERS_LATEST_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(217,'mod_weblinks','module','mod_weblinks','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_weblinks\",\"type\":\"module\",\"creationDate\":\"July 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_WEBLINKS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(218,'mod_whosonline','module','mod_whosonline','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_whosonline\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_WHOSONLINE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(219,'mod_wrapper','module','mod_wrapper','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_wrapper\",\"type\":\"module\",\"creationDate\":\"October 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_WRAPPER_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(220,'mod_articles_category','module','mod_articles_category','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_articles_category\",\"type\":\"module\",\"creationDate\":\"February 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_ARTICLES_CATEGORY_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(221,'mod_articles_categories','module','mod_articles_categories','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_articles_categories\",\"type\":\"module\",\"creationDate\":\"February 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_ARTICLES_CATEGORIES_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(222,'mod_languages','module','mod_languages','',0,1,1,1,'{\"legacy\":false,\"name\":\"mod_languages\",\"type\":\"module\",\"creationDate\":\"February 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_LANGUAGES_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(223,'mod_finder','module','mod_finder','',0,1,0,0,'','','','',0,'0000-00-00 00:00:00',0,0),(300,'mod_custom','module','mod_custom','',1,1,1,1,'{\"legacy\":false,\"name\":\"mod_custom\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_CUSTOM_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(301,'mod_feed','module','mod_feed','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_feed\",\"type\":\"module\",\"creationDate\":\"July 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_FEED_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(302,'mod_latest','module','mod_latest','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_latest\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_LATEST_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(303,'mod_logged','module','mod_logged','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_logged\",\"type\":\"module\",\"creationDate\":\"January 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_LOGGED_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(304,'mod_login','module','mod_login','',1,1,1,1,'{\"legacy\":false,\"name\":\"mod_login\",\"type\":\"module\",\"creationDate\":\"March 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_LOGIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(305,'mod_menu','module','mod_menu','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_menu\",\"type\":\"module\",\"creationDate\":\"March 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_MENU_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(307,'mod_popular','module','mod_popular','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_popular\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_POPULAR_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(308,'mod_quickicon','module','mod_quickicon','',1,1,1,1,'{\"legacy\":false,\"name\":\"mod_quickicon\",\"type\":\"module\",\"creationDate\":\"Nov 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_QUICKICON_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(309,'mod_status','module','mod_status','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_status\",\"type\":\"module\",\"creationDate\":\"Feb 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_STATUS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(310,'mod_submenu','module','mod_submenu','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_submenu\",\"type\":\"module\",\"creationDate\":\"Feb 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_SUBMENU_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(311,'mod_title','module','mod_title','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_title\",\"type\":\"module\",\"creationDate\":\"Nov 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_TITLE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(312,'mod_toolbar','module','mod_toolbar','',1,1,1,1,'{\"legacy\":false,\"name\":\"mod_toolbar\",\"type\":\"module\",\"creationDate\":\"Nov 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_TOOLBAR_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(313,'mod_multilangstatus','module','mod_multilangstatus','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_multilangstatus\",\"type\":\"module\",\"creationDate\":\"September 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_MULTILANGSTATUS_XML_DESCRIPTION\",\"group\":\"\"}','{\"cache\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(314,'mod_version','module','mod_version','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_version\",\"type\":\"module\",\"creationDate\":\"January 2012\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_VERSION_XML_DESCRIPTION\",\"group\":\"\"}','{\"format\":\"short\",\"product\":\"1\",\"cache\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(400,'plg_authentication_gmail','plugin','gmail','authentication',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_authentication_gmail\",\"type\":\"plugin\",\"creationDate\":\"February 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_GMAIL_XML_DESCRIPTION\",\"group\":\"\"}','{\"applysuffix\":\"0\",\"suffix\":\"\",\"verifypeer\":\"1\",\"user_blacklist\":\"\"}','','',0,'0000-00-00 00:00:00',1,0),(401,'plg_authentication_joomla','plugin','joomla','authentication',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_authentication_joomla\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_AUTH_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(402,'plg_authentication_ldap','plugin','ldap','authentication',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_authentication_ldap\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_LDAP_XML_DESCRIPTION\",\"group\":\"\"}','{\"host\":\"\",\"port\":\"389\",\"use_ldapV3\":\"0\",\"negotiate_tls\":\"0\",\"no_referrals\":\"0\",\"auth_method\":\"bind\",\"base_dn\":\"\",\"search_string\":\"\",\"users_dn\":\"\",\"username\":\"admin\",\"password\":\"bobby7\",\"ldap_fullname\":\"fullName\",\"ldap_email\":\"mail\",\"ldap_uid\":\"uid\"}','','',0,'0000-00-00 00:00:00',3,0),(404,'plg_content_emailcloak','plugin','emailcloak','content',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_content_emailcloak\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_CONTENT_EMAILCLOAK_XML_DESCRIPTION\",\"group\":\"\"}','{\"mode\":\"1\"}','','',0,'0000-00-00 00:00:00',1,0),(405,'plg_content_geshi','plugin','geshi','content',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_content_geshi\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"\",\"authorUrl\":\"qbnz.com\\/highlighter\",\"version\":\"2.5.0\",\"description\":\"PLG_CONTENT_GESHI_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',2,0),(406,'plg_content_loadmodule','plugin','loadmodule','content',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_content_loadmodule\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_LOADMODULE_XML_DESCRIPTION\",\"group\":\"\"}','{\"style\":\"xhtml\"}','','',0,'2011-09-18 15:22:50',0,0),(407,'plg_content_pagebreak','plugin','pagebreak','content',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_content_pagebreak\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_CONTENT_PAGEBREAK_XML_DESCRIPTION\",\"group\":\"\"}','{\"title\":\"1\",\"multipage_toc\":\"1\",\"showall\":\"1\"}','','',0,'0000-00-00 00:00:00',4,0),(408,'plg_content_pagenavigation','plugin','pagenavigation','content',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_content_pagenavigation\",\"type\":\"plugin\",\"creationDate\":\"January 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_PAGENAVIGATION_XML_DESCRIPTION\",\"group\":\"\"}','{\"position\":\"1\"}','','',0,'0000-00-00 00:00:00',5,0),(409,'plg_content_vote','plugin','vote','content',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_content_vote\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_VOTE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',6,0),(410,'plg_editors_codemirror','plugin','codemirror','editors',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_editors_codemirror\",\"type\":\"plugin\",\"creationDate\":\"28 March 2011\",\"author\":\"Marijn Haverbeke\",\"copyright\":\"\",\"authorEmail\":\"N\\/A\",\"authorUrl\":\"\",\"version\":\"1.0\",\"description\":\"PLG_CODEMIRROR_XML_DESCRIPTION\",\"group\":\"\"}','{\"linenumbers\":\"0\",\"tabmode\":\"indent\"}','','',0,'0000-00-00 00:00:00',1,0),(411,'plg_editors_none','plugin','none','editors',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_editors_none\",\"type\":\"plugin\",\"creationDate\":\"August 2004\",\"author\":\"Unknown\",\"copyright\":\"\",\"authorEmail\":\"N\\/A\",\"authorUrl\":\"\",\"version\":\"2.5.0\",\"description\":\"PLG_NONE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',2,0),(412,'plg_editors_tinymce','plugin','tinymce','editors',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_editors_tinymce\",\"type\":\"plugin\",\"creationDate\":\"2005-2012\",\"author\":\"Moxiecode Systems AB\",\"copyright\":\"Moxiecode Systems AB\",\"authorEmail\":\"N\\/A\",\"authorUrl\":\"tinymce.moxiecode.com\\/\",\"version\":\"3.4.9\",\"description\":\"PLG_TINY_XML_DESCRIPTION\",\"group\":\"\"}','{\"mode\":\"1\",\"skin\":\"0\",\"compressed\":\"0\",\"cleanup_startup\":\"0\",\"cleanup_save\":\"2\",\"entity_encoding\":\"raw\",\"lang_mode\":\"0\",\"lang_code\":\"en\",\"text_direction\":\"ltr\",\"content_css\":\"1\",\"content_css_custom\":\"\",\"relative_urls\":\"1\",\"newlines\":\"0\",\"invalid_elements\":\"script,applet,iframe\",\"extended_elements\":\"\",\"toolbar\":\"top\",\"toolbar_align\":\"left\",\"html_height\":\"550\",\"html_width\":\"750\",\"element_path\":\"1\",\"fonts\":\"1\",\"paste\":\"1\",\"searchreplace\":\"1\",\"insertdate\":\"1\",\"format_date\":\"%Y-%m-%d\",\"inserttime\":\"1\",\"format_time\":\"%H:%M:%S\",\"colors\":\"1\",\"table\":\"1\",\"smilies\":\"1\",\"media\":\"1\",\"hr\":\"1\",\"directionality\":\"1\",\"fullscreen\":\"1\",\"style\":\"1\",\"layer\":\"1\",\"xhtmlxtras\":\"1\",\"visualchars\":\"1\",\"nonbreaking\":\"1\",\"template\":\"1\",\"blockquote\":\"1\",\"wordcount\":\"1\",\"advimage\":\"1\",\"advlink\":\"1\",\"autosave\":\"1\",\"contextmenu\":\"1\",\"inlinepopups\":\"1\",\"safari\":\"0\",\"custom_plugin\":\"\",\"custom_button\":\"\"}','','',0,'0000-00-00 00:00:00',3,0),(413,'plg_editors-xtd_article','plugin','article','editors-xtd',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_editors-xtd_article\",\"type\":\"plugin\",\"creationDate\":\"October 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_ARTICLE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',1,0),(414,'plg_editors-xtd_image','plugin','image','editors-xtd',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_editors-xtd_image\",\"type\":\"plugin\",\"creationDate\":\"August 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_IMAGE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',2,0),(415,'plg_editors-xtd_pagebreak','plugin','pagebreak','editors-xtd',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_editors-xtd_pagebreak\",\"type\":\"plugin\",\"creationDate\":\"August 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_EDITORSXTD_PAGEBREAK_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',3,0),(416,'plg_editors-xtd_readmore','plugin','readmore','editors-xtd',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_editors-xtd_readmore\",\"type\":\"plugin\",\"creationDate\":\"March 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_READMORE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',4,0),(417,'plg_search_categories','plugin','categories','search',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_search_categories\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SEARCH_CATEGORIES_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),(418,'plg_search_contacts','plugin','contacts','search',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_search_contacts\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SEARCH_CONTACTS_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),(419,'plg_search_content','plugin','content','search',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_search_content\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SEARCH_CONTENT_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),(420,'plg_search_newsfeeds','plugin','newsfeeds','search',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_search_newsfeeds\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SEARCH_NEWSFEEDS_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),(421,'plg_search_weblinks','plugin','weblinks','search',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_search_weblinks\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SEARCH_WEBLINKS_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),(422,'plg_system_languagefilter','plugin','languagefilter','system',0,0,1,1,'{\"legacy\":false,\"name\":\"plg_system_languagefilter\",\"type\":\"plugin\",\"creationDate\":\"July 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SYSTEM_LANGUAGEFILTER_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',1,0),(423,'plg_system_p3p','plugin','p3p','system',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_system_p3p\",\"type\":\"plugin\",\"creationDate\":\"September 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_P3P_XML_DESCRIPTION\",\"group\":\"\"}','{\"headers\":\"NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM\"}','','',0,'0000-00-00 00:00:00',2,0),(424,'plg_system_cache','plugin','cache','system',0,0,1,1,'{\"legacy\":false,\"name\":\"plg_system_cache\",\"type\":\"plugin\",\"creationDate\":\"February 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_CACHE_XML_DESCRIPTION\",\"group\":\"\"}','{\"browsercache\":\"0\",\"cachetime\":\"15\"}','','',0,'0000-00-00 00:00:00',9,0),(425,'plg_system_debug','plugin','debug','system',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_system_debug\",\"type\":\"plugin\",\"creationDate\":\"December 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_DEBUG_XML_DESCRIPTION\",\"group\":\"\"}','{\"profile\":\"1\",\"queries\":\"1\",\"memory\":\"1\",\"language_files\":\"1\",\"language_strings\":\"1\",\"strip-first\":\"1\",\"strip-prefix\":\"\",\"strip-suffix\":\"\"}','','',0,'0000-00-00 00:00:00',4,0),(426,'plg_system_log','plugin','log','system',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_system_log\",\"type\":\"plugin\",\"creationDate\":\"April 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_LOG_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',5,0),(427,'plg_system_redirect','plugin','redirect','system',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_system_redirect\",\"type\":\"plugin\",\"creationDate\":\"April 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_REDIRECT_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',6,0),(428,'plg_system_remember','plugin','remember','system',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_system_remember\",\"type\":\"plugin\",\"creationDate\":\"April 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_REMEMBER_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',7,0),(429,'plg_system_sef','plugin','sef','system',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_system_sef\",\"type\":\"plugin\",\"creationDate\":\"December 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SEF_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',8,0),(430,'plg_system_logout','plugin','logout','system',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_system_logout\",\"type\":\"plugin\",\"creationDate\":\"April 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SYSTEM_LOGOUT_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',3,0),(431,'plg_user_contactcreator','plugin','contactcreator','user',0,0,1,1,'{\"legacy\":false,\"name\":\"plg_user_contactcreator\",\"type\":\"plugin\",\"creationDate\":\"August 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_CONTACTCREATOR_XML_DESCRIPTION\",\"group\":\"\"}','{\"autowebpage\":\"\",\"category\":\"34\",\"autopublish\":\"0\"}','','',0,'0000-00-00 00:00:00',1,0),(432,'plg_user_joomla','plugin','joomla','user',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_user_joomla\",\"type\":\"plugin\",\"creationDate\":\"December 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2009 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_USER_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{\"autoregister\":\"1\"}','','',0,'0000-00-00 00:00:00',2,0),(433,'plg_user_profile','plugin','profile','user',0,0,1,1,'{\"legacy\":false,\"name\":\"plg_user_profile\",\"type\":\"plugin\",\"creationDate\":\"January 2008\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_USER_PROFILE_XML_DESCRIPTION\",\"group\":\"\"}','{\"register-require_address1\":\"1\",\"register-require_address2\":\"1\",\"register-require_city\":\"1\",\"register-require_region\":\"1\",\"register-require_country\":\"1\",\"register-require_postal_code\":\"1\",\"register-require_phone\":\"1\",\"register-require_website\":\"1\",\"register-require_favoritebook\":\"1\",\"register-require_aboutme\":\"1\",\"register-require_tos\":\"1\",\"register-require_dob\":\"1\",\"profile-require_address1\":\"1\",\"profile-require_address2\":\"1\",\"profile-require_city\":\"1\",\"profile-require_region\":\"1\",\"profile-require_country\":\"1\",\"profile-require_postal_code\":\"1\",\"profile-require_phone\":\"1\",\"profile-require_website\":\"1\",\"profile-require_favoritebook\":\"1\",\"profile-require_aboutme\":\"1\",\"profile-require_tos\":\"1\",\"profile-require_dob\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),(434,'plg_extension_joomla','plugin','joomla','extension',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_extension_joomla\",\"type\":\"plugin\",\"creationDate\":\"May 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_EXTENSION_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',1,0),(435,'plg_content_joomla','plugin','joomla','content',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_content_joomla\",\"type\":\"plugin\",\"creationDate\":\"November 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_CONTENT_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(436,'plg_system_languagecode','plugin','languagecode','system',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_system_languagecode\",\"type\":\"plugin\",\"creationDate\":\"November 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SYSTEM_LANGUAGECODE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',10,0),(437,'plg_quickicon_joomlaupdate','plugin','joomlaupdate','quickicon',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_quickicon_joomlaupdate\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_QUICKICON_JOOMLAUPDATE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(438,'plg_quickicon_extensionupdate','plugin','extensionupdate','quickicon',0,1,1,1,'{\"legacy\":false,\"name\":\"plg_quickicon_extensionupdate\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_QUICKICON_EXTENSIONUPDATE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(439,'plg_captcha_recaptcha','plugin','recaptcha','captcha',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_captcha_recaptcha\",\"type\":\"plugin\",\"creationDate\":\"December 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_CAPTCHA_RECAPTCHA_XML_DESCRIPTION\",\"group\":\"\"}','{\"public_key\":\"\",\"private_key\":\"\",\"theme\":\"clean\"}','','',0,'0000-00-00 00:00:00',0,0),(440,'plg_system_highlight','plugin','highlight','system',0,1,1,0,'','{}','','',0,'0000-00-00 00:00:00',7,0),(441,'plg_content_finder','plugin','finder','content',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_content_finder\",\"type\":\"plugin\",\"creationDate\":\"December 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"1.7.0\",\"description\":\"PLG_CONTENT_FINDER_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(442,'plg_finder_categories','plugin','categories','finder',0,1,1,0,'','{}','','',0,'0000-00-00 00:00:00',1,0),(443,'plg_finder_contacts','plugin','contacts','finder',0,1,1,0,'','{}','','',0,'0000-00-00 00:00:00',2,0),(444,'plg_finder_content','plugin','content','finder',0,1,1,0,'','{}','','',0,'0000-00-00 00:00:00',3,0),(445,'plg_finder_newsfeeds','plugin','newsfeeds','finder',0,1,1,0,'','{}','','',0,'0000-00-00 00:00:00',4,0),(446,'plg_finder_weblinks','plugin','weblinks','finder',0,1,1,0,'','{}','','',0,'0000-00-00 00:00:00',5,0),(500,'atomic','template','atomic','',0,1,1,0,'{\"legacy\":false,\"name\":\"atomic\",\"type\":\"template\",\"creationDate\":\"10\\/10\\/09\",\"author\":\"Ron Severdia\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"contact@kontentdesign.com\",\"authorUrl\":\"http:\\/\\/www.kontentdesign.com\",\"version\":\"2.5.0\",\"description\":\"TPL_ATOMIC_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(502,'bluestork','template','bluestork','',1,1,1,0,'{\"legacy\":false,\"name\":\"bluestork\",\"type\":\"template\",\"creationDate\":\"07\\/02\\/09\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"TPL_BLUESTORK_XML_DESCRIPTION\",\"group\":\"\"}','{\"useRoundedCorners\":\"1\",\"showSiteName\":\"0\",\"textBig\":\"0\",\"highContrast\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(503,'beez_20','template','beez_20','',0,1,1,0,'{\"legacy\":false,\"name\":\"beez_20\",\"type\":\"template\",\"creationDate\":\"25 November 2009\",\"author\":\"Angie Radtke\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"a.radtke@derauftritt.de\",\"authorUrl\":\"http:\\/\\/www.der-auftritt.de\",\"version\":\"2.5.0\",\"description\":\"TPL_BEEZ2_XML_DESCRIPTION\",\"group\":\"\"}','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"sitetitle\":\"\",\"sitedescription\":\"\",\"navposition\":\"center\",\"templatecolor\":\"nature\"}','','',0,'0000-00-00 00:00:00',0,0),(504,'hathor','template','hathor','',1,1,1,0,'{\"legacy\":false,\"name\":\"hathor\",\"type\":\"template\",\"creationDate\":\"May 2010\",\"author\":\"Andrea Tarr\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"hathor@tarrconsulting.com\",\"authorUrl\":\"http:\\/\\/www.tarrconsulting.com\",\"version\":\"2.5.0\",\"description\":\"TPL_HATHOR_XML_DESCRIPTION\",\"group\":\"\"}','{\"showSiteName\":\"0\",\"colourChoice\":\"0\",\"boldText\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(505,'beez5','template','beez5','',0,1,1,0,'{\"legacy\":false,\"name\":\"beez5\",\"type\":\"template\",\"creationDate\":\"21 May 2010\",\"author\":\"Angie Radtke\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"a.radtke@derauftritt.de\",\"authorUrl\":\"http:\\/\\/www.der-auftritt.de\",\"version\":\"2.5.0\",\"description\":\"TPL_BEEZ5_XML_DESCRIPTION\",\"group\":\"\"}','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"sitetitle\":\"\",\"sitedescription\":\"\",\"navposition\":\"center\",\"html5\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(600,'English (United Kingdom)','language','en-GB','',0,1,1,1,'{\"legacy\":false,\"name\":\"English (United Kingdom)\",\"type\":\"language\",\"creationDate\":\"2008-03-15\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"en-GB site language\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(601,'English (United Kingdom)','language','en-GB','',1,1,1,1,'{\"legacy\":false,\"name\":\"English (United Kingdom)\",\"type\":\"language\",\"creationDate\":\"2008-03-15\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"en-GB administrator language\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(700,'files_joomla','file','joomla','',0,1,1,1,'{\"legacy\":false,\"name\":\"files_joomla\",\"type\":\"file\",\"creationDate\":\"April 2012\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2012 Open Source Matters. All rights reserved\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.4\",\"description\":\"FILES_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(800,'PKG_JOOMLA','package','pkg_joomla','',0,1,1,1,'{\"legacy\":false,\"name\":\"PKG_JOOMLA\",\"type\":\"package\",\"creationDate\":\"2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"http:\\/\\/www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PKG_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(10000,'e20','template','e20','',0,1,1,0,'{\"legacy\":true,\"name\":\"e20\",\"type\":\"template\",\"creationDate\":\"14\\/11\\/11\",\"author\":\"JomSocial Team\",\"copyright\":\"Copyright (C) Slashes n Dots Sdn Bhd. All rights reserved.\",\"authorEmail\":\"support@jomsocial.com\",\"authorUrl\":\"http:\\/\\/www.jomsocial.com\",\"version\":\"1.7.2\",\"description\":\"Enterprise 2.0\",\"group\":\"\"}','{\"colorVariation\":\"white\"}','','',0,'0000-00-00 00:00:00',0,0),(10001,'mod_navigator','module','mod_navigator','',0,1,0,0,'{\"legacy\":false,\"name\":\"mod_navigator\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2011 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"1.7.0\",\"description\":\"mod_navigator_XML_DESCRIPTION\",\"group\":\"\"}','{\"serverinfo\":\"0\",\"siteinfo\":\"0\",\"counter\":\"0\",\"increase\":\"0\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}','','',0,'0000-00-00 00:00:00',0,0),(10003,'com_stream','component','com_stream','',1,1,1,1,'','','','',0,'0000-00-00 00:00:00',0,0),(10004,'com_profile','component','com_profile','',1,1,1,1,'','','','',0,'0000-00-00 00:00:00',0,0),(10005,'com_people','component','com_people','',1,1,1,1,'','','','',0,'0000-00-00 00:00:00',0,0),(10006,'System - jFinalizer','plugin','jfinalizer','system',0,0,1,0,'{\"legacy\":false,\"name\":\"System - jFinalizer\",\"type\":\"plugin\",\"creationDate\":\"July 2022\",\"author\":\"farbfinal.de\",\"copyright\":\"(C) 2011 farbfinal.de. All rights reserved.\",\"authorEmail\":\"info@farbfinal.de\",\"authorUrl\":\"www.farbfinal.de\\/jfinalizer\",\"version\":\"2.0.1\",\"description\":\"JFINALIZER_DESCRIPTION\",\"group\":\"\"}','{\"usecache\":\"1\",\"debug\":\"2\",\"htmllevel\":\"3\",\"htmlcomments\":\"1\",\"htmlgenerator\":\"0\",\"processjs\":\"1\",\"compactjs\":\"1\",\"jquerynoconflict\":\"0\",\"processcss\":\"0\",\"compactcss\":\"1\",\"skipjs\":\"tiny_mce.js,xajax.js,print.css,media=\\\"print\",\"gzipmode\":\"2\",\"htmlpre\":\"1\",\"checkexcept\":\"1\",\"autoplace\":\"0\",\"outpath\":\"\",\"includeremote\":\"1\",\"subdir\":\"\",\"cachelifetime\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(10007,'Akeeba Backup Notification Module','module','mod_akadmin','',1,1,2,0,'{\"legacy\":true,\"name\":\"Akeeba Backup Notification Module\",\"type\":\"module\",\"creationDate\":\"2011-12-04\",\"author\":\"Nicholas K. Dionysopoulos\",\"copyright\":\"Copyright (c)2009-2010 Nicholas K. Dionysopoulos\",\"authorEmail\":\"nicholas@dionysopoulos.me\",\"authorUrl\":\"http:\\/\\/www.akeebabackup.com\",\"version\":\"3.3.10\",\"description\":\"\\n\\t<h1>Akeeba Backup Notification Module<\\/h1>\\n\\t<p>This is a handy  module to display a Akeeba icon on your administrator\\n\\tback-end\'s Control Panel page. The icon displays a warning site if the last\\n\\tbackup is failed, or if you haven\'t backed up your site for a period of time\\n\\t(user-defined). Clicking it brings you to the Akeeba Backup &quot;Backup Now&quot;\\n\\tpage.<\\/p>\\n\\t\",\"group\":\"\"}','[]','','',0,'0000-00-00 00:00:00',0,0),(10008,'akeeba','component','com_akeeba','',1,1,0,0,'{\"legacy\":true,\"name\":\"Akeeba\",\"type\":\"component\",\"creationDate\":\"2011-12-04\",\"author\":\"Nicholas K. Dionysopoulos\",\"copyright\":\"Copyright (c)2006-2010 Nicholas K. Dionysopoulos\",\"authorEmail\":\"nicholas@dionysopoulos.me\",\"authorUrl\":\"http:\\/\\/www.akeebabackup.com\",\"version\":\"3.3.10\",\"description\":\"Akeeba Backup Core (formerly JoomlaPack) - Full Joomla! site backup solution, Core Edition. Making backup as simple as ABC!\",\"group\":\"\"}','{\"frontend_enable\":\"0\",\"frontend_secret_word\":\"\",\"frontend_email_on_finish\":\"0\",\"frontend_email_address\":\"\",\"frontend_email_subject\":\"\",\"frontend_email_body\":\"\",\"siteurl\":\"http:\\/\\/bnm.offiria.com\\/\",\"jversion\":\"1.6\",\"jlibrariesdir\":\"\\/home\\/www\\/bnm.offiria.com\\/public_html\\/libraries\",\"lastversion\":\"3.3.10\",\"usesvnsource\":\"0\",\"update_dlid\":\"\",\"minstability\":\"alpha\",\"lastupdatecheck\":\"2009-01-01\",\"updateini\":\"\",\"liveupdate\":\"stuck=0\\nlastcheck=1329793497\\nupdatedata=\\\"\\\"{\\\\\\\"supported\\\\\\\":true,\\\\\\\"stuck\\\\\\\":false,\\\\\\\"version\\\\\\\":\\\\\\\"3.3.13\\\\\\\",\\\\\\\"date\\\\\\\":\\\\\\\"2012-01-29\\\\\\\",\\\\\\\"stability\\\\\\\":\\\\\\\"stable\\\\\\\",\\\\\\\"downloadURL\\\\\\\":\\\\\\\"http:\\\\\\\\\\\\\\/\\\\\\\\\\\\\\/joomlacode.org\\\\\\\\\\\\\\/gf\\\\\\\\\\\\\\/download\\\\\\\\\\\\\\/frsrelease\\\\\\\\\\\\\\/16484\\\\\\\\\\\\\\/71856\\\\\\\\\\\\\\/com_akeeba-3.3.13-core.zip\\\\\\\",\\\\\\\"infoURL\\\\\\\":\\\\\\\"https:\\\\\\\\\\\\\\/\\\\\\\\\\\\\\/www.akeebabackup.com\\\\\\\\\\\\\\/download\\\\\\\\\\\\\\/akeeba-backup\\\\\\\\\\\\\\/akeeba-backup-3-3-13.html\\\\\\\",\\\\\\\"releasenotes\\\\\\\":\\\\\\\"<h3 class=\\\\\\\\\\\\\\\"p1\\\\\\\\\\\\\\\"><span class=\\\\\\\\\\\\\\\"s1\\\\\\\\\\\\\\\"><strong>Changelog<\\\\\\\\\\\\\\/strong><\\\\\\\\\\\\\\/span><\\\\\\\\\\\\\\/h3><p class=\\\\\\\\\\\\\\\"p1\\\\\\\\\\\\\\\"><span class=\\\\\\\\\\\\\\\"s1\\\\\\\\\\\\\\\"><strong>New features<\\\\\\\\\\\\\\/strong><\\\\\\\\\\\\\\/span><\\\\\\\\\\\\\\/p><ul><li class=\\\\\\\\\\\\\\\"li9\\\\\\\\\\\\\\\">You can now update Akeeba Backup Professional using the Joomla! extensions update (you still have to supply your Download ID to the component)<\\\\\\\\\\\\\\/li><li class=\\\\\\\\\\\\\\\"li9\\\\\\\\\\\\\\\">System Restore Points: Allow skipping table data with the <skiptables \\\\\\\\\\\\\\/> element<\\\\\\\\\\\\\\/li><\\\\\\\\\\\\\\/ul><p class=\\\\\\\\\\\\\\\"p1\\\\\\\\\\\\\\\"><strong>High-priority fixes<\\\\\\\\\\\\\\/strong><\\\\\\\\\\\\\\/p><ul><li class=\\\\\\\\\\\\\\\"li9\\\\\\\\\\\\\\\">System Restore Points threw an error when updating a component<\\\\\\\\\\\\\\/li><li class=\\\\\\\\\\\\\\\"li9\\\\\\\\\\\\\\\">Configuration overrides weren\'t being applied (affecting backup.php CRON script and System Restore Points)<\\\\\\\\\\\\\\/li><\\\\\\\\\\\\\\/ul><p class=\\\\\\\\\\\\\\\"p1\\\\\\\\\\\\\\\"><span class=\\\\\\\\\\\\\\\"s1\\\\\\\\\\\\\\\"><strong>Bug fixes<\\\\\\\\\\\\\\/strong><\\\\\\\\\\\\\\/span><\\\\\\\\\\\\\\/p><ul><li class=\\\\\\\\\\\\\\\"li9\\\\\\\\\\\\\\\">The extension post-installation message would not show when System Restore Points was enabled<\\\\\\\\\\\\\\/li><li class=\\\\\\\\\\\\\\\"li9\\\\\\\\\\\\\\\">Language strings not showing on installation<\\\\\\\\\\\\\\/li><\\\\\\\\\\\\\\/ul>\\\\\\\"}\\\"\\\"\",\"useencryption\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),(10009,'mod_janalytics','module','mod_janalytics','',0,1,0,0,'{\"legacy\":false,\"name\":\"mod_janalytics\",\"type\":\"module\",\"creationDate\":\"March 2011\",\"author\":\"Dean Tedesco\",\"copyright\":\"Copyright (C) 2008 - 2011 Dean Tedesco. All rights reserved.\",\"authorEmail\":\"dino@tedesco.net.au\",\"authorUrl\":\"http:\\/\\/janalytics.tedesco.net.au\\/\",\"version\":\"3.0.0\",\"description\":\"MOD_JANALYTICS_XML_DESCRIPTION\",\"group\":\"\"}','{\"gaid\":\"\",\"tracking\":\"single\",\"domain\":\"\",\"anonymizeip\":\"off\"}','','',0,'0000-00-00 00:00:00',0,0),(10010,'plg_system_jch_optimize','plugin','jch_optimize','system',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_system_jch_optimize\",\"type\":\"plugin\",\"creationDate\":\"March 2010\",\"author\":\"Samuel Marshall\",\"copyright\":\"Copyright (C) 2010 Samuel Marshall. All rights reserved.\",\"authorEmail\":\"sdmarshall73@gmail.com\",\"authorUrl\":\"http:\\/\\/jch-optimize.sourceforge.net\",\"version\":\"2.0.0\",\"description\":\"JCH_OPTIMIZE_DESCRIPTION\",\"group\":\"\"}','{\"css\":\"0\",\"import\":\"0\",\"javascript\":\"1\",\"gzip\":\"0\",\"css_minify\":\"0\",\"js_minify\":\"0\",\"html_minify\":\"0\",\"defer_js\":\"0\",\"bottom_js\":\"2\",\"lifetime\":\"2\",\"excludeAllExtensions\":\"1\",\"excludeCss\":\"\",\"excludeJs\":\"\",\"excludeComponents\":\"\",\"jqueryNOConflict\":\"1\",\"jquery\":\"jquery.js\",\"customOrder\":\"mootools.js,jquery.js,jquery.innerfade.js\",\"htaccess\":\"0\",\"csg_enable\":\"0\",\"csg_file_output\":\"PNG\",\"csg_min_max_images\":\"0\",\"csg_direction\":\"vertical\",\"csg_wrap_images\":\"off\",\"csg_include_images\":\"\",\"csg_exclude_images\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),(10011,'PLG_SYS_ADMINEXILE','plugin','adminexile','system',0,1,1,0,'{\"legacy\":true,\"name\":\"PLG_SYS_ADMINEXILE\",\"type\":\"plugin\",\"creationDate\":\"Jan 2011\",\"author\":\"Michael Richey\",\"copyright\":\"Copyright (C) 2011 Michael Richey. All rights reserved.\",\"authorEmail\":\"adminexile@richeyweb.com\",\"authorUrl\":\"http:\\/\\/www.richeyweb.com\",\"version\":\"1.4\",\"description\":\"PLG_SYS_ADMINEXILE_XML_DESC\",\"group\":\"\"}','{\"key\":\"8731b34101cccad8e47a6fd6390bdbe6\",\"redirect\":\"{HOME}\",\"frontrestrict\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(10013,'plg_system_oauth','plugin','oauth','system',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_system_oauth\",\"type\":\"plugin\",\"creationDate\":\"December 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2011 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"1.7.0\",\"description\":\"PLG_OAUTH_XML_DESCRIPTION\",\"group\":\"\"}','{\"oauth_\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(10014,'PLG_SYSTEM_CDNFORJOOMLA','plugin','cdnforjoomla','system',0,0,1,0,'{\"legacy\":true,\"name\":\"PLG_SYSTEM_CDNFORJOOMLA\",\"type\":\"plugin\",\"creationDate\":\"November 2011\",\"author\":\"NoNumber! (Peter van Westen)\",\"copyright\":\"Copyright \\u00a9 2011 NoNumber! All Rights Reserved\",\"authorEmail\":\"peter@nonumber.nl\",\"authorUrl\":\"http:\\/\\/www.nonumber.nl\",\"version\":\"1.7.0\",\"description\":\"PLG_SYSTEM_CDNFORJOOMLA_DESC\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(10015,'PLG_SYSTEM_NNFRAMEWORK','plugin','nnframework','system',0,1,1,0,'{\"legacy\":true,\"name\":\"PLG_SYSTEM_NNFRAMEWORK\",\"type\":\"plugin\",\"creationDate\":\"November 2011\",\"author\":\"NoNumber! (Peter van Westen)\",\"copyright\":\"Copyright \\u00a9 2011 NoNumber! All Rights Reserved\",\"authorEmail\":\"peter@nonumber.nl\",\"authorUrl\":\"http:\\/\\/www.nonumber.nl\",\"version\":\"11.11.3\",\"description\":\"PLG_SYSTEM_NNFRAMEWORK_DESC\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),(10016,'Offiria','template','offiria','',0,1,1,0,'{\"legacy\":true,\"name\":\"Offiria\",\"type\":\"template\",\"creationDate\":\"18\\/01\\/12\",\"author\":\"JomSocial Team\",\"copyright\":\"Copyright (C) Slashes n Dots Sdn Bhd. All rights reserved.\",\"authorEmail\":\"support@jomsocial.com\",\"authorUrl\":\"http:\\/\\/www.jomsocial.com\",\"version\":\"1.7.2\",\"description\":\"Offiria\",\"group\":\"\"}','{\"colorVariation\":\"white\"}','','',0,'0000-00-00 00:00:00',0,0),(10017,'System - Asynchronous Google Analytics','plugin','AsynGoogleAnalytics','system',0,1,1,0,'{\"legacy\":false,\"name\":\"System - Asynchronous Google Analytics\",\"type\":\"plugin\",\"creationDate\":\"19th April 2010\",\"author\":\"Peter Bui\",\"copyright\":\"Copyright (c) 2010 PB Web Development. All rights reserved.\",\"authorEmail\":\"peter@pbwebdev.com.au\",\"authorUrl\":\"http:\\/\\/www.pbwebdev.com.au\",\"version\":\"1.0\",\"description\":\"Asynchronous Goolge analytics allows for a faster loading Google Analytics code and tracking. For more information please read - http:\\/\\/code.google.com\\/apis\\/analytics\\/docs\\/tracking\\/asyncTracking.html\\n    \\n    Please enable and configure this plugin in the plugin manager. Read the documentation at: http:\\/\\/www.pbwebdev.com.au\\/blog\\/asynchronous-google-analytics-plugin-for-joomla\",\"group\":\"\"}','{\"code\":\"UA-670908-19\"}','','',0,'0000-00-00 00:00:00',0,0),(10018,'plg_system_mobile','plugin','mobile','system',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_system_mobile\",\"type\":\"plugin\",\"creationDate\":\"December 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2011 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"1.7.0\",\"description\":\"PLG_MOBILE_XML_DESCRIPTION\",\"group\":\"\"}','{\"mobile_\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),(10019,'Mobile','template','mobile','',0,1,1,0,'{\"legacy\":true,\"name\":\"Mobile\",\"type\":\"template\",\"creationDate\":\"18\\/01\\/12\",\"author\":\"JomSocial Team\",\"copyright\":\"Copyright (C) Slashes n Dots Sdn Bhd. All rights reserved.\",\"authorEmail\":\"support@jomsocial.com\",\"authorUrl\":\"http:\\/\\/www.jomsocial.com\",\"version\":\"1.7.2\",\"description\":\"Offiria Mobile Theme\",\"group\":\"\"}','{\"colorVariation\":\"white\"}','','',0,'0000-00-00 00:00:00',0,0),(10020,'Search - Streams','plugin','streams','search',0,1,1,0,'{\"legacy\":true,\"name\":\"Search - Streams\",\"type\":\"plugin\",\"creationDate\":\"April 2012\",\"author\":\"Offiria\",\"copyright\":\"Copyright 2008 - 2012 by Slashes & Dots Sdn Bhd. All rights reserved\",\"authorEmail\":\"support@azrul.com\",\"authorUrl\":\"http:\\/\\/www.jomsocial.com\",\"version\":\"2.2.4\",\"description\":\"Search - Streams\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),(10021,'com_register','component','com_register','',0,1,1,0,'','','','',0,'0000-00-00 00:00:00',0,0),(10022,'com_account','component','com_account','',0,1,1,0,'','','','',0,'0000-00-00 00:00:00',0,0),(10023,'com_analytics','component','com_analytics','',0,1,1,0,'','','','',0,'0000-00-00 00:00:00',0,0);
/*!40000 ALTER TABLE `%%PREFIX%%extensions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_filters`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_filters` (
  `filter_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL,
  `created_by_alias` varchar(255) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `map_count` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `params` mediumtext,
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_filters`
--

LOCK TABLES `%%PREFIX%%finder_filters` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_filters` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_filters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links` (
  `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `indexdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `md5sum` varchar(32) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `state` int(5) DEFAULT '1',
  `access` int(5) DEFAULT '0',
  `language` varchar(8) NOT NULL,
  `publish_start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `list_price` double unsigned NOT NULL DEFAULT '0',
  `sale_price` double unsigned NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL,
  `object` mediumblob NOT NULL,
  PRIMARY KEY (`link_id`),
  KEY `idx_type` (`type_id`),
  KEY `idx_title` (`title`),
  KEY `idx_md5` (`md5sum`),
  KEY `idx_url` (`url`(75)),
  KEY `idx_published_list` (`published`,`state`,`access`,`publish_start_date`,`publish_end_date`,`list_price`),
  KEY `idx_published_sale` (`published`,`state`,`access`,`publish_start_date`,`publish_end_date`,`sale_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links`
--

LOCK TABLES `%%PREFIX%%finder_links` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms0`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms0`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms0` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms0`
--

LOCK TABLES `%%PREFIX%%finder_links_terms0` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms0` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms0` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms1`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms1` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms1`
--

LOCK TABLES `%%PREFIX%%finder_links_terms1` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms1` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms2`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms2` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms2`
--

LOCK TABLES `%%PREFIX%%finder_links_terms2` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms2` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms3`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms3` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms3`
--

LOCK TABLES `%%PREFIX%%finder_links_terms3` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms3` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms4`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms4` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms4`
--

LOCK TABLES `%%PREFIX%%finder_links_terms4` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms4` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms4` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms5`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms5`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms5` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms5`
--

LOCK TABLES `%%PREFIX%%finder_links_terms5` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms5` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms5` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms6`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms6` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms6`
--

LOCK TABLES `%%PREFIX%%finder_links_terms6` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms6` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms6` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms7`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms7` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms7`
--

LOCK TABLES `%%PREFIX%%finder_links_terms7` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms7` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms8`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms8`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms8` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms8`
--

LOCK TABLES `%%PREFIX%%finder_links_terms8` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms8` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms8` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_terms9`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_terms9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_terms9` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_terms9`
--

LOCK TABLES `%%PREFIX%%finder_links_terms9` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms9` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_terms9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_termsa`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_termsa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_termsa` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_termsa`
--

LOCK TABLES `%%PREFIX%%finder_links_termsa` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsa` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_termsb`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_termsb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_termsb` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_termsb`
--

LOCK TABLES `%%PREFIX%%finder_links_termsb` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsb` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_termsc`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_termsc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_termsc` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_termsc`
--

LOCK TABLES `%%PREFIX%%finder_links_termsc` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsc` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_termsd`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_termsd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_termsd` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_termsd`
--

LOCK TABLES `%%PREFIX%%finder_links_termsd` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsd` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_termse`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_termse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_termse` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_termse`
--

LOCK TABLES `%%PREFIX%%finder_links_termse` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termse` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_links_termsf`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_links_termsf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_links_termsf` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_links_termsf`
--

LOCK TABLES `%%PREFIX%%finder_links_termsf` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsf` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_links_termsf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_taxonomy`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_taxonomy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `state` (`state`),
  KEY `ordering` (`ordering`),
  KEY `access` (`access`),
  KEY `idx_parent_published` (`parent_id`,`state`,`access`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_taxonomy`
--

LOCK TABLES `%%PREFIX%%finder_taxonomy` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_taxonomy` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%finder_taxonomy` VALUES (1,0,'ROOT',0,0,0);
/*!40000 ALTER TABLE `%%PREFIX%%finder_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_taxonomy_map`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_taxonomy_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_taxonomy_map` (
  `link_id` int(10) unsigned NOT NULL,
  `node_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`node_id`),
  KEY `link_id` (`link_id`),
  KEY `node_id` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_taxonomy_map`
--

LOCK TABLES `%%PREFIX%%finder_taxonomy_map` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_taxonomy_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_taxonomy_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_terms`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_terms` (
  `term_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(75) NOT NULL,
  `stem` varchar(75) NOT NULL,
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phrase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `weight` float unsigned NOT NULL DEFAULT '0',
  `soundex` varchar(75) NOT NULL,
  `links` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `idx_term` (`term`),
  KEY `idx_term_phrase` (`term`,`phrase`),
  KEY `idx_stem_phrase` (`stem`,`phrase`),
  KEY `idx_soundex_phrase` (`soundex`,`phrase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_terms`
--

LOCK TABLES `%%PREFIX%%finder_terms` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_terms_common`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_terms_common`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_terms_common` (
  `term` varchar(75) NOT NULL,
  `language` varchar(3) NOT NULL,
  KEY `idx_word_lang` (`term`,`language`),
  KEY `idx_lang` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_terms_common`
--

LOCK TABLES `%%PREFIX%%finder_terms_common` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_terms_common` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%finder_terms_common` VALUES ('a','en'),('about','en'),('after','en'),('ago','en'),('all','en'),('am','en'),('an','en'),('and','en'),('ani','en'),('any','en'),('are','en'),('aren\'t','en'),('as','en'),('at','en'),('be','en'),('but','en'),('by','en'),('for','en'),('from','en'),('get','en'),('go','en'),('how','en'),('if','en'),('in','en'),('into','en'),('is','en'),('isn\'t','en'),('it','en'),('its','en'),('me','en'),('more','en'),('most','en'),('must','en'),('my','en'),('new','en'),('no','en'),('none','en'),('not','en'),('noth','en'),('nothing','en'),('of','en'),('off','en'),('often','en'),('old','en'),('on','en'),('onc','en'),('once','en'),('onli','en'),('only','en'),('or','en'),('other','en'),('our','en'),('ours','en'),('out','en'),('over','en'),('page','en'),('she','en'),('should','en'),('small','en'),('so','en'),('some','en'),('than','en'),('thank','en'),('that','en'),('the','en'),('their','en'),('theirs','en'),('them','en'),('then','en'),('there','en'),('these','en'),('they','en'),('this','en'),('those','en'),('thus','en'),('time','en'),('times','en'),('to','en'),('too','en'),('true','en'),('under','en'),('until','en'),('up','en'),('upon','en'),('use','en'),('user','en'),('users','en'),('veri','en'),('version','en'),('very','en'),('via','en'),('want','en'),('was','en'),('way','en'),('were','en'),('what','en'),('when','en'),('where','en'),('whi','en'),('which','en'),('who','en'),('whom','en'),('whose','en'),('why','en'),('wide','en'),('will','en'),('with','en'),('within','en'),('without','en'),('would','en'),('yes','en'),('yet','en'),('you','en'),('your','en'),('yours','en');
/*!40000 ALTER TABLE `%%PREFIX%%finder_terms_common` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_tokens`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_tokens` (
  `term` varchar(75) NOT NULL,
  `stem` varchar(75) NOT NULL,
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phrase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `weight` float unsigned NOT NULL DEFAULT '1',
  `context` tinyint(1) unsigned NOT NULL DEFAULT '2',
  KEY `idx_word` (`term`),
  KEY `idx_context` (`context`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_tokens`
--

LOCK TABLES `%%PREFIX%%finder_tokens` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_tokens_aggregate`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_tokens_aggregate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_tokens_aggregate` (
  `term_id` int(10) unsigned NOT NULL,
  `map_suffix` char(1) NOT NULL,
  `term` varchar(75) NOT NULL,
  `stem` varchar(75) NOT NULL,
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phrase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `term_weight` float unsigned NOT NULL,
  `context` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `context_weight` float unsigned NOT NULL,
  `total_weight` float unsigned NOT NULL,
  KEY `token` (`term`),
  KEY `keyword_id` (`term_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_tokens_aggregate`
--

LOCK TABLES `%%PREFIX%%finder_tokens_aggregate` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_tokens_aggregate` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_tokens_aggregate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%finder_types`
--

DROP TABLE IF EXISTS `%%PREFIX%%finder_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%finder_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `mime` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%finder_types`
--

LOCK TABLES `%%PREFIX%%finder_types` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%finder_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%finder_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%groups`
--

DROP TABLE IF EXISTS `%%PREFIX%%groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `creator` int(11) NOT NULL,
  `params` text NOT NULL,
  `followers` text,
  `members` text,
  `archived` tinyint(4) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%groups`
--

LOCK TABLES `%%PREFIX%%groups` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%integrations`
--

DROP TABLE IF EXISTS `%%PREFIX%%integrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%integrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%integrations`
--

LOCK TABLES `%%PREFIX%%integrations` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%integrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%integrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%languages`
--

DROP TABLE IF EXISTS `%%PREFIX%%languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%languages` (
  `lang_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang_code` char(7) NOT NULL,
  `title` varchar(50) NOT NULL,
  `title_native` varchar(50) NOT NULL,
  `sef` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL,
  `description` varchar(512) NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `sitename` varchar(1024) NOT NULL DEFAULT '',
  `published` int(11) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`),
  UNIQUE KEY `idx_sef` (`sef`),
  UNIQUE KEY `idx_image` (`image`),
  UNIQUE KEY `idx_langcode` (`lang_code`),
  KEY `idx_ordering` (`ordering`),
  KEY `idx_access` (`access`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%languages`
--

LOCK TABLES `%%PREFIX%%languages` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%languages` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%languages` VALUES (1,'en-GB','English (UK)','English (UK)','en','en','','','','',1,0,1);
/*!40000 ALTER TABLE `%%PREFIX%%languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%menu`
--

DROP TABLE IF EXISTS `%%PREFIX%%menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL COMMENT 'The type of menu this item belongs to. FK to #__menu_types.menutype',
  `title` varchar(255) NOT NULL COMMENT 'The display title of the menu item.',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'The SEF alias of the menu item.',
  `note` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(1024) NOT NULL COMMENT 'The computed path of the menu item based on the alias field.',
  `link` varchar(1024) NOT NULL COMMENT 'The actually link the menu item refers to.',
  `type` varchar(16) NOT NULL COMMENT 'The type of link: Component, URL, Alias, Separator',
  `published` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The published state of the menu link.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'The parent menu item in the menu tree.',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The relative level in the tree.',
  `component_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__extensions.id',
  `ordering` int(11) NOT NULL DEFAULT '0' COMMENT 'The relative ordering of the menu item in the tree.',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__users.id',
  `checked_out_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The time the menu item was checked out.',
  `browserNav` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The click behaviour of the link.',
  `access` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The access level required to view the menu item.',
  `img` varchar(255) NOT NULL COMMENT 'The image of the menu item.',
  `template_style_id` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL COMMENT 'JSON encoded data for the menu item.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `home` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicates if this menu item is the home or default page.',
  `language` char(7) NOT NULL DEFAULT '',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_client_id_parent_id_alias_language` (`client_id`,`parent_id`,`alias`,`language`),
  KEY `idx_componentid` (`component_id`,`menutype`,`published`,`access`),
  KEY `idx_menutype` (`menutype`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_path` (`path`(255)),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%menu`
--

LOCK TABLES `%%PREFIX%%menu` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%menu` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%menu` VALUES (1,'','Menu_Item_Root','root','','','','',1,0,0,0,0,0,'0000-00-00 00:00:00',0,0,'',0,'',0,53,0,'*',0),(2,'menu','com_banners','Banners','','Banners','index.php?option=com_banners','component',0,1,1,4,0,0,'0000-00-00 00:00:00',0,0,'class:banners',0,'',1,10,0,'*',1),(3,'menu','com_banners','Banners','','Banners/Banners','index.php?option=com_banners','component',0,2,2,4,0,0,'0000-00-00 00:00:00',0,0,'class:banners',0,'',2,3,0,'*',1),(4,'menu','com_banners_categories','Categories','','Banners/Categories','index.php?option=com_categories&extension=com_banners','component',0,2,2,6,0,0,'0000-00-00 00:00:00',0,0,'class:banners-cat',0,'',4,5,0,'*',1),(5,'menu','com_banners_clients','Clients','','Banners/Clients','index.php?option=com_banners&view=clients','component',0,2,2,4,0,0,'0000-00-00 00:00:00',0,0,'class:banners-clients',0,'',6,7,0,'*',1),(6,'menu','com_banners_tracks','Tracks','','Banners/Tracks','index.php?option=com_banners&view=tracks','component',0,2,2,4,0,0,'0000-00-00 00:00:00',0,0,'class:banners-tracks',0,'',8,9,0,'*',1),(7,'menu','com_contact','Contacts','','Contacts','index.php?option=com_contact','component',0,1,1,8,0,0,'0000-00-00 00:00:00',0,0,'class:contact',0,'',11,16,0,'*',1),(8,'menu','com_contact','Contacts','','Contacts/Contacts','index.php?option=com_contact','component',0,7,2,8,0,0,'0000-00-00 00:00:00',0,0,'class:contact',0,'',12,13,0,'*',1),(9,'menu','com_contact_categories','Categories','','Contacts/Categories','index.php?option=com_categories&extension=com_contact','component',0,7,2,6,0,0,'0000-00-00 00:00:00',0,0,'class:contact-cat',0,'',14,15,0,'*',1),(10,'menu','com_messages','Messaging','','Messaging','index.php?option=com_messages','component',0,1,1,15,0,0,'0000-00-00 00:00:00',0,0,'class:messages',0,'',17,22,0,'*',1),(11,'menu','com_messages_add','New Private Message','','Messaging/New Private Message','index.php?option=com_messages&task=message.add','component',0,10,2,15,0,0,'0000-00-00 00:00:00',0,0,'class:messages-add',0,'',18,19,0,'*',1),(12,'menu','com_messages_read','Read Private Message','','Messaging/Read Private Message','index.php?option=com_messages','component',0,10,2,15,0,0,'0000-00-00 00:00:00',0,0,'class:messages-read',0,'',20,21,0,'*',1),(13,'menu','com_newsfeeds','News Feeds','','News Feeds','index.php?option=com_newsfeeds','component',0,1,1,17,0,0,'0000-00-00 00:00:00',0,0,'class:newsfeeds',0,'',23,28,0,'*',1),(14,'menu','com_newsfeeds_feeds','Feeds','','News Feeds/Feeds','index.php?option=com_newsfeeds','component',0,13,2,17,0,0,'0000-00-00 00:00:00',0,0,'class:newsfeeds',0,'',24,25,0,'*',1),(15,'menu','com_newsfeeds_categories','Categories','','News Feeds/Categories','index.php?option=com_categories&extension=com_newsfeeds','component',0,13,2,6,0,0,'0000-00-00 00:00:00',0,0,'class:newsfeeds-cat',0,'',26,27,0,'*',1),(16,'menu','com_redirect','Redirect','','Redirect','index.php?option=com_redirect','component',0,1,1,24,0,0,'0000-00-00 00:00:00',0,0,'class:redirect',0,'',37,38,0,'*',1),(17,'menu','com_search','Search','','Search','index.php?option=com_search','component',0,1,1,19,0,0,'0000-00-00 00:00:00',0,0,'class:search',0,'',29,30,0,'*',1),(18,'menu','com_weblinks','Weblinks','','Weblinks','index.php?option=com_weblinks','component',0,1,1,21,0,0,'0000-00-00 00:00:00',0,0,'class:weblinks',0,'',31,36,0,'*',1),(19,'menu','com_weblinks_links','Links','','Weblinks/Links','index.php?option=com_weblinks','component',0,18,2,21,0,0,'0000-00-00 00:00:00',0,0,'class:weblinks',0,'',32,33,0,'*',1),(20,'menu','com_weblinks_categories','Categories','','Weblinks/Categories','index.php?option=com_categories&extension=com_weblinks','component',0,18,2,6,0,0,'0000-00-00 00:00:00',0,0,'class:weblinks-cat',0,'',34,35,0,'*',1),(21,'menu','com_finder','Smart Search','','Smart Search','index.php?option=com_finder','component',0,1,1,27,0,0,'0000-00-00 00:00:00',0,0,'class:finder',0,'',41,42,0,'*',1),(22,'menu','com_joomlaupdate','Joomla! Update','','Joomla! Update','index.php?option=com_joomlaupdate','component',0,1,1,28,0,0,'0000-00-00 00:00:00',0,0,'class:joomlaupdate',0,'',41,42,0,'*',1),(101,'mainmenu','Home','home','','home','index.php?option=com_content&view=featured','component',1,1,1,22,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"featured_categories\":[\"\"],\"num_leading_articles\":\"1\",\"num_intro_articles\":\"3\",\"num_columns\":\"3\",\"num_links\":\"0\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"order_date\":\"\",\"multi_column_order\":\"1\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_noauth\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',39,40,0,'*',0),(102,'hidden','Stream view','stream','','stream','index.php?option=com_stream&view=company','component',1,1,1,10003,0,0,'0000-00-00 00:00:00',0,2,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',41,42,0,'*',0),(103,'mainmenu','Company-wide Stream','real-home','','real-home','index.php?option=com_stream&view=company','component',1,1,1,10003,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',43,44,1,'*',0),(104,'mainmenu','Groups','groups','','groups','index.php?option=com_stream&view=groups','component',1,1,1,10003,0,0,'0000-00-00 00:00:00',0,2,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',45,46,0,'*',0),(105,'main','COM_AKEEBA','comakeeba','','comakeeba','index.php?option=com_akeeba','component',0,1,1,10008,0,0,'0000-00-00 00:00:00',0,1,'components/com_akeeba/assets/images/akeeba-16.png',0,'',47,48,0,'',1),(106,'footer','Terms','terms','','terms','index.php?option=com_content&view=article&id=1','component',1,1,1,22,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',49,50,0,'*',0),(107,'footer','Privacy policy','privacy-policy','','privacy-policy','index.php?option=com_content&view=article&id=2','component',1,1,1,22,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',51,52,0,'*',0);
/*!40000 ALTER TABLE `%%PREFIX%%menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%menu_types`
--

DROP TABLE IF EXISTS `%%PREFIX%%menu_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL,
  `title` varchar(48) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_menutype` (`menutype`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%menu_types`
--

LOCK TABLES `%%PREFIX%%menu_types` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%menu_types` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%menu_types` VALUES (1,'mainmenu','Main Menu','The main menu for the site'),(2,'hidden','Hidden',''),(3,'footer','Footer','');
/*!40000 ALTER TABLE `%%PREFIX%%menu_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%messages`
--

DROP TABLE IF EXISTS `%%PREFIX%%messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id_from` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id_to` int(10) unsigned NOT NULL DEFAULT '0',
  `folder_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `useridto_state` (`user_id_to`,`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%messages`
--

LOCK TABLES `%%PREFIX%%messages` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%messages_cfg`
--

DROP TABLE IF EXISTS `%%PREFIX%%messages_cfg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%messages_cfg` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cfg_name` varchar(100) NOT NULL DEFAULT '',
  `cfg_value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `idx_user_var_name` (`user_id`,`cfg_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%messages_cfg`
--

LOCK TABLES `%%PREFIX%%messages_cfg` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%messages_cfg` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%messages_cfg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%modules`
--

DROP TABLE IF EXISTS `%%PREFIX%%modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(50) NOT NULL DEFAULT '',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%modules`
--

LOCK TABLES `%%PREFIX%%modules` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%modules` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%modules` VALUES (1,'Main Menu','','',1,'position-7',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"mainmenu\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"_menu\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),(2,'Login','','',1,'login',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_login',1,1,'',1,'*'),(3,'Popular Articles','','',3,'cpanel',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_popular',3,1,'{\"count\":\"5\",\"catid\":\"\",\"user_id\":\"0\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"automatic_title\":\"1\"}',1,'*'),(4,'Recently Added Articles','','',4,'cpanel',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_latest',3,1,'{\"count\":\"5\",\"ordering\":\"c_dsc\",\"catid\":\"\",\"user_id\":\"0\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"automatic_title\":\"1\"}',1,'*'),(8,'Toolbar','','',1,'toolbar',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_toolbar',3,1,'',1,'*'),(9,'Quick Icons','','',1,'icon',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_quickicon',3,1,'',1,'*'),(10,'Logged-in Users','','',2,'cpanel',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_logged',3,1,'{\"count\":\"5\",\"name\":\"1\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"automatic_title\":\"1\"}',1,'*'),(12,'Admin Menu','','',1,'menu',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',3,1,'{\"layout\":\"\",\"moduleclass_sfx\":\"\",\"shownew\":\"1\",\"showhelp\":\"1\",\"cache\":\"0\"}',1,'*'),(13,'Admin Submenu','','',1,'submenu',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_submenu',3,1,'',1,'*'),(14,'User Status','','',2,'status',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_status',3,1,'',1,'*'),(15,'Title','','',1,'title',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_title',3,1,'',1,'*'),(16,'Login','','',1,'login',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_login',1,1,'{\"pretext\":\"\",\"posttext\":\"\",\"login\":\"\",\"logout\":\"\",\"greeting\":\"1\",\"name\":\"0\",\"usesecure\":\"0\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),(17,'Breadcrumbs','','',1,'breadcrumbs',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_breadcrumbs',1,0,'{\"showHere\":\"0\",\"showHome\":\"1\",\"homeText\":\"Home\",\"showLast\":\"1\",\"separator\":\"\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),(79,'Multilanguage status','','',1,'status',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_multilangstatus',3,1,'{\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',1,'*'),(80,'mod_navigator','','',1,'left',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_navigator',1,0,'{\"serverinfo\":\"0\",\"siteinfo\":\"0\",\"counter\":\"0\",\"increase\":\"0\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),(81,'Hidden menu','','',0,'atomic-sidebar',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"hidden\",\"startLevel\":\"1\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),(82,'Resync group','','<div class=\"alert-message block-message danger alert-empty-stream\">\r\n<p>Due to some changes in the code, you might have to leave and rejoin your group.</p>\r\n</div>',0,'component_top',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_custom',1,0,'{\"prepare_content\":\"1\",\"backgroundimage\":\"\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),(83,'Akeeba Backup Notification Module','','',97,'icon',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_akadmin',1,1,'',1,'*'),(84,'mod_janalytics','','',1,'analytics',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_janalytics',1,0,'{\"gaid\":\"UA-670908-19\",\"tracking\":\"single\",\"domain\":\"offiria.jomsocial.com\",\"anonymizeip\":\"off\"}',0,'*'),(85,'Footer','','',0,'footer',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,0,'{\"menutype\":\"footer\",\"startLevel\":\"1\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),(86,'Joomla Version','','',1,'footer',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_version',3,1,'{\"format\":\"short\",\"product\":\"1\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',1,'*');
/*!40000 ALTER TABLE `%%PREFIX%%modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%modules_menu`
--

DROP TABLE IF EXISTS `%%PREFIX%%modules_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%modules_menu`
--

LOCK TABLES `%%PREFIX%%modules_menu` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%modules_menu` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%modules_menu` VALUES (1,0),(2,0),(3,0),(4,0),(6,0),(7,0),(8,0),(9,0),(10,0),(12,0),(13,0),(14,0),(15,0),(16,0),(17,0),(79,0),(80,0),(81,0),(82,0),(83,0),(85,0),(86,0);
/*!40000 ALTER TABLE `%%PREFIX%%modules_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%newsfeeds`
--

DROP TABLE IF EXISTS `%%PREFIX%%newsfeeds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%newsfeeds` (
  `catid` int(11) NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `link` varchar(200) NOT NULL DEFAULT '',
  `filename` varchar(200) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `numarticles` int(10) unsigned NOT NULL DEFAULT '1',
  `cache_time` int(10) unsigned NOT NULL DEFAULT '3600',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rtl` tinyint(4) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%newsfeeds`
--

LOCK TABLES `%%PREFIX%%newsfeeds` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%newsfeeds` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%newsfeeds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%oauth_nonces`
--

DROP TABLE IF EXISTS `%%PREFIX%%oauth_nonces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%oauth_nonces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nonce` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%oauth_nonces`
--

LOCK TABLES `%%PREFIX%%oauth_nonces` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%oauth_nonces` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%oauth_nonces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%oauth_tokens`
--

DROP TABLE IF EXISTS `%%PREFIX%%oauth_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%oauth_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `client_id` varchar(20) NOT NULL,
  `client_secret` varchar(20) NOT NULL,
  `redirect_uri` varchar(200) NOT NULL,
  `code` varchar(40) NOT NULL,
  `expires` int(11) NOT NULL,
  `scope` varchar(250) NOT NULL,
  `oauth_token` varchar(40) NOT NULL,
  `authorized` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%oauth_tokens`
--

LOCK TABLES `%%PREFIX%%oauth_tokens` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%oauth_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%oauth_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%overrider`
--

DROP TABLE IF EXISTS `%%PREFIX%%overrider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%overrider` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `constant` varchar(255) NOT NULL,
  `string` text NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%overrider`
--

LOCK TABLES `%%PREFIX%%overrider` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%overrider` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%overrider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%profile_users`
--

DROP TABLE IF EXISTS `%%PREFIX%%profile_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%profile_users` (
  `userid` int(11) unsigned NOT NULL,
  `skills` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%profile_users`
--

LOCK TABLES `%%PREFIX%%profile_users` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%profile_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%profile_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%redirect_links`
--

DROP TABLE IF EXISTS `%%PREFIX%%redirect_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%redirect_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_url` varchar(255) NOT NULL,
  `new_url` varchar(255) NOT NULL,
  `referer` varchar(150) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_link_old` (`old_url`),
  KEY `idx_link_modifed` (`modified_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%redirect_links`
--

LOCK TABLES `%%PREFIX%%redirect_links` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%redirect_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%redirect_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%schemas`
--

DROP TABLE IF EXISTS `%%PREFIX%%schemas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%schemas` (
  `extension_id` int(11) NOT NULL,
  `version_id` varchar(20) NOT NULL,
  PRIMARY KEY (`extension_id`,`version_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%schemas`
--

LOCK TABLES `%%PREFIX%%schemas` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%schemas` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%schemas` VALUES (700,'2.5.4-2012-03-19');
/*!40000 ALTER TABLE `%%PREFIX%%schemas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%session`
--

DROP TABLE IF EXISTS `%%PREFIX%%session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%session` (
  `session_id` varchar(200) NOT NULL DEFAULT '',
  `client_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `guest` tinyint(4) unsigned DEFAULT '1',
  `time` varchar(14) DEFAULT '',
  `data` mediumtext,
  `userid` int(11) DEFAULT '0',
  `username` varchar(150) DEFAULT '',
  `usertype` varchar(50) DEFAULT '',
  PRIMARY KEY (`session_id`),
  KEY `whosonline` (`guest`,`usertype`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%session`
--

LOCK TABLES `%%PREFIX%%session` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%session` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%session` VALUES ('eojjce8ea2mq7ceqdsogesbin2',0,0,'1335419431','HmmGd9fSS0bFxZ4HsKMpchzSQncQXrxYBAEXlTk_VUXTD0Ixwsg9WBFDMah_xM4ct2B6cKsDnGuz1QI1dOORxt9E1tAtfN9-dGa5LIwcSl4lu_cY4RogafjBIJUexBLBp4Hl2ZJVJVbO7PdzENKvSG5CKeQfWxeNXlzq9EC2HpXK67eBNtuE1WBHnDL2kloKvFKgBe0cQCn3cIDJlktYckHO5ZzVK0heSN9ZgHClbP1FZSdQG-rrmzcTXw8lXSsMPLQ05EA0Z21WQr620r_A7qOYeGHmwMIdxwj7oOVrDJRPDwlWIS2KBRAk_rqHCvDSauYct6RDZC7oj8u7wwXV8KpWaZY-XdAVI3czkKhoQdPBsNAmz2A0eS5xFarLrD_zepZZAM42hk7-s4U47pPRlNUIFu9lTGVLqiVHc7ArOk-kDzdV0rvMO_ALp7Z6UwMxiwvvaBOuYl7y-y1VqnzxY_eeffOmVWGJg3GsTfeYpHTBL7x0VILp_A6Joi5xdbPReZZyoWnacM5TYbh7C-VD7zCgtNu_uhi8SJL3COcN7RjhU8oJ-fKC_gZXYGbo2__K7UdAdD3n-wlXFwC90bZW2I1IdfyQYX-lvQ4UIfqOJGBsMQLh1U7y7o7S8d1oYyqvNn-BnA4LjRbnet4OgnvhT5EDquupzXLGR4sBJtHL_OAV0BjStTz0M2Y_YNUBMOnqeVGGGefUHTfYiRrkboH8xZ1Y-K2j1SunkzzLg9fUWqqyJLEMEkuBPPq_xUgNzQOV6rG-dsER4DZftl_OMKnbX8_neZ6g44am30YgJXUovkA9k7d1FtMp9_SCJqMFzwsCnkGZpeAThpDU6Ui_KAGSeQ1wu2CVigqqpTsMWAbULEpKRR9lFxvL9WNGkthD4xPjgK07iEyRSgrHLMJ_LyWbZAuYvo-aBvlFFbbDSW8J17HYhJ2iffuGel0wTKdnVhRzE1Ww8zC6Sd0La6jUikXrNMqVaDsChwjLYji4wuWUYtX4ANBv8pHZ5kOoNeiHTTPMM3lPTIxMFNrTasyI7ab7mY4isq-Bv1RKzvULZ3ARIYUlJJT3PqyV6f3nU_FPTQsw74KHrg1i58H4BXXabrXEQAu7n8jKDXCCnZpQVnBmXQKWyQFA5CVH7nIC897AzP0ueHSfOxbTTHWlZb2WijYQKXmVWhwZKql6D_-U5XTlKSXyc7_RWN7T7jDaEUHn011nuVTn_VWxngHVfOvwxXBgkEcFVdcLq4-O1AqdYpnvPv5u6pWkb8Zdu7vw4FWnKWsF05EFeLPlNAKa1RZ-sHUFbwpSrVqee14_o2eZnr-riw8Mfv3f0F_D0ZFObaYNyHWh0XhYNPcar-BgnfeVq-eD2YploCMXXXlUfLoL7cfmsxeC7wfyoaVtqAizC-AjBTnt2ZuhEQU2s0hRph2ydIwRLugwTAglvaHiNs_reYAU5OzO-Z6Y_nndq9W92YnPO8Ez8A9d-R93eB4AMIYTs3e8K3IDgtxLWAH7gm4spim7ZxIxQqtDHcSQr9e4M6QecuUtoG-ML-UV7x9IrfbWia0siN55wbq29ldwplXhNyy20PYE1OZhgqpV2CZ6w2kRZqYoX9eJnXaTNO5oRdbZ5dxYUNu5WqP_1XBr0RaVXPGqkCGinHkCBGWTzlwngG2Am89HGHK3eagZV3dGX-_r2eVK8eCp_mvG96kbfomgOF86MvhHIckQlygkWAvRFaTQYyTDV0nz3K6rK1DKFeZswL9FLd7UvWkMGBGcpvknexI96hl8PzLY9FstLIkouIlVxysmfL01pR6SHLLo6Bu9zCS6mD8X79yq-mfxzwg4RVR2jJcPs-5n_ebcWIjnJn09Ar9JnRzDKyAg-WwsCAG3rFAwvR0dA3izeAfaPr2Ey9Xe8yCw62lttyciIqmFigZAXBabMgkwHGVp5JngMpwrWIfnHcZKN7U9xxrBT0iCLin7UXzbO9aA6Fa_OGbuvKa0WAbwBCfioSmGKg0Kr1IG4jpJ-yvoengHUTHEadCXm6PyfLEnXewYifWqw4lRzMU1drX4KdJEq4BvALcOZnJXtPcGlkw4ZzNYtobI0YJuiU5HCOvq7JKfvGvT97iMI5fDixOiN_ieljL6EeMToDhTtXFS3tsAJmYD20VBADLv9D13N53gbvfaiuIbn3kEp0TzG2-5ofiIe9n-Bh_WRbjOzIdR22lA97Qy0pCIYXLoEhmQgnaiO4O1F69ReaboSinwUCW11PdgLVshTxsfkx-WItms1QmNSRfCQdHvXcK5I1YVakytYdzDmu84-JqTIPESxIBfwjo5g1veCJC_RUxOGSWITA9O3d1yJ181OlLN-Lp9Uf-YC7-wYDRDegJRzYTxi5-01Qar4oR0_IEfHjK8XCym8w2FsR9UHbAmYceC4ecs2WE79JaifuCzjtKHYW6rNFlmDe3o_iMjb4NFK8r9xLMARC-5y5jrUiGliYWqjEST1pFkO4Py-iX-msOVxIzfpm6UOjsedw5uggTAnxecR3XLchQhKRnMOW2uMerueUGc3p2if-AEMpsGg-wJEnFKtXYjKWYnzPQuBQPPxgTpT2m44lcrxcXNMrYrqgxJi_H3BZOUr3puh2RcQxKM6uW_wd52VZNxgvtn_TYyFkcVBZES4jy70evx0OHN5-Izr65mh5WeMWjw7cRZgFHtuaq39zpWY9-hoiRnjhYKXr-nrRxJ3Nn40PE1_mubKW8087VPQp45xWEuoQlLZQ9FcJwainY7mT9nyWVX8KqEWBoWQoc_3QCzMNRu9jhD4Mcfqs4bL1N4wsXfwaawtyw477njr0kZK51DCREp1HmE0kBFOcpjn4nRvsNef9vVHBYZMP3l5gpbBu_wlh8Lt79-3hXbw9HrdntLx_0iIBr_7rQaPbpm3CdpSLMWw8IMFqokLRdug04S-jerv-scDOsnRylrS3k0yw1StXUrm5zrnkQw4RJ9XZ7mT_0XwB94W78LH2qmWj-C64BBMqO8_rxFA9gTS6ZMJ7Z7rhXtL4XUWq3EX3YfGFFtZpxPnhUiSbn6amzTk93YCk49Us-oC4kTnc0IeImHs-R00c958xBNeYuqM-ZtkKwyZBHUTz3bFJ38_DoSTB4Rkfdf9Gscgd048RaOxZPYDIsOvd6L7sj6gw47WqTvCugId2EMMuj5hqrjuqHqTQIcp5tVYlINbXnVOwQnj-exBj-IkzWq2GNusUB_B4UmNI0ap0aptc11qjhqprQ9h64B7XQJd0jZd1fBTis8xZEAyliz9WyEMYM2eEAQgbbH6dbRGZs4JXyc0L4hAKiZUifN21aqL8xA_vJdIObATOaEOA0L5OrlOc8iksZAXg8V-fUQ7rlyhsSZj3SsadVaRUMBFt3VqZlLe_c1qgKdsOMdDDRXChdHwSzy790EwsnADwonE2-mc2CtjmN92fHDqurWsmPdC3RriKitrYqVP7mA7diLyYJ2P3wZvzZNqD654Ka3v0cJFKExoJjEz_6ztLTHdWVuzDGwq6ubsoEXYsArdMoyMAgGFopABA3qMstjqDmt8XcGyI3C97XcgCArXV6IQtsIkBWa2nmlXpz6fbCTy0aT_aSLLInMLCxBkNtqcDYRJdoj7Rwdlw7a1YT9Wov0ch5hd_OYPqmUa4NCB9ow1Aoj9tMxCexPp2zgjZW0Ns_qn9ANaTVPvVf40hDFbM212Avp2-v7sxz08gTJm8WwsxtLThAUlPokuoLgZy6h50Q4ULZsTrSJgyNQVtOJ4m26VqWn2dJoIQdlxuE0YELO6fen7ZjcgAPb3iDEjUIG3FQ2VxJml2Yrj3PzxgoLuxyCpVNcp0ZsQwPvggPfwkoTlV4-W-rheKZsqXKr7liVc8J8fxv4r_XkBZC_YmuOT-LeXRhK0ngVLoeOQ2KCJk1PDxGTObS1BCFXHTObgYln3VckaaxPpsMPgx5n99VDhwdY61nxd5_uo5CLtJHDxulCc2leH_1xtMT5zFWzGePuGIIIOvWT6djCCD4q5szqopLagIZh1-QUHka3C5LQ0KxKNNtaBTfoPJm4umXzkA9szZ85fzK4qeJXImWvk0HrFxMaz-oYp5QMfFytV_X2y8aeFPmlL-pG9JbAO_NZhbVYJ7xbEz0liyKIyydEcQSWQhnkk_ekItaoPfEbKNixCjNXmOWKCxbn66yCNJ2xEcfaEx0uP1nwf3RmXsaKtCOYzTWdviT2ch6krQ-hhnvVrlI-Hpu77PWZ_Wp762cOirU8sscxYkUEnnpLdOuzEbWuTJs389zWI_kz_iNL9IBQ6MV6ozq130zGINN2g_P3jGb2WakUrK8CpOmRenAFfzzrTPCV0nKP77vUcYPUA37Ayp3cfqErAtadgF9VdYaRxIHRvc2DPWCF630qlt4FAdct1AedELJldxzGBohJ7xp9PNAEy9u_wfQbyEp1JT9p5Ly8BsA3wywtE9Y-jv_Y_abbuLaDweL5mUVQbnWmeFh0JYcfea7tz9qKeX2xRc4XHNv_WRSZxK7jm67_BzRDELV70qeGWnPEAPFd97E6P86Tbo97Nkrzbe9a5Oy3sjun986k_x6X8FubkrH4iL2SGPlaiFR4159ZGtv0V9yHUeNH9mwTufECTYDlX9oa77_7b0hvcDHx2oXUhE4zYbfAe7jKPW0XFeEMcG308VVq9LzywMtALB0pXU18U-LC9FZjXxDarhAwQctlcuPRAa0rgdSuBZ8DPzoH9TOtCf2QEG79oDQmSeHRQyLVMuVJ3jibHip9ZZ2oiWTmBFce2HOxeKGbM0L_6_FkEPTMikfLCsQC4UN2-E9as0_lnF684UNG2UDblGOFaogifA4NcThwEC8MN4XRTK4dwAmhlJOFC4IUwdJUnA6NlLHXoPtJAzETGW2CaZ5p1PKCNhFXPEkK3OBg_5daUHnNH-7NUudOXOjjCdFeTpwsdPwuiesMU0LkYvLdQh0kbDgwDMwf__g37yHS8uCxdU3b6h0WRbXg1a0WbZydrOLmwqHspPMgCqSG7wu-9Kk19AMnfM8aM-xjuAC6MPs3Lvr_QMPcZbXdsm4hilO9yTX7ikhzbdB4tOI_aNrb08Zi5g2fB-3Zaak3wg95bUljRlQY0S5ZoCaPANN-YPa8mJwXvxplMNiSgcSNwbdLbCycT-dLFgHlLmpXZ5_n96Wn_QG0c9sXpVJPg3UkCkMYf29ODI52fB_E-RC8kAb6vwdVT694rPuTaLcaJaiWth9iH6R7mLeLqfb_iUMefXffDUmkdZz_iJ9gK3wJCUqllOeH10Rdwls3XDHBiSYqVDTO_Xj4fIr-rSfMblYebrvHBTz-Ikm1SSsbnzliJJnq8zOzz8V7o75xG2HEgUjNyP4PSY-y-OWTtG0lpHx0NJbb6dnWPPSi4JVsqQFAoTzBocW80PBNj56hdpwZTM1VeZlD2tdBVTS6Kz0MwF9XPPBW5FNXTrNqSgbl2C1TYdPu644FdMO4Ct2BHi9EXRDEmRavU4NGQf0UF1xJSNJ6zpYmJZbyWTvHL0VQ0U00lMqvdcGFj3XHygGMwAvOBBnfgCwrf69Dl5ozVQ1iNkyGmFPW8yZSVY9_Ug45X3MkhGZQs8yNsmVMUbMDRJ31zFtZf6CTPHOS1lRvCKBJK23LHPl_s12qUVTEFGLpfS_LsBRB4kutnNn-qt93mYxWudHXbrJiITwAn9xYYbS6xbEBm-Ihsoiu9WehP8VftEMBSNRRuKUjn4hY2oEze1_wknk28iGVpY-HWsd71GVNQWF4eCF6nFTax82kCT11q-fwID8g9kZZjv-FaUook49oZD074BkEu-hB1gBXUENMdrlomtK7vqa-XjCIMQKWY71pqrSnJj91dGlVGqPr2h7AFyIspoaWUxSiwFkLYmaIhaSJ2NRJEavLzmsa5sv9XBiJbLOzM7BIpXiC4QG0mg4b_jCryJXCpvGmre58laEgalfOkcgdHzT2p1qd7J7A1C-j6jLtG5uWfkKV8GPY2tc1GL_2QCPbTzOY_kBOSsXvJvYJbQ-rMRTGnRnUR_L8dsWxJEt0fzuw5VAZWJ7Xclq2aAS_kyI4telDgnYNzJVmCSbsPIj0k54-MZ44ow8wmQHpdIVVF7dqLbubnaE-9hV8bmREsRuoftym5U4ksYEOdlMSLIqxmt7q0uGoNMT9zQBObWmeO7kd4MdEAj8KSPLL4vYkVikMGmjpYFOEj-nojb6mReb_0d7eIVXS7APoWLYvAkuzHXrXxTy2V-nh-4f_ms28iDDre-_BU5owoVlgGpqixQQx7l3yuekZ_9clyrr82KqNd5YLJker_BYrA-IoSpkm1R1qbZApBy1Kizw_QzXWEAa0ZhbRknL874upm9qNOtRxNka405xAc6tZVQJeaQhHOPMca9JB1fFQl3E_KL9UfCR2zhZTvoDrZoOghEEQsxGUcEhQfl_cBN8OCFH75dfxUNa0u1Yjcdsn3R0vuW6zdXUK1FIxc5cmgxBQVTrOUH8ctZF3HaMyuo77kIBzG9A9qg8aOStaNWxI6S6smpb_rro8MYeH3ESapWZVzn5ZewU2oE19LR8WUy4Nfyu3k_d0gqAZvQteLMDu6Eh00wpK9dsU1uulDIxfRTCzdpXD2FfNOZmLtA28lOFmlWCqjIgUVfo_cSBLqQUIW67qvOujZtMLzMPQIPFCO65qCbOPeZUhTMfbBGID9C0PtdOi2BIRizisziZqKekD0v3Ye25FZEf9CmOWJPqJja5-yaQL3J9WJDTOB_ArsDW_fHr4Y51F7TiHTwBmOm_aKvfuErLYTli1MNzFbZfelR466cfReoOxXId_Fb0A0fHDx1ooAcW8CMO4-erB6UM-jRGq4NNM2BoSXk1JC9O8BwPGXWOY5ThgWlnS69UH7L7UsXMNurjVm5hB4NACMRPtysym1DjJ_8EkqqpLeHJ9IaEMkcJz9ExrS6zULIumOBtdBASTXEL8Alsn6BnVPexEnOxp_ADtlFkImjaD8w22gNnKYKxwttDztJ837JrN4WUDUKK4X-IlBZELWMYdFaeicC0INPCXoPhnpi2Yq_T2vy6BgMMNUsjHACArrWxCvloJ0nEp_rsaEXfySt0WZFWDC5bQMFFsZiQQzmcnWa7ONXCLygoQfSOjX384874UlxUleLCrGc1GGB8STGlr4vz1mMu_KzW0QM75-IgxfidC5Nf6SXBv3pr52Vend0saS5GcNh__XPGWyRJlVQ1MBNkHahoLlDW-dTUuOr53tKQ0ajBMzO1oy33RWrleEz0hwF0SVhdWfNc5z5JO3BasJVfxirw17L-WPMFOn-naANNSuF4OV-wH2r31Y0zAqZ9sM9IMmdE0h1CC-o05O22ED626YV23oAVrml-HKI8bPSb1-b3er-DKem7CYR2caItpmNBNQwJfgXiwGW8WOQ9cD-EMwneZFKDFUocO3KIUFNNk8TbDsHZZAbTShx6ZAjRcSPAy8NRZzdR977Y4XqNtffJ-w0Y0dVU3B_iPCidQcwH5CZ3-5fbbHkyRg6Gcy_LCSIxCzDi1dMkz5_QoHSQK29VtU-IPojuzRvsSt5zOyPzyFdDUdX0hr_acg3rEuGUKvSHepNXbHnO69DXK8m2z_JB770t1rje7fof2-361lfG6s3poAjTTniftJ0kTV0r9-8MthO-g8wdMLj4Lo0-8x2Fo9GMaX4WQ-6J8p4kSwQrHvzn340qdTQNqh-YXh2eu9GUPfBwRjxpw9wEyaiL_MMOd5hHSfToO3eu2S6_EFPljRsdjoJr_n6ScXBFWgCMdhX--Nvkbc1qlF56RriTIApeEiO2wF6U3rqHkqzov0Z2CulgTrtfnMp6xT4gKkcaXbLnyZNri0wcIz-BcC-uNXyM5LP2jaHsGbUB2unR-knQUNJpxagGb0mXltMQ-vCRg9FcXfvF4_eqmJqpIo_E18bIB86jOLCclirtvB8O7p1l_YWk6ye8d-nfHj1xN71ZobnPU_fTPlyxH-ML-MfPKDwOG5EYjDLEiWGKCu88G0mtQPjW3ic7wBSBU8t-JUdyHi1H0Vii8BacnYv-By5efkxa-uCex3ASyuNRHcnUHxevehRC2PNTuPjscEOKvkf8euJpzuH0yS9Ve_GfklxnQUtcl0CIBvd0Ru-_CGQDaiWgHsMuu4wpU9nmu-NIigQrw7d84AfOsISIfQh_uPzhlM9bm8cN7IjDshPQ4d05Vz-Vhdg3VF1HuocCu8yNsIDEPGfHIUvdmzyjHvrG3yru0wK9Xx-gLGuS5ovyvVOWfOSBI5C1K7WaRmE8BXuu17QWcuMBkZyfSMsjP8ugRF7LrZFG9VGZRMSQCLfw6Rii7qWJ284VN2bnnBPFdhxKOQE7aHeR2ZLeKa-dhCEi80Eodnh0Mx367LQowvF5b4Lqc80TGa58un93l3_5gUbiFFlwbKrlETMsQSDZBpFk8-5mTGhHnD7roxMhIGztrAnJqJj1BMk1rJjU2NbzPes-wSs0BcqutXxEhcAOhIt7QFDodgvbSuAkdnkg010LuJQxDaOGRqqXP46XTMfQEeg3dadGQhxGR9RsCn0jKlnA7bONXZYf2FZzSpCUiHpQvR_VZgCDhImNwOXjWEwJnoebbuLddlk5_RD_1l0yBxRaojJK77bM-0kg13cso0e82psNA9-OWxYZY16q_e7j67XQv4d3Z_9Glp3SaLJYsKtU6fovEbeWYzVB3bfb0RFzM8U5J7bdeHmVnncMmCn32NL33nUG6mQMIx5r1EQ4JS6izjDhmyZGUlzCBADm56PYEx7uyQtk_0qvdVI1RY-BYdMY5IEfbctsA-kzKZNrzuLAR-T2lnug8QZH82A0KnnHTKqD2vdU5AsPTvyy6vk_CX43eZ5W06DxamSTWTGybL44RLlnczHj3L3iGEjzEJD0YJZQdjD5xeNvVBlfBFL1SQQG1-NfQB51EMu23UyBqmHMXErN0bSiKe1_dUuZVtc5lPzqZ3EdKp_BPCrLm8oglv_ympDVToqWUOWsscjmHLC8zIru_rGVtBRDuVDVnEQ59diJlc8BZBt2aPn6jrzb6CNKMTwPVA5cvDC-TuZainWAoeGuVt-JBAG5L0q4TXvbBMoeM66Z2E-ZEk585zgpk7o5OYv1WdPT4tmh3qAIMXbMDCIvlCyaJpbS-OtB3UtHnixrcmt5wo7OkytIN93hNuMDJRIvO_9Ys5SsXVpFZqGFwC8olyVDe6SezdyGLUNvjMhrwIaNNS8z1v4PXt9oU5yX30liz0TtTs5m-V_Q43_uqkMPvePKQaMIrwY9ZtPLXqXxSWIMTzgq7MwkpowByQh1OZQeTeh-N-vsfrG6jHM7HnGLE4wPJ_LvfywakwPK2Lk-bazwfrL117XRGIlA4rwXzE0_MK689R5-mKv2ixaXMBp7_c9YDlBIvJsah-9aVyvtUPAolkxfx0akXgGJKaJYYFt43e4ALELT_5sKhKtK_OOobVuN4ZHUGF1CZls0lzbEPbR78joxvSXux8BzeCSAVASJrGNLmP6QqsrC0WoxLiW1_pDe9p4BQ3ySyxsqReGNws7pJIzamy84WQY22BMrCga76K3aARWDbxjEotSU.',42,'admin','');
/*!40000 ALTER TABLE `%%PREFIX%%session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL,
  `access` tinyint(4) NOT NULL,
  `type` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `raw` text NOT NULL,
  `likes` text NOT NULL,
  `topics` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `source_id` tinyint(4) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `followers` text,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `type` (`type`),
  KEY `access` (`access`),
  KEY `user_id` (`user_id`),
  FULLTEXT KEY `message` (`message`,`raw`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream`
--

LOCK TABLES `%%PREFIX%%stream` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%stream` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream_category`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream_category`
--

LOCK TABLES `%%PREFIX%%stream_category` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%stream_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream_comments`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stream_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `likes` text NOT NULL,
  `comment` text NOT NULL,
  `raw` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream_comments`
--

LOCK TABLES `%%PREFIX%%stream_comments` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%stream_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream_customlist`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream_customlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream_customlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `filter` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream_customlist`
--

LOCK TABLES `%%PREFIX%%stream_customlist` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream_customlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%stream_customlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream_files`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stream_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `access` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `filename` varchar(1024) NOT NULL,
  `mimetype` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `path` varchar(1024) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `params` text NOT NULL,
  `followers` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream_files`
--

LOCK TABLES `%%PREFIX%%stream_files` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%stream_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream_hashtags`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream_hashtags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream_hashtags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hashtag` varchar(255) NOT NULL,
  `frequency` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hashtag` (`hashtag`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream_hashtags`
--

LOCK TABLES `%%PREFIX%%stream_hashtags` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream_hashtags` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%stream_hashtags` VALUES (1,'Getting Started',5,'2012-06-04 00:00:00');
/*!40000 ALTER TABLE `%%PREFIX%%stream_hashtags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream_links`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(4000) NOT NULL,
  `title` varchar(512) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `type` varchar(64) NOT NULL,
  `params` text NOT NULL,
  `user_ids` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream_links`
--

LOCK TABLES `%%PREFIX%%stream_links` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%stream_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream_slideshare`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream_slideshare`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream_slideshare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_ids` text NOT NULL,
  `source` varchar(255) NOT NULL,
  `slideshow_id` varchar(64) NOT NULL,
  `response` text NOT NULL,
  `params` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream_slideshare`
--

LOCK TABLES `%%PREFIX%%stream_slideshare` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream_slideshare` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%stream_slideshare` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream_tags_trend`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream_tags_trend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream_tags_trend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `frequency` int(11) NOT NULL,
  `occurrence_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream_tags_trend`
--

LOCK TABLES `%%PREFIX%%stream_tags_trend` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream_tags_trend` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%stream_tags_trend` VALUES (1,'Getting Started',0,3,'2012-06-04'),(2,'Getting Started',1,2,'2012-06-04');
/*!40000 ALTER TABLE `%%PREFIX%%stream_tags_trend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%stream_videos`
--

DROP TABLE IF EXISTS `%%PREFIX%%stream_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%stream_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_ids` text NOT NULL,
  `source` varchar(1024) NOT NULL,
  `video_id` varchar(255) NOT NULL,
  `type` varchar(64) NOT NULL,
  `thumb` varchar(512) NOT NULL,
  `title` varchar(512) NOT NULL,
  `description` text NOT NULL,
  `duration` varchar(64) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `source` (`source`(333))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%stream_videos`
--

LOCK TABLES `%%PREFIX%%stream_videos` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%stream_videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%stream_videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%template_styles`
--

DROP TABLE IF EXISTS `%%PREFIX%%template_styles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%template_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template` varchar(50) NOT NULL DEFAULT '',
  `client_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `home` char(7) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_template` (`template`),
  KEY `idx_home` (`home`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%template_styles`
--

LOCK TABLES `%%PREFIX%%template_styles` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%template_styles` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%template_styles` VALUES (2,'bluestork',1,'1','Bluestork - Default','{\"useRoundedCorners\":\"1\",\"showSiteName\":\"0\"}'),(3,'atomic',0,'0','Atomic - Default','{}'),(4,'beez_20',0,'0','Beez2 - Default','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"logo\":\"images\\/joomla_black.gif\",\"sitetitle\":\"Joomla!\",\"sitedescription\":\"Open Source Content Management\",\"navposition\":\"left\",\"templatecolor\":\"personal\",\"html5\":\"0\"}'),(5,'hathor',1,'0','Hathor - Default','{\"showSiteName\":\"0\",\"colourChoice\":\"\",\"boldText\":\"0\"}'),(6,'beez5',0,'0','Beez5 - Default','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"logo\":\"images\\/sampledata\\/fruitshop\\/fruits.gif\",\"sitetitle\":\"Joomla!\",\"sitedescription\":\"Open Source Content Management\",\"navposition\":\"left\",\"html5\":\"0\"}'),(7,'e20',0,'0','e20 - Default','{\"colorVariation\":\"white\"}'),(8,'offiria',0,'1','Offiria - Default','{\"colorVariation\":\"white\"}'),(9,'mobile',0,'0','Mobile - Default','{\"colorVariation\":\"white\"}');
/*!40000 ALTER TABLE `%%PREFIX%%template_styles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%update_categories`
--

DROP TABLE IF EXISTS `%%PREFIX%%update_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%update_categories` (
  `categoryid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT '',
  `description` text NOT NULL,
  `parent` int(11) DEFAULT '0',
  `updatesite` int(11) DEFAULT '0',
  PRIMARY KEY (`categoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Update Categories';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%update_categories`
--

LOCK TABLES `%%PREFIX%%update_categories` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%update_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%update_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%update_sites`
--

DROP TABLE IF EXISTS `%%PREFIX%%update_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%update_sites` (
  `update_site_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `location` text NOT NULL,
  `enabled` int(11) DEFAULT '0',
  `last_check_timestamp` bigint(20) DEFAULT '0',
  PRIMARY KEY (`update_site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Update Sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%update_sites`
--

LOCK TABLES `%%PREFIX%%update_sites` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%update_sites` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%update_sites` VALUES (1,'Joomla Core','collection','http://update.joomla.org/core/list.xml',1,1335167283),(2,'Joomla Extension Directory','collection','http://update.joomla.org/jed/list.xml',1,1335167283),(3,'jFinalizer Update Site','extension','http://update.farbfinal.de/jfinalizer.xml',1,1335167285),(4,'Akeeba Backup Core Updates','extension','http://nocdn.akeebabackup.com/updates/abcore.xml',1,1335167283);
/*!40000 ALTER TABLE `%%PREFIX%%update_sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%update_sites_extensions`
--

DROP TABLE IF EXISTS `%%PREFIX%%update_sites_extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%update_sites_extensions` (
  `update_site_id` int(11) NOT NULL DEFAULT '0',
  `extension_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`update_site_id`,`extension_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Links extensions to update sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%update_sites_extensions`
--

LOCK TABLES `%%PREFIX%%update_sites_extensions` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%update_sites_extensions` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%update_sites_extensions` VALUES (1,700),(2,700),(3,10006),(4,10008);
/*!40000 ALTER TABLE `%%PREFIX%%update_sites_extensions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%updates`
--

DROP TABLE IF EXISTS `%%PREFIX%%updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%updates` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `update_site_id` int(11) DEFAULT '0',
  `extension_id` int(11) DEFAULT '0',
  `categoryid` int(11) DEFAULT '0',
  `name` varchar(100) DEFAULT '',
  `description` text NOT NULL,
  `element` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `folder` varchar(20) DEFAULT '',
  `client_id` tinyint(3) DEFAULT '0',
  `version` varchar(10) DEFAULT '',
  `data` text NOT NULL,
  `detailsurl` text NOT NULL,
  `infourl` text NOT NULL,
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Available Updates';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%updates`
--

LOCK TABLES `%%PREFIX%%updates` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%updates` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%updates` VALUES (1,4,0,0,'akeebacore','Akeeba Backup Core','com_akeeba','component','',0,'3.4.3','','http://nocdn.akeebabackup.com/updates/abcore.xml','https://www.akeebabackup.com/download/akeeba-backup/akeeba-backup-3-4-3.html');
/*!40000 ALTER TABLE `%%PREFIX%%updates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%user_details`
--

DROP TABLE IF EXISTS `%%PREFIX%%user_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%user_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `field` varchar(35) CHARACTER SET utf8 NOT NULL,
  `value` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%user_details`
--

LOCK TABLES `%%PREFIX%%user_details` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%user_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%user_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%user_notes`
--

DROP TABLE IF EXISTS `%%PREFIX%%user_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%user_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(100) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(10) unsigned NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `review_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_category_id` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%user_notes`
--

LOCK TABLES `%%PREFIX%%user_notes` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%user_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%user_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%user_profiles`
--

DROP TABLE IF EXISTS `%%PREFIX%%user_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%user_profiles` (
  `user_id` int(11) NOT NULL,
  `profile_key` varchar(100) NOT NULL,
  `profile_value` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `idx_user_id_profile_key` (`user_id`,`profile_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Simple user profile storage table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%user_profiles`
--

LOCK TABLES `%%PREFIX%%user_profiles` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%user_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%user_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%user_usergroup_map`
--

DROP TABLE IF EXISTS `%%PREFIX%%user_usergroup_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%user_usergroup_map` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__users.id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__usergroups.id',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%user_usergroup_map`
--

LOCK TABLES `%%PREFIX%%user_usergroup_map` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%user_usergroup_map` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%user_usergroup_map` VALUES (42,7);
/*!40000 ALTER TABLE `%%PREFIX%%user_usergroup_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%usergroups`
--

DROP TABLE IF EXISTS `%%PREFIX%%usergroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%usergroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Adjacency List Reference Id',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_parent_title_lookup` (`parent_id`,`title`),
  KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`),
  KEY `idx_usergroup_nested_set_lookup` (`lft`,`rgt`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%usergroups`
--

LOCK TABLES `%%PREFIX%%usergroups` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%usergroups` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%usergroups` VALUES (1,0,1,20,'Public'),(2,1,6,17,'Registered'),(3,2,7,14,'Author'),(4,3,8,11,'Editor'),(5,4,9,10,'Publisher'),(6,1,2,5,'Manager'),(7,6,3,4,'Administrator'),(8,1,18,19,'Super Users');
/*!40000 ALTER TABLE `%%PREFIX%%usergroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%users`
--

DROP TABLE IF EXISTS `%%PREFIX%%users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `usertype` varchar(25) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(4) DEFAULT '0',
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usertype` (`usertype`),
  KEY `idx_name` (`name`),
  KEY `idx_block` (`block`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%users`
--

LOCK TABLES `%%PREFIX%%users` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%users` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%users_activity`
--

DROP TABLE IF EXISTS `%%PREFIX%%users_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%users_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` datetime NOT NULL,
  `blog` datetime NOT NULL,
  `groups` datetime NOT NULL,
  `events` datetime NOT NULL,
  `todo` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%users_activity`
--

LOCK TABLES `%%PREFIX%%users_activity` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%users_activity` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%users_activity` VALUES (1,42,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,92,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,93,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,94,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,95,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `%%PREFIX%%users_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%users_invite`
--

DROP TABLE IF EXISTS `%%PREFIX%%users_invite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%users_invite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invitor` int(11) NOT NULL,
  `from_email` varchar(100) NOT NULL,
  `invite_email` varchar(100) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  `last_invite_date` datetime NOT NULL,
  `created` datetime NOT NULL,
  `group_limited` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%users_invite`
--

LOCK TABLES `%%PREFIX%%users_invite` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%users_invite` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%users_invite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%users_token`
--

DROP TABLE IF EXISTS `%%PREFIX%%users_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%users_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_id` varchar(100) NOT NULL,
  `expires` datetime NOT NULL,
  `authorized` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%users_token`
--

LOCK TABLES `%%PREFIX%%users_token` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%users_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%users_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%viewlevels`
--

DROP TABLE IF EXISTS `%%PREFIX%%viewlevels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%viewlevels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `title` varchar(100) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_assetgroup_title_lookup` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%viewlevels`
--

LOCK TABLES `%%PREFIX%%viewlevels` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%viewlevels` DISABLE KEYS */;
INSERT INTO `%%PREFIX%%viewlevels` VALUES (1,'Public',0,'[1]'),(2,'Registered',1,'[6,2,8]'),(3,'Special',2,'[6,3,8]');
/*!40000 ALTER TABLE `%%PREFIX%%viewlevels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `%%PREFIX%%weblinks`
--

DROP TABLE IF EXISTS `%%PREFIX%%weblinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `%%PREFIX%%weblinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `sid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if link is featured.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `%%PREFIX%%weblinks`
--

LOCK TABLES `%%PREFIX%%weblinks` WRITE;
/*!40000 ALTER TABLE `%%PREFIX%%weblinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `%%PREFIX%%weblinks` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-06 16:59:37
