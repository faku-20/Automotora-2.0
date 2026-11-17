/*M!999999\- enable the sandbox mode */
-- MariaDB dump 10.20-13.0.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: automotora
-- ------------------------------------------------------
-- Server version	13.0.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Current Database: `automotora`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `automotora` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `automotora`;

--
-- Table structure for table `autos`
--

DROP TABLE IF EXISTS `autos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `autos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` varchar(80) NOT NULL,
  `modelo` varchar(80) NOT NULL,
  `anio` smallint(6) NOT NULL,
  `color` varchar(50) NOT NULL,
  `kilometros` int(11) NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autos`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `autos` WRITE;
/*!40000 ALTER TABLE `autos` DISABLE KEYS */;
INSERT INTO `autos` VALUES
(1,'Toyota','Corolla XEI',2022,'Blanco perlado',32000,28900.00),
(2,'Volkswagen','T-Cross',2023,'Gris plata',21000,31500.00),
(3,'Chevrolet','Onix Premier',2022,'Azul oscuro',28500,22400.00),
(4,'Hyundai','Creta',2024,'Negro',9800,36400.00),
(5,'Nissan','Kicks',2021,'Rojo',41700,24800.00),
(6,'Peugeot','208 Allure',2023,'Gris grafito',15600,26700.00),
(7,'Renault','Duster',2020,'Blanco',58700,21900.00),
(8,'Fiat','Cronos Precision',2024,'Plateado',7400,25500.00),
(9,'Honda','HR-V',2022,'Bordó',26300,34200.00),
(10,'Ford','Territory',2023,'Azul marino',19400,41200.00),
(11,'Suzuki','Vitara',2021,'Verde oliva',38800,27600.00),
(12,'Jeep','Renegade Sport',2022,'Negro',30100,33700.00),
(13,'Kia','Seltos',2024,'Blanco',6200,38900.00),
(14,'Mazda','CX-30',2023,'Rojo intenso',12700,40500.00),
(15,'Subaru','Crosstrek',2024,'Gris oscuro',4900,43800.00);
/*!40000 ALTER TABLE `autos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `configuracion_financiacion`
--

DROP TABLE IF EXISTS `configuracion_financiacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion_financiacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tasa_interes_mensual` decimal(5,2) NOT NULL DEFAULT 1.50,
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion_financiacion`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `configuracion_financiacion` WRITE;
/*!40000 ALTER TABLE `configuracion_financiacion` DISABLE KEYS */;
INSERT INTO `configuracion_financiacion` VALUES
(1,2.00,'2026-11-17 01:15:21');
/*!40000 ALTER TABLE `configuracion_financiacion` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_usuario` enum('administrador','comprador') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES
(1,'admin01','$2y$12$uOM6vnN9Xrcpt8JIO95sqOAk7Pm4dTJgqfCGlR6jXeqD7fnye5BNa','Alma','Rivas','administrador'),
(2,'admin02','$2y$12$xv0ttuCwt.qiBQqG1Ud6ieWF6QQ3xCwiWnb69AlqByCf7YFBInxIm','Bruno','Lagos','administrador'),
(3,'admin03','$2y$12$v7B1mdX1fjTgHnYfx32eneFN2ly.hG.6GITn6Fmtw7HDzJdeTAPYy','Clara','Nadal','administrador'),
(4,'admin04','$2y$12$.TGjt9FeQm72UNi34oJIOejFYHPftbsL0F2SwxZxT3jx8hesLfJrG','Dario','Mora','administrador'),
(5,'admin05','$2y$12$l7sd2lg7JMlBJiNR2hSKHe1rPlksPfpoO8vMcq8H4Hu4K8z.SktGy','Elena','Vidal','administrador'),
(6,'admin06','$2y$12$QnLLCjDabiTB3Zra4wWyTOJ.T22BnkVa/pd.BamY83iHZxrahdbLm','Fabian','Soler','administrador'),
(7,'admin07','$2y$12$IDR5ltIAhvFexFJtfEWyaO8WomW.fBRqx0Asu0GCkvlm6Utx02FRC','Greta','Acosta','administrador'),
(8,'admin08','$2y$12$6V35QzKQNQjVeUHszR3KR.LkGs53yrjOOabFSxZdno0tr1kED43DS','Hugo','Paz','administrador'),
(9,'admin09','$2y$12$un/MAtzBHuyWZh3appVVCupEh95v10Yn5tPSISS1vPFdxYOzPPN6K','Ines','Costa','administrador'),
(10,'admin10','$2y$12$o6RgA0F/WOkSVMVlQAGDHOzz2.ZvA8.BhKW0MEo.8tAu821H.0CD.','Joaquin','Rey','administrador'),
(11,'comprador01','$2y$12$TdidcCnffgh5p.tNxvEvnOScrzYkAKEkMqPMITdGRkjVoqQB2x4DC','Kiara','Vega','comprador'),
(12,'comprador02','$2y$12$NYVhQIP6QvFTYTWYIO2Ro.tPm/pMHsKYyTTz5hbm9plBJXEZuWzEK','Lautaro','Gil','comprador'),
(13,'comprador03','$2y$12$y6T9EW0Ap9dX8clSgg3sGeMYM/CDKeo5GHopgPIyM7vg9uRhmZGsO','Mara','Cano','comprador'),
(14,'comprador04','$2y$12$MJdnUNfYn4UQ.lOUIw6eDOb6FE84I/5ktfGakIsH0Ztecn97pItVC','Nereo','Luna','comprador'),
(15,'comprador05','$2y$12$Sz8zfZ.zag/pXlvHtqlSRu1X5Bn2mkB65tRPnVBXEXNI/AfMSGYaG','Olivia','Rios','comprador'),
(16,'comprador06','$2y$12$B7Awo.Uvtcyol4xxBPyhGu3lTJN6.g3ZM9zzGa5ucB2u7iTN88ClK','Pablo','Farias','comprador'),
(17,'comprador07','$2y$12$8otVW/9vhVn/KdVRMmf92eaNPdBNuW7qjeSR20IUhbVL1QduMiIjC','Rocio','Pardo','comprador'),
(18,'comprador08','$2y$12$Y8Stdk1CplwU3KXu79DYiOuos26.8ifIzyuAiVP2q5cMXwR45kuk.','Simon','Vera','comprador'),
(19,'comprador09','$2y$12$QdpW7e29Heo23Jy97y84N.0ZshSF/kdvAu1aCEG5f1qke8yOiBiF2','Tania','Sosa','comprador'),
(20,'comprador10','$2y$12$Kji1OMcydNK4IGKdpb14vOC5N1EJXSobQryNvGI1rI54tZgnZFL/O','Ulises','Neri','comprador');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-11-16 22:15:54
