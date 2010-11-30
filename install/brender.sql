-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 28, 2010 at 03:51 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `brender`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(32) NOT NULL,
  `speed` tinyint(4) NOT NULL,
  `machinetype` varchar(24) NOT NULL DEFAULT 'node',
  `machine_os` varchar(16) NOT NULL,
  `client_priority` tinyint(4) NOT NULL,
  `working_hour_start` time NOT NULL,
  `working_hour_end` time NOT NULL,
  `status` varchar(128) NOT NULL DEFAULT 'not running',
  `rem` varchar(1024) NOT NULL,
  `info` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='les clients' AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scene` varchar(64) NOT NULL,
  `shot` varchar(64) NOT NULL,
  `start` int(11) NOT NULL DEFAULT '1',
  `end` int(11) NOT NULL DEFAULT '100',
  `project` varchar(32) NOT NULL,
  `current` int(11) NOT NULL DEFAULT '0',
  `chunks` tinyint(4) NOT NULL DEFAULT '0',
  `filetype` varchar(8) NOT NULL DEFAULT 'PNG',
  `rem` varchar(255) NOT NULL,
  `config` varchar(64) NOT NULL,
  `status` varchar(65) NOT NULL,
  `progress_status` varchar(128) NOT NULL,
  `progress_remark` varchar(255) NOT NULL,
  `priority` smallint(6) NOT NULL DEFAULT '0',
  `lastseen` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `last_edited_by` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=141 ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(32) NOT NULL,
  `orders` varchar(255) NOT NULL,
  `priority` smallint(6) NOT NULL,
  `rem` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47957 ;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `blend_mac` varchar(255) NOT NULL,
  `blend_linux` varchar(255) NOT NULL,
  `blend_win` varchar(255) NOT NULL,
  `output_mac` varchar(255) NOT NULL,
  `output_win` varchar(255) NOT NULL,
  `output_linux` varchar(255) NOT NULL,
  `rem` varchar(255) NOT NULL,
  `status` varchar(24) NOT NULL DEFAULT 'active',
  `def` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `scenes`
--

CREATE TABLE `scenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project` varchar(24) DEFAULT NULL,
  `scene` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `server` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL,
  `pid` int(11) NOT NULL,
  `started` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sound` varchar(12) NOT NULL,
  `last_rendered` varchar(128) NOT NULL,
  `rem` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
