DROP TABLE IF EXISTS `#__openhrm_countries`;
CREATE TABLE IF NOT EXISTS `#__openhrm_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL,
  `iso_code_3` varchar(3) NOT NULL,
  `address_format` text NOT NULL,
  `postcode_required` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__openhrm_states`;
CREATE TABLE IF NOT EXISTS `#__openhrm_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `code` varchar(32) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__openhrm_timezones`;
CREATE TABLE IF NOT EXISTS `#__openhrm_timezones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timezone_value` float(3,1) NOT NULL DEFAULT '0.0',
  `timezone_text` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__openhrm_organization_infos`;
CREATE TABLE IF NOT EXISTS `#__openhrm_organization_infos` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tax_code` varchar(30) DEFAULT NULL,
  `registration_number` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `province` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `zip_code` varchar(30) DEFAULT NULL,
  `street1` varchar(100) DEFAULT NULL,
  `street2` varchar(100) DEFAULT NULL,
  `logo` varchar(30) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__openhrm_marital_states`;
CREATE TABLE IF NOT EXISTS `#__openhrm_marital_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__openhrm_usergroups`;
CREATE TABLE IF NOT EXISTS `#__openhrm_usergroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Adjacency List Reference Id',
  `lft` int(11) NOT NULL DEFAULT 0 COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT 0 COMMENT 'Nested set rgt.',
  `title` varchar(255) DEFAULT '',
  `alias` varchar(255) DEFAULT '',
  `description` text,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `created_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT '0000-00-00 00:00:00',
  `deleted_by` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `deleted_flg` tinyint(1) unsigned DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_openhrm_usergroup_parent_title_lookup` (`parent_id`,`title`),
  KEY `idx_openhrm_usergroup_title_lookup` (`title`),
  KEY `idx_openhrm_usergroup_adjacency_lookup` (`parent_id`),
  KEY `idx_openhrm_usergroup_nested_set_lookup` (`lft`,`rgt`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__openhrm_settings`;
CREATE TABLE IF NOT EXISTS `#__openhrm_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `modified` datetime DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `deleted` datetime DEFAULT '0000-00-00 00:00:00',
  `deleted_by` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `deleted_flg` tinyint(1) unsigned DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 PACK_KEYS=0;

  DROP TABLE IF EXISTS `#__openhrm_employees`;
  CREATE TABLE IF NOT EXISTS `#__openhrm_employees` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
    `company_id` int(11) NOT NULL DEFAULT '0',
    `employee_number` int(7) NOT NULL DEFAULT '0',
    `employee_code` varchar(50) DEFAULT NULL,
    `first_name` varchar(100) NOT NULL DEFAULT '',
    `middle_name` varchar(100) DEFAULT '',
    `last_name` varchar(100) NOT NULL DEFAULT '',
    `picture` varchar(100) NOT NULL DEFAULT '',
    `ssn_no` varchar(50) DEFAULT NULL,
    `nick_name` varchar(100) DEFAULT '',
    `gender` smallint(6) DEFAULT '0',
    `marital_state_id` int(11) NOT NULL DEFAULT '0',
    `birthday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `employee_status_id` int(11) NOT NULL DEFAULT '0',
    `smoker` tinyint(1) unsigned DEFAULT 0,
    `nationality_id` int(11) NOT NULL DEFAULT '0',
    `street1` varchar(100) DEFAULT '',
    `street2` varchar(100) DEFAULT '',
    `country_id` int(11) NOT NULL DEFAULT '0',
    `state_id` int(11) NOT NULL DEFAULT '0',
    `city` varchar(255) DEFAULT NULL,
    `zip_code` varchar(20) DEFAULT NULL,
    `home_phone` varchar(20) DEFAULT NULL,
    `work_phone` varchar(20) DEFAULT NULL,
    `mobile_phone` varchar(20) DEFAULT NULL,
    `work_email` varchar(100) DEFAULT NULL,
    `other_email` varchar(100) DEFAULT NULL,
    `driver_license_num` varchar(100) DEFAULT '',
    `driver_license_exp_date` date DEFAULT NULL,
    `military_service` varchar(100) DEFAULT '',
    `job_title_id` int(11) NOT NULL DEFAULT '0',
    `eeo_cat_id` int(11) NOT NULL DEFAULT '0',
    `work_station` int(6) DEFAULT NULL,
    `salary_grade_id` int(11) NOT NULL DEFAULT '0',
    `joined_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `terminal_reason_id` int(4) DEFAULT NULL,
    `terminal_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `ip` varchar(20) NOT NULL DEFAULT '',
    `created_date` datetime DEFAULT '0000-00-00 00:00:00',
    `created_by` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `modified_date` datetime DEFAULT '0000-00-00 00:00:00',
    `modified_by` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `deleted_date` datetime DEFAULT '0000-00-00 00:00:00',
    `deleted_by` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `deleted_flg` tinyint(1) unsigned DEFAULT 0,
    `published` tinyint(1) NOT NULL DEFAULT '1',
    `custom1` varchar(250) DEFAULT NULL,
    `custom2` varchar(250) DEFAULT NULL,
    `custom3` varchar(250) DEFAULT NULL,
    `custom4` varchar(250) DEFAULT NULL,
    `custom5` varchar(250) DEFAULT NULL,
    `custom6` varchar(250) DEFAULT NULL,
    `custom7` varchar(250) DEFAULT NULL,
    `custom8` varchar(250) DEFAULT NULL,
    `custom9` varchar(250) DEFAULT NULL,
    `custom10` varchar(250) DEFAULT NULL,

    `ethnic_race_code` varchar(13) DEFAULT NULL,
    `emp_sin_num` varchar(100) DEFAULT '',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;