-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: perpus
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
-- Table structure for table `buku`
--

DROP TABLE IF EXISTS `buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(50) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `penerbit` varchar(255) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `penulis` varchar(255) NOT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `denda_per_hari` int(11) DEFAULT 5000,
  `cover` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_buku`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buku`
--

LOCK TABLES `buku` WRITE;
/*!40000 ALTER TABLE `buku` DISABLE KEYS */;
INSERT INTO `buku` VALUES (21,'978-623-8587-55-1','Agama','An-Nizam Media','Menggali Kepercayaan Diri sesuai Tuntunan Islam','Nuri Septina',2024,0,'',5000,'1776752178_f23366fef402a9dde6ec.webp'),(24,'978-623-09-3270-0','Umum','Agna Media Indokarya','PENGANTAR TEKNOLOGI INFORMASI DAN KOMUNIKASI','Dr. Drs. Didik Tristianto, S.Kom., M.Kom., M.Cs.',2023,6,'',5000,'1776664931_02a4afae23c9171c4548.webp'),(25,'978-6-02412-518-9','Umum','Kompas','Filosofi Teras','Henry Manampiring',2018,7,'',5000,'1776665322_77fc7d9d7682bfb444f1.jpg'),(26,'978-602-453-292-5','Umum','Deepublish','Buku Islamic Intensive Study Pendidikan Agama Islam ','Sudirman',2017,3,'',5000,'1776665546_287ed8ed68741a8c4fe8.jpg'),(28,'9786024021795','Bahasa & Sastra','Qanita','Recehan Bahasa','Ivan Lanin',2020,3,'Buku “Recehan Bahasa” yang ditulis oleh Ivan Lanin ini berisi pembahasan mengenai kebahasaan Indonesia serta kesalahan yang sering dilakukan dalam penggunaannya. Pengemasannya yang menarik membuat buku ini berbeda dari buku referensi bahasa kebanyakan. Penggunaan gaya penulisan dan pemilihan katanya yang santai, serta terkadang diselipi humor membuat buku ini jauh dari kesan membosankan.',5000,'1776665909_97633d7ea1ca1b2f56cc.jpg'),(30,'9786024246945','Sosial & Sejarah','Kepustakaan Populer Gramedia','Laut Bercerita','Leila S. Chudori',2017,6,'',5000,'1776668213_d0014988ed6e64515443.jpg'),(31,'978-602-401-967-9','Seni & Rekreasi','Deepublish','Penuntun Pewarna Alam Soga Batik','R. Jati Nurcahyo',2017,4,'',5000,'1776668488_9330b606c1243320f7ca.jpg'),(34,'9786236166666','Agama','Ircisod','Paradigma Islam Rahmatan Lil \'Alamin','PROF. ABDURRAHMAN MAS`UD, M.A., PH.D.',2021,9,'',5000,'1776831258_08f91201e21a23aef46a.jpg'),(50,'1-4391-9281-2','Sains & Teknologi','Bantam Press','The Magic of Reality','Richard Dawkins',NULL,7,'',5000,'1776961090_d3a6862e76db159ecba3.jpeg'),(54,'978-979-97312-3-4','Sosial & Sejarah','Lentera Dipantara','Tetralogi Buru','Pramoedya Ananta Toer',1980,7,'',5000,'1776961885_9442e5c58e9fb9ecec80.jpg'),(56,'9786028252256','Umum','Jalasutra','Nirmana: Elemen-Elemen Seni dan Desain','Sadjiman Ebdi Sanyoto',2010,13,'',5000,'1776962501_e12a64b22b438633870a.jpg'),(61,'','Umum','','Laskar Pelangi','Andrea Herata',0000,1,'',5000,NULL);
/*!40000 ALTER TABLE `buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peminjaman` (
  `id_pinjam` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_buku` int(11) DEFAULT NULL,
  `tgl_pinjam` date DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `tgl_dikembalikan` date DEFAULT NULL,
  `denda` int(11) DEFAULT 0,
  `status` enum('dipinjam','diajukan','kembali') DEFAULT 'dipinjam',
  `status_bayar` enum('belum','proses','lunas') DEFAULT 'belum',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pinjam`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

LOCK TABLES `peminjaman` WRITE;
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
INSERT INTO `peminjaman` VALUES (101,7,24,'2026-04-24','2026-04-15','2026-04-24',45000,'kembali','lunas',NULL),(104,7,21,'2026-04-24','2026-04-14','2026-04-24',0,'diajukan','proses','1777046977_c4fa9fee99f18f211607.jpg'),(119,7,24,'2026-04-25','2026-04-22','2026-04-27',25000,'kembali','lunas',NULL),(120,7,24,'2026-04-27','2026-05-04','2026-04-27',0,'kembali','lunas',NULL),(121,7,24,'2026-04-27','2026-05-04','2026-04-27',0,'kembali','lunas',NULL),(122,7,24,'2026-04-27','2026-05-04','2026-04-27',0,'kembali','lunas',NULL),(123,7,24,'2026-04-27','2026-05-04','2026-04-27',0,'kembali','lunas',NULL),(124,7,24,'2026-04-27','2026-05-04','2026-04-27',0,'kembali','lunas',NULL),(125,7,25,'2026-04-27','2026-05-04','2026-04-27',0,'kembali','lunas',NULL),(126,7,28,'2026-04-27','2026-05-04',NULL,0,'dipinjam','belum',NULL),(127,7,26,'2026-04-27','2026-05-04',NULL,0,'dipinjam','belum',NULL);
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','anggota') DEFAULT 'anggota',
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'Irsyad Fariq','irsyadfariq@gmail.com','fariqadmin','$2y$10$FMQRdH0ecCbZZVWtN2n7/u1YZN/gr7X98Er4NG4sqDAWCXlZwmB6S','admin','1777132762_c54218f23000c01cdf7d.jpg','aktif','2026-04-11 04:04:12'),(7,'Fariq ','fariq.irsyad37@smk.belajar.id','fariquser','$2y$10$ykvfDO2phLLenmzm1Dp.AekKGhyMs57BDruG1KaboLpXlITC/Z.Im','anggota','1777132692_59f499b6ad3221fb73a0.jpg','aktif','2026-04-18 04:18:17'),(8,'Fariq Irsyad','fariq.irsyad37@smk.belajar.id','frq','$2y$10$0t1oAFtxvlMmEiHinVq8.uHrR/FQ4Y4YJWr38WpB4DdCr3/Y52aEC','anggota','1776793489_8363f3cd92cc43063a55.webp','aktif','2026-04-21 17:44:49');
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

-- Dump completed on 2026-04-27 18:39:09
