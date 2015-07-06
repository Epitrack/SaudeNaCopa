# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.42)
# Database: saudenacopa_db
# Generation Time: 2015-07-06 14:58:02 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table usuario_sentimento
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usuario_sentimento`;

CREATE TABLE `usuario_sentimento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` varchar(35) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jogo_ok` tinyint(1) NOT NULL,
  `pontuacao` int(11) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `cidade_id` varchar(40) NOT NULL,
  `cidade_regiao_metro` varchar(40) NOT NULL,
  `atualizado` tinyint(1) NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  `campo1` tinyint(1) NOT NULL,
  `campo2` tinyint(1) NOT NULL,
  `campo3` tinyint(1) NOT NULL,
  `campo4` tinyint(1) NOT NULL,
  `campo5` tinyint(1) NOT NULL,
  `campo6` tinyint(1) NOT NULL,
  `campo7` tinyint(1) NOT NULL,
  `campo8` tinyint(1) NOT NULL,
  `campo9` tinyint(1) NOT NULL,
  `campo10` tinyint(1) NOT NULL,
  `campo11` tinyint(1) NOT NULL,
  `campo12` tinyint(1) NOT NULL,
  `sentimento` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
