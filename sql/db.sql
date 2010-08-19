# Sequel Pro dump
# Version 2492
# http://code.google.com/p/sequel-pro
#
# Host: 74.86.200.60 (MySQL 5.1.39-log)
# Database: piratewars
# Generation Time: 2010-08-19 18:44:41 -0400
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table banned
# ------------------------------------------------------------

DROP TABLE IF EXISTS `banned`;

CREATE TABLE `banned` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table battles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `battles`;

CREATE TABLE `battles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `user_id_2` bigint(20) DEFAULT NULL,
  `battle_type` char(1) DEFAULT NULL,
  `opponent_type` varchar(255) DEFAULT NULL,
  `result` char(1) NOT NULL DEFAULT 'p',
  `gold_change` int(11) NOT NULL DEFAULT '0',
  `converted_to` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id_2`),
  KEY `result` (`result`),
  KEY `battle_type` (`battle_type`)
) ENGINE=InnoDB AUTO_INCREMENT=28594965 DEFAULT CHARSET=latin1;



# Dump of table bombing_limits
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bombing_limits`;

CREATE TABLE `bombing_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `from_id` (`from_id`,`to_id`)
) ENGINE=InnoDB AUTO_INCREMENT=880627 DEFAULT CHARSET=latin1;



# Dump of table bombs_sent
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bombs_sent`;

CREATE TABLE `bombs_sent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `from_id` (`from_id`),
  KEY `to_id` (`to_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18990636 DEFAULT CHARSET=latin1;



# Dump of table booby_traps
# ------------------------------------------------------------

DROP TABLE IF EXISTS `booby_traps`;

CREATE TABLE `booby_traps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL DEFAULT '0',
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `to_id` (`to_id`,`used`)
) ENGINE=InnoDB AUTO_INCREMENT=2040731 DEFAULT CHARSET=latin1;



# Dump of table bot_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bot_log`;

CREATE TABLE `bot_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41598 DEFAULT CHARSET=latin1;



# Dump of table buy_item_indexing
# ------------------------------------------------------------

DROP TABLE IF EXISTS `buy_item_indexing`;

CREATE TABLE `buy_item_indexing` (
  `stuff_id` int(11) NOT NULL,
  `index_string` text,
  PRIMARY KEY (`stuff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table clicks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clicks`;

CREATE TABLE `clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_site` varchar(255) NOT NULL,
  `to_site` varchar(255) NOT NULL,
  `facebook_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70960 DEFAULT CHARSET=latin1;



# Dump of table clicks_out
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clicks_out`;

CREATE TABLE `clicks_out` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer_id` int(11) NOT NULL,
  `offer_text` varchar(255) NOT NULL,
  `fb_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35447 DEFAULT CHARSET=latin1;



# Dump of table coin_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `coin_log`;

CREATE TABLE `coin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `action` varchar(255) NOT NULL,
  `secondary_user` int(11) NOT NULL DEFAULT '0',
  `current_coin_total` int(11) NOT NULL DEFAULT '0',
  `current_buried_coin_total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id_2` (`user_id`,`date`)
) ENGINE=InnoDB AUTO_INCREMENT=30085314 DEFAULT CHARSET=latin1;



# Dump of table generic_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `generic_data`;

