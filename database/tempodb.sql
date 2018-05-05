-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 29, 2017 at 04:56 PM
-- Server version: 5.7.20-0ubuntu0.16.04.1
-- PHP Version: 7.1.5-1+deb.sury.org~xenial+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tempodb`
--

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `city_name`, `created_at`, `updated_at`) VALUES
(1, 'Nagpur', '2017-09-15 00:00:00', NULL),
(2, 'New York', '2017-09-15 00:00:00', NULL),
(3, 'Sugar Land', '2017-09-15 00:00:00', NULL),
(4, 'Los Angeles', '2017-09-15 00:00:00', NULL),
(5, 'Boston', '2017-09-15 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cooling_down_slap`
--

CREATE TABLE `cooling_down_slap` (
  `id` int(11) NOT NULL,
  `max_rating_value` int(11) DEFAULT NULL,
  `percentage` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `emp_name` varchar(100) NOT NULL,
  `emp_contact` varchar(100) NOT NULL,
  `emp_role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eventpost_comments`
--

CREATE TABLE `eventpost_comments` (
  `event_comment_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `event_post_id` int(11) DEFAULT NULL,
  `comment_type` varchar(20) DEFAULT NULL,
  `comment_data` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `comment_status` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_host` varchar(50) DEFAULT NULL,
  `event_name` varchar(50) DEFAULT NULL,
  `event_description` text,
  `event_category` varchar(11) DEFAULT NULL,
  `event_start_datetime` datetime DEFAULT NULL,
  `event_end_datetime` datetime DEFAULT NULL,
  `is_published` varchar(11) DEFAULT NULL,
  `is_private` varchar(11) DEFAULT NULL,
  `promotion_image1` varchar(250) DEFAULT NULL,
  `promotion_image2` varchar(250) DEFAULT NULL,
  `promotion_image3` varchar(250) DEFAULT NULL,
  `key_words` varchar(50) DEFAULT NULL,
  `event_venu` varchar(50) DEFAULT NULL,
  `event_address` varchar(50) DEFAULT NULL,
  `event_zipcode` varchar(20) DEFAULT NULL,
  `event_city` varchar(50) DEFAULT NULL,
  `event_state` varchar(50) DEFAULT NULL,
  `event_country` varchar(50) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `max_no_of_seats` int(11) DEFAULT NULL,
  `per_seat_cost` float DEFAULT NULL,
  `created_by_user` varchar(50) DEFAULT NULL,
  `event_publised_on` datetime DEFAULT NULL,
  `updated_by` varchar(11) DEFAULT NULL,
  `event_status` varchar(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `guest_can_invite_friend` varchar(10) DEFAULT NULL,
  `event_rating` float DEFAULT NULL,
  `last_value` float DEFAULT NULL,
  `isdeleted` int(11) DEFAULT '0' COMMENT '0-Not Deleted; 1-Deleted'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_host`, `event_name`, `event_description`, `event_category`, `event_start_datetime`, `event_end_datetime`, `is_published`, `is_private`, `promotion_image1`, `promotion_image2`, `promotion_image3`, `key_words`, `event_venu`, `event_address`, `event_zipcode`, `event_city`, `event_state`, `event_country`, `latitude`, `longitude`, `max_no_of_seats`, `per_seat_cost`, `created_by_user`, `event_publised_on`, `updated_by`, `event_status`, `created_at`, `updated_at`, `guest_can_invite_friend`, `event_rating`, `last_value`, `isdeleted`) VALUES
(1, '', 'Tree Plantation', 'Tree Plantation is organized to save Nature', 'social', '2017-06-01 00:00:00', '2017-09-30 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/1_promo1_1505969618.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/1_promo2_1505969618.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/1_promo3_1505969618.jpg', '', 'Los Angeles', 'Amusement PArk', '90068', 'Los Angeles', 'California', 'US', 34.0522, -118.244, 250, 50, '1', '2017-09-15 05:58:58', NULL, 'published', '2017-09-15 05:58:58', '2017-09-21 04:53:39', '2', 405, 405, 0),
(2, '', 'Marathon for feetness', 'Marathon is organized for feetness.  ', 'health', '2017-06-05 00:00:00', '2017-08-02 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/2_promo1_1505456941.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/2_promo2_1505456941.jpeg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/2_promo3_1505456941.jpg', '', 'San Antonio', 'William Health Centre', '78201', 'San Antonio', 'Texas', 'US', 29.4241, -98.4936, 250, 50, '2', '2017-09-15 06:02:48', NULL, 'published', '2017-09-15 06:02:48', '2017-09-21 09:15:28', '1', 52, 52, 0),
(3, '', 'Road Safety Week', 'Road Safety Week is organized spreading message for safely driving of the people.', 'traffic', '2017-09-23 00:00:00', '2017-09-30 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/3_promo1_1505457060.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/3_promo2_1505457060.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/3_promo3_1505457060.jpg', '', 'Detroit', 'Upright Corner, City Tower', '78201', 'Detroit', 'Michigan', 'US', 42.3314, -83.0458, 150, 25, '3', '2017-09-15 06:07:55', NULL, 'published', '2017-09-15 06:07:55', '2017-09-15 06:31:00', '1', 1, 1, 0),
(4, '', 'Education for Poor', 'People from family are lack of education. For the Education for Poorprogram is organised.Please part', 'education', '2017-09-15 00:00:00', '2017-09-30 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/4_promo1_1505457211.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/4_promo2_1505457212.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/4_promo3_1505457212.jpg', '', 'San Antonio', 'Upright Corner, City Tower', '78201', 'San Antonio', 'Texas', 'US', 29.4241, -98.4936, 150, 25, '4', '2017-09-15 06:17:43', NULL, 'published', '2017-09-15 06:17:43', '2017-09-15 06:33:32', '2', 1, 1, 0),
(5, '', 'Football League', 'School level Football League Cup will be held in our city. Enjoy the Game.', 'game', '2017-09-18 00:00:00', '2017-10-30 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/5_promo1_1505457442.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/5_promo2_1505457442.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/5_promo3_1505457443.jpg', '', 'San Diego', 'Parera School Ground', '92101', 'San Diego', 'California', 'US', 32.7157, -117.161, 150, 25, '5', '2017-09-15 06:21:30', NULL, 'published', '2017-09-15 06:21:30', '2017-09-15 06:37:23', '1', 1, 1, 0),
(6, '', 'Expo Computers', 'Expo Computers is the program organised for the selling compurter accessories. New technologies will', 'computer', '2017-09-01 00:00:00', '2017-10-15 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/6_promo1_1505478137.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/6_promo2_1505478137.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/6_promo3_1505478137.jpg', '', 'Seattle', 'Parera School Ground', '98101', 'Seattle', 'Washington', 'US', 47.6062, -122.332, 150, 25, '5', '2017-09-15 12:17:23', NULL, 'published', '2017-09-15 12:17:23', '2017-09-25 18:04:41', '1', 322, 322, 0),
(8, '', 'Weekend party', 'Weekend party', '', '2017-09-15 00:00:00', '2017-09-17 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/8_promo1_1505479452.jpeg', '', '', '', 'West High Court Road', 'West High Court Road, Dharampeth, Nagpur - 440001,', '440001', 'Nagpur', 'Maharashtra', 'India', 21.1315, 79.0622, 10000, 50, '8', '2017-09-15 12:44:10', NULL, 'published', '2017-09-15 12:44:10', '2017-09-23 19:02:29', '1', 632.8, 632.8, 0),
(9, '', 'Swine Flu Awareness', 'Health is Wealth. Hence, we must be aware of the newly know disease Swine Flu. ', 'health', '2017-09-01 00:00:00', '2017-10-30 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/9_promo1_1505483358.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/9_promo2_1505483358.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/9_promo3_1505483358.jpg', '', 'Boston', 'GN Hospital, Opera Road', '02203', 'Boston', 'Massachusetts', 'US', 42.3601, -71.0589, 100, 20, '1', '2017-09-15 13:47:07', NULL, 'published', '2017-09-15 13:47:07', '2017-09-15 13:49:18', '2', 1, 1, 0),
(10, '', 'Saturday night ', 'Great way to end your week ', '', '2017-09-16 00:00:00', '2017-09-15 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/10_promo1_1505488823.jpeg', '', '', '', 'North Ambazari Road', 'Cafe Coffee Day, North Ambazari Road, Shivaji Naga', '440001', 'Nagpur', 'Maharashtra', 'India', 21.1318, 79.0523, 10000, 50, '9', '2017-09-15 15:20:18', NULL, 'published', '2017-09-15 15:20:18', '2017-09-15 15:20:23', '1', 1, 1, 0),
(11, '', 'House Party', 'We at the house ', '', '2017-09-15 00:00:00', '2017-09-16 00:00:00', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/11_promo1_1506613041.jpeg', '', '', '', 'Sadler Court', '7401 Sadler Court, Sugar Land, TX 77479, United St', '77479', 'Sugar Land', 'Texas', 'United States of America', 29.5913, -95.647, 10000, 50, '10', '2017-09-28 20:20:48', NULL, 'published', '2017-09-15 16:36:55', '2017-09-28 20:20:48', '1', 246, 246, 0),
(12, '', 'Health with Yoga ', 'Health can be kept fit by practising Yoga  daily.', 'health', '2017-09-19 07:00:00', '2017-09-30 08:30:12', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/12_promo1_1505828962.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/12_promo2_1505828963.jpg', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/12_promo3_1505828963.jpg', '', 'Boston', 'Reliance Park, Mountain road', '02203', 'Boston', 'Massachusetts', 'US', 42.3601, -71.0589, 100, 20, '1', '2017-09-19 12:21:51', NULL, 'published', '2017-09-19 12:21:51', '2017-09-19 13:49:23', '2', 25, 25, 0),
(13, '', 'Launch Party', 'Test test', '', '2017-09-21 12:30:48', '2017-09-22 12:30:57', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/13_promo1_1505909916.jpeg', '', '', '', 'Katol Road', 'Katol Road, Seminary Hills, Nagpur - 440001, Mahar', '440001', 'Nagpur', 'Maharashtra', 'India', 21.1745, 79.0562, 10000, 50, '6', '2017-09-25 04:42:33', NULL, 'published', '2017-09-20 12:18:34', '2017-09-25 04:42:33', '1', 26.5, 26.5, 0),
(14, '', 'Samsung', 'Sam', '', '2017-09-20 12:21:28', '2017-09-20 12:21:29', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/14_promo1_1505910123.jpeg', '', '', '', 'Calle 73', 'Parada MIO - Calle 73 entre Carrera 1A8 y 1A7, Cal', '760006', 'Cali', 'Valle del Cauca', 'Colombia', 3.48394, -76.4858, 10000, 50, '6', '2017-09-20 12:22:00', NULL, 'published', '2017-09-20 12:22:00', '2017-09-20 12:22:04', '1', 208, 208, 0),
(15, '', 'Mid week', 'Mid weeek', '', '2017-09-20 12:46:03', '2017-09-20 12:46:05', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/15_promo1_1505911601.jpeg', '', '', '', 'Automotive Square, Ring Road', 'Automotive Square, Ring Road, Seminary Hills, Nagp', '440001', 'Nagpur', 'Maharashtra', 'India', 21.1872, 79.0458, 10000, 50, '6', '2017-09-20 12:46:39', NULL, 'published', '2017-09-20 12:46:39', '2017-09-20 12:46:42', '1', 226, 226, 0),
(16, '', 'Microsoft', 'Microsoft', '', '2017-09-20 14:30:36', '2017-09-21 15:00:45', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/16_promo1_1505915402.jpeg', '', '', '', 'Katol Road', 'Katol Road, Seminary Hills, Nagpur - 440001, Mahar', '440001', 'Nagpur', 'Maharashtra', 'India', 21.1745, 79.0562, 10000, 50, '6', '2017-09-20 13:50:00', NULL, 'published', '2017-09-20 13:50:00', '2017-09-20 13:50:03', '1', 220, 220, 0),
(17, '', 'Health with Yoga 2222', 'Health can be kept fit by practising Yoga  daily.', 'health', '2017-09-20 14:00:28', '2017-09-20 15:00:28', '1', '0', NULL, NULL, NULL, '', 'Boston', 'Reliance Park, Mountain road', '02203', 'Boston', 'Massachusetts', 'US', 42.3601, -71.0589, 100, 20, '1', '2017-09-21 04:55:26', NULL, 'published', '2017-09-21 04:55:26', '2017-09-28 07:29:51', '2', 49, 49, 1),
(18, '', 'Weekend party 23rd', 'Party at v51', '', '2017-09-29 15:30:03', '2017-09-30 06:10:19', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/18_promo1_1506319842.jpeg', '', '', '', 'Katol Road', 'Katol Road, Seminary Hills, Nagpur - 440001, Mahar', '440001', 'Nagpur', 'Maharashtra', 'India', 21.1745, 79.0562, 10000, 50, '6', '2017-09-25 06:10:40', NULL, 'published', '2017-09-23 16:46:22', '2017-09-26 18:10:11', '1', 3924, 3924, 0),
(19, '', 'Tempo Launch Party', 'Help us Launch the app with a bang', '', '2017-09-28 21:09:12', '2017-09-29 04:09:17', '1', '0', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/19_promo1_1506632985.jpeg', '', '', '', 'South Main Street', 'City of Stafford City Hall, 2610 South Main Street', '77477', 'Stafford', 'Texas', 'United States of America', 29.6152, -95.5553, 10000, 50, '10', '2017-09-28 21:09:45', NULL, 'published', '2017-09-28 21:09:45', '2017-09-28 21:09:46', '1', 145, 145, 0),
(20, '', 'Test ', 'Test test ', '', '2017-09-28 21:31:40', '2017-09-28 23:31:44', '1', '1', 'https://s3-us-west-2.amazonaws.com/tempoevent/promotionalimages/20_promo1_1506634352.jpeg', '', '', '', 'Chatham Avenue', 'Chatham Avenue, Sugar Land, TX 77479, United State', '77479', 'Sugar Land', 'Texas', 'United States of America', 29.6032, -95.6477, 10000, 50, '10', '2017-09-28 21:32:31', NULL, 'published', '2017-09-28 21:32:31', '2017-09-28 21:32:33', '1', 163, 163, 0),
(21, '', 'Global Warming Awareness', 'Increasing Global Warming is dangerous for Nature.Global Warming Awareness program is organised. Please join us. ', 'health', '2017-10-01 09:00:28', '2017-10-15 15:00:28', '1', '0', NULL, NULL, NULL, '', 'Boston', 'Reliance Park, Mountain road', '02203', 'Cali', 'Valle del Cauca', 'Colombia', 3.48394, -76.4858, 50000, 200, '1', '2017-09-29 05:45:06', NULL, 'published', '2017-09-29 05:45:06', '2017-09-29 11:15:06', '2', 49, 49, 0);

-- --------------------------------------------------------

--
-- Table structure for table `event_association`
--

CREATE TABLE `event_association` (
  `event_association_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `associated_userid` int(11) DEFAULT NULL,
  `association_type` varchar(20) DEFAULT NULL,
  `association_datetime` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_association`
--

INSERT INTO `event_association` (`event_association_id`, `event_id`, `associated_userid`, `association_type`, `association_datetime`, `created_at`, `updated_at`) VALUES
(1, 4, 3, NULL, '2017-05-12 00:00:00', '2017-09-23 07:03:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_comments`
--

CREATE TABLE `event_comments` (
  `event_comment_id` int(11) NOT NULL,
  `event_post_id` int(11) DEFAULT NULL,
  `comment_type` varchar(20) DEFAULT NULL,
  `comment_data` text,
  `commented_by` int(11) DEFAULT NULL,
  `comment_status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_comments`
--

INSERT INTO `event_comments` (`event_comment_id`, `event_post_id`, `comment_type`, `comment_data`, `commented_by`, `comment_status`, `created_at`, `updated_at`) VALUES
(1, 1, '', 'Good One', 5, '', '2017-09-18 11:33:06', '2017-09-18 11:33:06'),
(2, 1, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/comment_post/1_1505738154.', 5, '', '2017-09-18 12:35:54', '2017-09-18 12:35:54'),
(3, 1, '', 'Good One', 5, '', '2017-09-19 10:03:12', '2017-09-19 10:03:12'),
(4, 1, '', 'Good One', 5, '', '2017-09-19 10:05:17', '2017-09-19 10:05:17'),
(5, 1, '', 'Good One', 5, '', '2017-09-19 10:05:25', '2017-09-19 10:05:25'),
(6, 1, '', 'Good One', 5, '', '2017-09-19 10:05:52', '2017-09-19 10:05:52'),
(7, 5, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/comment_post/5_1505833783.jpg', 4, '', '2017-09-19 15:09:43', '2017-09-19 15:09:43'),
(8, 0, '', 'Test', 6, '', '2017-09-21 13:01:27', '2017-09-21 13:01:27'),
(9, 17, '', 'Abc', 6, '', '2017-09-21 13:12:21', '2017-09-21 13:12:21'),
(10, 17, '', 'Jennifer here', 8, '', '2017-09-22 09:40:30', '2017-09-22 09:40:30'),
(11, 17, '', 'Hello how are you today, its a nice capture', 6, '', '2017-09-22 09:48:31', '2017-09-22 09:48:31'),
(12, 17, '', 'Hi', 6, '', '2017-09-22 09:48:52', '2017-09-22 09:48:52'),
(13, 17, '', 'Hello', 6, '', '2017-09-22 09:49:02', '2017-09-22 09:49:02'),
(14, 18, '', 'Hi', 6, '', '2017-09-22 09:53:59', '2017-09-22 09:53:59'),
(23, 18, '', 'Hi', 7, '', '2017-09-22 12:19:51', '2017-09-22 12:19:51'),
(16, 17, '', 'Hi', 1, '', '2017-09-22 12:11:09', '2017-09-22 12:11:09'),
(17, 17, '', 'Hi', 2, '', '2017-09-22 12:11:12', '2017-09-22 12:11:12'),
(18, 17, '', 'Hi', 3, '', '2017-09-22 12:11:14', '2017-09-22 12:11:14'),
(19, 17, '', 'Hi', 4, '', '2017-09-22 12:11:17', '2017-09-22 12:11:17'),
(20, 17, '', 'Hi', 5, '', '2017-09-22 12:11:22', '2017-09-22 12:11:22'),
(21, 17, '', 'Hi', 6, '', '2017-09-22 12:11:25', '2017-09-22 12:11:25'),
(22, 17, '', 'Hi', 7, '', '2017-09-22 12:11:30', '2017-09-22 12:11:30'),
(24, 18, '', 'Hi', 1, '', '2017-09-22 12:20:09', '2017-09-22 12:20:09'),
(25, 17, '', 'Hello', 6, '', '2017-09-23 17:17:23', '2017-09-23 17:17:23'),
(26, 26, '', 'Hi', 8, '', '2017-09-23 19:01:30', '2017-09-23 19:01:30'),
(27, 17, '', 'Hi', 8, '', '2017-09-23 19:02:29', '2017-09-23 19:02:29'),
(28, 17, '', 'Hi', 37, '', '2017-09-25 09:24:04', '2017-09-25 09:24:04'),
(29, 22, '', 'Hello ', 29, '', '2017-09-25 09:30:50', '2017-09-25 09:30:50'),
(30, 22, '', 'Çßzcscsdsdxdsf', 6, '', '2017-09-25 09:42:03', '2017-09-25 09:42:03'),
(31, 22, '', 'Hello Nail', 6, '', '2017-09-25 09:57:39', '2017-09-25 09:57:39'),
(32, 22, '', 'Hi', 6, '', '2017-09-25 09:57:48', '2017-09-25 09:57:48'),
(33, 22, '', 'Hi', 6, '', '2017-09-25 09:58:07', '2017-09-25 09:58:07'),
(34, 22, '', 'Hi ', 6, '', '2017-09-25 09:58:17', '2017-09-25 09:58:17'),
(35, 22, '', 'Hello', 6, '', '2017-09-25 10:05:32', '2017-09-25 10:05:32'),
(36, 22, '', 'Are you interested in upcoming event? Plz let me know', 29, '', '2017-09-25 10:13:44', '2017-09-25 10:13:44'),
(37, 22, '', 'Hi', 29, '', '2017-09-25 10:36:06', '2017-09-25 10:36:06'),
(38, 22, '', 'Hello how are you ?', 37, '', '2017-09-25 10:40:13', '2017-09-25 10:40:13'),
(39, 22, '', 'Hello', 37, '', '2017-09-25 10:40:33', '2017-09-25 10:40:33'),
(40, 22, '', 'Hi', 37, '', '2017-09-25 10:40:47', '2017-09-25 10:40:47'),
(41, 3, '', 'Hi', 6, '', '2017-09-25 18:04:41', '2017-09-25 18:04:41'),
(42, 15, '', 'Great ', 10, '', '2017-09-26 04:17:39', '2017-09-26 04:17:39'),
(43, 22, '', 'Gh', 10, '', '2017-09-26 04:17:56', '2017-09-26 04:17:56'),
(44, 22, '', 'Ghhf', 10, '', '2017-09-26 04:18:06', '2017-09-26 04:18:06'),
(45, 22, '', 'Hi', 36, '', '2017-09-26 06:41:23', '2017-09-26 06:41:23'),
(46, 22, '', 'Hi Kim', 36, '', '2017-09-26 06:41:58', '2017-09-26 06:41:58'),
(47, 47, '', 'Hi', 6, '', '2017-09-26 16:07:05', '2017-09-26 16:07:05'),
(48, 61, '', 'Hi', 6, '', '2017-09-26 18:10:11', '2017-09-26 18:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `event_co_host`
--

CREATE TABLE `event_co_host` (
  `event_co_host_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `co_host_user_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_co_host`
--

INSERT INTO `event_co_host` (`event_co_host_id`, `event_id`, `co_host_user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 13, 1, 'Invited', '2017-09-20 12:18:37', '2017-09-20 12:18:37'),
(2, 13, 2, 'Invited', '2017-09-20 12:18:37', '2017-09-20 12:18:37'),
(3, 14, 1, 'Invited', '2017-09-20 12:22:03', '2017-09-20 12:22:03'),
(4, 14, 2, 'Invited', '2017-09-20 12:22:03', '2017-09-20 12:22:03'),
(5, 15, 1, 'Invited', '2017-09-20 12:46:41', '2017-09-20 12:46:41'),
(6, 0, 1, 'Invited', '2017-09-21 10:37:10', '2017-09-21 10:37:10'),
(7, 0, 3, 'Invited', '2017-09-21 10:37:10', '2017-09-21 10:37:10'),
(8, 0, 4, 'Invited', '2017-09-21 10:37:10', '2017-09-21 10:37:10'),
(9, 0, 5, 'Invited', '2017-09-21 10:37:10', '2017-09-21 10:37:10'),
(10, 0, 1, 'Invited', '2017-09-21 11:20:01', '2017-09-21 11:20:01'),
(11, 0, 3, 'Invited', '2017-09-21 11:20:01', '2017-09-21 11:20:01'),
(12, 0, 4, 'Invited', '2017-09-21 11:20:01', '2017-09-21 11:20:01'),
(13, 0, 5, 'Invited', '2017-09-21 11:20:01', '2017-09-21 11:20:01'),
(14, 0, 8, 'Invited', '2017-09-21 11:20:01', '2017-09-21 11:20:01'),
(15, 0, 10, 'Invited', '2017-09-21 11:20:01', '2017-09-21 11:20:01'),
(16, 0, 27, 'Invited', '2017-09-21 11:20:01', '2017-09-21 11:20:01'),
(17, 0, 29, 'Invited', '2017-09-21 11:20:01', '2017-09-21 11:20:01'),
(18, 1, 8, 'Accepted', '2017-09-21 14:25:26', '2017-09-21 14:28:09'),
(19, 16, 1, 'Invited', '2017-09-21 14:42:49', '2017-09-21 14:42:49'),
(20, 16, 3, 'Invited', '2017-09-21 14:42:50', '2017-09-21 14:42:50'),
(21, 16, 4, 'Invited', '2017-09-21 14:42:50', '2017-09-21 14:42:50'),
(22, 16, 5, 'Invited', '2017-09-21 14:42:50', '2017-09-21 14:42:50'),
(23, 16, 10, 'Invited', '2017-09-21 14:42:50', '2017-09-21 14:42:50'),
(24, 16, 8, 'Accepted', '2017-09-21 14:42:50', '2017-09-23 17:11:10'),
(25, 16, 27, 'Invited', '2017-09-21 14:42:50', '2017-09-21 14:42:50'),
(26, 16, 29, 'Accepted', '2017-09-21 14:42:50', '2017-09-21 14:47:32'),
(27, 18, 8, 'Accepted', '2017-09-23 16:46:27', '2017-09-23 17:10:57'),
(28, 18, 10, 'Invited', '2017-09-23 16:46:27', '2017-09-23 16:46:27'),
(29, 18, 27, 'Invited', '2017-09-23 16:46:27', '2017-09-23 16:46:27'),
(30, 18, 29, 'Invited', '2017-09-23 16:46:27', '2017-09-23 16:46:27'),
(31, 0, 5, 'Invited', '2017-09-25 05:00:35', '2017-09-25 05:00:35'),
(32, 0, 8, 'Invited', '2017-09-25 05:00:35', '2017-09-25 05:00:35'),
(33, 0, 10, 'Invited', '2017-09-25 05:00:35', '2017-09-25 05:00:35'),
(34, 0, 27, 'Invited', '2017-09-25 05:00:35', '2017-09-25 05:00:35'),
(35, 0, 29, 'Invited', '2017-09-25 05:00:35', '2017-09-25 05:00:35'),
(36, 0, 8, 'Invited', '2017-09-25 05:03:12', '2017-09-25 05:03:12'),
(37, 0, 5, 'Invited', '2017-09-25 05:11:34', '2017-09-25 05:11:34'),
(38, 0, 8, 'Invited', '2017-09-25 05:11:34', '2017-09-25 05:11:34'),
(39, 0, 10, 'Invited', '2017-09-25 05:11:34', '2017-09-25 05:11:34'),
(40, 0, 27, 'Invited', '2017-09-25 05:11:35', '2017-09-25 05:11:35'),
(41, 0, 29, 'Invited', '2017-09-25 05:11:35', '2017-09-25 05:11:35'),
(42, 18, 4, 'Invited', '2017-09-25 05:54:04', '2017-09-25 05:54:04'),
(43, 18, 5, 'Invited', '2017-09-25 05:54:05', '2017-09-25 05:54:05'),
(44, 2, 4, 'Accepted', '2017-09-28 13:02:46', '2017-09-28 13:06:51'),
(45, 21, 3, 'Accepted', '2017-09-29 11:55:52', '2017-09-29 11:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `event_invitations`
--

CREATE TABLE `event_invitations` (
  `event_invitation_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `invited_to_userid` int(11) DEFAULT NULL,
  `invited_from_userid` int(11) DEFAULT NULL,
  `invitation_status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_invitations`
--

INSERT INTO `event_invitations` (`event_invitation_id`, `event_id`, `invited_to_userid`, `invited_from_userid`, `invitation_status`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 1, 'Invited', '2017-09-15 09:49:24', '2017-09-15 09:49:24'),
(2, 6, 6, 5, 'Accepted', '2017-09-15 12:26:24', '2017-09-15 12:27:10'),
(4, 1, 4, 1, 'Accepted', '2017-09-19 14:30:07', '2017-09-19 15:05:29'),
(5, 14, 1, 6, 'Invited', '2017-09-20 12:22:04', NULL),
(6, 14, 2, 6, 'Invited', '2017-09-20 12:22:04', NULL),
(7, 15, 1, 6, 'Invited', '2017-09-20 12:46:42', NULL),
(8, 15, 2, 6, 'Invited', '2017-09-20 12:46:42', NULL),
(9, 16, 0, 6, 'Invited', '2017-09-20 13:50:03', NULL),
(10, 2, 6, 2, 'Accepted', '2017-09-21 09:11:57', '2017-09-21 09:15:28'),
(11, 0, 1, 6, 'Invited', '2017-09-21 10:37:11', NULL),
(12, 0, 3, 6, 'Invited', '2017-09-21 10:37:11', NULL),
(13, 0, 4, 6, 'Invited', '2017-09-21 10:37:11', NULL),
(14, 0, 0, 6, 'Invited', '2017-09-21 10:42:20', NULL),
(15, 0, 0, 6, 'Invited', '2017-09-21 10:42:31', NULL),
(16, 0, 0, 6, 'Invited', '2017-09-21 10:43:15', NULL),
(17, 0, 0, 6, 'Invited', '2017-09-21 10:43:49', NULL),
(18, 0, 1, 6, 'Invited', '2017-09-21 11:20:02', NULL),
(19, 0, 3, 6, 'Invited', '2017-09-21 11:20:02', NULL),
(20, 0, 4, 6, 'Invited', '2017-09-21 11:20:02', NULL),
(21, 0, 5, 6, 'Invited', '2017-09-21 11:20:02', NULL),
(22, 0, 8, 6, 'Invited', '2017-09-21 11:20:02', NULL),
(23, 0, 10, 6, 'Invited', '2017-09-21 11:20:02', NULL),
(24, 0, 27, 6, 'Invited', '2017-09-21 11:20:02', NULL),
(25, 0, 29, 6, 'Invited', '2017-09-21 11:20:02', NULL),
(26, 0, 0, 6, 'Invited', '2017-09-21 12:10:16', NULL),
(27, 18, 8, 6, 'Accepted', '2017-09-23 16:46:27', '2017-09-23 17:10:55'),
(28, 18, 10, 6, 'Invited', '2017-09-23 16:46:27', NULL),
(29, 18, 27, 6, 'Invited', '2017-09-23 16:46:27', NULL),
(30, 18, 29, 6, 'Invited', '2017-09-23 16:46:27', NULL),
(31, 0, 0, 6, 'Invited', '2017-09-25 04:42:34', NULL),
(32, 0, 8, 6, 'Invited', '2017-09-25 05:00:35', NULL),
(33, 0, 10, 6, 'Invited', '2017-09-25 05:00:35', NULL),
(34, 0, 27, 6, 'Invited', '2017-09-25 05:00:35', NULL),
(35, 0, 29, 6, 'Invited', '2017-09-25 05:00:35', NULL),
(36, 0, 8, 6, 'Invited', '2017-09-25 05:03:13', NULL),
(37, 0, 8, 6, 'Invited', '2017-09-25 05:11:35', NULL),
(38, 0, 10, 6, 'Invited', '2017-09-25 05:11:35', NULL),
(39, 0, 27, 6, 'Invited', '2017-09-25 05:11:35', NULL),
(40, 0, 29, 6, 'Invited', '2017-09-25 05:11:35', NULL),
(41, 0, 4, 6, 'Invited', '2017-09-25 05:14:48', NULL),
(42, 0, 5, 6, 'Invited', '2017-09-25 05:14:48', NULL),
(43, 0, 8, 6, 'Invited', '2017-09-25 05:14:48', NULL),
(44, 0, 10, 6, 'Invited', '2017-09-25 05:14:48', NULL),
(45, 0, 27, 6, 'Invited', '2017-09-25 05:14:48', NULL),
(46, 0, 29, 6, 'Invited', '2017-09-25 05:14:48', NULL),
(47, 18, 5, 6, 'Invited', '2017-09-25 05:54:05', NULL),
(48, 0, 0, 10, 'Invited', '2017-09-25 20:06:10', NULL),
(49, 11, 0, 10, 'Invited', '2017-09-28 15:37:22', NULL),
(50, 19, 0, 10, 'Invited', '2017-09-28 21:09:46', NULL),
(51, 20, 0, 10, 'Invited', '2017-09-28 21:32:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_media_like`
--

CREATE TABLE `event_media_like` (
  `event_media_like_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_post_id` int(11) DEFAULT NULL,
  `islike` int(11) DEFAULT NULL COMMENT '1-Like; 0-Unlike',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_media_like`
--

INSERT INTO `event_media_like` (`event_media_like_id`, `event_id`, `user_id`, `event_post_id`, `islike`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 1, 0, '2017-09-16 12:13:09', NULL),
(2, 5, 2, 1, 1, '2017-09-16 12:14:06', NULL),
(3, 5, 2, 2, 0, '2017-09-18 13:06:40', NULL),
(4, 2, 2, 16, 1, '2017-09-21 09:24:51', NULL),
(5, 2, 4, 16, 1, '2017-09-21 09:24:59', NULL),
(6, 8, 6, 17, 1, '2017-09-22 09:20:14', NULL),
(7, 8, 6, 18, 1, '2017-09-23 17:17:31', NULL),
(8, 18, 8, 22, 0, '2017-09-23 19:01:21', NULL),
(9, 8, 8, 17, 1, '2017-09-23 19:02:40', NULL),
(10, 8, 8, 18, 1, '2017-09-23 19:02:43', NULL),
(11, 18, 29, 22, 1, '2017-09-25 09:31:16', NULL),
(12, 18, 36, 26, 0, '2017-09-25 11:24:32', NULL),
(13, 2, 6, 16, 0, '2017-09-25 11:33:05', NULL),
(14, 18, 36, 22, 1, '2017-09-25 11:39:32', NULL),
(15, 18, 33, 22, 1, '2017-09-25 11:50:12', NULL),
(16, 18, 6, 22, 1, '2017-09-25 12:17:02', NULL),
(17, 18, 6, 26, 0, '2017-09-25 12:17:12', NULL),
(18, 11, 10, 6, 1, '2017-09-25 17:54:33', NULL),
(19, 11, 10, 7, 1, '2017-09-25 17:54:37', NULL),
(20, 11, 10, 10, 1, '2017-09-25 17:54:53', NULL),
(21, 18, 10, 22, 1, '2017-09-25 17:55:12', NULL),
(22, 18, 10, 26, 0, '2017-09-25 23:48:32', NULL),
(23, 11, 10, 8, 1, '2017-09-26 04:17:23', NULL),
(24, 11, 10, 9, 1, '2017-09-26 04:17:26', NULL),
(25, 11, 10, 15, 1, '2017-09-26 04:17:29', NULL),
(26, 18, 36, 75, 1, '2017-09-26 06:42:42', NULL),
(27, 18, 36, 68, 1, '2017-09-26 06:42:45', NULL),
(28, 18, 36, 61, 1, '2017-09-26 06:42:46', NULL),
(29, 18, 36, 54, 1, '2017-09-26 11:44:09', NULL),
(30, 18, 6, 75, 1, '2017-09-26 18:06:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_posts`
--

CREATE TABLE `event_posts` (
  `event_post_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `post_type` varchar(20) DEFAULT NULL,
  `post_data` text,
  `post_by` int(11) DEFAULT NULL,
  `post_status` varchar(20) DEFAULT NULL,
  `like_count` int(11) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isdeleted` int(11) DEFAULT '0' COMMENT '0-Not Deleted; 1-Deleted'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_posts`
--

INSERT INTO `event_posts` (`event_post_id`, `event_id`, `post_type`, `post_data`, `post_by`, `post_status`, `like_count`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 5, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/1.jpg', 6, '', 1, '2017-09-15 10:21:37', '2017-10-10 12:50:43', 0),
(2, 5, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/2.mp4', 6, '', 0, '2017-09-15 12:07:16', '2017-09-19 13:48:37', 0),
(3, 6, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/3.mov', 6, '', 0, '2017-09-15 15:11:13', '2017-09-15 15:11:13', 0),
(4, 10, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/4.jpeg', 9, '', 0, '2017-09-15 15:21:50', '2017-09-15 15:21:50', 0),
(5, 10, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/5.mov', 9, '', 0, '2017-09-15 15:23:59', '2017-09-15 15:23:59', 0),
(6, 11, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/6.jpeg', 10, '', 1, '2017-09-15 16:55:07', '2017-09-25 23:28:49', 0),
(7, 11, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/7.jpeg', 10, '', 1, '2017-09-15 16:57:02', '2017-09-25 17:54:37', 0),
(8, 11, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/8.mov', 10, '', 1, '2017-09-16 03:23:33', '2017-09-26 04:17:23', 0),
(11, 0, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/11.jpg', 3, '', 0, '2017-09-19 13:51:04', '2017-09-19 13:51:04', 0),
(9, 11, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/9.jpeg', 10, '', 1, '2017-09-18 17:28:55', '2017-09-26 04:17:26', 0),
(10, 11, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/10.mov', 10, '', 1, '2017-09-18 19:41:26', '2017-09-25 17:54:53', 0),
(12, 0, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/12.jpg', 3, '', 0, '2017-09-19 13:55:33', '2017-09-19 13:55:33', 0),
(13, 0, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/13.jpg', 3, '', 0, '2017-09-19 13:57:24', '2017-09-19 13:57:24', 0),
(14, 12, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/14.jpg', 3, '', 0, '2017-09-19 13:58:00', '2017-09-19 13:58:00', 0),
(15, 11, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/15.jpeg', 10, '', 1, '2017-09-19 17:48:37', '2017-09-26 04:17:29', 0),
(16, 2, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/16.jpg', 6, '', 2, '2017-09-21 09:18:23', '2017-09-25 12:42:44', 0),
(17, 8, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/17.jpeg', 8, '', 2, '2017-09-21 12:14:05', '2017-09-23 19:02:40', 0),
(18, 8, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/18.jpeg', 8, '', 2, '2017-09-21 12:48:26', '2017-09-23 19:02:43', 0),
(19, 1, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/19.jpeg', 8, '', 0, '2017-09-23 17:15:52', '2017-09-23 17:15:52', 0),
(20, 8, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/20.jpeg', 8, '', 0, '2017-09-23 17:15:52', '2017-09-23 17:15:52', 0),
(21, 16, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/20.jpeg', 8, '', 0, '2017-09-23 17:15:52', '2017-09-23 17:15:52', 0),
(22, 18, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/20.jpeg', 8, '', 5, '2017-09-23 17:15:52', '2017-09-25 17:55:12', 0),
(23, 1, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/20.mov', 8, '', 0, '2017-09-23 19:00:18', '2017-09-23 19:00:18', 0),
(24, 8, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/24.mov', 8, '', 0, '2017-09-23 19:00:18', '2017-09-23 19:00:18', 0),
(25, 16, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/25.mov', 8, '', 0, '2017-09-23 19:00:18', '2017-09-23 19:00:18', 0),
(26, 18, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/26.mov', 8, '', 0, '2017-09-23 19:00:18', '2017-09-25 23:48:33', 0),
(27, 2, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/27.jpeg', 6, '', 0, '2017-09-26 04:40:32', '2017-09-26 04:40:32', 0),
(28, 6, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/28.jpeg', 6, '', 0, '2017-09-26 04:40:32', '2017-09-26 04:40:32', 0),
(29, 13, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/28.jpeg', 6, '', 0, '2017-09-26 04:40:33', '2017-09-26 04:40:33', 0),
(30, 14, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/30.jpeg', 6, '', 0, '2017-09-26 04:40:33', '2017-09-26 04:40:33', 0),
(31, 15, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/31.jpeg', 6, '', 0, '2017-09-26 04:40:33', '2017-09-26 04:40:33', 0),
(32, 16, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/32.jpeg', 6, '', 0, '2017-09-26 04:40:33', '2017-09-26 04:40:33', 0),
(33, 18, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/33.jpeg', 6, '', 0, '2017-09-26 04:40:33', '2017-09-26 04:40:33', 0),
(34, 2, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/34.jpeg', 6, '', 0, '2017-09-26 04:41:05', '2017-09-26 04:41:05', 0),
(35, 6, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/35.jpeg', 6, '', 0, '2017-09-26 04:41:05', '2017-09-26 04:41:05', 0),
(36, 13, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/35.jpeg', 6, '', 0, '2017-09-26 04:41:05', '2017-09-26 04:41:05', 0),
(37, 14, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/35.jpeg', 6, '', 0, '2017-09-26 04:41:06', '2017-09-26 04:41:06', 0),
(38, 15, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/38.jpeg', 6, '', 0, '2017-09-26 04:41:06', '2017-09-26 04:41:06', 0),
(39, 16, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/39.jpeg', 6, '', 0, '2017-09-26 04:41:06', '2017-09-26 04:41:06', 0),
(40, 18, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/40.jpeg', 6, '', 0, '2017-09-26 04:41:06', '2017-09-26 04:41:06', 0),
(41, 2, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/41.mov', 6, '', 0, '2017-09-26 04:42:23', '2017-09-26 04:42:23', 0),
(42, 6, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/42.mov', 6, '', 0, '2017-09-26 04:42:23', '2017-09-26 04:42:23', 0),
(43, 13, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/42.mov', 6, '', 0, '2017-09-26 04:42:23', '2017-09-26 04:42:23', 0),
(44, 14, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/42.mov', 6, '', 0, '2017-09-26 04:42:24', '2017-09-26 04:42:24', 0),
(45, 15, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/45.mov', 6, '', 0, '2017-09-26 04:42:24', '2017-09-26 04:42:24', 0),
(46, 16, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/46.mov', 6, '', 0, '2017-09-26 04:42:24', '2017-09-26 04:42:24', 0),
(47, 18, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/47.mov', 6, '', 0, '2017-09-26 04:42:24', '2017-09-26 04:42:24', 0),
(48, 2, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/48.jpeg', 6, '', 0, '2017-09-26 05:04:15', '2017-09-26 05:04:15', 0),
(49, 6, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/49.jpeg', 6, '', 0, '2017-09-26 05:04:15', '2017-09-26 05:04:15', 0),
(50, 13, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/49.jpeg', 6, '', 0, '2017-09-26 05:04:15', '2017-09-26 05:04:15', 0),
(51, 14, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/49.jpeg', 6, '', 0, '2017-09-26 05:04:15', '2017-09-26 05:04:15', 0),
(52, 15, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/49.jpeg', 6, '', 0, '2017-09-26 05:04:16', '2017-09-26 05:04:16', 0),
(53, 16, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/53.jpeg', 6, '', 0, '2017-09-26 05:04:16', '2017-09-26 05:04:16', 0),
(54, 18, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/54.jpeg', 6, '', 1, '2017-09-26 05:04:16', '2017-09-26 11:44:09', 0),
(55, 2, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/55.jpeg', 6, '', 0, '2017-09-26 05:04:47', '2017-09-26 05:04:47', 0),
(56, 6, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/56.jpeg', 6, '', 0, '2017-09-26 05:04:47', '2017-09-26 05:04:47', 0),
(57, 13, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/56.jpeg', 6, '', 0, '2017-09-26 05:04:47', '2017-09-26 05:04:47', 0),
(58, 14, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/56.jpeg', 6, '', 0, '2017-09-26 05:04:48', '2017-09-26 05:04:48', 0),
(59, 15, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/59.jpeg', 6, '', 0, '2017-09-26 05:04:48', '2017-09-26 05:04:48', 0),
(60, 16, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/60.jpeg', 6, '', 0, '2017-09-26 05:04:48', '2017-09-26 05:04:48', 0),
(61, 18, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/61.jpeg', 6, '', 1, '2017-09-26 05:04:48', '2017-09-26 06:42:46', 0),
(62, 2, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/62.jpeg', 6, '', 0, '2017-09-26 05:05:21', '2017-09-26 05:05:21', 0),
(63, 6, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/63.jpeg', 6, '', 0, '2017-09-26 05:05:21', '2017-09-26 05:05:21', 0),
(64, 13, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/63.jpeg', 6, '', 0, '2017-09-26 05:05:21', '2017-09-26 05:05:21', 0),
(65, 14, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/63.jpeg', 6, '', 0, '2017-09-26 05:05:21', '2017-09-26 05:05:21', 0),
(66, 15, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/63.jpeg', 6, '', 0, '2017-09-26 05:05:22', '2017-09-26 05:05:22', 0),
(67, 16, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/67.jpeg', 6, '', 0, '2017-09-26 05:05:23', '2017-09-26 05:05:23', 0),
(68, 18, 'image', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/68.jpeg', 6, '', 1, '2017-09-26 05:05:23', '2017-09-26 06:42:45', 0),
(69, 2, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/68.mov', 6, '', 0, '2017-09-26 05:58:17', '2017-09-26 05:58:17', 0),
(70, 6, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/70.mov', 6, '', 0, '2017-09-26 05:58:17', '2017-09-26 05:58:17', 0),
(71, 13, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/71.mov', 6, '', 0, '2017-09-26 05:58:17', '2017-09-26 05:58:17', 0),
(72, 14, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/72.mov', 6, '', 0, '2017-09-26 05:58:17', '2017-09-26 05:58:17', 0),
(73, 15, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/73.mov', 6, '', 0, '2017-09-26 05:58:17', '2017-09-26 05:58:17', 0),
(74, 16, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/74.mov', 6, '', 0, '2017-09-26 05:58:17', '2017-09-26 05:58:17', 0),
(75, 18, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/75.mov', 6, '', 2, '2017-09-26 05:58:17', '2017-09-26 18:06:48', 0),
(76, 18, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/event_post/76.mov', 8, '', 0, '2017-09-26 07:47:52', '2017-09-26 07:47:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `event_posts_to`
--

CREATE TABLE `event_posts_to` (
  `event_post_to_id` int(11) NOT NULL,
  `event_post_id` int(11) DEFAULT NULL,
  `posted_to` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userpost_comments`
--

CREATE TABLE `userpost_comments` (
  `userpost_comment_id` int(11) NOT NULL,
  `user_post_id` int(11) DEFAULT NULL,
  `comment_type` varchar(20) DEFAULT NULL,
  `comment_data` text,
  `commented_by` int(11) DEFAULT NULL,
  `comment_status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userpost_comments`
--

INSERT INTO `userpost_comments` (`userpost_comment_id`, `user_post_id`, `comment_type`, `comment_data`, `commented_by`, `comment_status`, `created_at`, `updated_at`) VALUES
(1, 2, '', 'Good One...!!!', 3, 'unread', '2017-09-21 07:18:20', '2017-09-21 07:18:20'),
(2, 2, '', 'Good One', 2, '', '2017-09-25 10:50:13', NULL),
(3, 14, '', 'Good One', 37, '', '2017-09-25 10:51:04', NULL),
(4, 21, '', 'Hi', 36, '', '2017-09-26 07:14:49', NULL),
(5, 21, '', 'Hello', 36, '', '2017-09-26 07:21:49', NULL),
(6, 10, '', 'Hi', 36, '', '2017-09-26 11:42:45', NULL),
(7, 15, '', 'Hi kenny', 6, '', '2017-09-26 11:48:02', NULL),
(8, 15, '', 'Hi', 36, '', '2017-09-26 11:58:26', NULL),
(9, 22, '', 'Hi EF', 36, '', '2017-09-27 08:58:30', NULL),
(10, 22, '', 'Hello', 36, '', '2017-09-27 08:58:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userpost_media_like`
--

CREATE TABLE `userpost_media_like` (
  `userpost_media_like_id` int(11) NOT NULL,
  `user_post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `islike` int(11) DEFAULT NULL COMMENT '0-Unlike; 1-Like',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userpost_media_like`
--

INSERT INTO `userpost_media_like` (`userpost_media_like_id`, `user_post_id`, `user_id`, `islike`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, '2017-09-20 12:32:26', NULL),
(2, 1, 4, 1, '2017-09-20 12:34:03', NULL),
(3, 1, 5, 0, '2017-09-20 12:34:11', NULL),
(4, 11, 36, 1, '2017-09-25 11:31:29', NULL),
(5, 11, 33, 1, '2017-09-25 11:40:37', NULL),
(6, 21, 36, 1, '2017-09-26 06:53:14', NULL),
(7, 20, 36, 1, '2017-09-26 06:56:29', NULL),
(8, 10, 36, 1, '2017-09-26 11:42:28', NULL),
(9, 19, 36, 1, '2017-09-26 11:45:28', NULL),
(10, 22, 36, 1, '2017-09-27 08:58:37', NULL),
(11, 3, 10, 1, '2017-09-28 20:21:18', NULL),
(12, 7, 10, 1, '2017-09-28 20:21:53', NULL),
(13, 1, 1, 0, '2017-10-10 07:21:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(50) DEFAULT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `user_dob` date DEFAULT NULL,
  `user_gender` varchar(10) DEFAULT NULL,
  `user_address` varchar(50) DEFAULT NULL,
  `user_city` varchar(50) DEFAULT NULL,
  `user_state` varchar(50) DEFAULT NULL,
  `user_zipcode` varchar(50) DEFAULT NULL,
  `user_country` varchar(50) DEFAULT NULL,
  `user_avatar` varchar(200) DEFAULT NULL,
  `user_unique_id` varchar(150) DEFAULT NULL,
  `user_created_from` varchar(50) DEFAULT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `account_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_usage` datetime DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_datetime` datetime DEFAULT NULL,
  `tempo_user_rank` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `first_name`, `last_name`, `user_password`, `display_name`, `user_email`, `user_phone`, `user_dob`, `user_gender`, `user_address`, `user_city`, `user_state`, `user_zipcode`, `user_country`, `user_avatar`, `user_unique_id`, `user_created_from`, `user_type`, `account_type`, `created_at`, `updated_at`, `last_usage`, `otp`, `otp_datetime`, `tempo_user_rank`) VALUES
(1, 'john', 'John', 'Krutela', 'YAB64qlLuVnv3s+vMXc=', 'John', 'john@gmail.com', '213123123', '1999-09-13', 'M', 'St. Perter Road', 'Los Angeles', 'California', '90068', 'USA', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/1_download.jpg', 't-1f9d9a9efc2f523b2f09629444632b5c', '', 'tempo', '', '2017-09-15 05:36:03', '2017-09-29 06:20:56', '2017-09-15 05:36:03', '467472', '2017-09-15 05:36:03', 1.5),
(2, 'mark', 'Mark', 'Syela', 'YAB64qlLuVnv3s+vMXc=', 'Mark Syela', 'mark@gmail.com', '1854352175', '1992-01-14', 'M', 'Plot No. 32- A, Paradise Park avenue', 'Houston', 'Texas', '77045', 'USA', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/2_images.jpg', 't-ac44930a62044d038e16f027629b9206', '', 'tempo', '', '2017-09-15 05:39:40', '2017-09-21 09:11:57', '2017-09-15 05:39:40', '347777', '2017-09-15 05:39:40', 10),
(3, 'ruby', 'Ruby', 'Pelora', 'YAB64qlLuVnv3s+vMXc=', 'Ruby Pelora', 'ruby@gmail.com', '2147483647', '1982-03-01', 'F', 'Plot No. 49- D, Clara Apartment', 'Philadelphia', 'Pennsylvania', '19092', 'USA', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/3_business woman.jpg', 't-e2f32208d96eb2ed7a7f5bd712ca31e0', '', 'tempo', '', '2017-09-15 05:43:16', '2017-10-10 07:25:22', '2017-09-15 05:43:16', '333052', '2017-09-15 05:43:16', 3.11111),
(4, 'george', 'George', 'Richardson', 'YAB64qlLuVnv3s+vMXc=', 'George Richardson', 'george@gmail.com', '54521', '1956-05-12', 'Male', 'qwewe', 'qweqwe', 'asdasd', '123123', 'USA', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/4_Man-PNG-Pic.png', 't-43e5b71c58899d8bb5efde54649536ac', '', 'tempo', '', '2017-09-15 05:46:42', '2017-10-10 07:25:33', '2017-09-15 05:46:42', '755366', '2017-09-15 05:46:42', 0.4),
(5, 'alisa', 'Alisa', 'Donald', 'YAB64qlLuVnv3s+vMXc=', 'Alisa Donald', 'alisa@gmail.com', '2147483647', '1997-05-15', 'F', 'Plot No. - 3, Near Millitry School', 'San Diego', 'California', '92101', 'USA', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/5_1505464244.jpg', 't-90e65cfe2ad01165fdcd7a35f406db70', '', 'tempo', '', '2017-09-15 05:49:48', '2017-09-25 18:04:41', '2017-09-15 05:49:48', '279314', '2017-09-15 05:49:48', 8.5),
(6, 'efrancis', 'Emmanuel', 'Francis', 'XACG01t7u1nfC+MJ', '', 'efrancis@appscelestial.com', '1111111111', '1999-08-26', 'Not specif', '', '', '', '', '', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/6_01505459110.jpeg', 't-a8ae93441cc8ac1a38aeadb399726d66', '', 'tempo', '', '2017-09-15 07:04:18', '2017-09-26 16:07:05', '2017-09-15 07:04:18', '846065', '2017-09-15 07:04:18', 118),
(7, 'johndavid', 'John', '', 'sALndamIu1lQvHw7', '', 'johndavid@appscelestial.com', '1111111112', '1999-09-15', '', '', '', '', '', '', 'default.png', 't-f4451b0b576782c627ee71ff981456c8', '', 'tempo', '', '2017-09-15 08:01:10', '2017-09-15 08:01:10', '2017-09-15 08:01:10', '538692', '2017-09-15 08:01:10', NULL),
(8, 'jfrancis', 'Jennifer', '', '8wLB+fqNu1n22tQY', '', 'jfrancis@appscelestial.com', '1111111113', '1999-09-15', '', 'Cafe Coffee Day, North Ambazari Road, Shivaji Naga', 'Nagpur', 'Maharashtra', '440001', 'India', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/8_1505464398.jpeg', 't-723a8c459e6cf8707a4bb5cb4db79dba', '', 'tempo', '', '2017-09-15 08:23:38', '2017-09-26 07:47:51', '2017-09-15 08:23:38', '789064', '2017-09-15 08:23:38', 136),
(9, 'lparmar', 'Laksh', '', 'SQJalNntu1md99hL4A==', '', 'lparmar@appscelestial.com', '1234567890', '1993-07-13', '', 'Cafe Coffee Day, North Ambazari Road, Shivaji Naga', 'Nagpur', 'Maharashtra', '440001', 'India', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png', 't-bb9bd30b52b86b15632beacdd8438dcc', '', 'tempo', '', '2017-09-15 15:12:54', '2017-09-15 15:13:28', '2017-09-15 15:12:54', '425368', '2017-09-15 15:12:54', 0),
(10, 'carlnnaji', 'Carl', 'Nnaji', 'dQOROLIAvFmXBUn5sScSsRbH', '', 'carlnnaji@gmail.com', '2147483647', '1999-09-28', 'Male', '7401 Sadler Court, Sugar Land, TX 77479, United St', 'Sugar Land', 'Texas', '77479', 'United States of America', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/10_1506633569.jpeg', 't-041c31bb0f03d4e32b256b6d2a0ebcfb', '', 'tempo', '', '2017-09-15 16:33:05', '2017-09-28 21:32:32', '2017-09-15 16:33:05', '285401', '2017-09-15 16:33:05', 27),
(26, 'test123', 'TestFirst', 'TestLast', 'YAB64qlLuVnv3s+vMXc=', 'Test', 'prafulla.natu39@gmail.com', '123456789', '1997-05-15', 'M', 'Plot No. - 3, Near Millitry School', 'San Diego', 'California', '92101', 'USA', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png', 't-a1bd6ec548abd3d7248b01e9e200ac4c', '', 'tempo', '', '2017-09-18 13:48:50', '2017-09-18 13:48:50', '2017-09-18 13:48:50', '611939', '2017-09-18 13:48:50', NULL),
(27, 'jack', 'Jack', 'Dorson', 'bwLy4JiCwlmCl/sz', '', 'jack@appscelestial.com', '1234567891', '1999-09-20', '', 'Katol Road, Seminary Hills, Nagpur - 440001, Mahar', 'Nagpur', 'Maharashtra', '440001', 'India', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png', 't-465c58881dc6b1de2404a114f97c76d1', '', 'tempo', '', '2017-09-20 15:01:08', '2017-09-20 15:02:01', '2017-09-20 15:01:08', '873587', '2017-09-20 15:01:08', 0),
(28, 'sam', 'Samuel', 'Jackson', 'EAOo+edvw1lJYN/c', '', 'sam@appscelestial.com', '2147483647', '1999-09-21', '', 'Katol Road, Seminary Hills, Nagpur - 440001, Mahar', 'Nagpur', 'Maharashtra', '440001', 'India', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png', 't-7d5e2784b70578ed5873fbd0eade1b0e', '', 'tempo', '', '2017-09-21 07:53:33', '2017-09-25 09:12:00', '2017-09-21 07:53:33', '651859', '2017-09-21 07:53:33', 0),
(29, 'nail', 'Nail', 'David', 'TQMgDQOPw1mFfBWY', '', 'nail@appscelestial.com', '1234567892', '1999-09-21', '', 'Katol Road, Seminary Hills, Nagpur - 440001, Mahar', 'Nagpur', 'Maharashtra', '440001', 'India', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png', 't-a31f7c19251654a8f2c0da4b82b366d5', '', 'tempo', '', '2017-09-21 10:06:18', '2017-09-21 10:07:13', '2017-09-21 10:06:18', '585135', '2017-09-21 10:06:18', 0),
(33, 'kenny', 'Kenny', 'Joseph', 'uAK3Afl5xlmcJ5cR', '', 'kenny@appscelestial.com', '1122112211', '1982-11-23', '', '', '', '', '', '', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/33_1506180123.jpeg', 't-299189a1ce8f37a67d48edba21c5d5c1', '', 'tempo', '', '2017-09-23 15:13:43', '2017-09-29 05:45:06', '2017-09-23 15:13:43', '761389', '2017-09-23 15:13:43', 12),
(34, 'emmanuelin', 'Emmanuel', 'Francis', '', '', '', '0', '2017-09-23', '', '', '', '', '', '', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png', '54167590', '', 'twitter', '', '2017-09-23 15:55:40', '2017-09-23 16:23:02', '2017-09-23 15:55:40', '509887', '2017-09-23 15:55:40', 0),
(35, '_xxxRula', 'Commander', 'N', '', '', '', '0', '2017-09-23', '', '', '', '', '', '', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png', '359592987', '', 'twitter', '', '2017-09-23 19:49:39', '2017-09-23 19:49:51', '2017-09-23 19:49:39', '412911', '2017-09-23 19:49:39', 0),
(36, 'kiran', 'Kiran', 'David', 'uwJaF12gyFnrp9oG', '', 'kirandavid@appscelestial.com', '98123', '1985-12-25', 'Female', 'Katol Road, Seminary Hills, Nagpur - 440001, Mahar', 'Nagpur', 'Maharashtra', '440001', 'India', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/36_1506412711.jpeg', 't-e5d5b43e32fc212be1f315ab98998251', '', 'tempo', '', '2017-09-25 06:21:36', '2017-09-26 18:04:38', '2017-09-25 06:21:36', '802120', '2017-09-25 06:21:36', 13),
(37, 'kim', 'Kim', 'John', 'SwJzZeyvyFniVQ1P', '', 'kim@appscelestial.com', '2147483647', '1999-09-25', '', 'Katol Road, Seminary Hills, Nagpur - 440001, Mahar', 'Nagpur', 'Maharashtra', '440001', 'India', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png', 't-0e78d666ea4ddfeb4f9d1b5eeb60adc0', '', 'tempo', '', '2017-09-25 07:28:05', '2017-09-25 09:49:06', '2017-09-25 07:28:05', '249212', '2017-09-25 07:28:05', 6),
(38, 'rebeca', 'Rebecca', 'Joseph', 'NAAB9wJHylm7H54v', '', 'rebeca@appscelestial.com', '9', '1997-10-13', 'Female', 'Katol Road, Seminary Hills, Nagpur - 440001, Mahar', 'Nagpur', 'Maharashtra', '440001', 'India', 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png', 't-ba192bf13c8ecec05ed96eea508efdd2', '', 'tempo', '', '2017-09-26 12:24:52', '2017-09-26 12:26:42', '2017-09-26 12:24:52', '625239', '2017-09-26 12:24:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `user_device_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `devicetype` varchar(50) DEFAULT NULL,
  `devicepushtoken` text,
  `one_signal_userid` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_devices`
--

INSERT INTO `user_devices` (`user_device_id`, `user_id`, `devicetype`, `devicepushtoken`, `one_signal_userid`, `created_at`, `updated_at`) VALUES
(4, 3, 'ios', '12312312', '2222', '2017-09-29 09:30:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_network`
--

CREATE TABLE `user_network` (
  `user_network_id` int(11) NOT NULL,
  `primary_user_id` int(11) DEFAULT NULL,
  `network_user_id` int(11) DEFAULT NULL,
  `association_type` varchar(20) DEFAULT NULL,
  `network_status` varchar(11) DEFAULT NULL,
  `association_status_date` datetime DEFAULT NULL,
  `network_initiated_by` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_network`
--

INSERT INTO `user_network` (`user_network_id`, `primary_user_id`, `network_user_id`, `association_type`, `network_status`, `association_status_date`, `network_initiated_by`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 'follow', 'Accepted', '2017-09-15 07:46:02', '6', '2017-09-15 07:21:06', '2017-09-15 07:56:04'),
(53, 29, 6, 'follow', 'Accepted', '2017-09-21 10:51:17', '29', '2017-09-21 10:07:13', '2017-09-21 10:51:17'),
(3, 6, 3, 'follow', 'Accepted', '2017-09-15 07:58:01', '6', '2017-09-15 07:57:44', '2017-09-15 07:58:01'),
(4, 6, 4, 'follow', 'Accepted', '2017-09-15 07:58:04', '6', '2017-09-15 07:57:45', '2017-09-15 07:58:04'),
(5, 6, 5, 'follow', 'Accepted', '2017-09-15 07:58:06', '6', '2017-09-15 07:57:46', '2017-09-15 07:58:06'),
(6, 9, 1, 'follow', 'Invited', '2017-09-15 15:13:28', '9', '2017-09-15 15:13:28', '2017-09-15 15:13:28'),
(7, 9, 2, 'follow', 'Invited', '2017-09-15 15:13:30', '9', '2017-09-15 15:13:30', '2017-09-15 15:13:30'),
(8, 9, 3, 'follow', 'Invited', '2017-09-15 15:13:31', '9', '2017-09-15 15:13:31', '2017-09-15 15:13:31'),
(9, 9, 4, 'follow', 'Invited', '2017-09-15 15:13:33', '9', '2017-09-15 15:13:33', '2017-09-15 15:13:33'),
(10, 9, 5, 'follow', 'Invited', '2017-09-15 15:13:34', '9', '2017-09-15 15:13:34', '2017-09-15 15:13:34'),
(11, 9, 7, 'follow', 'Invited', '2017-09-15 15:14:39', '9', '2017-09-15 15:14:39', '2017-09-15 15:14:39'),
(12, 10, 4, 'follow', 'Invited', '2017-09-15 16:34:47', '10', '2017-09-15 16:34:47', '2017-09-15 16:34:47'),
(13, 10, 3, 'follow', 'Invited', '2017-09-15 16:34:49', '10', '2017-09-15 16:34:49', '2017-09-15 16:34:49'),
(14, 10, 2, 'follow', 'Invited', '2017-09-15 16:34:50', '10', '2017-09-15 16:34:50', '2017-09-15 16:34:50'),
(15, 10, 1, 'follow', 'Invited', '2017-09-15 16:34:51', '10', '2017-09-15 16:34:51', '2017-09-15 16:34:51'),
(16, 3, 2, NULL, 'Accepted', '2017-10-10 07:25:22', '3', '2017-09-16 10:07:49', '2017-10-10 12:55:22'),
(17, 6, 10, 'follow', 'Accepted', '2017-09-20 19:02:56', '6', '2017-09-16 10:40:35', '2017-09-20 19:02:56'),
(18, 6, 8, 'follow', 'Accepted', '2017-09-23 17:12:22', '6', '2017-09-16 10:40:36', '2017-09-23 17:12:22'),
(19, 6, 7, 'follow', 'Invited', '2017-09-16 10:40:43', '6', '2017-09-16 10:40:43', '2017-09-16 10:40:43'),
(20, 1, 8, 'follow', 'Accepted', '2017-09-23 17:12:24', '1', '2017-09-18 08:43:03', '2017-09-23 17:12:24'),
(21, 8, 1, 'follow', 'Invited', '2017-09-18 09:24:56', '8', '2017-09-18 09:24:56', '2017-09-18 09:24:56'),
(22, 8, 2, 'follow', 'Accepted', '2017-09-18 09:25:20', '8', '2017-09-18 09:24:59', '2017-09-18 09:25:20'),
(23, 8, 3, 'follow', 'Accepted', '2017-09-18 09:29:05', '8', '2017-09-18 09:28:10', '2017-09-18 09:29:05'),
(105, 1, 3, 'follow', 'Accepted', '2017-09-29 06:21:56', '1', '2017-09-29 06:20:55', '2017-09-29 11:51:56'),
(46, 3, 5, 'following', 'Accepted', '2017-09-21 05:54:30', '3', '2017-09-21 05:44:12', '2017-09-21 05:54:30'),
(30, 2, 4, 'follow', 'Accepted', '2017-09-20 11:52:54', '2', '2017-09-20 11:51:50', '2017-09-20 11:52:54'),
(29, 2, 3, 'follow', 'Accepted', '2017-09-20 11:52:48', '2', '2017-09-20 11:51:40', '2017-09-20 11:52:48'),
(31, 2, 5, 'follow', 'Accepted', '2017-09-20 11:55:12', '2', '2017-09-20 11:54:43', '2017-09-20 11:55:12'),
(32, 5, 2, '', 'Invited', '2017-09-20 11:55:00', '5', '2017-09-20 11:55:00', '2017-09-20 11:55:00'),
(33, 27, 1, 'follow', 'Invited', '2017-09-20 15:02:01', '27', '2017-09-20 15:02:01', '2017-09-20 15:02:01'),
(34, 27, 2, 'follow', 'Invited', '2017-09-20 15:03:31', '27', '2017-09-20 15:03:31', '2017-09-20 15:03:31'),
(35, 27, 6, 'follow', 'Accepted', '2017-09-20 15:07:16', '27', '2017-09-20 15:03:34', '2017-09-20 15:07:16'),
(36, 27, 8, 'unfollow', 'Rejected', '2017-09-23 17:11:20', '27', '2017-09-20 15:03:36', '2017-09-23 17:11:20'),
(37, 27, 7, 'follow', 'Invited', '2017-09-20 15:03:37', '27', '2017-09-20 15:03:37', '2017-09-20 15:03:37'),
(38, 27, 10, 'follow', 'Accepted', '2017-09-21 22:06:10', '27', '2017-09-20 15:03:40', '2017-09-21 22:06:10'),
(39, 27, 26, 'follow', 'Invited', '2017-09-20 15:03:42', '27', '2017-09-20 15:03:42', '2017-09-20 15:03:42'),
(40, 27, 9, 'follow', 'Invited', '2017-09-20 15:03:43', '27', '2017-09-20 15:03:43', '2017-09-20 15:03:43'),
(41, 27, 5, 'follow', 'Invited', '2017-09-20 15:03:46', '27', '2017-09-20 15:03:46', '2017-09-20 15:03:46'),
(42, 27, 4, 'follow', 'Invited', '2017-09-20 15:03:47', '27', '2017-09-20 15:03:47', '2017-09-20 15:03:47'),
(43, 27, 3, 'follow', 'Invited', '2017-09-20 15:03:48', '27', '2017-09-20 15:03:48', '2017-09-20 15:03:48'),
(44, 6, 27, 'follow', 'Accepted', '2017-09-20 15:06:03', '6', '2017-09-20 15:05:42', '2017-09-20 15:06:03'),
(47, 28, 6, 'follow', 'Accepted', '2017-09-21 11:11:27', '28', '2017-09-21 07:57:30', '2017-09-21 11:11:27'),
(54, 6, 29, 'follow', 'Accepted', '2017-09-21 10:27:15', '6', '2017-09-21 10:27:07', '2017-09-21 10:27:15'),
(52, 2, 6, 'follow', 'Accepted', '2017-09-21 11:08:40', '2', '2017-09-21 08:41:25', '2017-09-21 11:08:40'),
(55, 33, 8, 'follow', 'Accepted', '2017-09-23 17:11:07', '33', '2017-09-23 15:15:13', '2017-09-23 17:11:07'),
(56, 33, 6, 'follow', 'Accepted', '2017-09-23 16:35:44', '33', '2017-09-23 15:15:15', '2017-09-23 16:35:44'),
(57, 33, 29, 'follow', 'Invited', '2017-09-23 15:15:30', '33', '2017-09-23 15:15:30', '2017-09-23 15:15:30'),
(58, 33, 32, 'follow', 'Invited', '2017-09-23 15:15:39', '33', '2017-09-23 15:15:39', '2017-09-23 15:15:39'),
(59, 34, 2, 'follow', 'Invited', '2017-09-23 16:23:02', '34', '2017-09-23 16:23:02', '2017-09-23 16:23:02'),
(60, 34, 1, 'follow', 'Invited', '2017-09-23 16:23:03', '34', '2017-09-23 16:23:03', '2017-09-23 16:23:03'),
(61, 34, 6, 'follow', 'Accepted', '2017-09-23 16:35:32', '34', '2017-09-23 16:23:05', '2017-09-23 16:35:32'),
(62, 34, 10, 'follow', 'Invited', '2017-09-23 16:23:09', '34', '2017-09-23 16:23:09', '2017-09-23 16:23:09'),
(63, 8, 4, 'follow', 'Invited', '2017-09-23 19:03:15', '8', '2017-09-23 19:03:15', '2017-09-23 19:03:15'),
(64, 8, 5, 'follow', 'Invited', '2017-09-23 19:03:16', '8', '2017-09-23 19:03:16', '2017-09-23 19:03:16'),
(65, 34, 26, 'follow', 'Invited', '2017-09-23 19:05:29', '34', '2017-09-23 19:05:29', '2017-09-23 19:05:29'),
(66, 34, 27, 'follow', 'Invited', '2017-09-23 19:05:31', '34', '2017-09-23 19:05:31', '2017-09-23 19:05:31'),
(67, 34, 33, 'follow', 'Accepted', '2017-09-23 19:06:11', '34', '2017-09-23 19:05:34', '2017-09-23 19:06:11'),
(68, 34, 29, 'follow', 'Accepted', '2017-09-23 19:10:44', '34', '2017-09-23 19:05:38', '2017-09-23 19:10:44'),
(69, 34, 28, 'follow', 'Accepted', '2017-09-25 09:07:37', '34', '2017-09-23 19:05:40', '2017-09-25 09:07:37'),
(70, 29, 8, 'follow', 'Accepted', '2017-09-23 19:11:09', '29', '2017-09-23 19:09:23', '2017-09-23 19:11:09'),
(71, 8, 29, 'follow', 'Accepted', '2017-09-23 19:10:42', '8', '2017-09-23 19:10:21', '2017-09-23 19:10:42'),
(72, 35, 1, 'follow', 'Invited', '2017-09-23 19:49:51', '35', '2017-09-23 19:49:51', '2017-09-23 19:49:51'),
(73, 35, 2, 'follow', 'Invited', '2017-09-23 19:49:52', '35', '2017-09-23 19:49:52', '2017-09-23 19:49:52'),
(74, 35, 3, 'follow', 'Invited', '2017-09-23 19:49:55', '35', '2017-09-23 19:49:55', '2017-09-23 19:49:55'),
(75, 35, 4, 'follow', 'Invited', '2017-09-23 19:49:56', '35', '2017-09-23 19:49:56', '2017-09-23 19:49:56'),
(76, 35, 5, 'follow', 'Invited', '2017-09-23 19:49:58', '35', '2017-09-23 19:49:58', '2017-09-23 19:49:58'),
(77, 35, 6, 'follow', 'Accepted', '2017-09-25 18:03:06', '35', '2017-09-23 19:49:58', '2017-09-25 18:03:06'),
(78, 35, 7, 'follow', 'Invited', '2017-09-23 19:50:00', '35', '2017-09-23 19:50:00', '2017-09-23 19:50:00'),
(79, 36, 33, 'follow', 'Accepted', '2017-09-25 07:06:31', '36', '2017-09-25 06:23:14', '2017-09-25 07:06:31'),
(80, 36, 34, 'follow', 'Invited', '2017-09-25 06:23:17', '36', '2017-09-25 06:23:17', '2017-09-25 06:23:17'),
(81, 6, 36, 'follow', 'Accepted', '2017-09-25 06:39:50', '6', '2017-09-25 06:39:44', '2017-09-25 06:39:51'),
(82, 36, 6, 'follow', 'Accepted', '2017-09-25 06:40:15', '36', '2017-09-25 06:40:10', '2017-09-25 06:40:15'),
(83, 36, 8, 'follow', 'Accepted', '2017-09-25 06:41:26', '36', '2017-09-25 06:40:45', '2017-09-25 06:41:26'),
(84, 8, 36, 'follow', 'Accepted', '2017-09-25 06:41:20', '8', '2017-09-25 06:41:13', '2017-09-25 06:41:20'),
(85, 33, 36, 'follow', 'Accepted', '2017-09-25 07:06:11', '33', '2017-09-25 07:06:07', '2017-09-25 07:06:11'),
(86, 28, 36, 'follow', 'Accepted', '2017-09-25 07:08:50', '28', '2017-09-25 07:08:43', '2017-09-25 07:08:50'),
(87, 36, 28, 'follow', 'Accepted', '2017-09-25 07:09:11', '36', '2017-09-25 07:09:01', '2017-09-25 07:09:11'),
(88, 28, 37, 'follow', 'Accepted', '2017-09-25 09:12:09', '28', '2017-09-25 09:12:00', '2017-09-25 09:12:09'),
(89, 37, 28, 'follow', 'Accepted', '2017-09-25 09:12:48', '37', '2017-09-25 09:12:31', '2017-09-25 09:12:48'),
(90, 37, 8, 'follow', 'Invited', '2017-09-25 09:17:50', '37', '2017-09-25 09:17:50', '2017-09-25 09:17:50'),
(91, 36, 37, 'follow', 'Accepted', '2017-09-25 09:48:16', '36', '2017-09-25 09:47:51', '2017-09-25 09:48:16'),
(92, 37, 36, 'follow', 'Accepted', '2017-09-25 09:48:53', '37', '2017-09-25 09:48:34', '2017-09-25 09:48:53'),
(93, 38, 34, 'follow', 'Invited', '2017-09-26 12:25:17', '38', '2017-09-26 12:25:17', '2017-09-26 12:25:17'),
(94, 38, 33, 'follow', 'Invited', '2017-09-26 12:25:20', '38', '2017-09-26 12:25:20', '2017-09-26 12:25:20'),
(95, 38, 36, 'follow', 'Invited', '2017-09-26 12:25:29', '38', '2017-09-26 12:25:29', '2017-09-26 12:25:29'),
(96, 38, 37, 'follow', 'Invited', '2017-09-26 12:26:12', '38', '2017-09-26 12:26:12', '2017-09-26 12:26:12'),
(97, 38, 1, 'follow', 'Invited', '2017-09-26 12:26:16', '38', '2017-09-26 12:26:16', '2017-09-26 12:26:16'),
(98, 38, 2, 'follow', 'Invited', '2017-09-26 12:26:18', '38', '2017-09-26 12:26:18', '2017-09-26 12:26:18'),
(99, 38, 5, 'follow', 'Invited', '2017-09-26 12:26:21', '38', '2017-09-26 12:26:21', '2017-09-26 12:26:21'),
(100, 38, 6, 'follow', 'Invited', '2017-09-26 12:26:22', '38', '2017-09-26 12:26:22', '2017-09-26 12:26:22'),
(101, 38, 7, 'follow', 'Invited', '2017-09-26 12:26:23', '38', '2017-09-26 12:26:23', '2017-09-26 12:26:23'),
(102, 10, 35, 'follow', 'Invited', '2017-09-28 20:19:05', '10', '2017-09-28 20:19:05', '2017-09-28 20:19:05'),
(103, 10, 6, 'follow', 'Invited', '2017-09-28 20:57:41', '10', '2017-09-28 20:57:41', '2017-09-28 20:57:41'),
(104, 10, 34, 'follow', 'Invited', '2017-09-28 20:57:43', '10', '2017-09-28 20:57:43', '2017-09-28 20:57:43'),
(106, 4, 2, 'follow', 'Accepted', '2017-10-10 07:26:26', '4', '2017-10-10 07:25:33', '2017-10-10 12:56:26');

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `user_notification_id` int(11) NOT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `comment_text` text,
  `notification_type` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_bin DEFAULT NULL COMMENT 'followrequest/followaccepted/eventlike/photolike/commentedonevent',
  `status` varchar(50) DEFAULT NULL COMMENT 'unread/read/processed',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_notifications`
--

INSERT INTO `user_notifications` (`user_notification_id`, `from_user_id`, `to_user_id`, `event_id`, `photo_id`, `network_id`, `comment_text`, `notification_type`, `status`, `created_at`, `updated_at`) VALUES
(3, 2, 3, NULL, NULL, 16, 'Ruby Pelora has Rejected follow request', 'followrejected', 'read', '2017-09-16 10:15:31', NULL),
(4, 2, 3, NULL, NULL, 16, 'Ruby Pelora has Accepted follow request', 'followaccepted', 'processed', '2017-09-16 10:15:49', NULL),
(5, 6, 10, NULL, NULL, 17, 'Emmanuel has Invited to you', 'followrequest', 'unread', '2017-09-16 10:40:35', NULL),
(7, 6, 7, NULL, NULL, 19, 'Emmanuel has Invited to you', 'followrequest', 'unread', '2017-09-16 10:40:43', NULL),
(8, 10, 6, NULL, NULL, 17, 'Carl has Accepted follow request', 'followaccepted', 'processed', '2017-09-16 10:42:27', NULL),
(11, 8, 1, NULL, NULL, 21, 'John Krutela wants to follow you', 'followrequest', 'unread', '2017-09-18 09:24:56', NULL),
(14, 8, 3, NULL, NULL, 23, 'Ruby Pelora wants to follow you', 'followrequest', 'unread', '2017-09-18 09:28:10', NULL),
(16, 8, 1, NULL, NULL, 20, 'Jennifer has Accepted follow request', 'followaccepted', 'processed', '2017-09-18 09:35:00', NULL),
(17, 8, 6, NULL, NULL, 18, 'Jennifer has Accepted follow request', 'followaccepted', 'processed', '2017-09-18 09:35:24', NULL),
(19, 5, 9, 5, NULL, NULL, 'Alisa Donald has commented on your event', 'commentonevent', 'unread', '2017-09-18 12:35:54', NULL),
(21, 5, 9, 5, NULL, NULL, 'Alisa Donald has commented on your event', 'commentonevent', 'unread', '2017-09-19 10:03:12', NULL),
(22, 5, 9, 5, NULL, NULL, 'Alisa Donald has commented on your event', 'commentonevent', 'unread', '2017-09-19 10:05:17', NULL),
(23, 5, 9, 5, NULL, NULL, 'Alisa Donald has commented on your event', 'commentonevent', 'unread', '2017-09-19 10:05:25', NULL),
(24, 5, 9, 5, NULL, NULL, 'Alisa Donald has commented on your event', 'commentonevent', 'unread', '2017-09-19 10:05:52', NULL),
(27, 2, 6, 5, NULL, NULL, 'Mark Syela has unliked your post', 'commentonevent', 'unread', '2017-09-19 13:48:37', NULL),
(28, 3, 0, NULL, NULL, NULL, 'Ruby Pelora has uploaded post on your event', 'uploadedpostonevent', 'processed', '2017-09-19 13:51:04', NULL),
(29, 3, 0, NULL, NULL, NULL, 'Ruby Pelora has uploaded post on your event', 'uploadedpostonevent', 'processed', '2017-09-19 13:55:33', NULL),
(30, 3, 0, NULL, NULL, NULL, 'Ruby Pelora has uploaded post on your event', 'uploadedpostonevent', 'processed', '2017-09-19 13:57:24', NULL),
(31, 3, 1, 12, NULL, NULL, 'John Krutela has uploaded post on your event', 'uploadedpostonevent', 'processed', '2017-09-19 13:58:00', NULL),
(35, 4, 1, 1, NULL, NULL, 'George Richardson has Accepted your invitation.', 'updateinvitaionevent', 'unread', '2017-09-19 15:05:29', NULL),
(36, 4, 9, 10, NULL, NULL, 'George Richardson has commented on your event', 'commentonevent', 'unread', '2017-09-19 15:09:44', NULL),
(37, 0, 0, NULL, NULL, 26, ' wants to follow you', 'followrequest', 'unread', '2017-09-20 11:47:30', NULL),
(38, 0, 0, NULL, NULL, 27, ' wants to follow you', 'followrequest', 'unread', '2017-09-20 11:47:37', NULL),
(39, 0, 0, NULL, NULL, 28, ' wants to follow you', 'followrequest', 'unread', '2017-09-20 11:50:13', NULL),
(40, 2, 3, NULL, NULL, 29, 'Mark Syela wants to follow you', 'followrequest', 'unread', '2017-09-20 11:51:40', NULL),
(41, 2, 4, NULL, NULL, 30, 'Mark Syela wants to follow you', 'followrequest', 'unread', '2017-09-20 11:51:50', NULL),
(42, 3, 2, NULL, NULL, 29, 'Ruby Pelora has Accepted follow request', 'followaccepted', 'processed', '2017-09-20 11:52:48', NULL),
(43, 4, 2, NULL, NULL, 30, 'George Richardson has Accepted follow request', 'followaccepted', 'processed', '2017-09-20 11:52:54', NULL),
(44, 2, 5, NULL, NULL, 31, 'Mark Syela wants to follow you', 'followrequest', 'unread', '2017-09-20 11:54:43', NULL),
(45, 5, 2, NULL, NULL, 32, 'Mark Syela wants to follow you', 'followrequest', 'unread', '2017-09-20 11:55:00', NULL),
(46, 5, 2, NULL, NULL, 31, 'Alisa Donald has Accepted follow request', 'followaccepted', 'processed', '2017-09-20 11:55:12', NULL),
(47, 6, 1, 14, NULL, NULL, 'John Krutela has Invited on an event', 'inviteonevent', 'unread', '2017-09-20 12:22:04', NULL),
(48, 6, 2, 14, NULL, NULL, 'Mark Syela has Invited on an event', 'inviteonevent', 'unread', '2017-09-20 12:22:04', NULL),
(49, 6, 1, 15, NULL, NULL, 'John Krutela has Invited on an event', 'inviteonevent', 'unread', '2017-09-20 12:46:42', NULL),
(50, 6, 2, 15, NULL, NULL, 'Mark Syela has Invited on an event', 'inviteonevent', 'unread', '2017-09-20 12:46:42', NULL),
(51, 6, 0, 16, NULL, NULL, 'Emmanuel has Invited on an event', 'inviteonevent', 'unread', '2017-09-20 13:50:03', NULL),
(52, 27, 1, NULL, NULL, 33, 'John Krutela wants to follow you', 'followrequest', 'unread', '2017-09-20 15:02:01', NULL),
(53, 27, 2, NULL, NULL, 34, 'Mark Syela wants to follow you', 'followrequest', 'unread', '2017-09-20 15:03:31', NULL),
(56, 27, 7, NULL, NULL, 37, 'John wants to follow you', 'followrequest', 'unread', '2017-09-20 15:03:37', NULL),
(57, 27, 10, NULL, NULL, 38, 'Carl wants to follow you', 'followrequest', 'unread', '2017-09-20 15:03:40', NULL),
(58, 27, 26, NULL, NULL, 39, 'TestFirst TestLast wants to follow you', 'followrequest', 'unread', '2017-09-20 15:03:42', NULL),
(59, 27, 9, NULL, NULL, 40, 'Laksh wants to follow you', 'followrequest', 'unread', '2017-09-20 15:03:43', NULL),
(60, 27, 5, NULL, NULL, 41, 'Alisa Donald wants to follow you', 'followrequest', 'unread', '2017-09-20 15:03:46', NULL),
(61, 27, 4, NULL, NULL, 42, 'George Richardson wants to follow you', 'followrequest', 'unread', '2017-09-20 15:03:47', NULL),
(62, 27, 3, NULL, NULL, 43, 'Ruby Pelora wants to follow you', 'followrequest', 'unread', '2017-09-20 15:03:48', NULL),
(63, 6, 27, NULL, NULL, 44, 'Emmanuel wants to follow you', 'followrequest', 'unread', '2017-09-20 15:05:42', NULL),
(64, 27, 6, NULL, NULL, 44, 'Jack Dorson has Accepted follow request', 'followaccepted', 'processed', '2017-09-20 15:06:03', NULL),
(65, 6, 27, NULL, NULL, 35, 'Jack Dorson has Accepted follow request', 'followaccepted', 'processed', '2017-09-20 15:07:16', NULL),
(66, 10, 27, NULL, NULL, 38, 'Jack Dorson has Accepted follow request', 'followaccepted', 'processed', '2017-09-20 17:34:57', NULL),
(68, 10, 6, NULL, NULL, 17, 'Carl has Accepted follow request', 'followaccepted', 'processed', '2017-09-20 19:02:56', NULL),
(71, 3, 5, NULL, NULL, 46, 'Ruby Pelora wants to follow you', 'followrequest', 'unread', '2017-09-21 05:44:12', NULL),
(73, 5, 3, NULL, NULL, 46, 'Alisa Donald has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 05:54:30', NULL),
(84, 6, 2, NULL, NULL, 52, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 08:42:16', NULL),
(86, 6, 2, 2, NULL, NULL, 'Mark Syela has Accepted your invitation.', 'updateinvitaionevent', 'unread', '2017-09-21 09:15:28', NULL),
(87, 6, 2, 2, NULL, NULL, 'Emmanuel  has uploaded post on your event', 'uploadedpostonevent', 'processed', '2017-09-21 09:18:23', NULL),
(89, 4, 9, 2, NULL, NULL, 'George Richardson has liked your post', 'commentonevent', 'unread', '2017-09-21 09:24:59', NULL),
(91, 6, 29, NULL, NULL, 53, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 10:07:33', NULL),
(92, 6, 29, NULL, NULL, 54, 'Emmanuel  wants to follow you', 'followrequest', 'unread', '2017-09-21 10:27:07', NULL),
(93, 29, 6, NULL, NULL, 54, 'Nail David has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 10:27:15', NULL),
(94, 6, 1, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 10:37:11', NULL),
(95, 6, 3, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 10:37:11', NULL),
(96, 6, 4, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 10:37:11', NULL),
(97, 6, 0, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 10:42:20', NULL),
(98, 6, 0, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 10:42:31', NULL),
(99, 6, 0, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 10:43:15', NULL),
(100, 6, 0, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 10:43:49', NULL),
(101, 6, 29, NULL, NULL, 53, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 10:50:41', NULL),
(102, 6, 29, NULL, NULL, 53, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 10:51:17', NULL),
(103, 6, 2, NULL, NULL, 52, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 11:08:40', NULL),
(104, 6, 28, NULL, NULL, 47, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 11:11:27', NULL),
(105, 6, 1, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 11:20:02', NULL),
(106, 6, 3, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 11:20:02', NULL),
(107, 6, 4, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 11:20:02', NULL),
(108, 6, 5, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 11:20:02', NULL),
(110, 6, 10, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 11:20:02', NULL),
(111, 6, 27, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 11:20:02', NULL),
(112, 6, 29, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 11:20:02', NULL),
(113, 6, 0, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-21 12:10:16', NULL),
(114, 6, 10, 0, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-21 13:01:27', NULL),
(115, 6, 10, 8, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-21 13:12:22', NULL),
(117, 8, 1, 1, NULL, NULL, 'Jennifer  has accepted your request for a Host for an event', 'accepthostinvitation', 'unread', '2017-09-21 14:28:09', NULL),
(118, 6, 1, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:49', NULL),
(119, 6, 3, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:49', NULL),
(120, 6, 4, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(121, 6, 5, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(122, 6, 10, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(124, 6, 27, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(125, 6, 29, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(126, 6, 1, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(127, 6, 3, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(128, 6, 4, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(129, 6, 5, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(130, 6, 10, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(132, 6, 27, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(133, 6, 29, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(134, 6, 1, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(135, 6, 3, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(136, 6, 4, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(137, 6, 5, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(138, 6, 10, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(140, 6, 27, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(141, 6, 29, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(142, 6, 1, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(143, 6, 3, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(144, 6, 4, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(145, 6, 5, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(146, 6, 10, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(148, 6, 27, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(149, 6, 29, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(150, 6, 1, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(151, 6, 3, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(152, 6, 4, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(153, 6, 5, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(154, 6, 10, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(156, 6, 27, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(157, 6, 29, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(158, 6, 1, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(159, 6, 3, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(160, 6, 4, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(161, 6, 5, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(162, 6, 10, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(164, 6, 27, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(165, 6, 29, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(166, 6, 1, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(167, 6, 3, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(168, 6, 4, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(169, 6, 5, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(170, 6, 10, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(172, 6, 27, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(173, 6, 29, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(174, 6, 1, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(175, 6, 3, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(176, 6, 4, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(177, 6, 5, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(178, 6, 10, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(180, 6, 27, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(181, 6, 29, 16, NULL, NULL, 'Emmanuel  has invited you as a Host for an event', 'hostinvitaion', 'unread', '2017-09-21 14:42:50', NULL),
(182, 29, 1, 1, NULL, NULL, 'Nail David has accepted your request for a Host for an event', 'accepthostinvitation', 'unread', '2017-09-21 14:43:40', NULL),
(183, 29, 6, 16, NULL, NULL, 'Nail David has accepted your request for a Host for an event', 'accepthostinvitation', 'unread', '2017-09-21 14:47:32', NULL),
(184, 10, 27, NULL, NULL, 38, 'Carl  has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 20:24:16', NULL),
(185, 10, 27, NULL, NULL, 38, 'Carl  has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 22:06:07', NULL),
(186, 10, 27, NULL, NULL, 38, 'Carl  has Accepted follow request', 'followaccepted', 'processed', '2017-09-21 22:06:10', NULL),
(187, 6, 10, 8, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-22 09:20:14', NULL),
(188, 6, 10, 8, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-22 09:22:59', NULL),
(189, 6, 10, 8, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-22 09:23:01', NULL),
(190, 6, 10, 8, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-22 09:23:12', NULL),
(191, 6, 10, 8, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-22 09:23:15', NULL),
(192, 6, 10, 8, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-22 09:24:13', NULL),
(193, 6, 10, 8, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-22 09:24:15', NULL),
(194, 6, 10, 8, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-22 09:24:24', NULL),
(195, 6, 10, 8, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-22 09:24:25', NULL),
(196, 8, 10, 8, NULL, NULL, 'Jennifer  has commented on your event', 'commentonevent', 'unread', '2017-09-22 09:40:30', NULL),
(197, 6, 10, 8, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-22 09:48:31', NULL),
(198, 6, 10, 8, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-22 09:48:52', NULL),
(199, 6, 10, 8, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-22 09:49:02', NULL),
(200, 6, 10, 8, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-22 09:53:48', NULL),
(201, 6, 10, 8, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-22 09:53:50', NULL),
(202, 6, 10, 8, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-22 09:53:59', NULL),
(203, 0, 0, 8, NULL, NULL, 'You have commented on your event', 'commentonevent', 'unread', '2017-09-22 10:45:50', NULL),
(206, 3, 6, 8, NULL, NULL, 'Ruby Pelora has commented on your event', 'commentonevent', 'unread', '2017-09-22 12:11:15', NULL),
(207, 4, 9, 8, NULL, NULL, 'George Richardson has commented on your event', 'commentonevent', 'unread', '2017-09-22 12:11:17', NULL),
(208, 5, 9, 8, NULL, NULL, 'Alisa Donald has commented on your event', 'commentonevent', 'unread', '2017-09-22 12:11:22', NULL),
(209, 6, 10, 8, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-22 12:11:25', NULL),
(210, 7, 10, 8, NULL, NULL, 'John  has commented on your event', 'commentonevent', 'unread', '2017-09-22 12:11:30', NULL),
(211, 7, 10, 8, NULL, NULL, 'John  has commented on your event', 'commentonevent', 'unread', '2017-09-22 12:19:51', NULL),
(215, 33, 29, NULL, NULL, 57, 'Kenny Joseph wants to follow you', 'followrequest', 'unread', '2017-09-23 15:15:30', NULL),
(216, 33, 32, NULL, NULL, 58, 'Kenny Joseph wants to follow you', 'followrequest', 'unread', '2017-09-23 15:15:39', NULL),
(217, 34, 2, NULL, NULL, 59, 'Emmanuel Francis wants to follow you', 'followrequest', 'unread', '2017-09-23 16:23:02', NULL),
(218, 34, 1, NULL, NULL, 60, 'Emmanuel Francis wants to follow you', 'followrequest', 'unread', '2017-09-23 16:23:03', NULL),
(220, 34, 10, NULL, NULL, 62, 'Emmanuel Francis wants to follow you', 'followrequest', 'unread', '2017-09-23 16:23:09', NULL),
(221, 6, 34, NULL, NULL, 61, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-23 16:35:32', NULL),
(222, 6, 33, NULL, NULL, 56, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-23 16:35:44', NULL),
(224, 6, 10, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(225, 6, 27, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(226, 6, 29, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(228, 6, 10, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(229, 6, 27, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(230, 6, 29, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(232, 6, 10, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(233, 6, 27, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(234, 6, 29, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(236, 6, 10, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(237, 6, 27, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(238, 6, 29, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-23 16:46:27', NULL),
(240, 6, 10, 18, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-23 16:46:27', NULL),
(241, 6, 27, 18, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-23 16:46:27', NULL),
(242, 6, 29, 18, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-23 16:46:27', NULL),
(243, 8, 6, 18, NULL, NULL, 'Emmanuel  has Accepted your invitation.', 'updateinvitaionevent', 'unread', '2017-09-23 17:10:55', NULL),
(244, 8, 6, 18, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:10:57', NULL),
(245, 8, 6, 18, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:11:00', NULL),
(246, 8, 6, 18, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:11:02', NULL),
(247, 8, 6, 18, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:11:05', NULL),
(248, 8, 33, NULL, NULL, 55, 'Jennifer  has Accepted follow request', 'followaccepted', 'processed', '2017-09-23 17:11:07', NULL),
(249, 8, 6, 16, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:11:10', NULL),
(250, 8, 6, 16, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:11:13', NULL),
(251, 8, 27, NULL, NULL, 36, 'Jennifer  has Rejected follow request', 'followrejected', 'processed', '2017-09-23 17:11:20', NULL),
(252, 8, 6, 16, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:11:42', NULL),
(253, 8, 6, 16, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:11:55', NULL),
(254, 8, 6, 16, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:11:58', NULL),
(255, 8, 6, 16, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:12:00', NULL),
(256, 8, 6, 16, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:12:02', NULL),
(257, 8, 6, 16, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:12:04', NULL),
(258, 8, 1, 1, NULL, NULL, 'Jennifer  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-23 17:12:06', NULL),
(259, 8, 6, NULL, NULL, 18, 'Jennifer  has Accepted follow request', 'followaccepted', 'processed', '2017-09-23 17:12:22', NULL),
(260, 8, 1, NULL, NULL, 20, 'Jennifer  has Accepted follow request', 'followaccepted', 'processed', '2017-09-23 17:12:24', NULL),
(261, 6, 10, 8, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-23 17:17:23', NULL),
(262, 6, 10, 8, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-23 17:17:31', NULL),
(263, 8, 10, 18, NULL, NULL, 'Jennifer  has unliked your post', 'commentonevent', 'unread', '2017-09-23 19:01:21', NULL),
(264, 8, 10, 18, NULL, NULL, 'Jennifer  has commented on your event', 'commentonevent', 'unread', '2017-09-23 19:01:30', NULL),
(265, 8, 10, 8, NULL, NULL, 'Jennifer  has commented on your event', 'commentonevent', 'unread', '2017-09-23 19:02:29', NULL),
(266, 8, 10, 8, NULL, NULL, 'Jennifer  has liked your post', 'commentonevent', 'unread', '2017-09-23 19:02:40', NULL),
(267, 8, 10, 8, NULL, NULL, 'Jennifer  has liked your post', 'commentonevent', 'unread', '2017-09-23 19:02:43', NULL),
(268, 8, 4, NULL, NULL, 63, 'Jennifer  wants to follow you', 'followrequest', 'unread', '2017-09-23 19:03:15', NULL),
(269, 8, 5, NULL, NULL, 64, 'Jennifer  wants to follow you', 'followrequest', 'unread', '2017-09-23 19:03:16', NULL),
(270, 34, 26, NULL, NULL, 65, 'Emmanuel Francis wants to follow you', 'followrequest', 'unread', '2017-09-23 19:05:29', NULL),
(271, 34, 27, NULL, NULL, 66, 'Emmanuel Francis wants to follow you', 'followrequest', 'unread', '2017-09-23 19:05:31', NULL),
(275, 33, 34, NULL, NULL, 67, 'Kenny Joseph has Accepted follow request', 'followaccepted', 'processed', '2017-09-23 19:06:11', NULL),
(278, 29, 8, NULL, NULL, 71, 'Nail David has Accepted follow request', 'followaccepted', 'processed', '2017-09-23 19:10:42', NULL),
(279, 29, 34, NULL, NULL, 68, 'Nail David has Accepted follow request', 'followaccepted', 'processed', '2017-09-23 19:10:44', NULL),
(280, 8, 29, NULL, NULL, 70, 'Jennifer  has Accepted follow request', 'followaccepted', 'processed', '2017-09-23 19:11:09', NULL),
(281, 35, 1, NULL, NULL, 72, 'Commander N wants to follow you', 'followrequest', 'unread', '2017-09-23 19:49:51', NULL),
(282, 35, 2, NULL, NULL, 73, 'Commander N wants to follow you', 'followrequest', 'unread', '2017-09-23 19:49:52', NULL),
(283, 35, 3, NULL, NULL, 74, 'Commander N wants to follow you', 'followrequest', 'unread', '2017-09-23 19:49:55', NULL),
(284, 35, 4, NULL, NULL, 75, 'Commander N wants to follow you', 'followrequest', 'unread', '2017-09-23 19:49:56', NULL),
(285, 35, 5, NULL, NULL, 76, 'Commander N wants to follow you', 'followrequest', 'unread', '2017-09-23 19:49:58', NULL),
(287, 35, 7, NULL, NULL, 78, 'Commander N wants to follow you', 'followrequest', 'unread', '2017-09-23 19:50:00', NULL),
(288, 6, 0, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 04:42:34', NULL),
(289, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(290, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(292, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(293, 0, 29, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(294, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(295, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(297, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(299, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(300, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(302, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(303, 0, 29, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(304, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(305, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(306, 0, 10, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(307, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(308, 0, 29, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(309, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(310, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(312, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:00:35', NULL),
(314, 6, 8, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:00:35', NULL),
(315, 6, 10, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:00:35', NULL),
(316, 6, 27, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:00:35', NULL),
(317, 6, 29, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:00:35', NULL),
(318, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:03:12', NULL),
(319, 6, 8, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:03:13', NULL),
(320, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(321, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(322, 0, 10, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(323, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(324, 0, 29, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(325, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(326, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(327, 0, 10, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(328, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(329, 0, 29, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(330, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(331, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(332, 0, 10, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(333, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:34', NULL),
(334, 0, 29, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(335, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(336, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(337, 0, 10, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(338, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(339, 0, 29, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(340, 0, 5, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(341, 0, 8, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(342, 0, 10, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(343, 0, 27, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(344, 0, 29, NULL, NULL, NULL, ' has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:11:35', NULL),
(345, 6, 8, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:11:35', NULL),
(346, 6, 10, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:11:35', NULL),
(347, 6, 27, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:11:35', NULL),
(348, 6, 29, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:11:35', NULL),
(349, 6, 4, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:14:48', NULL),
(350, 6, 5, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:14:48', NULL),
(351, 6, 8, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:14:48', NULL),
(352, 6, 10, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:14:48', NULL),
(353, 6, 27, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:14:48', NULL),
(354, 6, 29, NULL, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:14:48', NULL),
(355, 29, 0, NULL, NULL, NULL, 'Nail David has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-25 05:25:38', NULL),
(356, 29, 0, NULL, NULL, NULL, 'Nail David has rejected your request for Host for an event', 'rejectthostinvitation', 'unread', '2017-09-25 05:25:42', NULL),
(357, 6, 4, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:04', NULL),
(358, 6, 5, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:04', NULL),
(359, 6, 8, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:04', NULL),
(360, 6, 10, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:04', NULL),
(361, 6, 27, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:05', NULL),
(362, 6, 29, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:05', NULL),
(363, 6, 4, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:05', NULL),
(364, 6, 5, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:05', NULL),
(365, 6, 8, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:05', NULL),
(366, 6, 10, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:05', NULL),
(367, 6, 27, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:05', NULL),
(368, 6, 29, 18, NULL, NULL, 'Emmanuel  has invited you as Host for an event', 'hostinvitaion', 'unread', '2017-09-25 05:54:05', NULL),
(369, 6, 5, 18, NULL, NULL, 'Emmanuel  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 05:54:05', NULL),
(371, 36, 34, NULL, NULL, 80, 'Koran David wants to follow you', 'followrequest', 'unread', '2017-09-25 06:23:17', NULL),
(373, 36, 6, NULL, NULL, 81, 'Koran David has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 06:39:50', NULL),
(375, 6, 36, NULL, NULL, 82, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 06:40:15', NULL),
(378, 36, 8, NULL, NULL, 84, 'Koran David has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 06:41:20', NULL),
(379, 8, 36, NULL, NULL, 83, 'Jennifer  has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 06:41:26', NULL),
(381, 36, 33, NULL, NULL, 85, 'Koran David has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 07:06:11', NULL),
(382, 33, 36, NULL, NULL, 79, 'Kenny Joseph has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 07:06:31', NULL),
(384, 36, 28, NULL, NULL, 86, 'Koran David has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 07:08:50', NULL),
(386, 28, 36, NULL, NULL, 87, 'Samuel Jackson has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 07:09:11', NULL),
(387, 28, 34, NULL, NULL, 69, 'Samuel Jackson has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 09:07:37', NULL),
(389, 37, 28, NULL, NULL, 88, 'Kim John has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 09:12:09', NULL),
(391, 28, 37, NULL, NULL, 89, 'Samuel Jackson has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 09:12:48', NULL),
(392, 37, 8, NULL, NULL, 90, 'Kim John wants to follow you', 'followrequest', 'unread', '2017-09-25 09:17:50', NULL),
(393, 37, 0, 8, NULL, NULL, 'Kim John has commented on your event', 'commentonevent', 'unread', '2017-09-25 09:24:04', NULL),
(394, 29, 0, 18, NULL, NULL, 'Nail David has commented on your event', 'commentonevent', 'unread', '2017-09-25 09:30:50', NULL),
(395, 29, 0, 18, NULL, NULL, 'Nail David has liked your post', 'commentonevent', 'unread', '2017-09-25 09:31:16', NULL),
(396, 29, 0, 18, NULL, NULL, 'Nail David has unliked your post', 'commentonevent', 'unread', '2017-09-25 09:31:18', NULL),
(397, 29, 0, 18, NULL, NULL, 'Nail David has liked your post', 'commentonevent', 'unread', '2017-09-25 09:31:20', NULL),
(398, 29, 0, 18, NULL, NULL, 'Nail David has unliked your post', 'commentonevent', 'unread', '2017-09-25 09:31:22', NULL),
(399, 29, 0, 18, NULL, NULL, 'Nail David has liked your post', 'commentonevent', 'unread', '2017-09-25 09:31:23', NULL),
(400, 6, 10, 18, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-25 09:42:03', NULL),
(402, 37, 36, NULL, NULL, 91, 'Kim John has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 09:48:16', NULL),
(404, 36, 37, NULL, NULL, 92, 'Koran David has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 09:48:53', NULL),
(405, 6, 10, 18, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-25 09:57:39', NULL),
(406, 6, 10, 18, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-25 09:57:48', NULL),
(407, 6, 10, 18, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-25 09:58:07', NULL),
(408, 6, 10, 18, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-25 09:58:17', NULL),
(409, 6, 10, 18, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-25 10:05:32', NULL),
(410, 29, 0, 18, NULL, NULL, 'Nail David has commented on your event', 'commentonevent', 'unread', '2017-09-25 10:13:44', NULL),
(411, 29, 0, 18, NULL, NULL, 'Nail David has commented on your event', 'commentonevent', 'unread', '2017-09-25 10:36:06', NULL),
(412, 37, 0, 18, NULL, NULL, 'Kim John has commented on your event', 'commentonevent', 'unread', '2017-09-25 10:40:13', NULL),
(413, 37, 0, 18, NULL, NULL, 'Kim John has commented on your event', 'commentonevent', 'unread', '2017-09-25 10:40:33', NULL),
(414, 37, 0, 18, NULL, NULL, 'Kim John has commented on your event', 'commentonevent', 'unread', '2017-09-25 10:40:47', NULL),
(415, 36, 0, 18, NULL, NULL, 'Koran David has liked your post', 'commentonevent', 'unread', '2017-09-25 11:24:32', NULL),
(416, 6, 10, 2, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 11:33:05', NULL),
(417, 36, 0, 18, NULL, NULL, 'Kiran David has liked your post', 'commentonevent', 'unread', '2017-09-25 11:39:32', NULL),
(418, 36, 0, 18, NULL, NULL, 'Kiran David has unliked your post', 'commentonevent', 'unread', '2017-09-25 11:39:33', NULL),
(419, 36, 0, 18, NULL, NULL, 'Kiran David has liked your post', 'commentonevent', 'unread', '2017-09-25 11:44:10', NULL),
(420, 33, 0, 18, NULL, NULL, 'Kenny Joseph has liked your post', 'commentonevent', 'unread', '2017-09-25 11:50:12', NULL),
(421, 36, 0, 18, NULL, NULL, 'Kiran David has unliked your post', 'commentonevent', 'unread', '2017-09-25 11:57:08', NULL),
(422, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:17:02', NULL),
(423, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:17:03', NULL),
(424, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:17:05', NULL),
(425, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:17:06', NULL),
(426, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:17:13', NULL),
(427, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:17:14', NULL),
(428, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:17:15', NULL),
(429, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:18:17', NULL),
(430, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:18:19', NULL),
(431, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:18:20', NULL),
(432, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:19:36', NULL),
(433, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:19:37', NULL),
(434, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:19:41', NULL),
(435, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:20:56', NULL),
(436, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:20:58', NULL),
(437, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:20:59', NULL),
(438, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:21:02', NULL),
(439, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:21:02', NULL),
(440, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:21:05', NULL),
(441, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:23:06', NULL),
(442, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:23:08', NULL),
(443, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:23:11', NULL),
(444, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:23:12', NULL),
(445, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:37:23', NULL),
(446, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:37:24', NULL),
(447, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:37:26', NULL),
(448, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:37:27', NULL),
(449, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:37:30', NULL),
(450, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:37:33', NULL),
(451, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:39:27', NULL),
(452, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:39:28', NULL),
(453, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:39:31', NULL),
(454, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:39:32', NULL),
(455, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:39:34', NULL),
(456, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:39:35', NULL),
(457, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:39:43', NULL),
(458, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:39:44', NULL),
(459, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:39:45', NULL),
(460, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:39:46', NULL),
(461, 6, 10, 2, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:42:42', NULL),
(462, 6, 10, 2, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:42:43', NULL),
(463, 6, 10, 2, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:42:44', NULL),
(464, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:47:29', NULL),
(465, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:47:32', NULL),
(466, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:47:33', NULL),
(467, 6, 10, 18, NULL, NULL, 'Emmanuel  has unliked your post', 'commentonevent', 'unread', '2017-09-25 12:47:37', NULL),
(468, 6, 10, 18, NULL, NULL, 'Emmanuel  has liked your post', 'commentonevent', 'unread', '2017-09-25 12:47:38', NULL),
(469, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-25 17:54:33', NULL),
(470, 10, 10, 11, NULL, NULL, 'You have unliked on your post', 'commentonevent', 'unread', '2017-09-25 17:54:34', NULL),
(471, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-25 17:54:35', NULL);
INSERT INTO `user_notifications` (`user_notification_id`, `from_user_id`, `to_user_id`, `event_id`, `photo_id`, `network_id`, `comment_text`, `notification_type`, `status`, `created_at`, `updated_at`) VALUES
(472, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-25 17:54:37', NULL),
(473, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-25 17:54:53', NULL),
(474, 10, 10, 18, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-25 17:55:12', NULL),
(475, 10, 10, 11, NULL, NULL, 'You have unliked on your post', 'commentonevent', 'unread', '2017-09-25 17:55:32', NULL),
(476, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-25 17:55:33', NULL),
(477, 6, 35, NULL, NULL, 77, 'Emmanuel  has Accepted follow request', 'followaccepted', 'processed', '2017-09-25 18:03:06', NULL),
(478, 6, 10, 6, NULL, NULL, 'Emmanuel  has commented on your event', 'commentonevent', 'unread', '2017-09-25 18:04:41', NULL),
(479, 10, 0, NULL, NULL, NULL, 'Carl  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-25 19:46:00', NULL),
(480, 10, 0, NULL, NULL, NULL, 'Carl  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-25 19:46:04', NULL),
(481, 10, 0, NULL, NULL, NULL, 'Carl  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-25 19:46:07', NULL),
(482, 10, 0, NULL, NULL, NULL, 'Carl  has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-25 19:46:11', NULL),
(483, 10, 10, 11, NULL, NULL, 'You have unliked on your post', 'commentonevent', 'unread', '2017-09-25 19:47:08', NULL),
(484, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-25 19:47:10', NULL),
(485, 10, 10, 11, NULL, NULL, 'You have unliked on your post', 'commentonevent', 'unread', '2017-09-25 19:47:12', NULL),
(486, 10, 0, 0, NULL, NULL, 'Carl  has Invited on an event', 'inviteonevent', 'unread', '2017-09-25 20:06:10', NULL),
(487, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-25 23:28:49', NULL),
(488, 10, 10, 18, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-25 23:48:32', NULL),
(489, 10, 10, 18, NULL, NULL, 'You have unliked on your post', 'commentonevent', 'unread', '2017-09-25 23:48:33', NULL),
(490, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-26 04:17:23', NULL),
(491, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-26 04:17:26', NULL),
(492, 10, 10, 11, NULL, NULL, 'You have liked your post', 'commentonevent', 'unread', '2017-09-26 04:17:29', NULL),
(493, 10, 10, 11, NULL, NULL, 'You have commented on your event', 'commentonevent', 'unread', '2017-09-26 04:17:39', NULL),
(494, 10, 10, 18, NULL, NULL, 'You have commented on your event', 'commentonevent', 'unread', '2017-09-26 04:17:56', NULL),
(495, 10, 10, 18, NULL, NULL, 'You have commented on your event', 'commentonevent', 'unread', '2017-09-26 04:18:06', NULL),
(496, 36, 6, 18, NULL, NULL, 'Kiran David has commented on your event', 'commentonevent', 'unread', '2017-09-26 06:41:23', NULL),
(497, 36, 6, 18, NULL, NULL, 'Kiran David has commented on your event', 'commentonevent', 'unread', '2017-09-26 06:41:58', NULL),
(498, 36, 6, 18, NULL, NULL, 'Kiran David has liked your post', 'commentonevent', 'unread', '2017-09-26 06:42:42', NULL),
(499, 36, 6, 18, NULL, NULL, 'Kiran David has liked your post', 'commentonevent', 'unread', '2017-09-26 06:42:45', NULL),
(500, 36, 6, 18, NULL, NULL, 'Kiran David has liked your post', 'commentonevent', 'unread', '2017-09-26 06:42:46', NULL),
(501, 36, 6, 18, NULL, NULL, 'Kiran David has unliked your post', 'commentonevent', 'unread', '2017-09-26 06:50:12', NULL),
(502, 36, 6, 18, NULL, NULL, 'Kiran David has liked your post', 'commentonevent', 'unread', '2017-09-26 06:50:14', NULL),
(503, 36, 6, 18, NULL, NULL, 'Kiran David has liked your post', 'commentonevent', 'unread', '2017-09-26 11:44:09', NULL),
(504, 38, 34, NULL, NULL, 93, 'Rebecca Joseph wants to follow you', 'followrequest', 'unread', '2017-09-26 12:25:17', NULL),
(505, 38, 33, NULL, NULL, 94, 'Rebecca Joseph wants to follow you', 'followrequest', 'unread', '2017-09-26 12:25:20', NULL),
(506, 38, 36, NULL, NULL, 95, 'Rebecca Joseph wants to follow you', 'followrequest', 'unread', '2017-09-26 12:25:29', NULL),
(507, 38, 37, NULL, NULL, 96, 'Rebecca Joseph wants to follow you', 'followrequest', 'unread', '2017-09-26 12:26:12', NULL),
(508, 38, 1, NULL, NULL, 97, 'Rebecca Joseph wants to follow you', 'followrequest', 'unread', '2017-09-26 12:26:16', NULL),
(509, 38, 2, NULL, NULL, 98, 'Rebecca Joseph wants to follow you', 'followrequest', 'unread', '2017-09-26 12:26:18', NULL),
(510, 38, 5, NULL, NULL, 99, 'Rebecca Joseph wants to follow you', 'followrequest', 'unread', '2017-09-26 12:26:21', NULL),
(511, 38, 6, NULL, NULL, 100, 'Rebecca Joseph wants to follow you', 'followrequest', 'unread', '2017-09-26 12:26:22', NULL),
(512, 38, 7, NULL, NULL, 101, 'Rebecca Joseph wants to follow you', 'followrequest', 'unread', '2017-09-26 12:26:23', NULL),
(513, 6, 10, 18, NULL, NULL, 'Emmanuel Francis has commented on your event', 'commentonevent', 'unread', '2017-09-26 16:07:05', NULL),
(514, 6, 10, 18, NULL, NULL, 'Emmanuel Francis has liked your post', 'commentonevent', 'unread', '2017-09-26 18:06:48', NULL),
(515, 6, 10, 18, NULL, NULL, 'Emmanuel Francis has commented on your event', 'commentonevent', 'unread', '2017-09-26 18:10:11', NULL),
(516, 2, 4, 2, NULL, NULL, 'Mark Syela has invited you as Host for an event', 'hostinvitation', 'unread', '2017-09-28 13:02:46', NULL),
(517, 4, 2, 2, NULL, NULL, 'George Richardson has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-28 13:06:51', NULL),
(518, 10, 0, 11, NULL, NULL, 'Carl Nnaji has Invited on an event', 'inviteonevent', 'unread', '2017-09-28 15:37:22', NULL),
(519, 10, 35, NULL, NULL, 102, 'Carl Nnaji wants to follow you', 'followrequest', 'unread', '2017-09-28 20:19:05', NULL),
(520, 10, 6, NULL, NULL, 103, 'Carl Nnaji wants to follow you', 'followrequest', 'unread', '2017-09-28 20:57:41', NULL),
(521, 10, 34, NULL, NULL, 104, 'Carl Nnaji wants to follow you', 'followrequest', 'unread', '2017-09-28 20:57:43', NULL),
(522, 10, 0, 19, NULL, NULL, 'Carl Nnaji has Invited on an event', 'inviteonevent', 'unread', '2017-09-28 21:09:46', NULL),
(523, 10, 0, 20, NULL, NULL, 'Carl Nnaji has Invited on an event', 'inviteonevent', 'unread', '2017-09-28 21:32:33', NULL),
(524, 1, 3, NULL, NULL, 105, 'John Krutela wants to follow you', 'followrequest', 'unread', '2017-09-29 06:20:55', NULL),
(525, 3, 1, NULL, NULL, 105, 'Ruby Pelora has Accepted follow request', 'followaccepted', 'processed', '2017-09-29 06:21:30', NULL),
(527, 1, 3, 21, NULL, NULL, 'John Krutela has invited you as Host for an event', 'hostinvitation', 'unread', '2017-09-29 06:25:52', NULL),
(528, 3, 1, 21, NULL, NULL, 'Ruby Pelora has accepted your request for Host for an event', 'accepthostinvitation', 'unread', '2017-09-29 06:27:32', NULL),
(529, 1, 6, 5, NULL, NULL, 'John Krutela has liked your post', 'commentonevent', 'unread', '2017-10-10 07:19:23', NULL),
(530, 1, 6, 5, NULL, NULL, 'John Krutela has liked your post', 'eventpostlike', 'unread', '2017-10-10 07:20:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_posts`
--

CREATE TABLE `user_posts` (
  `user_post_id` int(11) NOT NULL,
  `user_post_type` varchar(20) DEFAULT NULL,
  `user_post_data` text,
  `user_post_by` int(11) DEFAULT NULL,
  `user_post_status` varchar(20) DEFAULT NULL,
  `like_count` int(11) DEFAULT '0',
  `comment_count` int(11) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isdeleted` int(11) DEFAULT '0' COMMENT '0-Not Deleted; 1-Deleted'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_posts`
--

INSERT INTO `user_posts` (`user_post_id`, `user_post_type`, `user_post_data`, `user_post_by`, `user_post_status`, `like_count`, `comment_count`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/user_post/1.mov', 6, '', 2, 0, '2017-11-22 11:00:00', '2017-11-22 15:42:12', 0),
(27, 'video', 'https://s3-us-west-2.amazonaws.com/tempoevent/user_post/1.mov', 6, '', 2, 0, '2017-11-19 11:00:00', '2017-11-22 15:57:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_posts_to`
--

CREATE TABLE `user_posts_to` (
  `user_posts_to_id` int(11) NOT NULL,
  `user_post_id` int(11) DEFAULT NULL,
  `posted_to` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'unread',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_posts_to`
--

INSERT INTO `user_posts_to` (`user_posts_to_id`, `user_post_id`, `posted_to`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'unread', '2017-09-15 15:11:13', '2017-09-26 10:42:51'),
(2, 3, 2, 'unread', '2017-09-20 11:38:35', '2017-09-26 10:42:55'),
(3, 3, 5, 'unread', '2017-09-20 11:38:35', '2017-09-26 10:42:55'),
(4, 4, 1, 'unread', '2017-09-20 11:40:55', '2017-09-26 10:42:56'),
(5, 4, 2, 'unread', '2017-09-20 11:40:55', '2017-09-26 10:42:57'),
(6, 4, 3, 'unread', '2017-09-20 11:40:55', '2017-09-26 10:42:59'),
(7, 4, 4, 'unread', '2017-09-20 11:40:55', '2017-09-26 10:42:59'),
(8, 4, 5, 'unread', '2017-09-20 11:40:55', '2017-09-26 10:43:01'),
(9, 4, 10, 'unread', '2017-09-20 11:40:55', '2017-09-26 10:43:02'),
(10, 4, 8, 'unread', '2017-09-20 11:40:55', '2017-09-26 10:43:03'),
(11, 5, 5, 'unread', '2017-09-21 07:28:22', '2017-09-26 10:43:04'),
(12, 6, 5, 'read', '2017-09-21 07:36:10', '2017-09-21 07:37:30'),
(13, 7, 2, 'unread', '2017-09-21 07:51:54', NULL),
(14, 7, 5, 'unread', '2017-09-21 07:51:54', NULL),
(15, 8, 2, 'unread', '2017-09-21 12:14:04', '2017-09-26 10:43:05'),
(16, 8, 3, 'unread', '2017-09-21 12:14:04', '2017-09-26 10:43:05'),
(17, 9, 8, 'unread', '2017-09-25 06:40:38', '2017-09-26 10:43:06'),
(18, 9, 36, 'unread', '2017-09-25 06:40:38', '2017-09-26 10:46:37'),
(19, 10, 36, 'unread', '2017-09-25 06:41:47', '2017-09-26 10:46:37'),
(20, 11, 6, 'unread', '2017-09-25 07:06:49', '2017-09-26 10:46:37'),
(21, 11, 8, 'unread', '2017-09-25 07:06:49', '2017-09-26 10:46:37'),
(22, 11, 36, 'unread', '2017-09-25 07:06:49', '2017-09-26 10:46:37'),
(23, 12, 6, 'unread', '2017-09-25 07:07:18', '2017-09-26 10:46:37'),
(24, 12, 8, 'unread', '2017-09-25 07:07:19', '2017-09-26 10:46:37'),
(25, 12, 36, 'unread', '2017-09-25 07:07:19', '2017-09-26 10:46:37'),
(26, 13, 6, 'unread', '2017-09-25 07:09:24', '2017-09-26 10:46:37'),
(27, 13, 36, 'unread', '2017-09-25 07:09:24', '2017-09-26 10:46:37'),
(28, 14, 28, 'unread', '2017-09-25 09:49:06', '2017-09-26 10:46:37'),
(29, 14, 36, 'unread', '2017-09-25 09:49:06', '2017-09-26 10:46:37'),
(30, 15, 6, 'unread', '2017-09-25 11:18:35', '2017-09-26 10:46:37'),
(31, 15, 8, 'unread', '2017-09-25 11:18:35', '2017-09-26 10:46:37'),
(32, 15, 36, 'read', '2017-09-25 11:18:35', '2017-09-26 11:15:49'),
(33, 16, 1, 'unread', '2017-09-26 04:40:32', '2017-09-26 10:46:37'),
(34, 16, 3, 'unread', '2017-09-26 04:40:32', '2017-09-26 10:46:37'),
(35, 16, 4, 'unread', '2017-09-26 04:40:32', '2017-09-26 10:46:37'),
(36, 16, 5, 'read', '2017-09-26 04:40:32', '2017-09-27 05:07:46'),
(37, 16, 8, 'unread', '2017-09-26 04:40:32', '2017-09-26 10:46:37'),
(38, 16, 10, 'read', '2017-09-26 04:40:32', '2017-09-26 10:48:27'),
(39, 16, 27, 'unread', '2017-09-26 04:40:32', '2017-09-26 10:46:37'),
(40, 16, 29, 'unread', '2017-09-26 04:40:32', '2017-09-26 10:46:37'),
(41, 16, 36, 'read', '2017-09-26 04:40:32', '2017-09-27 06:25:29'),
(42, 17, 8, 'unread', '2017-09-26 04:41:05', '2017-09-26 10:46:37'),
(43, 17, 10, 'unread', '2017-09-26 04:41:05', '2017-09-26 10:46:37'),
(44, 17, 27, 'unread', '2017-09-26 04:41:05', '2017-09-26 10:46:37'),
(45, 17, 29, 'unread', '2017-09-26 04:41:05', '2017-09-26 10:46:37'),
(46, 17, 36, 'read', '2017-09-26 04:41:05', '2017-09-27 06:25:27'),
(47, 18, 8, 'unread', '2017-09-26 04:42:22', '2017-09-26 10:46:37'),
(48, 18, 10, 'unread', '2017-09-26 04:42:22', '2017-09-26 10:46:37'),
(49, 18, 27, 'unread', '2017-09-26 04:42:22', '2017-09-26 10:46:37'),
(50, 18, 29, 'unread', '2017-09-26 04:42:22', '2017-09-26 10:46:37'),
(51, 18, 36, 'read', '2017-09-26 04:42:22', '2017-09-27 06:25:25'),
(52, 19, 8, 'unread', '2017-09-26 05:04:15', '2017-09-26 10:46:37'),
(53, 19, 10, 'unread', '2017-09-26 05:04:15', '2017-09-26 10:46:37'),
(54, 19, 27, 'unread', '2017-09-26 05:04:15', '2017-09-26 10:46:37'),
(55, 19, 29, 'unread', '2017-09-26 05:04:15', '2017-09-26 10:46:37'),
(56, 19, 36, 'read', '2017-09-26 05:04:15', '2017-09-27 06:25:22'),
(57, 20, 8, 'unread', '2017-09-26 05:04:47', '2017-09-26 10:46:37'),
(58, 20, 10, 'unread', '2017-09-26 05:04:47', '2017-09-26 10:47:08'),
(59, 20, 27, 'unread', '2017-09-26 05:04:47', '2017-09-26 10:43:55'),
(60, 20, 29, 'unread', '2017-09-26 05:04:47', '2017-09-26 10:43:43'),
(61, 20, 36, 'read', '2017-09-26 05:04:47', '2017-09-27 06:25:21'),
(62, 21, 8, 'unread', '2017-09-26 05:05:21', '2017-09-26 10:43:42'),
(63, 21, 10, 'unread', '2017-09-26 05:05:21', '2017-09-26 10:43:41'),
(64, 21, 27, 'unread', '2017-09-26 05:05:21', '2017-09-26 10:43:41'),
(65, 21, 29, 'unread', '2017-09-26 05:05:21', '2017-09-26 10:43:40'),
(66, 21, 36, 'read', '2017-09-26 05:05:21', '2017-09-27 06:25:18'),
(67, 22, 5, 'unread', '2017-09-26 05:58:16', '2017-09-26 10:43:39'),
(68, 22, 8, 'unread', '2017-09-26 05:58:16', '2017-09-26 10:43:38'),
(69, 22, 10, 'unread', '2017-09-26 05:58:16', '2017-09-26 10:43:09'),
(70, 22, 27, 'unread', '2017-09-26 05:58:16', '2017-09-26 10:43:08'),
(71, 22, 29, 'unread', '2017-09-26 05:58:16', '2017-09-26 10:43:08'),
(72, 22, 36, 'read', '2017-09-26 05:58:16', '2017-09-27 06:25:09'),
(73, 23, 29, 'unread', '2017-09-26 07:47:51', '2017-09-26 10:43:07'),
(74, 23, 36, 'read', '2017-09-26 07:47:51', '2017-09-27 06:49:38'),
(75, 24, 6, 'read', '2017-09-26 14:55:29', '2017-09-27 06:22:09'),
(76, 24, 8, 'unread', '2017-09-26 14:55:29', NULL),
(77, 24, 28, 'unread', '2017-09-26 14:55:29', NULL),
(78, 24, 37, 'unread', '2017-09-26 14:55:29', NULL),
(79, 25, 6, 'read', '2017-09-26 18:04:06', '2017-09-27 06:21:59'),
(80, 25, 8, 'unread', '2017-09-26 18:04:06', NULL),
(81, 25, 28, 'unread', '2017-09-26 18:04:06', NULL),
(82, 25, 37, 'unread', '2017-09-26 18:04:06', NULL),
(83, 26, 6, 'read', '2017-09-26 18:04:38', '2017-09-27 06:21:49'),
(84, 26, 8, 'unread', '2017-09-26 18:04:38', NULL),
(85, 26, 28, 'unread', '2017-09-26 18:04:38', NULL),
(86, 26, 33, 'unread', '2017-09-26 18:04:38', NULL),
(87, 26, 37, 'unread', '2017-09-26 18:04:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_post_notifications`
--

CREATE TABLE `user_post_notifications` (
  `user_post_notification_id` int(11) NOT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `user_post_id` int(11) DEFAULT NULL COMMENT 'From user_posts',
  `comment_text` text,
  `notification_type` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_post_notifications`
--

INSERT INTO `user_post_notifications` (`user_post_notification_id`, `from_user_id`, `to_user_id`, `user_post_id`, `comment_text`, `notification_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 3, 'Ruby Pelora has posted to you', 'posted', 'unread', '2017-09-20 11:38:35', NULL),
(2, 3, 5, 3, 'Ruby Pelora has posted to you', 'posted', 'unread', '2017-09-20 11:38:35', NULL),
(3, 6, 1, 4, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-20 11:40:55', NULL),
(4, 6, 2, 4, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-20 11:40:55', NULL),
(5, 6, 3, 4, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-20 11:40:55', NULL),
(6, 6, 4, 4, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-20 11:40:55', NULL),
(7, 6, 5, 4, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-20 11:40:55', NULL),
(8, 6, 10, 4, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-20 11:40:55', NULL),
(9, 6, 8, 4, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-20 11:40:55', NULL),
(10, 3, 5, 2, 'Ruby Pelora has commented on your post', 'posted', 'unread', '2017-09-21 07:15:37', NULL),
(11, 3, 5, 2, 'Ruby Pelora has commented on your post', 'posted', 'unread', '2017-09-21 07:18:20', NULL),
(14, 3, 2, 7, 'Ruby Pelora has posted to you', 'posted', 'unread', '2017-09-21 07:51:54', NULL),
(15, 3, 5, 7, 'Ruby Pelora has posted to you', 'posted', 'unread', '2017-09-21 07:51:54', NULL),
(16, 8, 2, 8, 'Jennifer  has posted to you', 'posted', 'unread', '2017-09-21 12:14:04', NULL),
(17, 8, 3, 8, 'Jennifer  has posted to you', 'posted', 'unread', '2017-09-21 12:14:04', NULL),
(18, 6, 8, 9, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-25 06:40:38', NULL),
(19, 6, 36, 9, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-25 06:40:38', NULL),
(20, 8, 36, 10, 'Jennifer  has posted to you', 'posted', 'unread', '2017-09-25 06:41:47', NULL),
(21, 33, 6, 11, 'Kenny Joseph has posted to you', 'posted', 'unread', '2017-09-25 07:06:49', NULL),
(22, 33, 8, 11, 'Kenny Joseph has posted to you', 'posted', 'unread', '2017-09-25 07:06:49', NULL),
(23, 33, 36, 11, 'Kenny Joseph has posted to you', 'posted', 'unread', '2017-09-25 07:06:49', NULL),
(24, 33, 6, 12, 'Kenny Joseph has posted to you', 'posted', 'unread', '2017-09-25 07:07:19', NULL),
(25, 33, 8, 12, 'Kenny Joseph has posted to you', 'posted', 'unread', '2017-09-25 07:07:19', NULL),
(26, 33, 36, 12, 'Kenny Joseph has posted to you', 'posted', 'unread', '2017-09-25 07:07:19', NULL),
(27, 28, 6, 13, 'Samuel Jackson has posted to you', 'posted', 'unread', '2017-09-25 07:09:24', NULL),
(28, 28, 36, 13, 'Samuel Jackson has posted to you', 'posted', 'unread', '2017-09-25 07:09:24', NULL),
(29, 37, 28, 14, 'Kim John has posted to you', 'posted', 'unread', '2017-09-25 09:49:06', NULL),
(30, 37, 36, 14, 'Kim John has posted to you', 'posted', 'unread', '2017-09-25 09:49:06', NULL),
(31, 2, 5, 2, 'Mark Syela has commented on your post', 'posted', 'unread', '2017-09-25 10:50:13', NULL),
(32, 37, 37, 14, 'Kim John has commented on your post', 'posted', 'unread', '2017-09-25 10:51:04', NULL),
(33, 33, 6, 15, 'Kenny Joseph has posted to you', 'posted', 'unread', '2017-09-25 11:18:35', NULL),
(34, 33, 8, 15, 'Kenny Joseph has posted to you', 'posted', 'unread', '2017-09-25 11:18:35', NULL),
(35, 33, 36, 15, 'Kenny Joseph has posted to you', 'posted', 'unread', '2017-09-25 11:18:35', NULL),
(36, 36, 33, 11, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-25 11:31:29', NULL),
(37, 33, 33, 11, 'Kenny Joseph has liked your post', 'posted', 'processed', '2017-09-25 11:40:37', NULL),
(38, 6, 1, 16, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:40:32', NULL),
(39, 6, 3, 16, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:40:32', NULL),
(40, 6, 4, 16, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:40:32', NULL),
(41, 6, 5, 16, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:40:32', NULL),
(42, 6, 8, 16, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:40:32', NULL),
(43, 6, 10, 16, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:40:32', NULL),
(44, 6, 27, 16, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:40:32', NULL),
(45, 6, 29, 16, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:40:32', NULL),
(46, 6, 36, 16, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:40:32', NULL),
(47, 6, 8, 17, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:41:05', NULL),
(48, 6, 10, 17, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:41:05', NULL),
(49, 6, 27, 17, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:41:05', NULL),
(50, 6, 29, 17, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:41:05', NULL),
(51, 6, 36, 17, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:41:05', NULL),
(52, 6, 8, 18, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:42:22', NULL),
(53, 6, 10, 18, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:42:22', NULL),
(54, 6, 27, 18, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:42:22', NULL),
(55, 6, 29, 18, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:42:22', NULL),
(56, 6, 36, 18, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 04:42:22', NULL),
(57, 6, 8, 19, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:15', NULL),
(58, 6, 10, 19, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:15', NULL),
(59, 6, 27, 19, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:15', NULL),
(60, 6, 29, 19, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:15', NULL),
(61, 6, 36, 19, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:15', NULL),
(62, 6, 8, 20, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:47', NULL),
(63, 6, 10, 20, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:47', NULL),
(64, 6, 27, 20, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:47', NULL),
(65, 6, 29, 20, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:47', NULL),
(66, 6, 36, 20, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:04:47', NULL),
(67, 6, 8, 21, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:05:21', NULL),
(68, 6, 10, 21, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:05:21', NULL),
(69, 6, 27, 21, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:05:21', NULL),
(70, 6, 29, 21, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:05:21', NULL),
(71, 6, 36, 21, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:05:21', NULL),
(72, 6, 5, 22, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:58:16', NULL),
(73, 6, 8, 22, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:58:16', NULL),
(74, 6, 10, 22, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:58:16', NULL),
(75, 6, 27, 22, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:58:16', NULL),
(76, 6, 29, 22, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:58:16', NULL),
(77, 6, 36, 22, 'Emmanuel  has posted to you', 'posted', 'unread', '2017-09-26 05:58:16', NULL),
(78, 36, 6, 21, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-26 06:53:14', NULL),
(79, 36, 6, 21, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-26 06:55:04', NULL),
(80, 36, 6, 21, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-26 06:56:24', NULL),
(81, 36, 6, 20, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-26 06:56:29', NULL),
(82, 36, 6, 20, 'Kiran David has unliked your post', 'posted', 'processed', '2017-09-26 06:56:31', NULL),
(83, 36, 6, 21, 'Kiran David has commented on your post', 'posted', 'unread', '2017-09-26 07:14:49', NULL),
(84, 36, 6, 21, 'Kiran David has commented on your post', 'posted', 'unread', '2017-09-26 07:21:49', NULL),
(85, 8, 29, 23, 'Jennifer  has posted to you', 'posted', 'unread', '2017-09-26 07:47:51', NULL),
(86, 8, 36, 23, 'Jennifer  has posted to you', 'posted', 'unread', '2017-09-26 07:47:51', NULL),
(87, 36, 8, 10, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-26 11:42:28', NULL),
(88, 36, 8, 10, 'Kiran David has commented on your post', 'posted', 'unread', '2017-09-26 11:42:45', NULL),
(89, 36, 6, 20, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-26 11:45:24', NULL),
(90, 36, 6, 19, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-26 11:45:28', NULL),
(91, 6, 33, 15, 'Emmanuel  has commented on your post', 'posted', 'unread', '2017-09-26 11:48:02', NULL),
(92, 36, 33, 15, 'Kiran David has commented on your post', 'posted', 'unread', '2017-09-26 11:58:26', NULL),
(93, 36, 6, 24, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 14:55:29', NULL),
(94, 36, 8, 24, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 14:55:29', NULL),
(95, 36, 28, 24, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 14:55:29', NULL),
(96, 36, 37, 24, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 14:55:29', NULL),
(97, 36, 6, 25, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 18:04:06', NULL),
(98, 36, 8, 25, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 18:04:06', NULL),
(99, 36, 28, 25, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 18:04:06', NULL),
(100, 36, 37, 25, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 18:04:06', NULL),
(102, 36, 8, 26, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 18:04:38', NULL),
(103, 36, 28, 26, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 18:04:38', NULL),
(104, 36, 33, 26, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 18:04:38', NULL),
(105, 36, 37, 26, 'Kiran David has posted to you', 'posted', 'unread', '2017-09-26 18:04:38', NULL),
(106, 36, 6, 22, 'Kiran David has commented on your post', 'posted', 'unread', '2017-09-27 08:58:30', NULL),
(107, 36, 6, 22, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-27 08:58:37', NULL),
(108, 36, 6, 22, 'Kiran David has commented on your post', 'posted', 'unread', '2017-09-27 08:58:48', NULL),
(109, 36, 6, 21, 'Kiran David has liked your post', 'posted', 'processed', '2017-09-27 08:59:09', NULL),
(110, 10, 3, 3, 'Carl Nnaji has liked your post', 'likepost', 'processed', '2017-09-28 20:21:18', NULL),
(111, 10, 3, 3, 'Carl Nnaji has liked your post', 'likepost', 'processed', '2017-09-28 20:21:36', NULL),
(112, 10, 3, 3, 'Carl Nnaji has unliked your post', 'unlikepost', 'processed', '2017-09-28 20:21:36', NULL),
(113, 10, 3, 3, 'Carl Nnaji has liked your post', 'likepost', 'processed', '2017-09-28 20:21:38', NULL),
(114, 10, 3, 7, 'Carl Nnaji has liked your post', 'likepost', 'processed', '2017-09-28 20:21:53', NULL),
(115, 1, 6, 1, 'John Krutela has liked your post', 'likepost', 'processed', '2017-10-10 07:22:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_ranking`
--

CREATE TABLE `user_ranking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `base_ratio` float DEFAULT NULL,
  `hate_ratio` float DEFAULT NULL,
  `user_ratio` float DEFAULT NULL,
  `recent_posts_count` float DEFAULT NULL,
  `recent_share_count` float DEFAULT NULL,
  `recent_comment_count` float DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_ranking`
--

INSERT INTO `user_ranking` (`id`, `user_id`, `base_ratio`, `hate_ratio`, `user_ratio`, `recent_posts_count`, `recent_share_count`, `recent_comment_count`, `created_at`, `updated_at`) VALUES
(1, 1, 0.25, NULL, NULL, 4, 1, NULL, '2017-09-15 05:58:58', '2017-09-29 11:50:56'),
(2, 2, 2, NULL, NULL, 1, 2, NULL, '2017-09-15 06:02:48', '2017-09-21 09:11:57'),
(3, 3, 0.444444, NULL, NULL, 3, 2, NULL, '2017-09-15 06:07:55', '2017-10-10 12:55:22'),
(4, 4, 0.4, NULL, NULL, 1, NULL, NULL, '2017-09-15 06:17:43', '2017-10-10 12:55:33'),
(5, 5, 0.5, NULL, NULL, 3, 1, 3, '2017-09-15 06:21:30', '2017-09-25 18:04:41'),
(6, 6, 2, NULL, NULL, 15, 16, 3, '2017-09-15 07:21:06', '2017-09-26 16:07:05'),
(7, 8, 2, NULL, NULL, 4, 2, 15, '2017-09-15 12:42:18', '2017-09-26 07:47:51'),
(8, 9, 0, NULL, NULL, 1, NULL, 1, '2017-09-15 15:13:28', '2017-09-19 15:09:44'),
(9, 10, 3, NULL, NULL, 3, 1, 1, '2017-09-15 16:34:47', '2017-09-28 21:32:32'),
(10, NULL, 3, NULL, NULL, 0, 0, 0, '2017-09-20 11:47:30', '2017-09-21 13:01:27'),
(11, 27, 0.333333, NULL, NULL, NULL, NULL, NULL, '2017-09-20 15:02:01', '2017-09-20 15:03:48'),
(12, 0, NULL, NULL, NULL, NULL, 1, NULL, '2017-09-21 07:28:22', '2017-09-21 07:28:22'),
(13, 28, 0, NULL, NULL, 1, 2, NULL, '2017-09-21 07:57:30', '2017-09-25 09:12:00'),
(14, 29, 1, NULL, NULL, NULL, NULL, NULL, '2017-09-21 10:07:13', '2017-09-23 19:09:23'),
(15, 33, 1.33333, NULL, NULL, 3, 3, NULL, '2017-09-23 15:15:13', '2017-09-25 11:18:35'),
(16, 34, 1, NULL, NULL, NULL, NULL, NULL, '2017-09-23 16:23:02', '2017-09-23 19:05:40'),
(17, 35, 0, NULL, NULL, NULL, NULL, NULL, '2017-09-23 19:49:51', '2017-09-23 19:50:00'),
(18, 36, 1, NULL, NULL, 3, 5, NULL, '2017-09-25 06:23:14', '2017-09-26 18:04:38'),
(19, 37, 1.2, NULL, NULL, 1, 2, NULL, '2017-09-25 09:12:31', '2017-09-25 09:49:06'),
(20, 38, 0, NULL, NULL, NULL, NULL, NULL, '2017-09-26 12:25:17', '2017-09-26 12:26:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `cooling_down_slap`
--
ALTER TABLE `cooling_down_slap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eventpost_comments`
--
ALTER TABLE `eventpost_comments`
  ADD PRIMARY KEY (`event_comment_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_association`
--
ALTER TABLE `event_association`
  ADD PRIMARY KEY (`event_association_id`);

--
-- Indexes for table `event_comments`
--
ALTER TABLE `event_comments`
  ADD PRIMARY KEY (`event_comment_id`);

--
-- Indexes for table `event_co_host`
--
ALTER TABLE `event_co_host`
  ADD PRIMARY KEY (`event_co_host_id`);

--
-- Indexes for table `event_invitations`
--
ALTER TABLE `event_invitations`
  ADD PRIMARY KEY (`event_invitation_id`);

--
-- Indexes for table `event_media_like`
--
ALTER TABLE `event_media_like`
  ADD PRIMARY KEY (`event_media_like_id`);

--
-- Indexes for table `event_posts`
--
ALTER TABLE `event_posts`
  ADD PRIMARY KEY (`event_post_id`);

--
-- Indexes for table `userpost_comments`
--
ALTER TABLE `userpost_comments`
  ADD PRIMARY KEY (`userpost_comment_id`);

--
-- Indexes for table `userpost_media_like`
--
ALTER TABLE `userpost_media_like`
  ADD PRIMARY KEY (`userpost_media_like_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`user_device_id`);

--
-- Indexes for table `user_network`
--
ALTER TABLE `user_network`
  ADD PRIMARY KEY (`user_network_id`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`user_notification_id`);

--
-- Indexes for table `user_posts`
--
ALTER TABLE `user_posts`
  ADD PRIMARY KEY (`user_post_id`);

--
-- Indexes for table `user_posts_to`
--
ALTER TABLE `user_posts_to`
  ADD PRIMARY KEY (`user_posts_to_id`);

--
-- Indexes for table `user_post_notifications`
--
ALTER TABLE `user_post_notifications`
  ADD PRIMARY KEY (`user_post_notification_id`);

--
-- Indexes for table `user_ranking`
--
ALTER TABLE `user_ranking`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `cooling_down_slap`
--
ALTER TABLE `cooling_down_slap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `eventpost_comments`
--
ALTER TABLE `eventpost_comments`
  MODIFY `event_comment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `event_association`
--
ALTER TABLE `event_association`
  MODIFY `event_association_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `event_comments`
--
ALTER TABLE `event_comments`
  MODIFY `event_comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `event_co_host`
--
ALTER TABLE `event_co_host`
  MODIFY `event_co_host_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT for table `event_invitations`
--
ALTER TABLE `event_invitations`
  MODIFY `event_invitation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `event_media_like`
--
ALTER TABLE `event_media_like`
  MODIFY `event_media_like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `event_posts`
--
ALTER TABLE `event_posts`
  MODIFY `event_post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `userpost_comments`
--
ALTER TABLE `userpost_comments`
  MODIFY `userpost_comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `userpost_media_like`
--
ALTER TABLE `userpost_media_like`
  MODIFY `userpost_media_like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `user_device_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user_network`
--
ALTER TABLE `user_network`
  MODIFY `user_network_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `user_notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=531;
--
-- AUTO_INCREMENT for table `user_posts`
--
ALTER TABLE `user_posts`
  MODIFY `user_post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `user_posts_to`
--
ALTER TABLE `user_posts_to`
  MODIFY `user_posts_to_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT for table `user_post_notifications`
--
ALTER TABLE `user_post_notifications`
  MODIFY `user_post_notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;
--
-- AUTO_INCREMENT for table `user_ranking`
--
ALTER TABLE `user_ranking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
