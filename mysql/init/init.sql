CREATE DATABASE IF NOT EXISTS `webapp`;
GRANT ALL ON `webapp`.* TO 'web'@'%';
CREATE DATABASE IF NOT EXISTS `bizapp`;
GRANT ALL ON `bizapp`.* TO 'web'@'%';
CREATE DATABASE IF NOT EXISTS `chatapp`;
GRANT ALL ON `chatapp`.* TO 'web'@'%';