# phpMyAdmin MySQL-Dump
# version 2.3.0
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Oct 15, 2002 at 06:50 PM
# Server version: 3.23.52
# PHP Version: 4.2.2
# Database : `lm_sensors`
# $Id: lm_sensors.sql,v 1.1 2002/10/15 16:53:01 holler Exp $
# --------------------------------------------------------

#
# Table structure for table `boards`
#

CREATE TABLE boards (
  no tinyint(4) NOT NULL auto_increment,
  manufacturer tinyint(4) NOT NULL default '0',
  name varchar(100) NOT NULL default '',
  chip1 tinyint(4) NOT NULL default '0',
  chip2 tinyint(4) default NULL,
  chip3 tinyint(4) default NULL,
  PRIMARY KEY  (no)
) TYPE=MyISAM;

#
# Dumping data for table `boards`
#

INSERT INTO boards VALUES (1, 1, 'MS-6368 Rev 5', 2, 3, NULL);
INSERT INTO boards VALUES (2, 2, 'P5A', 4, NULL, NULL);
# --------------------------------------------------------

#
# Table structure for table `chips`
#

CREATE TABLE chips (
  no tinyint(4) NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  count_in tinyint(4) NOT NULL default '0',
  count_fan tinyint(4) NOT NULL default '0',
  count_temp tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (no),
  UNIQUE KEY name (name)
) TYPE=MyISAM;

#
# Dumping data for table `chips`
#

INSERT INTO chips VALUES (1, 'lm78-*', 7, 0, 1);
INSERT INTO chips VALUES (2, 'lm80-*', 7, 2, 1);
INSERT INTO chips VALUES (3, 'via686a-*', 5, 2, 3);
INSERT INTO chips VALUES (4, 'w83781d-*', 7, 0, 1);
# --------------------------------------------------------

#
# Table structure for table `fan_descriptions`
#

CREATE TABLE fan_descriptions (
  no tinyint(4) NOT NULL auto_increment,
  board_no tinyint(4) NOT NULL default '0',
  processor_no tinyint(4) NOT NULL default '0',
  chip_no tinyint(4) NOT NULL default '0',
  fan_no tinyint(4) NOT NULL default '0',
  ignored char(1) NOT NULL default '0',
  label varchar(100) default NULL,
  min varchar(100) default NULL,
  PRIMARY KEY  (no),
  KEY board_nr (board_no),
  KEY processor_no (processor_no),
  KEY chip_no (chip_no)
) TYPE=MyISAM;

#
# Dumping data for table `fan_descriptions`
#

INSERT INTO fan_descriptions VALUES (1, 0, 0, 3, 0, '0', 'CPU Fan', NULL);
INSERT INTO fan_descriptions VALUES (2, 0, 0, 3, 1, '0', 'P/S Fan', NULL);
INSERT INTO fan_descriptions VALUES (3, 1, 1, 2, 1, '1', NULL, NULL);
# --------------------------------------------------------

#
# Table structure for table `in_descriptions`
#

CREATE TABLE in_descriptions (
  no tinyint(4) NOT NULL auto_increment,
  board_no tinyint(4) NOT NULL default '0',
  processor_no tinyint(4) NOT NULL default '0',
  chip_no tinyint(4) NOT NULL default '0',
  in_no tinyint(4) NOT NULL default '0',
  ignored char(1) NOT NULL default '0',
  label varchar(100) default NULL,
  compute varchar(100) default NULL,
  min varchar(100) default NULL,
  max varchar(100) default NULL,
  PRIMARY KEY  (no),
  KEY board_nr (board_no),
  KEY processor_nr (processor_no),
  KEY chip_nr (chip_no)
) TYPE=MyISAM;

#
# Dumping data for table `in_descriptions`
#

