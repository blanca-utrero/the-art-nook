-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: the-art-nook
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Pintura','Explora una selección de pinturas únicas y originales en distintos estilos y técnicas. Encuentra la obra ideal para dar vida y personalidad a tus espacios.'),(2,'Fotografía','Captura instantes únicos con obras fotográficas llenas de emoción, perspectiva y luz. Descubre una nueva forma de mirar el mundo.'),(3,'Escultura','Sumérgete en formas tridimensionales que despiertan los sentidos. Obras escultóricas que juegan con el espacio, la materia y el movimiento.'),(4,'Arte digital','Explora creaciones digitales que combinan técnica y creatividad. Arte innovador nacido en el mundo virtual con fuerza visual impactante.'),(5,'Ilustración','Dibujos e ilustraciones que cuentan historias, transmiten emociones y despiertan la imaginación. Descubre el encanto del trazo personal.'),(6,'Cerámica','Piezas de cerámica con carácter único: funcionales, decorativas y artísticas. Texturas y formas moldeadas a mano con dedicación.'),(7,'Textil','Textiles que entrelazan tradición y diseño contemporáneo. Obras que puedes tocar, sentir y admirar por su técnica y simbolismo.'),(8,'Joyería','Descubre joyas hechas a mano con intención artística. Cada pieza es una forma de expresión, una historia en miniatura.'),(9,'Collage','Obras que rompen las reglas y combinan elementos diversos para crear nuevas narrativas visuales. El arte del collage, sin límites.'),(10,'Libre','Categoría libre para creaciones que no encajan en ninguna otra. Espacio para lo experimental, lo híbrido, lo inesperado.');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos_artistas`
--

DROP TABLE IF EXISTS `favoritos_artistas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favoritos_artistas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `artista_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`,`artista_id`),
  KEY `artista_id` (`artista_id`),
  CONSTRAINT `favoritos_artistas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_artistas_ibfk_2` FOREIGN KEY (`artista_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos_artistas`
--

LOCK TABLES `favoritos_artistas` WRITE;
/*!40000 ALTER TABLE `favoritos_artistas` DISABLE KEYS */;
/*!40000 ALTER TABLE `favoritos_artistas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos_obras`
--

DROP TABLE IF EXISTS `favoritos_obras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favoritos_obras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `obra_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`,`obra_id`),
  KEY `obra_id` (`obra_id`),
  CONSTRAINT `favoritos_obras_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_obras_ibfk_2` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos_obras`
--

LOCK TABLES `favoritos_obras` WRITE;
/*!40000 ALTER TABLE `favoritos_obras` DISABLE KEYS */;
/*!40000 ALTER TABLE `favoritos_obras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes_obras`
--

DROP TABLE IF EXISTS `imagenes_obras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagenes_obras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obra_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `obra_id` (`obra_id`),
  CONSTRAINT `imagenes_obras_ibfk_1` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_obras`
--

LOCK TABLES `imagenes_obras` WRITE;
/*!40000 ALTER TABLE `imagenes_obras` DISABLE KEYS */;
INSERT INTO `imagenes_obras` VALUES (1,1,'uploads/obras/1_0_683753ae7fd85.png',1),(2,2,'uploads/obras/2_0_68376318d1548.png',1),(3,3,'uploads/obras/3_0_6839a2761f3cd.png',1),(4,4,'uploads/obras/4_0_6839b6b30f6e4.png',1),(5,5,'uploads/obras/5_0_68431168bf403.jpg',1);
/*!40000 ALTER TABLE `imagenes_obras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obras`
--

DROP TABLE IF EXISTS `obras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `destacado` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `obras_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obras`
--

