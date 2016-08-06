DROP TABLE IF EXISTS `#__correspondence_header`;
DROP TABLE IF EXISTS `#__correspondence_users`;

CREATE TABLE IF NOT EXISTS `#__correspondence_header` (
  `postid` int(10) unsigned NOT NULL,
  `parent` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT 'Alias value, used for SEF URLs',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__groups table',
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Published state (1=published, 0=unpublished, -2=trashed)',
  `access` int(11) NOT NULL DEFAULT '1' COMMENT 'Used to control access to subscriptions',
  `params` text NOT NULL COMMENT 'For possible future use to add item-level parameters (JSON string format)',
  `language` char(7) NOT NULL DEFAULT '' COMMENT 'For possible future use to add language switching',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__users table for user who created this item',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__users table for user who modified this item',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date to start publishing this item',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date to stop publishing this item', 
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `catid` int(11) NOT NULL DEFAULT '0',
  `url` varchar(500) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metadata` text NOT NULL,
  `status` int(11) DEFAULT '0' COMMENT 'Δείχνει αν ο χρήστης εχει επιλέξει χρήστες για αποστολή.',
  `children` int(1) NOT NULL DEFAULT '0',
  `description` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__correspondence_header`
  ADD PRIMARY KEY (`postid`),
  ADD FULLTEXT KEY `Haystack` (`title`,`description`);

ALTER TABLE `#__correspondence_header`
  MODIFY `postid` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `#__correspondence_users` (
  `userid` int(10) unsigned NOT NULL,
  `postid` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `box` enum('0','1') NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__correspondence_users`
  ADD PRIMARY KEY (`userid`,`postid`);



