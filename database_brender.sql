-- phpMyAdmin SQL Dump
-- version 3.3.7deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 10, 2010 at 01:14 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-1ubuntu9.1

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

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(32) NOT NULL,
  `speed` tinyint(4) NOT NULL,
  `machinetype` varchar(24) NOT NULL DEFAULT 'node',
  `machine_os` varchar(16) NOT NULL,
  `client_priority` tinyint(4) NOT NULL,
  `status` varchar(128) NOT NULL DEFAULT 'not running',
  `rem` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='les clients' AUTO_INCREMENT=17 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `client`, `speed`, `machinetype`, `machine_os`, `client_priority`, `status`, `rem`) VALUES
(1, 'macbook', 1, 'node', 'mac', 0, 'not running', 'client not responding (PING)'),
(11, 'station3d', 1, 'node', 'linux', 0, 'rendering', '-b ''/mnt/cutstation01/GRAND_PRIX_GENEVE_2010/01_3D/SCENES/06_grande_aiguille/06_02_boite_saute.blend'' -o ''/mnt/cutstation01/GRAND_PRIX_GENEVE_2010/01_3D/RENDER/06_grande_aiguille/06_02_boite_saute/06_02_boite_saute'' -P conf/GPHG_full.py -F PNG  -s 1 -e 3 -a'),
(13, 'macpro', 2, 'node', 'mac', 1, 'rendering', '-b ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/SCENES//06_grande_aiguille/06_02_boite_saute.blend'' -o ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/RENDER//06_grande_aiguille/06_02_boite_saute/06_02_boite_saute'' -P conf/GPHG_full.py -F PNG  -s 10 -e 15 -a'),
(15, 'macpro2', 2, 'node', 'mac', 1, 'rendering', '-b ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/SCENES//06_grande_aiguille/06_02_boite_saute.blend'' -o ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/RENDER//06_grande_aiguille/06_02_boite_saute/06_02_boite_saute'' -P conf/GPHG_full.py -F PNG  -s 22 -e 27 -a'),
(14, 'imac', 1, 'node', 'mac', 1, 'disabled', ''),
(16, 'station3d2', 2, 'node', 'linux', 1, 'not running', '');

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
  `priority` smallint(6) NOT NULL DEFAULT '0',
  `lastseen` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=125 ;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `scene`, `shot`, `start`, `end`, `project`, `current`, `chunks`, `filetype`, `rem`, `config`, `status`, `priority`, `lastseen`) VALUES
