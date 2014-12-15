-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 22, 2012 at 09:24 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cassa_lan`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendee`
--

DROP TABLE IF EXISTS `attendee`;
CREATE TABLE IF NOT EXISTS `attendee` (
  `attendeeID` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Auto_increment',
  `seatID` tinyint(4) DEFAULT NULL COMMENT 'Foreign Key',
  `eventID` smallint(6) NOT NULL COMMENT 'Foreign Key(eventID)',
  `clientID` smallint(6) NOT NULL COMMENT 'Foreign Key(clientID)',
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`attendeeID`),
  KEY `eventID` (`eventID`,`clientID`),
  KEY `seatID` (`seatID`),
  KEY `clientID` (`clientID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- RELATIONS FOR TABLE `attendee`:
--   `eventID`
--       `event` -> `eventID`
--   `clientID`
--       `client` -> `clientID`
--   `seatID`
--       `seat` -> `seatID`
--

--
-- Dumping data for table `attendee`
--

INSERT INTO `attendee` (`attendeeID`, `seatID`, `eventID`, `clientID`, `paid`) VALUES
(2, 3, 1, 7, 0),
(47, 32, 4, 25, 0),
(49, NULL, 4, 1, 1),
(50, NULL, 4, 7, 1),
(51, 33, 4, 26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `attendee_tournament`
--

DROP TABLE IF EXISTS `attendee_tournament`;
CREATE TABLE IF NOT EXISTS `attendee_tournament` (
  `attendeeID` smallint(6) NOT NULL,
  `tournID` smallint(6) NOT NULL,
  KEY `attendeeID` (`attendeeID`,`tournID`),
  KEY `tournID` (`tournID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `attendee_tournament`:
--   `attendeeID`
--       `attendee` -> `attendeeID`
--   `tournID`
--       `tournament` -> `tournID`
--

--
-- Dumping data for table `attendee_tournament`
--

INSERT INTO `attendee_tournament` (`attendeeID`, `tournID`) VALUES
(2, 3),
(2, 4),
(2, 5),
(49, 5);

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `clientID` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Auto_increment',
  `username` varchar(256) NOT NULL,
  `password` text NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `irc` varchar(64) DEFAULT NULL,
  `mobile` char(10) NOT NULL,
  `email` varchar(256) NOT NULL,
  `admin` int(1) DEFAULT '0' COMMENT '0= client, 1= staff, 2= super user',
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`clientID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`clientID`, `username`, `password`, `first_name`, `last_name`, `irc`, `mobile`, `email`, `admin`, `active`) VALUES
(1, 'nash@hotmail.com', 'testing123', 'Tinashe', 'Masvaure', '', '0432488797', 'nash@hotmail.com', 1, 1),
(7, 'dman@hotmail.com', 'mand10101', 'Dwayne', 'Wayne', NULL, '0478987632', 'dman@hotmail.com', 1, 1),
(15, 'lyndons@eco.com.au', 'lyndonsmith', 'Lyndon', 'Smith', NULL, '0414499866', 'lyndons@eco.com.au', 0, 1),
(16, 'admin@domain.com', 'admin', 'first_name', 'surname', NULL, '0404041234', 'admin@domain.com', 2, 1),
(25, 'qmaseyk@our.ecu.edu.au', 'testing123', 'Quintin', 'Maseyk', NULL, '0404040404', 'qmaseyk@our.ecu.edu.au', 0, 1),
(26, 'lukes@our.ecu.edu.au', 'testing123', 'Luke', 'Spartal', NULL, '0412345678', 'lukes@our.ecu.edu.au', 0, 1),
(27, 'testin@testing.com', 'testing123', 'testing', 'testing', NULL, '0404040404', 'testin@testing.com', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `contactID` int(11) NOT NULL AUTO_INCREMENT,
  `blur` varchar(1024) NOT NULL,
  `president` varchar(64) DEFAULT NULL,
  `v_president` varchar(64) DEFAULT NULL,
  `secretary` varchar(64) DEFAULT NULL,
  `treasurer` varchar(64) DEFAULT NULL,
  `tech_admin` varchar(64) DEFAULT NULL,
  `webmaster` varchar(64) DEFAULT NULL,
  `social_events` varchar(64) DEFAULT NULL,
  `pre_irc` varchar(64) DEFAULT NULL,
  `vpre_irc` varchar(64) DEFAULT NULL,
  `sec_irc` varchar(64) DEFAULT NULL,
  `tre_irc` varchar(64) DEFAULT NULL,
  `tec_irc` varchar(64) DEFAULT NULL,
  `web_irc` varchar(64) DEFAULT NULL,
  `soc_irc` varchar(64) DEFAULT NULL,
  `pre_email` varchar(64) DEFAULT NULL,
  `vpre_email` varchar(64) DEFAULT NULL,
  `sec_email` varchar(64) DEFAULT NULL,
  `tre_email` varchar(64) DEFAULT NULL,
  `tec_email` varchar(64) DEFAULT NULL,
  `web_email` varchar(64) DEFAULT NULL,
  `soc_email` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`contactID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contactID`, `blur`, `president`, `v_president`, `secretary`, `treasurer`, `tech_admin`, `webmaster`, `social_events`, `pre_irc`, `vpre_irc`, `sec_irc`, `tre_irc`, `tec_irc`, `web_irc`, `soc_irc`, `pre_email`, `vpre_email`, `sec_email`, `tre_email`, `tec_email`, `web_email`, `soc_email`) VALUES
(1, 'We are located on ECU Mt. Lawley campus, room 03.202 (upstairs next to the lecture theatre). We have the screen on top of our door so you won’t miss us.', 'Mike Swift', 'Mark Goes', 'Dick Spartalis', 'Well Being', 'Able Seaman', 'Willy Webster', 'Pixie Person', 'Funkballs', 'rith', 'Radx', 'falcon', 'Cake_Man', 'Spartan101', 'Eskilla', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `eventID` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Auto_increment',
  `event_name` varchar(64) NOT NULL,
  `event_location` varchar(128) NOT NULL,
  `startDate` date NOT NULL,
  `days` tinyint(4) NOT NULL DEFAULT '1',
  `startTime` time NOT NULL,
  `seatQuantity` tinyint(4) NOT NULL,
  `server_IP_address` varchar(28) NOT NULL,
  `event_started` tinyint(1) NOT NULL DEFAULT '0',
  `event_completed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`eventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`eventID`, `event_name`, `event_location`, `startDate`, `days`, `startTime`, `seatQuantity`, `server_IP_address`, `event_started`, `event_completed`) VALUES
(1, 'Cassa Royal 1', 'Mt Lawly', '2012-06-13', 1, '11:35:00', 80, '192.168.1.25', 0, 0),
(4, 'DOTA Festival 2012', 'ECU Mount Lawley ML13.124', '2012-06-29', 1, '18:00:00', 62, '192.168.0.1', 0, 0),
(17, 'TESTING 123', 'ML04.232', '2012-06-30', 2, '16:00:00', 70, '', 0, 0),
(20, 'Mario Bros', 'ECU ML04.123', '2012-07-08', 5, '16:00:00', 80, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `FAQID` smallint(6) NOT NULL AUTO_INCREMENT,
  `faqDate` date DEFAULT NULL,
  `question` varchar(256) NOT NULL,
  `answer` varchar(1024) NOT NULL,
  PRIMARY KEY (`FAQID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`FAQID`, `faqDate`, `question`, `answer`) VALUES
