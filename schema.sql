-- MySQL dump 10.16  Distrib 10.1.38-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: bibliotecalpaw
-- ------------------------------------------------------
-- Server version	10.1.38-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_autores`
--

DROP TABLE IF EXISTS `tb_autores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_autores` (
  `idtb_autores` int(11) NOT NULL AUTO_INCREMENT,
  `nomeAutor` varchar(255) NOT NULL,
  PRIMARY KEY (`idtb_autores`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_autores`
--

LOCK TABLES `tb_autores` WRITE;
/*!40000 ALTER TABLE `tb_autores` DISABLE KEYS */;
INSERT INTO `tb_autores` VALUES (1,'Rua Jair da Silva Spinelli2'),(2,'savio macedo'),(3,'teste'),(4,'teste'),(5,'Savious Macedo');
/*!40000 ALTER TABLE `tb_autores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_categoria`
--

DROP TABLE IF EXISTS `tb_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_categoria` (
  `idtb_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nomeCategoria` varchar(255) NOT NULL,
  PRIMARY KEY (`idtb_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_categoria`
--

LOCK TABLES `tb_categoria` WRITE;
/*!40000 ALTER TABLE `tb_categoria` DISABLE KEYS */;
INSERT INTO `tb_categoria` VALUES (2,'Dale'),(3,'Filosofia');
/*!40000 ALTER TABLE `tb_categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_editora`
--

DROP TABLE IF EXISTS `tb_editora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_editora` (
  `idtb_editora` int(11) NOT NULL AUTO_INCREMENT,
  `nomeEditora` varchar(255) NOT NULL,
  PRIMARY KEY (`idtb_editora`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_editora`
--

LOCK TABLES `tb_editora` WRITE;
/*!40000 ALTER TABLE `tb_editora` DISABLE KEYS */;
INSERT INTO `tb_editora` VALUES (1,'Amanhecer');
/*!40000 ALTER TABLE `tb_editora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_emprestimo`
--

DROP TABLE IF EXISTS `tb_emprestimo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_emprestimo` (
  `tb_usuario_idtb_usuario` int(11) NOT NULL,
  `tb_exemplar_idtb_exemplar` int(11) NOT NULL,
  `dataEmprestimo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observacao` text,
  `dataDevolucao` timestamp NULL DEFAULT NULL,
  `dataPrevista` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tb_usuario_idtb_usuario`,`tb_exemplar_idtb_exemplar`,`dataEmprestimo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_emprestimo`
--

LOCK TABLES `tb_emprestimo` WRITE;
/*!40000 ALTER TABLE `tb_emprestimo` DISABLE KEYS */;
INSERT INTO `tb_emprestimo` VALUES (1,5,'2019-05-19 04:39:13','.','0000-00-00 00:00:00','2019-05-29 04:39:08'),(1,6,'2019-05-19 04:45:15','.','0000-00-00 00:00:00','2019-05-20 04:44:21'),(1,7,'2019-05-19 04:46:10','teste','0000-00-00 00:00:00','2019-05-20 04:45:50'),(1,8,'2019-05-19 04:45:32','.','0000-00-00 00:00:00','2019-05-29 04:45:27'),(1,9,'2019-05-19 18:06:33','.','0000-00-00 00:00:00','2019-05-29 18:06:31');
/*!40000 ALTER TABLE `tb_emprestimo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_exemplar`
--

DROP TABLE IF EXISTS `tb_exemplar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_exemplar` (
  `idtb_exemplar` int(11) NOT NULL AUTO_INCREMENT,
  `tb_livro_idtb_livro` int(11) NOT NULL,
  `podeCircular` varchar(1) NOT NULL,
  PRIMARY KEY (`idtb_exemplar`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_exemplar`
--

LOCK TABLES `tb_exemplar` WRITE;
/*!40000 ALTER TABLE `tb_exemplar` DISABLE KEYS */;
INSERT INTO `tb_exemplar` VALUES (5,2,'S'),(6,2,'N'),(7,2,'N'),(8,2,'S'),(9,2,'S');
/*!40000 ALTER TABLE `tb_exemplar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_livro`
--

DROP TABLE IF EXISTS `tb_livro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_livro` (
  `idtb_livro` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `edicao` varchar(4) DEFAULT NULL,
  `ano` year(4) NOT NULL,
  `upload` varchar(255) DEFAULT NULL,
  `tb_editora_idtb_editora` int(11) NOT NULL,
  `tb_categoria_idtb_categoria` int(11) NOT NULL,
  PRIMARY KEY (`idtb_livro`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_livro`
--

LOCK TABLES `tb_livro` WRITE;
/*!40000 ALTER TABLE `tb_livro` DISABLE KEYS */;
INSERT INTO `tb_livro` VALUES (2,'Livro de teste','20','30',2019,'testito',1,2);
/*!40000 ALTER TABLE `tb_livro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_livro_autor`
--

DROP TABLE IF EXISTS `tb_livro_autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_livro_autor` (
  `tb_livro_idtb_livro` int(11) NOT NULL,
  `tb_autores_idtb_autores` int(11) NOT NULL,
  PRIMARY KEY (`tb_livro_idtb_livro`,`tb_autores_idtb_autores`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_livro_autor`
--

LOCK TABLES `tb_livro_autor` WRITE;
/*!40000 ALTER TABLE `tb_livro_autor` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_livro_autor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_reserva`
--

DROP TABLE IF EXISTS `tb_reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_reserva` (
  `dataReserva` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observacao` text,
  `dataLimite` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tb_usuario_idtb_usuario` int(11) NOT NULL,
  `tb_exemplar_idtb_exemplar` int(11) NOT NULL,
  PRIMARY KEY (`tb_usuario_idtb_usuario`,`tb_exemplar_idtb_exemplar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_reserva`
--

LOCK TABLES `tb_reserva` WRITE;
/*!40000 ALTER TABLE `tb_reserva` DISABLE KEYS */;
INSERT INTO `tb_reserva` VALUES ('2019-05-19 18:00:16','.','2019-05-21 03:00:00',1,9),('2019-05-19 18:01:32','.','2019-05-23 03:00:00',2,9);
/*!40000 ALTER TABLE `tb_reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_usuario`
--

DROP TABLE IF EXISTS `tb_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_usuario` (
  `idtb_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nomeUsuario` varchar(255) NOT NULL,
  `tipo` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`idtb_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usuario`
--

LOCK TABLES `tb_usuario` WRITE;
/*!40000 ALTER TABLE `tb_usuario` DISABLE KEYS */;
INSERT INTO `tb_usuario` VALUES (1,'Savio Macedo',1,'saviom.cedo@hotmail.com','finalitymu'),(2,'Macedo',3,'saviom.cedo@hotmails.com','finalitymu'),(3,'admin',3,'admin@admin.com','admin');
/*!40000 ALTER TABLE `tb_usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-19 17:34:47