CREATE TABLE `generic_data` (
  `unique_identifier` varchar(255) NOT NULL,
  `generic_date` datetime NOT NULL,
  PRIMARY KEY (`unique_identifier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table items_posted_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `items_posted_table`;

CREATE TABLE `items_posted_table` (
  `uid` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `seller` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table items_sell
# ------------------------------------------------------------

DROP TABLE IF EXISTS `items_sell`;

CREATE TABLE `items_sell` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `stuff_id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=13704187 DEFAULT CHARSET=latin1;



# Dump of table items_sold_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `items_sold_table`;

CREATE TABLE `items_sold_table` (
  `uid` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `seller` int(11) NOT NULL,
  `buyer` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table jokes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jokes`;

CREATE TABLE `jokes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `approved` tinyint(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=511 DEFAULT CHARSET=latin1;



# Dump of table jokes_approved
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jokes_approved`;

CREATE TABLE `jokes_approved` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;



# Dump of table leader_barbary_highest_level
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_barbary_highest_level`;

CREATE TABLE `leader_barbary_highest_level` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_barbary_most_coins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_barbary_most_coins`;

CREATE TABLE `leader_barbary_most_coins` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_barbary_overall
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_barbary_overall`;

CREATE TABLE `leader_barbary_overall` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_buccaneer_highest_level
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_buccaneer_highest_level`;

CREATE TABLE `leader_buccaneer_highest_level` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_buccaneer_most_coins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_buccaneer_most_coins`;

CREATE TABLE `leader_buccaneer_most_coins` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_buccaneer_overall
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_buccaneer_overall`;

CREATE TABLE `leader_buccaneer_overall` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_corsair_highest_level
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_corsair_highest_level`;

CREATE TABLE `leader_corsair_highest_level` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_corsair_most_coins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_corsair_most_coins`;

CREATE TABLE `leader_corsair_most_coins` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_corsair_overall
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_corsair_overall`;

CREATE TABLE `leader_corsair_overall` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_highest_level
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_highest_level`;

CREATE TABLE `leader_highest_level` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_most_coins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_most_coins`;

CREATE TABLE `leader_most_coins` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_overall
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_overall`;

CREATE TABLE `leader_overall` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_weekly_level
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_weekly_level`;

CREATE TABLE `leader_weekly_level` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_weekly_miles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_weekly_miles`;

CREATE TABLE `leader_weekly_miles` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_weekly_money
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_weekly_money`;

CREATE TABLE `leader_weekly_money` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `array_data` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;



# Dump of table leader_weekly_winners
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leader_weekly_winners`;

CREATE TABLE `leader_weekly_winners` (
  `uid` int(11) NOT NULL,
  `award_won` varchar(255) NOT NULL,
  `date_won` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table level_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `level_log`;

CREATE TABLE `level_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `action` varchar(255) NOT NULL,
  `secondary_user` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=363023 DEFAULT CHARSET=latin1;



# Dump of table logged_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `logged_users`;

CREATE TABLE `logged_users` (
  `id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table magic_bottles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `magic_bottles`;

CREATE TABLE `magic_bottles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` date DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table missions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `missions`;

CREATE TABLE `missions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `payout_low` int(11) NOT NULL,
  `payout_high` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  `energy` int(11) NOT NULL,
  `required_1_id` int(11) DEFAULT NULL,
  `required_2_id` int(11) DEFAULT NULL,
  `required_3_id` int(11) DEFAULT NULL,
  `required_1_amount` int(11) NOT NULL DEFAULT '0',
  `required_2_amount` int(11) NOT NULL DEFAULT '0',
  `required_3_amount` int(11) NOT NULL DEFAULT '0',
  `required_level` int(11) NOT NULL DEFAULT '1',
  `required_mob` int(11) NOT NULL DEFAULT '0',
  `category` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;



# Dump of table npc_battles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `npc_battles`;

CREATE TABLE `npc_battles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `cannons` int(11) NOT NULL,
  `result` char(1) NOT NULL,
  `coins` int(11) NOT NULL,
  `booty` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `crew_lost` int(11) NOT NULL DEFAULT '0',
  `level_up` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=740593 DEFAULT CHARSET=latin1;



# Dump of table offers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `offers`;

CREATE TABLE `offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `offer_id` int(11) NOT NULL DEFAULT '0',
  `site` varchar(255) NOT NULL,
  `completed_at` datetime NOT NULL,
  `currency` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5463 DEFAULT CHARSET=latin1;



# Dump of table race_results
# ------------------------------------------------------------

DROP TABLE IF EXISTS `race_results`;

CREATE TABLE `race_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` char(1) NOT NULL,
  `jackpot` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `entrants` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96752 DEFAULT CHARSET=latin1;



# Dump of table races
# ------------------------------------------------------------

DROP TABLE IF EXISTS `races`;

CREATE TABLE `races` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` char(1) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `race_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`completed`)
) ENGINE=InnoDB AUTO_INCREMENT=1977224 DEFAULT CHARSET=latin1;



# Dump of table random_captcha
# ------------------------------------------------------------

DROP TABLE IF EXISTS `random_captcha`;

CREATE TABLE `random_captcha` (
  `id` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table sinking_ship_booty
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sinking_ship_booty`;

CREATE TABLE `sinking_ship_booty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `upgrade_name` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=157416 DEFAULT CHARSET=latin1;



# Dump of table stuff
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stuff`;

CREATE TABLE `stuff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `stuff_id` int(2) NOT NULL,
  `how_many` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`stuff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10689214 DEFAULT CHARSET=latin1;



# Dump of table stuff_info
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stuff_info`;

CREATE TABLE `stuff_info` (
  `stuff_id` int(2) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`stuff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table surveys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `surveys`;

CREATE TABLE `surveys` (
  `cmd` varchar(255) NOT NULL,
  `userId` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `offerInvitationId` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `oidHash` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tlapd_entries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tlapd_entries`;

CREATE TABLE `tlapd_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4723 DEFAULT CHARSET=latin1;



# Dump of table tlapd_winners
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tlapd_winners`;

CREATE TABLE `tlapd_winners` (
  `id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `how_many_recruited` int(11) NOT NULL,
  `address` mediumtext NOT NULL,
  `size` varchar(2555) NOT NULL,
  `style` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table treasure_booty
# ------------------------------------------------------------

DROP TABLE IF EXISTS `treasure_booty`;

CREATE TABLE `treasure_booty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `coins` int(11) NOT NULL,
  `booty_id` int(11) NOT NULL,
  `booty_amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=629288 DEFAULT CHARSET=latin1;



# Dump of table upgrades
# ------------------------------------------------------------

DROP TABLE IF EXISTS `upgrades`;

CREATE TABLE `upgrades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `upgrade_name` varchar(11) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT '1',
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `upgrade_name` (`upgrade_name`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2667667 DEFAULT CHARSET=latin1;



# Dump of table user_ranks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_ranks`;

CREATE TABLE `user_ranks` (
  `user_id` bigint(20) NOT NULL,
  `overall` int(11) NOT NULL DEFAULT '0',
  `most_coins` int(11) NOT NULL DEFAULT '0',
  `highest_level` int(11) NOT NULL DEFAULT '0',
  `overall_team` int(11) NOT NULL DEFAULT '0',
  `most_coins_team` int(11) NOT NULL DEFAULT '0',
  `highest_level_team` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `auto_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint(20) NOT NULL,
  `user_was_bombed` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `path` text NOT NULL,
  `team` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `recruited_by` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `last_moved_at` datetime NOT NULL,
  `inactive` int(1) NOT NULL DEFAULT '0',
  `session_key` varchar(255) NOT NULL DEFAULT '',
  `distance_from_home` int(11) NOT NULL DEFAULT '1',
  `current_action` varchar(255) DEFAULT NULL,
  `coin_total` int(11) NOT NULL DEFAULT '10',
  `buried_coin_total` int(11) NOT NULL DEFAULT '0',
  `level_is_correct` int(1) NOT NULL DEFAULT '0',
  `damage` int(11) NOT NULL DEFAULT '0',
  `user_was_attacked` int(11) NOT NULL DEFAULT '0',
  `user_in_battle` tinyint(1) NOT NULL DEFAULT '0',
  `gender` char(1) NOT NULL,
  `age` varchar(255) NOT NULL DEFAULT '0',
  `country` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `joke_on` tinyint(1) NOT NULL DEFAULT '1',
  `zip` int(6) NOT NULL DEFAULT '0',
  `pvp_off` tinyint(1) NOT NULL DEFAULT '0',
  `battling_enemy_id` int(11) NOT NULL DEFAULT '0',
  `weekly_miles` int(11) NOT NULL DEFAULT '0',
  `weekly_money` int(11) NOT NULL DEFAULT '0',
  `weekly_level` int(11) NOT NULL DEFAULT '0',
  `pillage_invites` int(11) NOT NULL DEFAULT '0',
  `consecutive_merchant_trades` int(11) NOT NULL DEFAULT '0',
  `total_merchant_trades` int(11) NOT NULL DEFAULT '0',
  `last_treasure_mile` int(11) NOT NULL DEFAULT '0',
  `treasure_attack_mile` int(11) NOT NULL DEFAULT '0',
  `secondary_action` varchar(255) NOT NULL,
  `animate_on` tinyint(1) NOT NULL DEFAULT '0',
  `bomb_on` tinyint(1) NOT NULL DEFAULT '1',
  `use_old_image` tinyint(1) NOT NULL DEFAULT '0',
  `mob_size` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`auto_id`),
  UNIQUE KEY `id` (`id`),
  KEY `team` (`team`),
  KEY `level_is_correct` (`level_is_correct`),
  KEY `recruited_by` (`recruited_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2925078 DEFAULT CHARSET=latin1;






/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