(1, '2012-06-21', 'What should I bring to the MegaLAN', '<ul>\r\n<li>Computer/Laptop</li>\r\n<li>Monitor (no CRTs please)</li>\r\n<li>Cables (Power, USB, Ethernet)</li>\r\n<li>Power Board (no surge protected boards please)</li>\r\n<li>Headphones</li>\r\n<li>Any gaming accessories you require</li>\r\n<li>Money</li>\r\n<li>Deodorant</li>\r\n<li>Sleeping gear if desired</li>\r\n</ul>\r\n<br />\r\nAlso a side note for Steam users, please update all your games the night before.'),
(3, '2012-06-21', 'Will food be available at the MegaLAN?', 'Candy and drinks will be available to purchase, CASSA also do a pizza run during the evening around 5:00pm or 6:00pm in which you can order pizzas if you are hungry.'),
(4, '2012-06-21', 'Where can I pay for the MegaLAN?', 'You can pay on the day or at our office which is located at ECU Mt. Lawley campus, room 03.202 (upstairs next to the lecture theatre). We have the screen on top of our door so you won’t miss us.'),
(5, '2012-06-21', 'Where is it?', 'The LAN has been traditionally held in building 14 of the ECU Mt. Lawley Campus in rooms 114-116. It is a fairly large area with space for about 70-80 attendees.'),
(6, '2012-06-21', 'How long does it run for?', 'The MegaLAN usually starts at around 10am on the first day and most people arrive by lunch time. It carries on throughout the day and night, ending the following morning (most people leave around lunch time).');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `menuID` smallint(6) NOT NULL COMMENT 'Foreign Key(menuID)',
  `pizzaID` tinyint(6) NOT NULL COMMENT 'Foreign Key(pizzaID)',
  KEY `menuID` (`menuID`,`pizzaID`),
  KEY `pizzaID` (`pizzaID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `menu_items`:
--   `menuID`
--       `pizza_menu` -> `menuID`
--   `pizzaID`
--       `pizza_type` -> `pizzaID`
--

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`menuID`, `pizzaID`) VALUES
(1, 2),
(1, 3),
(1, 4),
(1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `newsID` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(64) NOT NULL,
  `date` date NOT NULL,
  `author` varchar(32) NOT NULL,
  `message` varchar(800) NOT NULL,
  `image` varchar(256) DEFAULT NULL,
  `tag` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`newsID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`newsID`, `subject`, `date`, `author`, `message`, `image`, `tag`) VALUES
