SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+05:00";

--
-- Database: `stagecoach`
--
USE `stagecoach`;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `flags` int(11) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
SET FOREIGN_KEY_CHECKS=1;

INSERT INTO `stagecoach`.`user` (`id`, `username`, `password_hash`, `name`, `flags`, `is_deleted`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'alebear', '$2y$10$so1TVyk6Css3dv3KCUtvF.A8jFchXXzEGRnxCBmMVI55TZ9rq8iTi', 'A. LeBear', '0', '0', NOW(), NULL, NULL);

DROP TABLE IF EXISTS `simple_access_code`;
CREATE TABLE IF NOT EXISTS `simple_access_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `code` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
