-- phpMyAdmin SQL Dump
-- version 3.4.3.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2014 at 12:01 PM
-- Server version: 5.0.95
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fdcms_framework`
--

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_floorplans`
--

CREATE TABLE IF NOT EXISTS `fdcms_floorplans` (
  `floorplan_id` int(11) NOT NULL auto_increment,
  `floorplan_name` varchar(255) NOT NULL,
  `floorplan_price` int(25) NOT NULL,
  `floorplan_image` varchar(255) NOT NULL,
  `floorplan_br` int(11) NOT NULL,
  `floorplan_ba` int(11) NOT NULL,
  `floorplan_sf` int(11) NOT NULL,
  `floorplan_sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`floorplan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_floorplans_categories`
--

CREATE TABLE IF NOT EXISTS `fdcms_floorplans_categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_floorplans_f2c`
--

CREATE TABLE IF NOT EXISTS `fdcms_floorplans_f2c` (
  `floorplan_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_forms`
--

CREATE TABLE IF NOT EXISTS `fdcms_forms` (
  `form_id` int(11) NOT NULL auto_increment,
  `form_name` varchar(255) NOT NULL,
  `form_to` varchar(255) NOT NULL,
  `form_from` varchar(255) NOT NULL,
  `form_cc` varchar(255) NOT NULL,
  `form_bcc` varchar(255) NOT NULL,
  `form_subject` varchar(150) NOT NULL,
  `form_response_action` int(11) NOT NULL default '1',
  `form_response_message` text NOT NULL,
  `form_response_forward` varchar(255) NOT NULL default '/',
  PRIMARY KEY  (`form_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `fdcms_forms`
--

INSERT INTO `fdcms_forms` (`form_id`, `form_name`, `form_to`, `form_from`, `form_cc`, `form_bcc`, `form_subject`, `form_response_action`, `form_response_message`, `form_response_forward`) VALUES
(6, 'Contact Form', 'manager.pearlmidtown@morgangroup.com', 'noreply@pearlmidtown.com', '', '', 'FDCMS - Contact Us', 2, '<p>Thanks for filling out this form, dude.</p>', '/contact-us/thank-you');

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_forms_f2f`
--

CREATE TABLE IF NOT EXISTS `fdcms_forms_f2f` (
  `field_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fdcms_forms_f2f`
--

INSERT INTO `fdcms_forms_f2f` (`field_id`, `form_id`) VALUES
(66, 6),
(65, 6),
(64, 6),
(63, 6);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_forms_fields`
--

CREATE TABLE IF NOT EXISTS `fdcms_forms_fields` (
  `field_id` int(11) NOT NULL auto_increment,
  `field_type` varchar(255) NOT NULL,
  `field_label` varchar(255) NOT NULL,
  `field_options` text NOT NULL,
  `field_required` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

--
-- Dumping data for table `fdcms_forms_fields`
--

INSERT INTO `fdcms_forms_fields` (`field_id`, `field_type`, `field_label`, `field_options`, `field_required`, `sort_order`) VALUES
(66, 'email', 'Your Email', '[]', 1, 1),
(65, 'tel', 'Your Phone', '[]', 0, 2),
(64, 'text', 'Your Name', '[]', 1, 0),
(63, 'textarea', 'Message', '[]', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_icons`
--

CREATE TABLE IF NOT EXISTS `fdcms_icons` (
  `icon_id` int(11) NOT NULL auto_increment,
  `icon_key` varchar(100) NOT NULL,
  `icon_src` text NOT NULL,
  PRIMARY KEY  (`icon_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `fdcms_icons`
--

INSERT INTO `fdcms_icons` (`icon_id`, `icon_key`, `icon_src`) VALUES
(1, 'image/jpeg', '/images/app/icons/icon_jpg.jpg'),
(2, 'image/png', '/images/app/icons/icon_png.jpg'),
(3, 'application/pdf', '/images/app/icons/icon_pdf.jpg'),
(4, 'image/gif', '/images/app/icons/icon_gif.jpg'),
(5, 'application/zip', '/images/app/icons/icon_zip.jpg'),
(6, 'application/vnd.ms-powerpoint', '/images/app/icons/icon_ppt.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_locations`
--

CREATE TABLE IF NOT EXISTS `fdcms_locations` (
  `location_id` int(11) NOT NULL auto_increment,
  `location_name` varchar(255) NOT NULL,
  `location_summary` text NOT NULL,
  `location_street` varchar(200) NOT NULL,
  `location_city` varchar(150) NOT NULL,
  `location_state` varchar(25) NOT NULL,
  `location_zip` varchar(15) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`location_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_locations_api`
--

CREATE TABLE IF NOT EXISTS `fdcms_locations_api` (
  `setting_id` int(11) NOT NULL auto_increment,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY  (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `fdcms_locations_api`
--

INSERT INTO `fdcms_locations_api` (`setting_id`, `setting_key`, `setting_value`) VALUES
(1, 'api_key', 'AIzaSyDdf7QGFdDxW25Q-d97UknrXc3Q5YqD3Pk');

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_locations_c2m`
--

CREATE TABLE IF NOT EXISTS `fdcms_locations_c2m` (
  `category_id` int(11) NOT NULL,
  `map_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fdcms_locations_c2m`
--

INSERT INTO `fdcms_locations_c2m` (`category_id`, `map_id`) VALUES
(0, 5),
(0, 6),
(0, 7),
(0, 8),
(13, 2),
(10, 2),
(7, 2),
(8, 2),
(9, 2),
(12, 2),
(0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_locations_categories`
--

CREATE TABLE IF NOT EXISTS `fdcms_locations_categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_locations_l2c`
--

CREATE TABLE IF NOT EXISTS `fdcms_locations_l2c` (
  `location_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_locations_l2m`
--

CREATE TABLE IF NOT EXISTS `fdcms_locations_l2m` (
  `location_id` int(11) NOT NULL,
  `map_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fdcms_locations_l2m`
--

INSERT INTO `fdcms_locations_l2m` (`location_id`, `map_id`) VALUES
(11, 6),
(43, 5),
(42, 5),
(12, 6),
(42, 7),
(43, 7),
(42, 8),
(43, 8),
(0, 2),
(44, 4);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_locations_maps`
--

CREATE TABLE IF NOT EXISTS `fdcms_locations_maps` (
  `map_id` int(11) NOT NULL auto_increment,
  `map_name` varchar(255) NOT NULL,
  `map_type` varchar(15) NOT NULL,
  `map_zoom` int(11) NOT NULL default '1',
  `map_center` varchar(255) NOT NULL default '(0,0)',
  `map_styles` text NOT NULL,
  `map_slug` varchar(255) NOT NULL,
  PRIMARY KEY  (`map_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_media_f2p`
--

CREATE TABLE IF NOT EXISTS `fdcms_media_f2p` (
  `file_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_media_files`
--

CREATE TABLE IF NOT EXISTS `fdcms_media_files` (
  `file_id` int(11) NOT NULL auto_increment,
  `file_title` varchar(255) NOT NULL,
  `file_src` text NOT NULL,
  `file_size` varchar(150) NOT NULL,
  `file_type` varchar(150) NOT NULL,
  `file_upload_date` date NOT NULL,
  `file_sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_media_galleries`
--

CREATE TABLE IF NOT EXISTS `fdcms_media_galleries` (
  `gallery_id` int(11) NOT NULL auto_increment,
  `gallery_name` varchar(255) NOT NULL,
  `gallery_slug` varchar(255) NOT NULL,
  PRIMARY KEY  (`gallery_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_media_i2g`
--

CREATE TABLE IF NOT EXISTS `fdcms_media_i2g` (
  `image_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_media_i2p`
--

CREATE TABLE IF NOT EXISTS `fdcms_media_i2p` (
  `image_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fdcms_media_i2p`
--

INSERT INTO `fdcms_media_i2p` (`image_id`, `page_id`) VALUES
(186, 1),
(187, 1),
(38, 1),
(185, 1),
(197, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_media_images`
--

CREATE TABLE IF NOT EXISTS `fdcms_media_images` (
  `image_id` int(11) NOT NULL auto_increment,
  `image_title` varchar(255) NOT NULL,
  `image_link` text NOT NULL,
  `image_src` text NOT NULL,
  `image_size` varchar(150) NOT NULL,
  `image_width` varchar(25) NOT NULL default '0',
  `image_height` varchar(25) NOT NULL default '0',
  `image_type` varchar(150) NOT NULL,
  `image_upload_date` date NOT NULL,
  `image_sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=198 ;

--
-- Dumping data for table `fdcms_media_images`
--

INSERT INTO `fdcms_media_images` (`image_id`, `image_title`, `image_link`, `image_src`, `image_size`, `image_width`, `image_height`, `image_type`, `image_upload_date`, `image_sort_order`) VALUES
(38, 'RESERVE ONLINE or call', '', '/uploads/images/pearl-midtown-sample-header_055c4eff1f55.jpg', '177098', '0', '0', 'image/jpeg', '0000-00-00', 2),
(186, 'RESERVE ONLINE or call', '', '/uploads/images/pearl-ss-3_e1507313909b.jpg', '329488', '0', '0', 'image/jpeg', '0000-00-00', 1),
(192, 'Pearl Midtown Sitemap', '', '/uploads/gallery/gallery_16f5d147d31b_sitemap.jpg', '353071', '1865', '1553', 'application/octet-stream', '0000-00-00', 3),
(187, 'RESERVE ONLINE or call', '', '/uploads/images/pearl-ss-4_4b3e272e99a4.jpg', '175495', '0', '0', 'image/jpeg', '0000-00-00', 3),
(193, 'Pearl Midtown Rendering', '', '/uploads/gallery/gallery_1c661dde6d4a_gallery-3.jpg', '553685', '1920', '1207', 'application/octet-stream', '0000-00-00', 4),
(194, 'Pearl Midtown Rendering', '', '/uploads/gallery/gallery_949f1063d6d3_gallery-1.jpg', '620762', '1920', '1233', 'application/octet-stream', '0000-00-00', 2),
(195, 'Convenient Access to Downtown Houston', '', '/uploads/gallery/gallery_9243f64f803a_gallery-2.jpg', '657977', '1650', '1119', 'application/octet-stream', '0000-00-00', 1),
(196, 'Fitness Center', '', '/uploads/gallery/gallery_301511271924_gallery-5.jpg', '495187', '1920', '1280', 'application/octet-stream', '0000-00-00', 0),
(197, 'RESERVE ONLINE or call', 'https://player.vimeo.com/video/91296556?" class="vimeo slide-link', '/uploads/images/ss-5_e7328935baf1.jpg', '98645', '0', '0', 'image/jpeg', '0000-00-00', 0),
(172, 'pearl-22.jpg', '', '/uploads/gallery/gallery_ef5bf05611d6_pearl-22.jpg', '81459', '310', '206', 'application/octet-stream', '0000-00-00', 4),
(173, 'pearl-17.jpg', '', '/uploads/gallery/gallery_578d8b9f591d_pearl-17.jpg', '105217', '310', '206', 'application/octet-stream', '0000-00-00', 5),
(174, 'pearl-20.jpg', '', '/uploads/gallery/gallery_25d7c4ed0baf_pearl-20.jpg', '107080', '310', '206', 'application/octet-stream', '0000-00-00', 8),
(175, 'pearl-18.jpg', '', '/uploads/gallery/gallery_23adb8b4b839_pearl-18.jpg', '105712', '310', '206', 'application/octet-stream', '0000-00-00', 7),
(176, 'pearl-19.jpg', '', '/uploads/gallery/gallery_3b94a38f86c5_pearl-19.jpg', '101801', '310', '206', 'application/octet-stream', '0000-00-00', 6),
(177, 'pearl-21.jpg', '', '/uploads/gallery/gallery_69e8c005cf0c_pearl-21.jpg', '95284', '310', '466', 'application/octet-stream', '0000-00-00', 3),
(178, 'pearl-26.jpg', '', '/uploads/gallery/gallery_e69f5ad6a9cb_pearl-26.jpg', '91191', '310', '206', 'application/octet-stream', '0000-00-00', 0),
(179, 'pearl-31.jpg', '', '/uploads/gallery/gallery_537db1133fef_pearl-31.jpg', '102720', '310', '206', 'application/octet-stream', '0000-00-00', 1),
(180, 'pearl-23.jpg', '', '/uploads/gallery/gallery_19dafa21feb9_pearl-23.jpg', '69836', '310', '206', 'application/octet-stream', '0000-00-00', 2),
(181, 'pearl-30.jpg', '', '/uploads/gallery/gallery_e3fb08b51859_pearl-30.jpg', '103907', '310', '206', 'application/octet-stream', '0000-00-00', 9),
(182, 'pearl-25.jpg', '', '/uploads/gallery/gallery_85ce8e47a4df_pearl-25.jpg', '90110', '310', '206', 'application/octet-stream', '0000-00-00', 10),
(183, 'pearl-24.jpg', '', '/uploads/gallery/gallery_351848741cd6_pearl-24.jpg', '73082', '310', '206', 'application/octet-stream', '0000-00-00', 11),
(184, 'pearl-32.jpg', '', '/uploads/gallery/gallery_7417473e03e2_pearl-32.jpg', '102474', '310', '206', 'application/octet-stream', '0000-00-00', 12),
(185, 'RESERVE ONLINE or call', '', '/uploads/images/pearl-ss-2_daf068b3152b.jpg', '224287', '0', '0', 'image/jpeg', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_media_v2p`
--

CREATE TABLE IF NOT EXISTS `fdcms_media_v2p` (
  `video_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fdcms_media_v2p`
--

INSERT INTO `fdcms_media_v2p` (`video_id`, `page_id`) VALUES
(27, 1),
(26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_media_videos`
--

CREATE TABLE IF NOT EXISTS `fdcms_media_videos` (
  `video_id` int(11) NOT NULL auto_increment,
  `video_title` varchar(150) NOT NULL,
  `video_desc` text NOT NULL,
  `video_embed_code` text NOT NULL,
  `video_embed_id` varchar(255) NOT NULL,
  `video_embed_type` varchar(150) NOT NULL COMMENT '''youtube'' OR ''vimeo''',
  `video_sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`video_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `fdcms_media_videos`
--

INSERT INTO `fdcms_media_videos` (`video_id`, `video_title`, `video_desc`, `video_embed_code`, `video_embed_id`, `video_embed_type`, `video_sort_order`) VALUES
(27, 'Le Sirenuse', '', '', '95034569', 'vimeo', 2),
(26, 'Turning Fear Into Fuel: Jonathan Fields at TEDxCMU 2010', 'How to turn fear from a source of anxiety and paralysis into fuel for action and achievement. \n\nJonathan Fields is a former private equity attorney turned lifestyle-entrepreneur, blogger, marketer, speaker and author of Career Renegade: How to Make a Great Living Doing What You Love(Broadway 2009). He writes about the crossroads between family, passion, entrepreneurship, social media and marketing at JonathanFields. com and has been featured in The New York Times, Wall Street Journal, BusinessWeek, and hundreds of magazines, radio shows and websites. And, BusinessWeek named him one of the 20 people entrepreneurs need to follow on twitter (@jonathanfields). When not writing, speaking or building something, you can usually find him dancing around his living room with his wife and daughter.\n\nAbout TEDx, x = independently organized event \n\nIn the spirit of ideas worth spreading, TEDx is a program of local, self-organized events that bring people together to share a TED-like experience. At a TEDx event, TEDTalks video and live speakers combine to spark deep discussion and connection in a small group. These local, self-organized events are branded TEDx, where x = independently organized TED event. The TED Conference provides general guidance for the TEDx program, but individual TEDx events are self-organized.* (*Subject to certain rules and regulations)', '', 'pkFRwhJEOos', 'youtube', 0);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_menus`
--

CREATE TABLE IF NOT EXISTS `fdcms_menus` (
  `menu_id` int(11) NOT NULL auto_increment,
  `menu_name` varchar(255) NOT NULL,
  `menu_slug` varchar(255) NOT NULL,
  PRIMARY KEY  (`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `fdcms_menus`
--

INSERT INTO `fdcms_menus` (`menu_id`, `menu_name`, `menu_slug`) VALUES
(1, 'Primary Navigation', 'primary');

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_menus_items`
--

CREATE TABLE IF NOT EXISTS `fdcms_menus_items` (
  `item_id` int(11) NOT NULL auto_increment,
  `item_identity` int(11) NOT NULL,
  `item_menu` int(11) NOT NULL,
  `item_page` int(11) NOT NULL default '0',
  `item_text` varchar(255) NOT NULL,
  `item_url` text NOT NULL,
  `item_parent_item` int(11) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=280 ;

--
-- Dumping data for table `fdcms_menus_items`
--

INSERT INTO `fdcms_menus_items` (`item_id`, `item_identity`, `item_menu`, `item_page`, `item_text`, `item_url`, `item_parent_item`, `sort_order`) VALUES
(279, 1, 1, 1, 'Home', '/', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_pages`
--

CREATE TABLE IF NOT EXISTS `fdcms_pages` (
  `page_id` int(11) NOT NULL auto_increment,
  `page_index` int(11) NOT NULL default '0',
  `page_locked` int(11) NOT NULL default '0',
  `page_layout` varchar(150) NOT NULL default 'layout.php',
  `page_name` varchar(255) NOT NULL,
  `page_subtitle` varchar(255) NOT NULL,
  `page_url` varchar(255) NOT NULL,
  `page_parent` int(11) NOT NULL,
  `page_content` text NOT NULL,
  `page_meta_title` varchar(100) NOT NULL,
  `page_meta_desc` varchar(155) NOT NULL,
  `page_meta_canonical` text NOT NULL,
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

--
-- Dumping data for table `fdcms_pages`
--

INSERT INTO `fdcms_pages` (`page_id`, `page_index`, `page_locked`, `page_layout`, `page_name`, `page_subtitle`, `page_url`, `page_parent`, `page_content`, `page_meta_title`, `page_meta_desc`, `page_meta_canonical`, `sort_order`) VALUES
(1, 1, 1, 'layout.php', 'Home', '', '/', 0, '{"Main_Content":"%3Cp%3EWelcome%20to%20the%20FirmDesign%20CMS%20v4.0%3C/p%3E"}', 'FDCMS v4.0', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_url_rewrites`
--

CREATE TABLE IF NOT EXISTS `fdcms_url_rewrites` (
  `rewrite_id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  `page_id` int(11) NOT NULL default '0',
  `news_id` int(11) NOT NULL default '0',
  `event_id` int(11) NOT NULL default '0',
  `blog_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`rewrite_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

--
-- Dumping data for table `fdcms_url_rewrites`
--

INSERT INTO `fdcms_url_rewrites` (`rewrite_id`, `url`, `page_id`, `news_id`, `event_id`, `blog_id`) VALUES
(5, '/', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fdcms_users`
--

CREATE TABLE IF NOT EXISTS `fdcms_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_active` int(11) NOT NULL default '1',
  `user_fname` varchar(155) NOT NULL,
  `user_lname` varchar(155) NOT NULL,
  `user_password` varchar(55) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `fdcms_users`
--

INSERT INTO `fdcms_users` (`user_id`, `user_active`, `user_fname`, `user_lname`, `user_password`, `user_email`) VALUES
(1, 1, 'Brian', 'Fleming', 'ee309b4028b56104e362fcd60f2d4c76', 'brian@firmdesign.com'),
(2, 1, 'Jesse', 'Brewer', 'ee309b4028b56104e362fcd60f2d4c76', 'jesse@firmdesign.com'),
(3, 1, 'Mark', 'Gammill', 'ee309b4028b56104e362fcd60f2d4c76', 'mark@firmdesign.com'),
(4, 1, 'Nick', 'Zinkie', 'ee309b4028b56104e362fcd60f2d4c76', 'nick@firmdesign.com'),
(5, 1, 'Michelle', 'Chapman', 'ee309b4028b56104e362fcd60f2d4c76', 'michelle@firmdesign.com'),
(6, 1, 'Matt', 'Pawlowski', 'ee309b4028b56104e362fcd60f2d4c76', 'matt@firmdesign.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