(1, 'I Cyborg', '2012-04-04', 'Spartan101', 'Have you guys played any of the Deus Ex games? if so you would know all about augmentations. This technology seems years and years in the future, but it may be closer than you think.<br /><br />Professor Kevin Warwick, from the University of Reading (a uni in the uk) has been doing research into the feild of Cybernetics and biomedical engineering and been trialing this technology on himself.<br/><br/>During Project 1 he placed a chip in his arm and was able to control lights, eletronic doors and the like. During Project 2 he successfully controlled an artificial hand and had a chip implanted in his wife to comunicate telepathically. This is some interesting stuff.<br /><br />To read more more visit http://www.kevinwarwick.com/Cyborg1.htm or google  Project Cyborg', 'icyborg.jpg', 'Tech'),
(2, 'MegaLAN', '2012-04-06', 'Spartan101', 'Hey Gamers, the MegaLAN is just around the corner. Doors open 10am on the 8th, here is what to bring<ul><li>Computer/Laptop</li><li>Monitor</li><li>Cables</li><li>Power Board</li><li>Headphones</li><li>Any Gaming Accessories you require</li><li>Money</li></ul><br/><br/>Also a side note for Steam users, please update all your games the night before.<br/><br/>See you there', NULL, 'Social Events');

-- --------------------------------------------------------

--
-- Table structure for table `pizza_menu`
--

