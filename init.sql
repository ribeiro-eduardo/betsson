-- MySQL dump 10.13  Distrib 8.0.31, for Linux (aarch64)
--
-- Host: localhost    Database: betsson_api
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `bonus` double(8,2) DEFAULT NULL,
  `balance` double(8,2) DEFAULT NULL,
  `bonus_balance` double(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1,1,14.00,200.00,28.00),(2,2,10.00,100.00,14.00),(3,7,3.00,100.00,14.00),(4,19,18.00,100.00,14.00),(5,20,20.00,0.00,0.00),(6,22,20.00,0.00,0.00),(7,23,9.00,400.00,27.00),(8,51,6.00,123.00,0.00),(9,52,16.00,123.00,0.00),(10,54,15.00,123.00,0.00),(44,143,14.00,0.00,14.00);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_history`
--

DROP TABLE IF EXISTS `account_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `operation` enum('deposit','withdrawal') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_history`
--

LOCK TABLES `account_history` WRITE;
/*!40000 ALTER TABLE `account_history` DISABLE KEYS */;
INSERT INTO `account_history` VALUES (16,1,'deposit',100.00,'2022-12-07 22:56:20'),(17,2,'deposit',100.00,'2022-12-08 22:56:39'),(18,2,'deposit',100.00,'2022-12-08 22:56:46'),(19,7,'deposit',300.00,'2022-12-09 23:01:49'),(20,7,'deposit',400.00,'2022-12-09 23:02:35'),(21,2,'withdrawal',200.00,'2022-12-10 23:02:40'),(22,1,'deposit',100.00,'2022-12-10 23:04:03'),(23,1,'deposit',100.00,'2022-12-01 23:04:03'),(24,1,'withdrawal',100.00,'2022-12-01 23:04:03'),(25,1,'withdrawal',100.00,'2022-12-01 23:04:03'),(26,4,'deposit',100.00,'2022-12-01 23:04:03'),(27,1,'deposit',100.00,'2022-12-01 23:04:03'),(28,7,'withdrawal',400.00,'2022-12-09 23:02:35'),(29,2,'deposit',200.00,'2022-12-10 23:02:40'),(30,1,'deposit',100.00,'2022-12-10 23:04:03'),(31,7,'deposit',100.00,'2022-12-12 02:42:03'),(32,7,'deposit',100.00,'2022-12-12 02:43:02'),(33,7,'deposit',100.00,'2022-12-12 02:43:07'),(34,7,'deposit',100.00,'2022-12-12 02:43:12'),(35,7,'deposit',100.00,'2022-12-12 02:43:18'),(36,7,'deposit',100.00,'2022-12-12 02:43:19'),(37,7,'deposit',100.00,'2022-12-12 02:43:19'),(38,7,'withdrawal',300.00,'2022-12-12 02:43:30'),(39,1,'deposit',100.00,'2022-12-12 02:44:37'),(40,44,'deposit',100.00,'2022-12-21 22:37:21'),(41,44,'deposit',100.00,'2022-12-21 22:38:03'),(42,44,'deposit',100.00,'2022-12-21 22:38:04'),(43,44,'withdrawal',100.00,'2022-12-21 22:41:06'),(44,44,'withdrawal',200.00,'2022-12-21 22:41:23');
/*!40000 ALTER TABLE `account_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `country` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_UN` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'Eduardo','Ribeiro','male','BR','eduardo@betsson.com'),(2,'Bogdan','Gersak','male','MT','bogdan@betsson.com'),(7,'Donnabel','Galea ','female','MT','donnabel@betsson.com'),(19,'María','Cruzes','female','BR','maria@betsson.com.br'),(20,'Mircea','Lucescu','male','MT','mircea@betsson.com'),(22,'Gabriela','Ribeiro','female','BR','gabriela@betsson.com'),(23,'Gabriela','Ribeiro','female','BR','gabrielsdfa@betsson.com'),(52,'teste','teste','male','MT','fake@gmail.com.br'),(143,'Manuel Neuer','Muller','male','MT','manuel@betsson.com');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-22  1:51:20
