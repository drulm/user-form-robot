-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: localhost    Database: my_app
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.04.1

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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_users` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(254) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  PRIMARY KEY (`id_users`),
  UNIQUE KEY `idusers_UNIQUE` (`id_users`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'email1.dev','Rasmus','Lerdorf','$2y$10$BdhpBDcHlmEO6LlKj9i5j.pJI8n5aZNnAsWGgP51VgEJKV7M53B7G'),(2,'email2.dev','Blaise','Pascal','$2y$10$9A2zu/MZ779GK2zt8EIiRuXLW8Cz4bpdSTIU7NhznGK8ehnQMYKuK'),(3,'email3.dev','Charles','Babbage','$2y$10$KeD8KfiNUIfweeZpHie2XuvgrPmYjruucV.bB/asRmP26c7k0rQC2'),(4,'email4.dev','Ada','Lovelace','$2y$10$98kvtzGUPiRGXE0JJZXLCeYSkfH98EJ2FOO9h55./WH1NbObzqfam'),(5,'email5.dev','Benoit','Mandelbrot','$2y$10$/ALFKzCMUBqpZQYW007nxOEUB7JX70G4GM57eyT82QDGd7sFrFhNa'),(6,'email6.dev','Albert','Brudzewski','$2y$10$omaVU108HjSYo2GlqskRxe4ZERkl7fAQCzg4pBsayEuntulb/rofK'),(7,'email7.dev','Stefan','Banach','$2y$10$l2r5eMcipiNHoMNeKnSCz.uf0bpmvv80R50Z1gl4LqpA8BdwXFRVa');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-13 10:37:25
