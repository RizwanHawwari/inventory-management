-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: inventory_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `produk_masuk` int(11) DEFAULT 0,
  `produk_keluar` int(11) DEFAULT 0,
  `jumlah` int(11) DEFAULT 0,
  `penerima_barang` varchar(255) DEFAULT NULL,
  `penerima_barang_keluar` varchar(255) DEFAULT NULL,
  `alasan_keluar` enum('Terjual','Expired') DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` VALUES (29,'Air Mineral','Aquas',120,28,79,'ada','Maliki','Terjual','20:51:00','2024-10-15'),(30,'Makanan','Sushi Bako',68,10,66,'Mas Hardin Sukamto','Zakran','Expired','17:44:00','2024-10-14'),(31,'Xx','ada',12,3,12,'adas','Mas Anis','Terjual','17:57:00','2024-10-14'),(32,'Minuman','Teh Kotak',99,50,87,'Kirana Queen','Zakran','Terjual','20:44:00','2024-10-15');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `riwayat_perubahan`
--

DROP TABLE IF EXISTS `riwayat_perubahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `riwayat_perubahan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `perubahan` int(11) NOT NULL,
  `penerima_barang` varchar(255) DEFAULT NULL,
  `jenis_perubahan` varchar(100) NOT NULL,
  `waktu_perubahan` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `riwayat_perubahan_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riwayat_perubahan`
--

LOCK TABLES `riwayat_perubahan` WRITE;
/*!40000 ALTER TABLE `riwayat_perubahan` DISABLE KEYS */;
INSERT INTO `riwayat_perubahan` VALUES (6,32,'Minuman','Teh Kotak',1,'Sagizo Masturono','Keluar: Update Terjual','2024-10-15 13:25:17'),(7,29,'Air Mineral','Aqua',28,'Maliki','Keluar: Update Terjual','2024-10-15 13:46:04');
/*!40000 ALTER TABLE `riwayat_perubahan` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-15 20:53:55
