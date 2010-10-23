-- phpMyAdmin SQL Dump
-- version 2.11.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 07, 2009 at 12:55 PM
-- Server version: 5.0.41
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `brender`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL auto_increment,
  `client` varchar(32) NOT NULL,
  `speed` tinyint(4) NOT NULL,
  `machinetype` varchar(24) NOT NULL default 'node',
  `client_priority` tinyint(4) NOT NULL,
  `status` varchar(128) NOT NULL default 'not running',
  `rem` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='les clients' AUTO_INCREMENT=11 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` VALUES(1, 'macbook', 1, 'node', 0, 'not running', 'client not responding (PING)');
INSERT INTO `clients` VALUES(2, 'macpro', 2, 'work', 0, 'not running', '');
INSERT INTO `clients` VALUES(5, 'pc1', 1, 'node', 0, 'not running', 'client not responding (PING)');
INSERT INTO `clients` VALUES(8, 'ubuntu1', 1, 'node', 0, 'not running', 'client not responding (PING)');
INSERT INTO `clients` VALUES(9, 'pc2', 1, 'node', 0, 'not running', 'client not responding (PING)');
INSERT INTO `clients` VALUES(10, 'ubuntu2', 1, 'node', 0, 'not running', 'client not responding (PING)');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `jobtype` varchar(32) NOT NULL,
  `file` varchar(64) NOT NULL,
  `start` int(11) NOT NULL default '1',
  `end` int(11) NOT NULL default '100',
  `project` varchar(32) NOT NULL,
  `output` varchar(64) NOT NULL,
  `current` int(11) NOT NULL default '0',
  `chunks` tinyint(4) NOT NULL default '0',
  `rem` varchar(255) NOT NULL,
  `filetype` varchar(12) NOT NULL,
  `config` varchar(64) NOT NULL,
  `status` varchar(65) NOT NULL,
  `priority` smallint(6) NOT NULL default '0',
  `lastseen` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=98 ;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` VALUES(1, 'test', 'special', 'brender_test.blend', 1, 550, 'humard', 'tester', 49, 6, 'pas top mais bon', 'jpg', 'hd720', 'finished +', 85, '2009-04-20 12:21:05');
INSERT INTO `jobs` VALUES(71, 'blocking', 'blend', 'blocking.blend', 1, 350, 'demoreel', 'blocking', 1, 127, '', 'tga', 'pal', 'finished +', 55, '2009-04-20 12:20:21');
INSERT INTO `jobs` VALUES(95, 'brender_test', 'blend', 'brender_test.blend', 1, 50, 'tests', 'brender_test', 49, 3, '', 'jpg', 'preview_hd', 'finished +', 50, '2009-08-06 21:21:56');
INSERT INTO `jobs` VALUES(94, 'cathedrale2', 'blend', 'cathedrale2.blend', 1, 100, 'tests', 'cathedrale2', 100, 3, '', 'tga', 'preview_hd', 'finished at ', 50, '2009-08-04 16:02:19');
INSERT INTO `jobs` VALUES(96, 'lambiel', 'blend', 'anim_chapitre_intro.blend', 1, 150, 'tests', 'chapitre_intro', 151, 3, '', 'tga', 'pal', 'finished at 2009/04/08 15:59:46', 50, '2009-08-04 15:59:46');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL auto_increment,
  `client` varchar(32) NOT NULL,
  `orders` varchar(64) NOT NULL,
  `priority` smallint(6) NOT NULL,
  `rem` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31156 ;

--
-- Dumping data for table `orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `mac_path` varchar(128) NOT NULL,
  `win_path` varchar(128) NOT NULL,
  `rem` varchar(255) NOT NULL,
  `status` varchar(24) NOT NULL default 'active',
  `def` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` VALUES(1, 'humard', '', '', '', 'finished', 0);
INSERT INTO `projects` VALUES(18, 'tests', '/Users/o/o2/whiteframe/brendertests/', '/Users/o/o2/whiteframe/brendertests/', 'tests', 'active', 1);
INSERT INTO `projects` VALUES(8, 'demoreel', '/Volumes/VIDEO\\ 02/PROJET_DEMOREEL2009/3D', '/Volumes/VIDEO\\ 02/PROJET_DEMOREEL2009/3D', 'demo regb 2009', 'finished', 0);

-- --------------------------------------------------------

--
-- Table structure for table `scenes`
--

CREATE TABLE `scenes` (
  `id` int(11) NOT NULL auto_increment,
  `project` varchar(24) default NULL,
  `scene` varchar(24) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `scenes`
--


-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `server` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL,
  `pid` int(11) NOT NULL,
  `started` timestamp NULL default CURRENT_TIMESTAMP,
  `sound` varchar(12) NOT NULL,
  `last_rendered` varchar(128) NOT NULL,
  `rem` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` VALUES('brender', 'died', 0, '0000-00-00 00:00:00', 'no', '', '');

