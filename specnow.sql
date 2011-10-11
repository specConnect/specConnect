-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 11, 2011 at 06:32 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `specnow`
--
CREATE DATABASE `specnow` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `specnow`;

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `summary` text NOT NULL,
  `modified` datetime NOT NULL,
  `lastpost` varchar(50) NOT NULL,
  `category` varchar(40) NOT NULL DEFAULT 'Projects',
  `threads` int(50) NOT NULL DEFAULT '0',
  `posts` int(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `name`, `summary`, `modified`, `lastpost`, `category`, `threads`, `posts`) VALUES
(1, 'UHDMS', 'Unmanned Highway Drive System project team discussions and more.', '2011-10-04 19:09:12', 'edchand', 'Projects', 0, 0),
(2, 'News and Information', 'Look here for news and information relating to all SPEC members.', '2011-10-10 05:06:12', 'edchand', 'Latest News', 0, 0),
(3, 'MROMS', 'Multi Robot Open-space Mapping System team discussion and more.', '2011-10-04 19:09:12', 'edchand', 'Projects', 0, 0),
(4, 'SPEC Announcements', 'Executive announcements, new project announcement, and much more.', '2011-10-04 19:09:25', 'edchand', 'Latest News', 0, 0),
(5, 'Tickets and Glitches', 'Your input matters. Tell us about glitches or features you would like to see here.', '2011-10-11 17:42:36', 'edchand', 'Bug Reporting', 3, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `posts`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`id`, `username`, `forum_id`, `thread_name`, `content`, `created`, `modified`, `posts`, `sticky`, `private`, `view`) VALUES
(1, 'edchand', 5, 'TODO List for next 2 weeks', 'List of task''s to do (order doesn''t matter): \r\n<br /><br />\r\n1. Pagination for Posts <br />\r\n2. Adding Posts <br />\r\n3. Avatars for Users <br />\r\n4. Show most recent POST or THREAD in forum view page <br />\r\n5. Show most recent THREAD on thread view page <br />\r\n6. User roles -SADMIN-do everything -ADMIN-edit/delete all posts -REGULAR-only post and edit own posts <br />\r\n7. Editing threads/posts (in accordance with user roles <br />\r\n8. Delete threads and posts (in accordance with user roles) <br />\r\n9. Proper Breadcrumb Menu <br />\r\n10. Page numbers for posts in threads view page under thread name BETA release will be once 1-5 & 7,8 have been completed. Other features will be worked on as BETA testing resumes.<br />\r\n11. RICH TEXT EDITOR NEEDED - THIS IS A MUST <br />\r\n<br />\r\nGood luck', '2011-10-09 08:00:21', '2011-10-09 08:00:21', 0, 0, 0, 0),
(12, 'edchand', 5, 'Confirmation Email System', 'Need to setup a confirmation email system so that the user cannot login unless the he puts in his confirmation number.', '2011-10-10 22:47:35', '2011-10-10 22:47:35', 0, 0, 0, 0),
(13, 'edchand', 5, 'Advanced Features', '1. RSS Feeds for new posts <br />\r\n2. Be able to subscript to particular Forums of interest <br />\r\n3. Email Notifications <br />\r\n4. Quote on comments <br />\r\n5. Ban members, only for super admins <br />\r\n6. Thumbs Up/ Thumbs Down <br />\r\n7. Facebook/Twitter API to get social feeds<br />', '2011-10-11 06:51:30', '2011-10-11 06:51:30', 0, 0, 0, 0);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `roles`, `first_name`, `last_name`, `avatar`) VALUES
(1, 'edchand', '662c39204b127d49411c791b9319bf91f5ef3418', 'eac7@sfu.ca', 'sadmin', 'Edwin', 'Chand', 'avatar/default.jpg'),
(2, 'testUser', '662c39204b127d49411c791b9319bf91f5ef3418', 'tbagers@gmail.com', 'regular', 'Edwin', 'Chand', 'avatar/default.jpg');