LOCK TABLES `obras` WRITE;
/*!40000 ALTER TABLE `obras` DISABLE KEYS */;
INSERT INTO `obras` VALUES (1,1,'Leafwork','',1,'2025-05-28 18:19:26'),(2,1,'ἄνθος','',0,'2025-05-28 19:25:12'),(3,1,'Why chose?','',0,'2025-05-30 12:20:06'),(4,2,'Esencia','Una celebración de la figura femenina en su forma más pura y esencial. \"Esencia\" representa la conexión entre cuerpo, tierra y emoción, despojando al cuerpo de rostro y detalles para centrar la atención en su fuerza, su volumen y su simbolismo ancestral. Acompañada de formas abstractas que evocan elementos naturales, la pieza dialoga con el equilibrio entre lo humano y lo orgánico, lo sensual y lo sagrado.',0,'2025-05-30 13:46:27'),(5,3,'Ventana','',0,'2025-06-06 16:03:52');
/*!40000 ALTER TABLE `obras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obras_categorias`
--

DROP TABLE IF EXISTS `obras_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obras_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obra_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obra_id` (`obra_id`,`categoria_id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `obras_categorias_ibfk_1` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`) ON DELETE CASCADE,
  CONSTRAINT `obras_categorias_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obras_categorias`
--

LOCK TABLES `obras_categorias` WRITE;
/*!40000 ALTER TABLE `obras_categorias` DISABLE KEYS */;
INSERT INTO `obras_categorias` VALUES (1,1,1),(2,2,5),(3,3,10),(4,4,3),(5,5,2);
/*!40000 ALTER TABLE `obras_categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `destacado_home` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Salomi','Johnson','Creo, luego existo','Hola, soy Salomi, ilustradora y amante de los colores tierra, las texturas orgánicas y los silencios que dicen más que las palabras. Pinto desde que tengo memoria, pero fue hace poco cuando me atreví a compartir mi trabajo con el mundo. Me inspiran la naturaleza, las emociones honestas y las historias que se cuentan sin decir nada.  Aquí encontrarás pedacitos de mi universo: obras hechas con calma, con cariño y con la intención de que conecten contigo, aunque sea solo por un instante.','SalomiJohnson@gmail.com','$2y$10$/RIbpNl1ysoycgEncweVReDvolwYkkLUBMXav94d7tIHUH9U/VqsS','uploads/profile-pictures/1_68362a1d545b6.png','','',0,'2025-05-27 19:29:04'),(2,'Jose','López','Mis manos no crean, revelan.','Soy José López, escultor.\r\nDesde que tengo memoria, me he sentido atraído por la forma, el volumen y el peso de las cosas. Trabajo principalmente con piedra, madera y metal, buscando siempre ese instante en el que el material deja de ser solo materia y empieza a contar una historia. Para mí, esculpir no es solo crear, es descubrir: quitar lo que sobra hasta que aparece lo esencial.\r\nMi arte nace del silencio, de la observación y del respeto profundo por los materiales. Cada obra es un diálogo entre lo que imagino y lo que la materia permite.','JoseLopez@gmail.com','$2y$10$L3tb3wDWLj.GjfMWszxcKOSxjEqiUjJ5K5egag1JGf7Vl1GjFNi7y','uploads/profile-pictures/2_6839b5efaf22d.png','','',0,'2025-05-30 13:40:10'),(3,'Korra','Smith','La luz lo cambia todo.','Hola, soy Korra Smith. La fotografía es mi forma de detener el tiempo y encontrar poesía en lo cotidiano. Me encanta trabajar con luz natural, jugar con el color y capturar momentos que, aunque simples, dicen mucho.\r\nMis fotos nacen entre pinceles, cafés a medio tomar y ventanas abiertas: escenas reales, con alma, que me gusta observar en silencio antes de disparar.\r\nAquí comparto mi mirada, con la esperanza de que alguna imagen te haga sentir justo eso que no sabías poner en palabras.','KorraSmith@gmail.com','$2y$10$pEq9c7GVuCDdVNGocl0YM.azViJhM61xJvvLJAsLmL0WEngCCtvDq','uploads/profile-pictures/3_6843110aa35da.png','674928909','',0,'2025-06-06 16:00:58');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-06 21:53:44
