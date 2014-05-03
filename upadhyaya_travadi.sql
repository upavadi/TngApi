-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2014 at 11:34 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `upadhyaya_travadi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tng_addresses`
--

CREATE TABLE IF NOT EXISTS `tng_addresses` (
  `addressID` int(11) NOT NULL AUTO_INCREMENT,
  `address1` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(64) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `country` varchar(64) NOT NULL,
  `www` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `gedcom` varchar(20) NOT NULL,
  PRIMARY KEY (`addressID`),
  KEY `address` (`gedcom`,`country`,`state`,`city`,`address1`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_albumlinks`
--

CREATE TABLE IF NOT EXISTS `tng_albumlinks` (
  `albumlinkID` int(11) NOT NULL AUTO_INCREMENT,
  `albumID` int(11) NOT NULL,
  `mediaID` int(11) NOT NULL,
  `ordernum` int(11) DEFAULT NULL,
  `defphoto` varchar(1) NOT NULL,
  PRIMARY KEY (`albumlinkID`),
  KEY `albumID` (`albumID`,`ordernum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=507 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_albumplinks`
--

CREATE TABLE IF NOT EXISTS `tng_albumplinks` (
  `alinkID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `linktype` char(1) NOT NULL,
  `entityID` varchar(100) NOT NULL,
  `eventID` varchar(10) NOT NULL,
  `albumID` int(11) NOT NULL,
  `ordernum` float NOT NULL,
  PRIMARY KEY (`alinkID`),
  UNIQUE KEY `alinkID` (`gedcom`,`entityID`,`albumID`),
  KEY `entityID` (`gedcom`,`entityID`,`ordernum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_albums`
--

CREATE TABLE IF NOT EXISTS `tng_albums` (
  `albumID` int(11) NOT NULL AUTO_INCREMENT,
  `albumname` varchar(50) NOT NULL,
  `description` text,
  `alwayson` tinyint(4) DEFAULT NULL,
  `keywords` text,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`albumID`),
  KEY `albumname` (`albumname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_associations`
--

CREATE TABLE IF NOT EXISTS `tng_associations` (
  `assocID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `personID` varchar(22) NOT NULL,
  `passocID` varchar(22) NOT NULL,
  `reltype` varchar(1) NOT NULL,
  `relationship` varchar(25) NOT NULL,
  PRIMARY KEY (`assocID`),
  KEY `assoc` (`gedcom`,`personID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_branches`
--

CREATE TABLE IF NOT EXISTS `tng_branches` (
  `branch` varchar(20) NOT NULL,
  `gedcom` varchar(20) NOT NULL,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`branch`),
  KEY `description` (`gedcom`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tng_branchlinks`
--

CREATE TABLE IF NOT EXISTS `tng_branchlinks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `branch` varchar(20) NOT NULL,
  `gedcom` varchar(20) NOT NULL,
  `persfamID` varchar(22) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `branch` (`gedcom`,`branch`,`persfamID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3455 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_cemeteries`
--

CREATE TABLE IF NOT EXISTS `tng_cemeteries` (
  `cemeteryID` int(11) NOT NULL AUTO_INCREMENT,
  `cemname` varchar(64) NOT NULL,
  `maplink` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `county` varchar(64) DEFAULT NULL,
  `state` varchar(64) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `zoom` tinyint(4) DEFAULT NULL,
  `notes` text,
  `place` varchar(248) NOT NULL,
  PRIMARY KEY (`cemeteryID`),
  KEY `cemname` (`cemname`),
  KEY `place` (`place`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_children`
--

CREATE TABLE IF NOT EXISTS `tng_children` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `familyID` varchar(22) NOT NULL,
  `personID` varchar(22) NOT NULL,
  `frel` varchar(20) NOT NULL,
  `mrel` varchar(20) NOT NULL,
  `sealdate` varchar(50) NOT NULL,
  `sealdatetr` date NOT NULL,
  `sealplace` text NOT NULL,
  `haskids` tinyint(4) NOT NULL,
  `ordernum` smallint(6) NOT NULL,
  `parentorder` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `familyID` (`gedcom`,`familyID`,`personID`),
  KEY `personID` (`gedcom`,`personID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1126 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_citations`
--

CREATE TABLE IF NOT EXISTS `tng_citations` (
  `citationID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `persfamID` varchar(22) NOT NULL,
  `eventID` varchar(10) NOT NULL,
  `sourceID` varchar(22) NOT NULL,
  `ordernum` float NOT NULL,
  `description` text NOT NULL,
  `citedate` varchar(50) NOT NULL,
  `citedatetr` date NOT NULL,
  `citetext` text NOT NULL,
  `page` text NOT NULL,
  `quay` varchar(2) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`citationID`),
  KEY `citation` (`gedcom`,`persfamID`,`eventID`,`sourceID`,`description`(20)),
  KEY `citations_fk3` (`gedcom`,`sourceID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_countries`
--

CREATE TABLE IF NOT EXISTS `tng_countries` (
  `country` varchar(64) NOT NULL,
  PRIMARY KEY (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tng_events`
--

CREATE TABLE IF NOT EXISTS `tng_events` (
  `eventID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `persfamID` varchar(22) NOT NULL,
  `eventtypeID` int(11) NOT NULL,
  `eventdate` varchar(50) NOT NULL,
  `eventdatetr` date NOT NULL,
  `eventplace` text NOT NULL,
  `age` varchar(12) NOT NULL,
  `agency` varchar(120) NOT NULL,
  `cause` varchar(90) NOT NULL,
  `addressID` varchar(10) NOT NULL,
  `parenttag` varchar(10) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`eventID`),
  KEY `persfamID` (`gedcom`,`persfamID`),
  KEY `eventplace` (`gedcom`,`eventplace`(20)),
  KEY `events_fk4` (`eventtypeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4302 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_eventtypes`
--

CREATE TABLE IF NOT EXISTS `tng_eventtypes` (
  `eventtypeID` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(10) NOT NULL,
  `description` varchar(90) NOT NULL,
  `display` text NOT NULL,
  `keep` tinyint(4) NOT NULL,
  `ordernum` smallint(6) NOT NULL,
  `type` char(1) NOT NULL,
  PRIMARY KEY (`eventtypeID`),
  UNIQUE KEY `typetagdesc` (`type`,`tag`,`description`),
  KEY `ordernum` (`ordernum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_families`
--

CREATE TABLE IF NOT EXISTS `tng_families` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `familyID` varchar(22) NOT NULL,
  `husband` varchar(22) NOT NULL,
  `wife` varchar(22) NOT NULL,
  `marrdate` varchar(50) NOT NULL,
  `marrdatetr` date NOT NULL,
  `marrplace` text NOT NULL,
  `marrtype` varchar(50) NOT NULL,
  `divdate` varchar(50) NOT NULL,
  `divdatetr` date NOT NULL,
  `divplace` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `sealdate` varchar(50) NOT NULL,
  `sealdatetr` date NOT NULL,
  `sealplace` text NOT NULL,
  `husborder` tinyint(4) NOT NULL,
  `wifeorder` tinyint(4) NOT NULL,
  `changedate` datetime NOT NULL,
  `living` tinyint(4) NOT NULL,
  `private` tinyint(4) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `changedby` varchar(20) NOT NULL,
  `edituser` varchar(20) NOT NULL,
  `edittime` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `familyID` (`gedcom`,`familyID`),
  KEY `husband` (`gedcom`,`husband`),
  KEY `wife` (`gedcom`,`wife`),
  KEY `marrplace` (`marrplace`(20)),
  KEY `divplace` (`divplace`(20)),
  KEY `changedate` (`changedate`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=538 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_languages`
--

CREATE TABLE IF NOT EXISTS `tng_languages` (
  `languageID` smallint(6) NOT NULL AUTO_INCREMENT,
  `display` varchar(100) NOT NULL,
  `folder` varchar(50) NOT NULL,
  `charset` varchar(30) NOT NULL,
  PRIMARY KEY (`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_media`
--

CREATE TABLE IF NOT EXISTS `tng_media` (
  `mediaID` int(11) NOT NULL AUTO_INCREMENT,
  `mediatypeID` varchar(20) NOT NULL,
  `mediakey` varchar(127) NOT NULL,
  `gedcom` varchar(20) NOT NULL,
  `form` varchar(10) NOT NULL,
  `path` varchar(127) DEFAULT NULL,
  `description` text,
  `datetaken` varchar(50) DEFAULT NULL,
  `placetaken` text,
  `notes` text,
  `owner` text,
  `thumbpath` varchar(127) DEFAULT NULL,
  `alwayson` tinyint(4) DEFAULT NULL,
  `map` text,
  `abspath` tinyint(4) DEFAULT NULL,
  `status` varchar(40) DEFAULT NULL,
  `showmap` smallint(6) DEFAULT NULL,
  `cemeteryID` int(11) DEFAULT NULL,
  `plot` text,
  `linktocem` tinyint(4) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `zoom` tinyint(4) DEFAULT NULL,
  `width` smallint(6) DEFAULT NULL,
  `height` smallint(6) DEFAULT NULL,
  `bodytext` text,
  `usenl` tinyint(4) DEFAULT NULL,
  `newwindow` tinyint(4) DEFAULT NULL,
  `usecollfolder` tinyint(4) DEFAULT NULL,
  `changedate` datetime DEFAULT NULL,
  `changedby` varchar(20) NOT NULL,
  PRIMARY KEY (`mediaID`),
  UNIQUE KEY `mediakey` (`gedcom`,`mediakey`),
  KEY `mediatypeID` (`mediatypeID`),
  KEY `changedate` (`changedate`),
  KEY `description` (`description`(20)),
  KEY `headstones` (`cemeteryID`,`description`(20))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=341 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_medialinks`
--

CREATE TABLE IF NOT EXISTS `tng_medialinks` (
  `medialinkID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `linktype` char(1) NOT NULL,
  `personID` varchar(100) NOT NULL,
  `eventID` varchar(10) NOT NULL,
  `mediaID` int(11) NOT NULL,
  `altdescription` text NOT NULL,
  `altnotes` text NOT NULL,
  `ordernum` float NOT NULL,
  `dontshow` tinyint(4) NOT NULL,
  `defphoto` varchar(1) NOT NULL,
  PRIMARY KEY (`medialinkID`),
  UNIQUE KEY `mediaID` (`gedcom`,`personID`,`mediaID`,`eventID`),
  KEY `personID` (`gedcom`,`personID`,`ordernum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=384 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_mediatypes`
--

CREATE TABLE IF NOT EXISTS `tng_mediatypes` (
  `mediatypeID` varchar(20) NOT NULL,
  `display` varchar(40) NOT NULL,
  `path` varchar(127) NOT NULL,
  `liketype` varchar(20) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `thumb` varchar(50) NOT NULL,
  `exportas` varchar(20) NOT NULL,
  `ordernum` tinyint(4) NOT NULL,
  PRIMARY KEY (`mediatypeID`),
  KEY `ordernum` (`ordernum`,`display`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tng_mostwanted`
--

CREATE TABLE IF NOT EXISTS `tng_mostwanted` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ordernum` float NOT NULL,
  `gedcom` varchar(20) NOT NULL,
  `mwtype` varchar(10) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `personID` varchar(22) NOT NULL,
  `mediaID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `mwtype` (`mwtype`,`ordernum`,`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_notelinks`
--

CREATE TABLE IF NOT EXISTS `tng_notelinks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `persfamID` varchar(22) NOT NULL,
  `gedcom` varchar(20) NOT NULL,
  `xnoteID` int(11) NOT NULL,
  `eventID` varchar(10) NOT NULL,
  `ordernum` float NOT NULL,
  `secret` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `notelinks` (`gedcom`,`persfamID`,`eventID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_people`
--

CREATE TABLE IF NOT EXISTS `tng_people` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `personID` varchar(22) NOT NULL,
  `lnprefix` varchar(25) NOT NULL,
  `lastname` varchar(127) NOT NULL,
  `firstname` varchar(127) NOT NULL,
  `birthdate` varchar(50) NOT NULL,
  `birthdatetr` date NOT NULL,
  `sex` tinytext NOT NULL,
  `birthplace` text NOT NULL,
  `deathdate` varchar(50) NOT NULL,
  `deathdatetr` date NOT NULL,
  `deathplace` text NOT NULL,
  `altbirthdate` varchar(50) NOT NULL,
  `altbirthdatetr` date NOT NULL,
  `altbirthplace` text NOT NULL,
  `burialdate` varchar(50) NOT NULL,
  `burialdatetr` date NOT NULL,
  `burialplace` text NOT NULL,
  `baptdate` varchar(50) NOT NULL,
  `baptdatetr` date NOT NULL,
  `baptplace` text NOT NULL,
  `endldate` varchar(50) NOT NULL,
  `endldatetr` date NOT NULL,
  `endlplace` text NOT NULL,
  `changedate` datetime NOT NULL,
  `nickname` text NOT NULL,
  `title` tinytext NOT NULL,
  `prefix` tinytext NOT NULL,
  `suffix` tinytext NOT NULL,
  `nameorder` tinyint(4) NOT NULL,
  `famc` varchar(22) NOT NULL,
  `metaphone` varchar(15) NOT NULL,
  `living` tinyint(4) NOT NULL,
  `private` tinyint(4) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `changedby` varchar(20) NOT NULL,
  `edituser` varchar(20) NOT NULL,
  `edittime` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `gedpers` (`gedcom`,`personID`),
  KEY `lastname` (`lastname`,`firstname`),
  KEY `firstname` (`firstname`),
  KEY `gedlast` (`gedcom`,`lastname`,`firstname`),
  KEY `gedfirst` (`gedcom`,`firstname`),
  KEY `birthplace` (`birthplace`(20)),
  KEY `altbirthplace` (`altbirthplace`(20)),
  KEY `deathplace` (`deathplace`(20)),
  KEY `burialplace` (`burialplace`(20)),
  KEY `baptplace` (`baptplace`(20)),
  KEY `endlplace` (`endlplace`(20)),
  KEY `changedate` (`changedate`),
  KEY `idx_personID` (`personID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1549 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_places`
--

CREATE TABLE IF NOT EXISTS `tng_places` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `place` varchar(248) NOT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `zoom` tinyint(4) DEFAULT NULL,
  `placelevel` tinyint(4) DEFAULT NULL,
  `temple` tinyint(4) NOT NULL,
  `geoignore` tinyint(4) NOT NULL,
  `notes` text,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `place` (`gedcom`,`place`),
  KEY `temple` (`temple`,`gedcom`,`place`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=635 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_reports`
--

CREATE TABLE IF NOT EXISTS `tng_reports` (
  `reportID` int(11) NOT NULL AUTO_INCREMENT,
  `reportname` varchar(80) NOT NULL,
  `reportdesc` text NOT NULL,
  `rank` int(11) NOT NULL,
  `display` text NOT NULL,
  `criteria` text NOT NULL,
  `orderby` text NOT NULL,
  `sqlselect` text NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`reportID`),
  KEY `reportname` (`reportname`),
  KEY `rank` (`rank`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=283 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_repositories`
--

CREATE TABLE IF NOT EXISTS `tng_repositories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `repoID` varchar(22) NOT NULL,
  `reponame` varchar(90) NOT NULL,
  `gedcom` varchar(20) NOT NULL,
  `addressID` int(11) NOT NULL,
  `changedate` datetime DEFAULT NULL,
  `changedby` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `repoID` (`gedcom`,`repoID`),
  KEY `reponame` (`reponame`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_saveimport`
--

CREATE TABLE IF NOT EXISTS `tng_saveimport` (
  `ID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `filename` varchar(100) DEFAULT NULL,
  `icount` int(11) DEFAULT NULL,
  `ioffset` int(11) DEFAULT NULL,
  `fcount` int(11) DEFAULT NULL,
  `foffset` int(11) DEFAULT NULL,
  `scount` int(11) DEFAULT NULL,
  `soffset` int(11) DEFAULT NULL,
  `offset` int(11) DEFAULT NULL,
  `delvar` varchar(10) DEFAULT NULL,
  `gedcom` varchar(20) DEFAULT NULL,
  `branch` varchar(20) DEFAULT NULL,
  `ncount` int(11) DEFAULT NULL,
  `noffset` int(11) DEFAULT NULL,
  `rcount` int(11) DEFAULT NULL,
  `roffset` int(11) DEFAULT NULL,
  `mcount` int(11) DEFAULT NULL,
  `pcount` int(11) DEFAULT NULL,
  `ucaselast` tinyint(4) DEFAULT NULL,
  `norecalc` tinyint(4) DEFAULT NULL,
  `media` tinyint(4) DEFAULT NULL,
  `neweronly` tinyint(4) DEFAULT NULL,
  `lasttype` tinyint(4) DEFAULT NULL,
  `lastid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_sources`
--

CREATE TABLE IF NOT EXISTS `tng_sources` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `gedcom` varchar(20) NOT NULL,
  `sourceID` varchar(22) NOT NULL,
  `callnum` varchar(50) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `title` text NOT NULL,
  `author` text NOT NULL,
  `publisher` text NOT NULL,
  `other` text NOT NULL,
  `shorttitle` text NOT NULL,
  `comments` text,
  `actualtext` text NOT NULL,
  `repoID` varchar(22) NOT NULL,
  `changedate` datetime DEFAULT NULL,
  `changedby` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `sourceID` (`gedcom`,`sourceID`),
  KEY `changedate` (`changedate`),
  FULLTEXT KEY `sourcetext` (`actualtext`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_states`
--

CREATE TABLE IF NOT EXISTS `tng_states` (
  `state` varchar(64) NOT NULL,
  PRIMARY KEY (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tng_temp_events`
--

CREATE TABLE IF NOT EXISTS `tng_temp_events` (
  `tempID` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(1) NOT NULL,
  `gedcom` varchar(20) NOT NULL,
  `personID` varchar(22) NOT NULL,
  `familyID` varchar(22) NOT NULL,
  `eventID` varchar(10) NOT NULL,
  `eventdate` varchar(50) NOT NULL,
  `eventplace` text NOT NULL,
  `info` text NOT NULL,
  `note` text NOT NULL,
  `user` varchar(20) NOT NULL,
  `postdate` datetime NOT NULL,
  PRIMARY KEY (`tempID`),
  KEY `gedtype` (`gedcom`,`type`),
  KEY `user` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_timelineevents`
--

CREATE TABLE IF NOT EXISTS `tng_timelineevents` (
  `tleventID` int(11) NOT NULL AUTO_INCREMENT,
  `evday` tinyint(4) NOT NULL,
  `evmonth` tinyint(4) NOT NULL,
  `evyear` varchar(10) NOT NULL,
  `endday` tinyint(4) NOT NULL,
  `endmonth` tinyint(4) NOT NULL,
  `endyear` varchar(10) NOT NULL,
  `evtitle` varchar(128) NOT NULL,
  `evdetail` text NOT NULL,
  PRIMARY KEY (`tleventID`),
  KEY `evyear` (`evyear`,`evmonth`,`evday`,`evdetail`(100)),
  KEY `evdetail` (`evdetail`(100))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_trees`
--

CREATE TABLE IF NOT EXISTS `tng_trees` (
  `gedcom` varchar(20) NOT NULL,
  `treename` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `owner` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(40) NOT NULL,
  `state` varchar(30) NOT NULL,
  `country` varchar(30) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `secret` tinyint(4) NOT NULL,
  `disallowgedcreate` tinyint(4) NOT NULL,
  `disallowpdf` tinyint(4) NOT NULL,
  `lastimportdate` datetime DEFAULT NULL,
  PRIMARY KEY (`gedcom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tng_users`
--

CREATE TABLE IF NOT EXISTS `tng_users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `password_type` varchar(10) NOT NULL,
  `gedcom` varchar(20) DEFAULT NULL,
  `mygedcom` varchar(20) NOT NULL,
  `personID` varchar(22) NOT NULL,
  `role` varchar(15) NOT NULL,
  `allow_edit` tinyint(4) NOT NULL,
  `allow_add` tinyint(4) NOT NULL,
  `tentative_edit` tinyint(4) NOT NULL,
  `allow_delete` tinyint(4) NOT NULL,
  `allow_lds` tinyint(4) NOT NULL,
  `allow_ged` tinyint(4) NOT NULL,
  `allow_pdf` tinyint(4) NOT NULL,
  `allow_living` tinyint(4) NOT NULL,
  `allow_private` tinyint(4) NOT NULL,
  `allow_profile` tinyint(4) NOT NULL,
  `branch` varchar(20) DEFAULT NULL,
  `realname` varchar(50) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` varchar(64) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `disabled` tinyint(4) NOT NULL,
  `dt_registered` datetime DEFAULT NULL,
  `dt_activated` datetime DEFAULT NULL,
  `no_email` tinyint(4) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=125 ;

-- --------------------------------------------------------

--
-- Table structure for table `tng_xnotes`
--

CREATE TABLE IF NOT EXISTS `tng_xnotes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `noteID` varchar(22) NOT NULL,
  `gedcom` varchar(20) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `noteID` (`gedcom`,`noteID`),
  KEY `xnotes_fk1` (`gedcom`,`ID`),
  FULLTEXT KEY `note` (`note`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;