DROP TABLE IF EXISTS `pizza_menu`;
CREATE TABLE IF NOT EXISTS `pizza_menu` (
  `menuID` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Auto_increment',
  `eventID` smallint(6) NOT NULL COMMENT 'Foreign Key(eventID)',
  `menu_name` varchar(28) NOT NULL,
  PRIMARY KEY (`menuID`),
  KEY `eventID` (`eventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- RELATIONS FOR TABLE `pizza_menu`:
--   `eventID`
--       `event` -> `eventID`
--

--
-- Dumping data for table `pizza_menu`
--

INSERT INTO `pizza_menu` (`menuID`, `eventID`, `menu_name`) VALUES
(1, 4, 'Ancient Pizzas');

-- --------------------------------------------------------

--
-- Table structure for table `pizza_order`
--

DROP TABLE IF EXISTS `pizza_order`;
CREATE TABLE IF NOT EXISTS `pizza_order` (
  `orderID` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Auto_increment',
  `pizzaID` tinyint(4) NOT NULL COMMENT 'Foreign Key(pizzaID)',
  `attendeeID` smallint(6) NOT NULL COMMENT 'Foreign Key(attendeeID)',
  `quantity` tinyint(4) NOT NULL,
  `paid_pizza` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0= no, 1= yes',
  `menuID` smallint(6) NOT NULL,
  PRIMARY KEY (`orderID`),
  KEY `pizzaID` (`pizzaID`,`attendeeID`),
  KEY `attendeeID` (`attendeeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `pizza_order`
--

INSERT INTO `pizza_order` (`orderID`, `pizzaID`, `attendeeID`, `quantity`, `paid_pizza`, `menuID`) VALUES
(2, 2, 2, 1, 1, 1),
(22, 2, 47, 1, 1, 1),
(23, 4, 51, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pizza_type`
--

DROP TABLE IF EXISTS `pizza_type`;
CREATE TABLE IF NOT EXISTS `pizza_type` (
  `pizzaID` tinyint(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Auto_increment',
  `pizza_name` varchar(32) NOT NULL,
  `description` varchar(128) NOT NULL,
  `price` double(4,2) NOT NULL,
  PRIMARY KEY (`pizzaID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `pizza_type`
--

INSERT INTO `pizza_type` (`pizzaID`, `pizza_name`, `description`, `price`) VALUES
(2, 'Something Meaty', 'Full Of Meat', 7.80),
(3, 'Magarita', 'Cheese And Tomato', 5.00),
(4, 'Prawn Lover', 'Prawns,tomatoes And Garlic', 5.50),
(5, 'Mexican', 'Chilli, Chilli, Pepper, Chilli', 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `seat`
--

DROP TABLE IF EXISTS `seat`;
CREATE TABLE IF NOT EXISTS `seat` (
  `seatID` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Auto_increment',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = Booked, 1 = Available, 2 = Reserved',
  PRIMARY KEY (`seatID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

--
-- Dumping data for table `seat`
--

INSERT INTO `seat` (`seatID`, `status`) VALUES
(1, 1),
(2, 1),
(3, 0),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 0),
(33, 0),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `teamID` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Auto_increment',
  `team_name` varchar(64) NOT NULL,
  `team_password` varchar(64) NOT NULL,
  `player_count` tinyint(4) NOT NULL,
  `wins` int(11) NOT NULL,
  PRIMARY KEY (`teamID`),
  UNIQUE KEY `team_name` (`team_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `team_attendee`
--

DROP TABLE IF EXISTS `team_attendee`;
CREATE TABLE IF NOT EXISTS `team_attendee` (
  `teamID` smallint(6) NOT NULL COMMENT 'Foreign Key(teamID)',
  `attendeeID` smallint(6) NOT NULL COMMENT 'Foreign Key(attendeeID)',
  KEY `TeamID` (`teamID`,`attendeeID`),
  KEY `AttendeeID` (`attendeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `team_attendee`:
--   `teamID`
--       `teams` -> `teamID`
--   `attendeeID`
--       `attendee` -> `attendeeID`
--

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

DROP TABLE IF EXISTS `tournament`;
CREATE TABLE IF NOT EXISTS `tournament` (
  `tournID` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Auto_increment',
  `eventID` smallint(6) NOT NULL COMMENT 'Foreign Key(eventID)',
  `day` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(64) NOT NULL,
  `description` varchar(256) DEFAULT NULL COMMENT 'The description/rules of this tournament. ',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `winner` varchar(64) NOT NULL,
  `started` tinyint(1) NOT NULL COMMENT '0=no,1=yes',
  `finished` tinyint(1) NOT NULL COMMENT '0= no, 1=yes',
  PRIMARY KEY (`tournID`),
  KEY `eventID` (`eventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- RELATIONS FOR TABLE `tournament`:
--   `eventID`
--       `event` -> `eventID`
--

--
-- Dumping data for table `tournament`
--

INSERT INTO `tournament` (`tournID`, `eventID`, `day`, `name`, `description`, `start_time`, `end_time`, `winner`, `started`, `finished`) VALUES
(3, 1, 1, 'Killer_Geeks', NULL, '09:00:00', '12:00:00', '', 0, 0),
(4, 1, 1, 'Assin_Games', NULL, '08:00:00', '12:00:00', '', 0, 0),
(5, 4, 1, 'Heros of Newearth', NULL, '21:00:00', '23:00:00', '', 0, 0),
(7, 4, 1, 'Sentinal vs Scourge (5v5)', '', '18:30:00', '20:00:00', '', 0, 0),
(8, 4, 1, 'Call of Duty MW3', NULL, '19:00:00', '21:00:00', '', 1, 0),
(9, 4, 2, 'Halls of the Dead', '', '12:00:00', '24:00:00', '', 0, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendee`
--
ALTER TABLE `attendee`
  ADD CONSTRAINT `attendee_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `event` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendee_ibfk_5` FOREIGN KEY (`clientID`) REFERENCES `client` (`clientID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendee_ibfk_8` FOREIGN KEY (`seatID`) REFERENCES `seat` (`seatID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendee_tournament`
--
ALTER TABLE `attendee_tournament`
  ADD CONSTRAINT `attendee_tournament_ibfk_1` FOREIGN KEY (`attendeeID`) REFERENCES `attendee` (`attendeeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendee_tournament_ibfk_2` FOREIGN KEY (`tournID`) REFERENCES `tournament` (`tournID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`menuID`) REFERENCES `pizza_menu` (`menuID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_items_ibfk_2` FOREIGN KEY (`pizzaID`) REFERENCES `pizza_type` (`pizzaID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pizza_menu`
--
ALTER TABLE `pizza_menu`
  ADD CONSTRAINT `pizza_menu_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `event` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `team_attendee`
--
ALTER TABLE `team_attendee`
  ADD CONSTRAINT `team_attendee_ibfk_1` FOREIGN KEY (`teamID`) REFERENCES `teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `team_attendee_ibfk_2` FOREIGN KEY (`attendeeID`) REFERENCES `attendee` (`attendeeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `team_attendee_ibfk_3` FOREIGN KEY (`teamID`) REFERENCES `teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `team_attendee_ibfk_4` FOREIGN KEY (`attendeeID`) REFERENCES `attendee` (`attendeeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `tournament_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `event` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
