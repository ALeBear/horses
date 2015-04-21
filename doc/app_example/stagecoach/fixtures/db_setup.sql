CREATE DATABASE IF NOT EXISTS `stagecoach` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE USER 'stagecoach'@'localhost' IDENTIFIED BY 'stagecoach';
GRANT ALL PRIVILEGES ON  `stagecoach`.* TO  'stagecoach'@'localhost' WITH GRANT OPTION ;