INSERT INTO in_descriptions VALUES (1, 0, 0, 3, 0, '0', 'CPU core', NULL, NULL, NULL);
INSERT INTO in_descriptions VALUES (2, 0, 0, 3, 1, '0', '+2.5V', NULL, NULL, NULL);
INSERT INTO in_descriptions VALUES (3, 0, 0, 3, 2, '0', 'I/O', NULL, NULL, NULL);
INSERT INTO in_descriptions VALUES (4, 0, 0, 3, 3, '0', '+5V', NULL, NULL, NULL);
INSERT INTO in_descriptions VALUES (5, 0, 0, 3, 4, '0', '+12V', NULL, NULL, NULL);
INSERT INTO in_descriptions VALUES (11, 0, 0, 2, 0, '0', '+5V', '(24/14.7 + 1) * @ ,       @ / (24/14.7 + 1)', '5 * 0.95', '5 * 0.95');
INSERT INTO in_descriptions VALUES (12, 0, 0, 2, 1, '0', 'VTT', NULL, '2*0.95', '2*1.05');
INSERT INTO in_descriptions VALUES (13, 0, 0, 2, 2, '0', '+3.3V', '(22.1/30 + 1) * @ ,       @ / (22.1/30 + 1)', '3.3 * 0.95', '3.3 * 1.05');
INSERT INTO in_descriptions VALUES (14, 0, 0, 2, 3, '0', '+Vcore', '(2.8/1.9) * @,            @ * 1.9/2.8', '1.9 * 0.95', '1.9 * 1.05');
INSERT INTO in_descriptions VALUES (15, 0, 0, 2, 4, '0', '+12V', '(160/30.1 + 1) * @,       @ / (160/30.1 + 1)', '12 * 0.95', '12 * 1.05');
INSERT INTO in_descriptions VALUES (16, 0, 0, 2, 5, '0', '-12V', '(160/35.7)*(@ - in0) + @, (@ + in0 * 160/35.7)/ (1 + 160/35.7)', '-12 * 1.05', '-12 * 0.95');
INSERT INTO in_descriptions VALUES (17, 0, 0, 2, 6, '0', '-5V', '(36/16.2)*(@ - in0) + @,  (@ + in0 * 36/16.2) / (1 + 36/16.2)', '-5 * 1.05', '-5 * 0.95');
# --------------------------------------------------------

#
# Table structure for table `manufacturers`
#

CREATE TABLE manufacturers (
  no tinyint(4) NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  PRIMARY KEY  (no),
  UNIQUE KEY name (name)
) TYPE=MyISAM;

#
# Dumping data for table `manufacturers`
#

INSERT INTO manufacturers VALUES (1, 'MSI');
INSERT INTO manufacturers VALUES (2, 'ASUS');
# --------------------------------------------------------

#
# Table structure for table `processors`
#

CREATE TABLE processors (
  no tinyint(4) NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  PRIMARY KEY  (no),
  UNIQUE KEY name (name)
) TYPE=MyISAM;

#
# Dumping data for table `processors`
#

INSERT INTO processors VALUES (1, 'Intel Celeron 1.20 GHz / 100 MHz System Bus with fan');
# --------------------------------------------------------

#
# Table structure for table `temp_descriptions`
#

CREATE TABLE temp_descriptions (
  no tinyint(4) NOT NULL auto_increment,
  board_no tinyint(4) NOT NULL default '0',
  processor_no tinyint(4) NOT NULL default '0',
  chip_no tinyint(4) NOT NULL default '0',
  temp_no tinyint(4) NOT NULL default '0',
  ignored char(1) NOT NULL default '0',
  label varchar(100) default NULL,
  over varchar(100) default NULL,
  hyst varchar(100) default NULL,
  PRIMARY KEY  (no),
  KEY board_nr (board_no),
  KEY processor_nr (processor_no),
  KEY chip_nr (chip_no)
) TYPE=MyISAM;

#
# Dumping data for table `temp_descriptions`
#

INSERT INTO temp_descriptions VALUES (1, 1, 0, 3, 0, '0', 'CPU Temp', NULL, NULL);
INSERT INTO temp_descriptions VALUES (2, 1, 0, 3, 1, '0', 'SYS Temp', NULL, NULL);
INSERT INTO temp_descriptions VALUES (7, 1, 1, 3, 0, '0', 'CPU Temp', 'want to know', 'want to know');
INSERT INTO temp_descriptions VALUES (4, 0, 0, 3, 0, '0', 'SYS Temp', NULL, NULL);
INSERT INTO temp_descriptions VALUES (5, 0, 0, 3, 1, '0', 'CPU Temp', NULL, NULL);
INSERT INTO temp_descriptions VALUES (6, 0, 0, 3, 2, '0', 'SBr Temp', NULL, NULL);