(99, 'test', 'test', 1, 90, 'test', 1, 3, 'TGA', '', '1k', 'finished at ', 40, '2010-11-07 14:18:50'),
(100, '99_tests', '00_brender_test', 1, 100, 'GPHG_2010', 103, 3, 'PNG', '', 'GPHG_preview', 'finished at 2010/06/11 15:53:53', 60, '2010-11-08 16:24:11'),
(101, '07_plumes_boa', '07_01_plumes_boa', 1, 261, 'GPHG_2010', 261, 5, 'PNG', '', 'GPHG_full', 'finished at ', 40, '2010-11-09 22:49:28'),
(103, '03_animal_ballon', '03_02_chien_ballon', 1, 330, 'GPHG_2010', 331, 3, 'PNG', '', 'GPHG_full', 'finished at 2010/10/11 02:19:11', 50, '2010-11-10 02:18:41'),
(102, '11_champagne_fusee', '11_01_champagne_fusee', 1, 230, 'GPHG_2010', 1, 4, 'PNG', '', 'GPHG_full', 'finished +', 60, '2010-11-09 16:29:55'),
(110, '02_katana', '02_02_katana', 1, 155, 'GPHG_2010', 1, 3, 'PNG', '', 'GPHG_full', 'waiting', 55, '2010-11-09 16:33:58'),
(109, '02_katana', '02_01_katana', 1, 140, 'GPHG_2010', 1, 3, 'PNG', '', 'GPHG_full', 'waiting', 55, '2010-11-09 16:33:51'),
(105, '03_animal_ballon', '03_03_ballon_idee', 1, 70, 'GPHG_2010', 73, 3, 'PNG', '', 'GPHG_full', 'finished at 2010/10/11 10:03:51', 50, '2010-11-10 10:03:26'),
(106, '10_homme_orchestre', '10_01_homme_orchestre', 1, 300, 'GPHG_2010', 1, 3, 'PNG', '', 'GPHG_full', 'waiting', 60, '2010-11-09 16:29:09'),
(107, '03_animal_ballon', '03_01_ballon_gonflage', 1, 100, 'GPHG_2010', 100, 3, 'PNG', '', 'GPHG_full', 'finished at ', 52, '2010-11-10 12:01:20'),
(108, '03_animal_ballon', '03_04_ballon_long', 1, 350, 'GPHG_2010', 355, 6, 'PNG', '', 'GPHG_full', 'finished at 2010/10/11 08:23:13', 50, '2010-11-10 08:22:47'),
(111, '02_katana', '02_03_katana', 1, 100, 'GPHG_2010', 100, 3, 'PNG', '', 'GPHG_full', 'finished at ', 50, '2010-11-10 02:26:18'),
(112, '05_petite_aiguille', '05_01_petite_aiguille', 1, 160, 'GPHG_2010', 160, 3, 'PNG', '', 'GPHG_full', 'finished at ', 50, '2010-11-10 03:47:37'),
(113, '05_petite_aiguille', '05_02_petite_aiguille', 1, 90, 'GPHG_2010', 91, 3, 'PNG', '', 'GPHG_full', 'finished at 2010/10/11 08:57:37', 50, '2010-11-10 08:57:11'),
(114, '05_petite_aiguille', '05_03_petite_aiguille', 1, 110, 'GPHG_2010', 112, 3, 'PNG', '', 'GPHG_full', 'finished at 2010/10/11 10:57:06', 50, '2010-11-10 10:56:41'),
(115, '06_grande_aiguille', '06_01_grande_aiguille_cadeaux', 1, 110, 'GPHG_2010', 115, 3, 'PNG', '', 'GPHG_full', 'finished at 2010/10/11 12:57:40', 52, '2010-11-10 12:57:16'),
(122, '01_bulles_champagne', '01_02_nage_dans_champagne', 1, 230, 'GPHG_2010', 232, 3, 'PNG', '', 'GPHG_preview', 'finished at 2010/08/11 17:40:03', 25, '2010-11-08 17:39:11'),
(118, '04_montee_en_ballon', '04_020_montee', 1, 280, 'GPHG_2010', 286, 5, 'PNG', '', 'GPHG_full', 'finished at 2010/09/11 21:00:01', 35, '2010-11-09 20:59:27'),
(121, '01_bulles_champagne', '01_01_plonge_champagne', 1, 280, 'GPHG_2010', 161, 5, 'PNG', '', 'GPHG_preview', 'finished +', 25, '2010-11-09 15:03:38'),
(116, '06_grande_aiguille', '06_02_boite_saute', 1, 110, 'GPHG_2010', 28, 3, 'PNG', '', 'GPHG_full', 'rendering', 52, '2010-11-10 13:12:37'),
(117, '06_grande_aiguille', '06_03_aiguille_sort', 1, 120, 'GPHG_2010', 1, 3, 'PNG', '', 'GPHG_full', 'waiting', 52, '2010-11-09 16:28:52'),
(124, '08_langue_de_belle_mere', '08_01_langue_belle_mere', 1, 230, 'GPHG_2010', 1, 3, 'PNG', '', 'GPHG_full', 'waiting', 60, '2010-11-10 11:36:55'),
(119, '04_montee_en_ballon', '04_030_eclatement_ballon', 1, 130, 'GPHG_2010', 136, 5, 'PNG', '', 'GPHG_full', 'finished at 2010/09/11 19:10:54', 35, '2010-11-09 19:10:19'),
(120, '04_montee_en_ballon', '04_040_descente', 1, 50, 'GPHG_2010', 55, 3, 'PNG', '', 'GPHG_full', 'finished at 2010/09/11 16:58:20', 30, '2010-11-09 16:57:43'),
(123, '01_bulles_champagne', '01_03_bulles_champagne', 1, 310, 'GPHG_2010', 311, 5, 'PNG', '', 'GPHG_preview', 'finished at 2010/09/11 14:00:15', 25, '2010-11-09 13:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(32) NOT NULL,
  `orders` varchar(255) NOT NULL,
  `priority` smallint(6) NOT NULL,
  `rem` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37856 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client`, `orders`, `priority`, `rem`) VALUES
(37854, 'macpro2', 'render', 20, '-b ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/SCENES//06_grande_aiguille/06_02_boite_saute.blend'' -o ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/RENDER//06_grande_aiguille/06_02_boite_saute/06_02_boite_saute'' -P conf/GPHG_full.py -F PNG  -s 22 -e 27 -a'),
(37847, 'station3d', 'render', 20, '-b ''/mnt/cutstation01/GRAND_PRIX_GENEVE_2010/01_3D/SCENES/06_grande_aiguille/06_02_boite_saute.blend'' -o ''/mnt/cutstation01/GRAND_PRIX_GENEVE_2010/01_3D/RENDER/06_grande_aiguille/06_02_boite_saute/06_02_boite_saute'' -P conf/GPHG_full.py -F PNG  -s 1 -e 3 -a'),
(37850, 'macpro', 'render', 20, '-b ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/SCENES//06_grande_aiguille/06_02_boite_saute.blend'' -o ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/RENDER//06_grande_aiguille/06_02_boite_saute/06_02_boite_saute'' -P conf/GPHG_full.py -F PNG  -s 10 -e 15 -a'),
(37851, 'macpro', 'render', 20, '-b ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/SCENES//06_grande_aiguille/06_02_boite_saute.blend'' -o ''/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/RENDER//06_grande_aiguille/06_02_boite_saute/06_02_boite_saute'' -P conf/GPHG_full.py -F PNG  -s 16 -e 21 -a'),
(37800, 'station3d2', 'stop', 1, ''),
(37801, 'station3d2', 'stop', 1, ''),
(37802, 'station3d2', 'stop', 1, '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `blend_mac`, `blend_linux`, `blend_win`, `output_mac`, `output_win`, `output_linux`, `rem`, `status`, `def`) VALUES
(21, 'test', 'blend', '', '', 'render', '', '', 'grand prix d-horlogerie version 2010', 'active', 0),
(22, 'GPHG_2010', '/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/SCENES/', '/mnt/cutstation01/GRAND_PRIX_GENEVE_2010/01_3D/SCENES', '', '/Volumes/CS01_HD_03/GRAND_PRIX_GENEVE_2010/01_3D/RENDER/', '', '/mnt/cutstation01/GRAND_PRIX_GENEVE_2010/01_3D/RENDER', '', 'active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `scenes`
--

CREATE TABLE IF NOT EXISTS `scenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project` varchar(24) DEFAULT NULL,
  `scene` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `scenes`
--


-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `server` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL,
  `pid` int(11) NOT NULL,
  `started` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sound` varchar(12) NOT NULL,
  `last_rendered` varchar(128) NOT NULL,
  `rem` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

