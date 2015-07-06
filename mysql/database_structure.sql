# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.42)
# Database: saudenacopa_db
# Generation Time: 2015-07-06 14:56:23 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table duvidas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `duvidas`;

CREATE TABLE `duvidas` (
  `idDuvidas` int(11) NOT NULL AUTO_INCREMENT,
  `usuarios_id` int(4) NOT NULL,
  `msg` text NOT NULL,
  `dataHora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idDuvidas`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table logGame
# ------------------------------------------------------------

CREATE TABLE `logGame` (
  `idLogGame` int(11) NOT NULL AUTO_INCREMENT,
  `usuarios_id` int(11) NOT NULL,
  `acao` varchar(50) NOT NULL,
  `pontuacao` tinyint(3) NOT NULL,
  `dataHora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idLogGame`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table problemas
# ------------------------------------------------------------

CREATE TABLE `problemas` (
  `idProblema` int(11) NOT NULL AUTO_INCREMENT,
  `usuarios_id` tinyint(4) NOT NULL,
  `msg` text NOT NULL,
  `dataHora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idProblema`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sessions
# ------------------------------------------------------------

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `expiredate` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table usuario_sentimento
# ------------------------------------------------------------

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



# Dump of table usuarios
# ------------------------------------------------------------

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apelido` varchar(255) NOT NULL,
  `idade` int(11) NOT NULL,
  `sexo` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `termo` tinyint(4) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hash` varchar(32) NOT NULL,
  `gcmid` varchar(255) NOT NULL,
  `idioma` tinyint(4) NOT NULL,
  `totalPontosPossiveis` tinyint(3) NOT NULL,
  `pontuacao` smallint(3) NOT NULL,
  `arena` tinyint(2) NOT NULL,
  `device` varchar(80) NOT NULL,
  `device_id` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
