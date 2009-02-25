CREATE TABLE IF NOT EXISTS `#__shortlink` (
      `id` int(11) NOT NULL auto_increment,
      `phrase` varchar(100) NOT NULL default '',
      `link` varchar(255) NOT NULL default '',
      `target` varchar(10) NOT NULL default '',
      `counter` int(11) NOT NULL default 0,
      `description` varchar(255) NOT NULL default '',
      `create_date` datetime NOT NULL default '0000-00-00 00:00:00',
      `last_call` datetime NOT NULL default '0000-00-00 00:00:00',
      PRIMARY KEY  (`id`),
      UNIQUE KEY `phrase` (`phrase`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
