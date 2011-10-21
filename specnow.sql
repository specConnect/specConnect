-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 21, 2011 at 02:02 AM
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
(2, 'News and Information', 'Look here for news and information relating to all SPEC members.', '2011-10-16 08:28:46', 'Latest News', 0, 0),
(3, 'MROMS', 'Multi Robot Open-space Mapping System team discussion and more.', '2011-10-04 19:09:12', 'Projects', 0, 0),
(4, 'SPEC Announcements', 'Executive announcements, new project announcement, and much more.', '2011-10-19 00:11:04', 'Latest News', 0, 0),
(5, 'Tickets and Glitches', 'Your input matters. Tell us about glitches or features you would like to see here.', '2011-10-19 00:14:56', 'Bug Reporting', 3, 4);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `thread_id`, `username`, `content`, `modified`) VALUES
(2, 1, 'edchand', '<p>Another important feature is to add a User Profile. Things profile will include:&nbsp; \r\n<img alt="crying" height="20" src="http://localhost/js/ckeditor/plugins/smiley/images/cry_smile.gif" title="crying" width="20" /></p>\r\n<ol>\r\n<li>Users LAST LOGIN TIME</li>\r\n<li>User personal information such as name and email</li>\r\n<li>Users website</li>\r\n<li>Users company information</li>\r\n<li>Users Awards Based on his specConnect Interactions</li>\r\n<li>Users GRAVATAR</li>\r\n<li>Users interest in SPEC projects</li>\r\n</ol>\r\n<p>This information will be presented in some NICE manner. \r\n<img alt="smiley" height="20" src="http://localhost/js/ckeditor/plugins/smiley/images/regular_smile.gif" title="smiley" width="20" /></p>\r\n', '2011-10-16 23:43:31'),
(3, 8, 'edchand', '<p>Almost forgot this important feature:&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li>Forum Subscription - Users should be able to subscribe to forums and get updates via email when someone makes a thread of a post in that forum.\r\n<ul>\r\n<li>A note on this feature: BEFORE SUBSCRIBING A USER MUST CONFIRM HIS/HER EMAIL.</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n', '2011-10-16 03:26:56'),
(4, 1, 'edchand', '<p>Figure out also how to fix the breadcrumb menu</p>\r\n', '2011-10-16 23:48:08'),
(5, 1, 'edchand', '<p>Be able to make certain forums PRIVATE&nbsp;</p>\r\n<p>Only&nbsp; \r\n<strong>SOME</strong>\r\nmembers can have access to these forums.</p>\r\n<p>Also make sure that the non-members cannot add posts/threads to this forum by just changin the URL...&nbsp;</p>\r\n<p>&nbsp;</p>\r\n', '2011-10-16 23:46:01');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`id`, `username`, `forum_id`, `thread_name`, `content`, `created`, `modified`, `posts`, `sticky`, `private`, `view`) VALUES
(1, 'edchand', 5, 'Long & Short term goals', '<p>Here is the goals for upcoming weeks (short term):</p>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li>Thumbs up/down threads</li>\r\n</ol>\r\n<ul style="margin-left: 40px; ">\r\n<li>This will be implemented using a simple table tracking users and their IP&#39;s and is a hasMany relationship with threads.</li>\r\n</ul>\r\n<ol>\r\n<li>Views on threads</li>\r\n<li>User Profiles (Gravatar, and what not)</li>\r\n<li>Fix Breadcrumb</li>\r\n<li>Emailing Users When Posts are Made</li>\r\n<li>Subscription List for forums</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<p>Long Term Goals (in order of IMPORTANCE - long term):</p>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li>Ajax Integration - UI enhancements with fades and what not</li>\r\n<li>Google Calendar Integration</li>\r\n<li>Live Feeds -Latest forum posts, facebook posts, twitter posts.</li>\r\n<li>Facebook Integration</li>\r\n<li>Twitter Integration</li>\r\n<li>Google+ or Skype integration</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<p>These are the goals. The sooner they get done the better.</p>\r\n', '2011-10-16 02:03:58', '2011-10-20 03:11:04', 3, 1, 0, 0),
(8, 'edchand', 5, 'Advanced Features', '<p>Here is a list of advanced features for users:</p>\r\n<ol>\r\n<li>RSS feeds</li>\r\n<li>Integration with Facebook/Googe/Twiiter API</li>\r\n<li>AJAX/Long Polling Integration for seemless user experience</li>\r\n</ol>\r\n<p>More to be added later...</p>\r\n', '2011-10-16 03:24:09', '2011-10-16 03:26:56', 1, 1, 0, 0),
(10, 'edchand', 5, 'FEATURES TO TWEAK', '<p>For keeping forum data in sync, we will make a function that will be run and all the forum posts and threads will be counted at the end of the day.</p>\r\n<p>&nbsp;</p>\r\n<p>This will make sure that the post/threads data of forums and thread replies is kept VALID an that count isn&#39;t lost.</p>\r\n<p>&nbsp;</p>\r\n<p>we can do this using CRONJOB or CRON mangement in domain.com control panel &gt; manage webspace....</p>\r\n', '2011-10-16 11:41:19', '2011-10-19 00:11:18', 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `thumbs`
--

CREATE TABLE IF NOT EXISTS `thumbs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `thumbs`
--

INSERT INTO `thumbs` (`id`, `thread_id`, `username`, `ip`, `modified`) VALUES
(1, 1, 'edchand', '127.0.0.1', '2011-10-20 10:10:37'),
(2, 10, 'edchand', '127.0.0.1', '2011-10-20 10:16:45'),
(3, 8, 'edchand', '127.0.0.1', '2011-10-20 11:01:38'),
(4, 1, 'testUser', '127.0.0.1', '2011-10-20 11:02:20'),
(5, 10, 'testUser', '127.0.0.1', '2011-10-20 11:05:29'),
(6, 8, 'testUser', '127.0.0.1', '2011-10-20 11:05:32'),
(20, 10, 'tbagers', '127.0.0.1', '2011-10-20 11:22:11'),
(21, 1, 'tbagers', '127.0.0.1', '2011-10-20 11:22:12'),
(22, 8, 'tbagers', '127.0.0.1', '2011-10-20 11:22:13');

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
(1, 'edchand', '662c39204b127d49411c791b9319bf91f5ef3418', 'eac7@sfu.ca', 'sadmin', 'Edwin', 'Chand', 'avatar/default.jpg', 4),
(2, 'testUser', '662c39204b127d49411c791b9319bf91f5ef3418', 'edchand@gmail.com', 'regular', 'Edwin', 'Chand', 'avatar/default.jpg', 2),
(3, 'tbagers', '662c39204b127d49411c791b9319bf91f5ef3418', 'tbagers@gmail.com', 'admin', 'Edwin', 'Chand', 'avatar/default.jpg', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
