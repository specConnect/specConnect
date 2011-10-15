-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 16, 2011 at 01:15 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `specnow`
--

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `summary` text NOT NULL,
  `modified` datetime NOT NULL,
  `category` varchar(40) NOT NULL DEFAULT 'Projects',
  `threads` int(50) NOT NULL DEFAULT '0',
  `posts` int(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `name`, `summary`, `modified`, `category`, `threads`, `posts`) VALUES
(1, 'UHDMS', 'Unmanned Highway Drive System project team discussions and more.', '2011-10-04 19:09:12', 'Projects', 0, 0),
(2, 'News and Information', 'Look here for news and information relating to all SPEC members.', '2011-10-15 05:11:00', 'Latest News', 0, 0),
(3, 'MROMS', 'Multi Robot Open-space Mapping System team discussion and more.', '2011-10-04 19:09:12', 'Projects', 0, 0),
(4, 'SPEC Announcements', 'Executive announcements, new project announcement, and much more.', '2011-10-15 09:47:35', 'Latest News', 0, 0),
(5, 'Tickets and Glitches', 'Your input matters. Tell us about glitches or features you would like to see here.', '2011-10-16 00:46:24', 'Bug Reporting', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE IF NOT EXISTS `threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `thread_name` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `posts` int(50) NOT NULL DEFAULT '0',
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `view` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `roles` varchar(10) NOT NULL DEFAULT 'regular',
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `avatar` varchar(100) NOT NULL DEFAULT 'avatar/default.jpg',
  `posts` int(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `roles`, `first_name`, `last_name`, `avatar`, `posts`) VALUES
(1, 'edchand', '662c39204b127d49411c791b9319bf91f5ef3418', 'eac7@sfu.ca', 'sadmin', 'Edwin', 'Chand', 'avatar/default.jpg', 0),
(2, 'testUser', '662c39204b127d49411c791b9319bf91f5ef3418', 'edchand@gmail.com', 'regular', 'Edwin', 'Chand', 'avatar/default.jpg', 0),
(3, 'tbagers', '662c39204b127d49411c791b9319bf91f5ef3418', 'tbagers@gmail.com', 'admin', 'Edwin', 'Chand', 'avatar/default.jpg', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
