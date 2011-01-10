-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 07, 2011 at 10:43 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `brender`
--

CREATE DATABASE IF NOT EXISTS `brender` ;

USE `brender`;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS  `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(32) NOT NULL,
  `speed` tinyint(4) NOT NULL,
  `machine_type` varchar(24) NOT NULL DEFAULT 'node',
  `machine_os` varchar(16) NOT NULL,
  `blender_local_path` varchar(512) NOT NULL,
  `client_priority` tinyint(4) NOT NULL,
  `working_hour_start` time NOT NULL,
  `working_hour_end` time NOT NULL,
  `status` varchar(128) NOT NULL DEFAULT 'not running',
  `rem` varchar(1024) NOT NULL,
  `info` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='les clients' AUTO_INCREMENT=29 ;

--
-- Dumping data for table `clients`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=236 ;

--
-- Dumping data for table `jobs`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(32) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `orders` varchar(255) NOT NULL,
  `priority` smallint(6) NOT NULL,
  `rem` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48424 ;

--
-- Dumping data for table `orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `projects`
--

-- INSERT INTO `projects` VALUES(21, 'test', '/Users/o/brender/brender/blend', '/Users/o/brender/brender/blend', '/Users/o/brender/brender/blend', '/Users/o/brender/brender/render', '/Users/o/brender/brender/render', '/Users/o/brender/brender/render', 'grand prix d-horlogerie version 2010', 'active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rendered_frames`
--

CREATE TABLE IF NOT EXISTS `rendered_frames` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `frame` int(11) NOT NULL,
  `rendered_by` varchar(32) NOT NULL,
  `finished_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_thumbnailed` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=381 ;

--
-- Dumping data for table `rendered_frames`
--

-- --------------------------------------------------------

--
-- Table structure for table `server_settings`
--

CREATE TABLE IF NOT EXISTS `server_settings` (
  `server` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL,
  `pid` int(11) NOT NULL,
  `started` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sound` varchar(12) NOT NULL,
  `server_os` varchar(128) NOT NULL,
  `rem` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `server_settings`
--

-- INSERT INTO `server_settings` VALUES('server', 'not started ', 0, '1972-01-07 22:39:49', 'no', 'unknown', '');

