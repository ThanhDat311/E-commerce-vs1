-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: e-commerce
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `address_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,14,'Home','John Smith','0292144445','thanh,  ạvadcbzklzabn','áds','aa','4007','Untied',1,'2026-02-19 04:35:46','2026-02-19 04:35:46');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ai_feature_store`
--

DROP TABLE IF EXISTS `ai_feature_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_feature_store` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `auth_log_id` bigint unsigned DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `velocity_check` double DEFAULT NULL,
  `ip_reputation_score` double DEFAULT NULL,
  `risk_score` double NOT NULL DEFAULT '0',
  `reasons` json DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_feature_store_auth_log_id_foreign` (`auth_log_id`),
  KEY `ai_feature_store_order_id_foreign` (`order_id`),
  CONSTRAINT `ai_feature_store_auth_log_id_foreign` FOREIGN KEY (`auth_log_id`) REFERENCES `auth_logs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ai_feature_store_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_feature_store`
--

LOCK TABLES `ai_feature_store` WRITE;
/*!40000 ALTER TABLE `ai_feature_store` DISABLE KEYS */;
INSERT INTO `ai_feature_store` VALUES (1,NULL,1,32.99,'127.0.0.1',NULL,NULL,0,'[\"New user account (< 24h)\"]','allow','2026-02-19 04:35:56','2026-02-19 04:35:57'),(2,NULL,2,172.99,'127.0.0.1',NULL,NULL,0,'[\"New user account (< 24h)\"]','allow','2026-02-19 04:36:09','2026-02-19 04:36:09'),(3,NULL,3,452.99,'127.0.0.1',NULL,NULL,0,'[\"New user account (< 24h)\"]','allow','2026-02-19 04:36:22','2026-02-19 04:36:22');
/*!40000 ALTER TABLE `ai_feature_store` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `old_values` longtext COLLATE utf8mb4_unicode_ci,
  `new_values` longtext COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `audit_logs_action_index` (`action`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,NULL,'created','App\\Models\\User',14,NULL,'{\"name\":\"Nguyen Dat\",\"email\":\"hihihihi13245768@gmail.com\",\"google_id\":\"103222369643418386800\",\"password\":\"$2y$12$zASoqN4K47hCe4xA\\/DlD\\/eAzNDH6byalKnB1SlrqcaZuCj4yBQa7y\",\"role_id\":3,\"is_active\":true,\"email_verified_at\":\"2026-02-17 14:40:16\",\"id\":14}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-17 07:40:16','2026-02-17 07:40:16'),(2,2,'updated','App\\Models\\Product',1,'{\"id\":1,\"vendor_id\":null,\"category_id\":3,\"name\":\"iPhone 15 Pro Max 256GB\",\"slug\":\"iphone-15-pro-max-256gb\",\"sku\":\"IPH15PM-256\",\"price\":\"1199.00\",\"sale_price\":null,\"stock_quantity\":50,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/1a1a1a\\/ffffff?text=iPhone+15+Pro\",\"is_new\":true,\"is_featured\":true,\"description\":\"Latest iPhone with A17 Pro chip, titanium design, and advanced camera system. Features 6.7-inch Super Retina XDR display.\"}','{\"id\":1,\"vendor_id\":null,\"category_id\":\"3\",\"name\":\"iPhone 15 Pro Max 256GB\",\"slug\":\"iphone-15-pro-max-256gb\",\"sku\":\"IPH15PM-256\",\"price\":\"1199.00\",\"sale_price\":\"1099.00\",\"stock_quantity\":50,\"image_url\":\"img\\/products\\/1771414452_iphone-15-pro-max-titan-xanh-2-638629415445427350-750x500.jpg\",\"is_new\":1,\"is_featured\":1,\"description\":\"Latest iPhone with A17 Pro chip, titanium design, and advanced camera system. Features 6.7-inch Super Retina XDR display.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 04:34:12','2026-02-18 04:34:12'),(3,2,'updated','App\\Models\\Product',2,'{\"id\":2,\"vendor_id\":null,\"category_id\":3,\"name\":\"Samsung Galaxy S24 Ultra 512GB\",\"slug\":\"samsung-galaxy-s24-ultra-512gb\",\"sku\":\"SGS24U-512\",\"price\":\"1299.00\",\"sale_price\":null,\"stock_quantity\":35,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/5856d6\\/ffffff?text=Galaxy+S24\",\"is_new\":true,\"is_featured\":true,\"description\":\"Premium Android flagship with S Pen, 200MP camera, and AI features. 6.8-inch Dynamic AMOLED display.\"}','{\"id\":2,\"vendor_id\":null,\"category_id\":\"3\",\"name\":\"Samsung Galaxy S24 Ultra 512GB\",\"slug\":\"samsung-galaxy-s24-ultra-512gb\",\"sku\":\"SGS24U-512\",\"price\":\"1299.00\",\"sale_price\":null,\"stock_quantity\":35,\"image_url\":\"img\\/products\\/1771416024_samsung-galaxy-s24-ultra-xam-1-750x500.jpg\",\"is_new\":1,\"is_featured\":1,\"description\":\"Premium Android flagship with S Pen, 200MP camera, and AI features. 6.8-inch Dynamic AMOLED display.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:00:24','2026-02-18 05:00:24'),(4,2,'updated','App\\Models\\Product',3,'{\"id\":3,\"vendor_id\":null,\"category_id\":3,\"name\":\"iPad Pro 12.9\\\" M2 256GB\",\"slug\":\"ipad-pro-129-m2-256gb\",\"sku\":\"IPADPRO-M2-256\",\"price\":\"1099.00\",\"sale_price\":null,\"stock_quantity\":25,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/2c2c2c\\/ffffff?text=iPad+Pro\",\"is_new\":false,\"is_featured\":true,\"description\":\"Powerful tablet with M2 chip, Liquid Retina XDR display, and Apple Pencil support.\"}','{\"id\":3,\"vendor_id\":null,\"category_id\":\"3\",\"name\":\"iPad Pro 12.9\\\" M2 256GB\",\"slug\":\"ipad-pro-129-m2-256gb\",\"sku\":\"IPADPRO-M2-256\",\"price\":\"1099.00\",\"sale_price\":\"999.00\",\"stock_quantity\":25,\"image_url\":\"img\\/products\\/1771416282_619G-Sa2NSL._AC_SX522_.jpg\",\"is_new\":0,\"is_featured\":1,\"description\":\"Powerful tablet with M2 chip, Liquid Retina XDR display, and Apple Pencil support.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:04:42','2026-02-18 05:04:42'),(5,2,'updated','App\\Models\\Product',4,'{\"id\":4,\"vendor_id\":null,\"category_id\":4,\"name\":\"MacBook Pro 16\\\" M3 Max 1TB\",\"slug\":\"macbook-pro-16-m3-max-1tb\",\"sku\":\"MBP16-M3MAX-1TB\",\"price\":\"3499.00\",\"sale_price\":null,\"stock_quantity\":15,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/000000\\/ffffff?text=MacBook+Pro\",\"is_new\":true,\"is_featured\":true,\"description\":\"Professional laptop with M3 Max chip, 16-inch Liquid Retina XDR display, up to 22 hours battery life.\"}','{\"id\":4,\"vendor_id\":null,\"category_id\":\"4\",\"name\":\"MacBook Pro 16\\\" M3 Max 1TB\",\"slug\":\"macbook-pro-16-m3-max-1tb\",\"sku\":\"MBP16-M3MAX-1TB\",\"price\":\"3499.00\",\"sale_price\":null,\"stock_quantity\":15,\"image_url\":\"img\\/products\\/1771417342_41H8TjeAcwL._AC_SL1200_.jpg\",\"is_new\":1,\"is_featured\":1,\"description\":\"Professional laptop with M3 Max chip, 16-inch Liquid Retina XDR display, up to 22 hours battery life.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:22:22','2026-02-18 05:22:22'),(6,2,'updated','App\\Models\\Product',5,'{\"id\":5,\"vendor_id\":null,\"category_id\":4,\"name\":\"Dell XPS 15 Intel i9 32GB RAM\",\"slug\":\"dell-xps-15-intel-i9-32gb-ram\",\"sku\":\"DELLXPS15-I9\",\"price\":\"2299.00\",\"sale_price\":null,\"stock_quantity\":20,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/007db8\\/ffffff?text=Dell+XPS+15\",\"is_new\":false,\"is_featured\":false,\"description\":\"Premium Windows laptop with 15.6\\\" 4K OLED display, NVIDIA RTX 4060, perfect for creators.\"}','{\"id\":5,\"vendor_id\":null,\"category_id\":\"4\",\"name\":\"Dell XPS 15 Intel i9 32GB RAM\",\"slug\":\"dell-xps-15-intel-i9-32gb-ram\",\"sku\":\"DELLXPS15-I9\",\"price\":\"2299.00\",\"sale_price\":\"1999.00\",\"stock_quantity\":20,\"image_url\":\"img\\/products\\/1771417500_61Ks9X44eVL._AC_SL1181_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Premium Windows laptop with 15.6\\\" 4K OLED display, NVIDIA RTX 4060, perfect for creators.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:25:00','2026-02-18 05:25:00'),(7,2,'updated','App\\Models\\Product',6,'{\"id\":6,\"vendor_id\":null,\"category_id\":4,\"name\":\"ASUS ROG Zephyrus G16 Gaming Laptop\",\"slug\":\"asus-rog-zephyrus-g16-gaming-laptop\",\"sku\":\"ASUS-ROG-G16\",\"price\":\"2499.00\",\"sale_price\":null,\"stock_quantity\":12,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/ff0000\\/ffffff?text=ROG+Zephyrus\",\"is_new\":true,\"is_featured\":false,\"description\":\"High-performance gaming laptop with RTX 4080, Intel Core i9, 240Hz display.\"}','{\"id\":6,\"vendor_id\":null,\"category_id\":\"4\",\"name\":\"ASUS ROG Zephyrus G16 Gaming Laptop\",\"slug\":\"asus-rog-zephyrus-g16-gaming-laptop\",\"sku\":\"ASUS-ROG-G16\",\"price\":\"2499.00\",\"sale_price\":null,\"stock_quantity\":12,\"image_url\":\"img\\/products\\/1771417709_81n1T4CYfmL._AC_SL1500_.jpg\",\"is_new\":1,\"is_featured\":0,\"description\":\"High-performance gaming laptop with RTX 4080, Intel Core i9, 240Hz display.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:28:29','2026-02-18 05:28:29'),(8,2,'updated','App\\Models\\Product',7,'{\"id\":7,\"vendor_id\":null,\"category_id\":5,\"name\":\"Sony WH-1000XM5 Wireless Headphones\",\"slug\":\"sony-wh-1000xm5-wireless-headphones\",\"sku\":\"SONY-WH1000XM5\",\"price\":\"399.00\",\"sale_price\":null,\"stock_quantity\":60,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/000000\\/ffffff?text=Sony+XM5\",\"is_new\":false,\"is_featured\":true,\"description\":\"Industry-leading noise cancellation, exceptional sound quality, 30-hour battery life.\"}','{\"id\":7,\"vendor_id\":null,\"category_id\":\"5\",\"name\":\"Sony WH-1000XM5 Wireless Headphones\",\"slug\":\"sony-wh-1000xm5-wireless-headphones\",\"sku\":\"SONY-WH1000XM5\",\"price\":\"399.00\",\"sale_price\":\"349.00\",\"stock_quantity\":60,\"image_url\":\"img\\/products\\/1771417817_61BGLYEN-xL._AC_SL1500_.jpg\",\"is_new\":0,\"is_featured\":1,\"description\":\"Industry-leading noise cancellation, exceptional sound quality, 30-hour battery life.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:30:17','2026-02-18 05:30:17'),(9,2,'updated','App\\Models\\Product',8,'{\"id\":8,\"vendor_id\":null,\"category_id\":5,\"name\":\"AirPods Pro 2nd Generation with MagSafe\",\"slug\":\"airpods-pro-2nd-generation-with-magsafe\",\"sku\":\"AIRPODS-PRO2\",\"price\":\"249.00\",\"sale_price\":null,\"stock_quantity\":100,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/ffffff\\/000000?text=AirPods+Pro\",\"is_new\":false,\"is_featured\":true,\"description\":\"Premium wireless earbuds with active noise cancellation, spatial audio, and adaptive transparency.\"}','{\"id\":8,\"vendor_id\":null,\"category_id\":\"5\",\"name\":\"AirPods Pro 2nd Generation with MagSafe\",\"slug\":\"airpods-pro-2nd-generation-with-magsafe\",\"sku\":\"AIRPODS-PRO2\",\"price\":\"249.00\",\"sale_price\":null,\"stock_quantity\":100,\"image_url\":\"img\\/products\\/1771417919_41QztOA1UuL._AC_SL1200_.jpg\",\"is_new\":0,\"is_featured\":1,\"description\":\"Premium wireless earbuds with active noise cancellation, spatial audio, and adaptive transparency.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:31:59','2026-02-18 05:31:59'),(10,2,'updated','App\\Models\\Product',9,'{\"id\":9,\"vendor_id\":null,\"category_id\":5,\"name\":\"Bose QuietComfort Ultra Earbuds\",\"slug\":\"bose-quietcomfort-ultra-earbuds\",\"sku\":\"BOSE-QCUE\",\"price\":\"299.00\",\"sale_price\":null,\"stock_quantity\":45,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/1a1a1a\\/ffffff?text=Bose+QC\",\"is_new\":true,\"is_featured\":false,\"description\":\"World-class noise cancellation, immersive audio, comfortable all-day wear.\"}','{\"id\":9,\"vendor_id\":null,\"category_id\":\"5\",\"name\":\"Bose QuietComfort Ultra Earbuds\",\"slug\":\"bose-quietcomfort-ultra-earbuds\",\"sku\":\"BOSE-QCUE\",\"price\":\"299.00\",\"sale_price\":\"279.00\",\"stock_quantity\":45,\"image_url\":\"img\\/products\\/1771417997_51HYcr7W1QL._AC_SL1500_.jpg\",\"is_new\":1,\"is_featured\":0,\"description\":\"World-class noise cancellation, immersive audio, comfortable all-day wear.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:33:17','2026-02-18 05:33:17'),(11,2,'updated','App\\Models\\Product',10,'{\"id\":10,\"vendor_id\":null,\"category_id\":6,\"name\":\"Canon EOS R5 Mirrorless Camera Body\",\"slug\":\"canon-eos-r5-mirrorless-camera-body\",\"sku\":\"CANON-R5-BODY\",\"price\":\"3899.00\",\"sale_price\":null,\"stock_quantity\":8,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/cc0000\\/ffffff?text=Canon+R5\",\"is_new\":false,\"is_featured\":true,\"description\":\"Professional full-frame mirrorless camera with 45MP sensor, 8K video recording.\"}','{\"id\":10,\"vendor_id\":null,\"category_id\":\"6\",\"name\":\"Canon EOS R5 Mirrorless Camera Body\",\"slug\":\"canon-eos-r5-mirrorless-camera-body\",\"sku\":\"CANON-R5-BODY\",\"price\":\"3899.00\",\"sale_price\":null,\"stock_quantity\":8,\"image_url\":\"img\\/products\\/1771418108_71hpUUcC5uL._AC_SL1500_.jpg\",\"is_new\":0,\"is_featured\":1,\"description\":\"Professional full-frame mirrorless camera with 45MP sensor, 8K video recording.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:35:08','2026-02-18 05:35:08'),(12,2,'updated','App\\Models\\Product',11,'{\"id\":11,\"vendor_id\":null,\"category_id\":6,\"name\":\"Sony A7 IV Full Frame Camera\",\"slug\":\"sony-a7-iv-full-frame-camera\",\"sku\":\"SONY-A7IV\",\"price\":\"2499.00\",\"sale_price\":null,\"stock_quantity\":10,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/000000\\/ffffff?text=Sony+A7+IV\",\"is_new\":false,\"is_featured\":false,\"description\":\"Versatile hybrid camera with 33MP sensor, 4K 60p video, advanced autofocus.\"}','{\"id\":11,\"vendor_id\":null,\"category_id\":\"6\",\"name\":\"Sony A7 IV Full Frame Camera\",\"slug\":\"sony-a7-iv-full-frame-camera\",\"sku\":\"SONY-A7IV\",\"price\":\"2499.00\",\"sale_price\":\"2299.00\",\"stock_quantity\":10,\"image_url\":\"img\\/products\\/1771418171_61kkhm9rT4L._AC_SL1200_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Versatile hybrid camera with 33MP sensor, 4K 60p video, advanced autofocus.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:36:11','2026-02-18 05:36:11'),(13,2,'updated','App\\Models\\Product',12,'{\"id\":12,\"vendor_id\":null,\"category_id\":8,\"name\":\"Nike Dri-FIT Training T-Shirt\",\"slug\":\"nike-dri-fit-training-t-shirt\",\"sku\":\"NIKE-DFIT-TEE-M\",\"price\":\"29.99\",\"sale_price\":null,\"stock_quantity\":200,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/000000\\/ffffff?text=Nike+Tee\",\"is_new\":false,\"is_featured\":false,\"description\":\"Moisture-wicking performance t-shirt, lightweight and breathable fabric.\"}','{\"id\":12,\"vendor_id\":null,\"category_id\":\"8\",\"name\":\"Nike Dri-FIT Training T-Shirt\",\"slug\":\"nike-dri-fit-training-t-shirt\",\"sku\":\"NIKE-DFIT-TEE-M\",\"price\":\"29.99\",\"sale_price\":\"24.99\",\"stock_quantity\":200,\"image_url\":\"img\\/products\\/1771418301_51VCdmBI8XL._AC_SX679_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Moisture-wicking performance t-shirt, lightweight and breathable fabric.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:38:21','2026-02-18 05:38:21'),(14,2,'updated','App\\Models\\Product',13,'{\"id\":13,\"vendor_id\":null,\"category_id\":8,\"name\":\"Levi\'s 501 Original Fit Jeans\",\"slug\":\"levis-501-original-fit-jeans\",\"sku\":\"LEVIS-501-BLUE\",\"price\":\"89.99\",\"sale_price\":null,\"stock_quantity\":150,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/1e3a8a\\/ffffff?text=Levis+501\",\"is_new\":false,\"is_featured\":false,\"description\":\"Classic straight fit jeans, 100% cotton denim, timeless style.\"}','{\"id\":13,\"vendor_id\":null,\"category_id\":\"8\",\"name\":\"Levi\'s 501 Original Fit Jeans\",\"slug\":\"levis-501-original-fit-jeans\",\"sku\":\"LEVIS-501-BLUE\",\"price\":\"89.99\",\"sale_price\":null,\"stock_quantity\":150,\"image_url\":\"img\\/products\\/1771418428_51sY1YmXtRL._AC_SX569_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Classic straight fit jeans, 100% cotton denim, timeless style.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:40:28','2026-02-18 05:40:28'),(15,2,'updated','App\\Models\\Product',14,'{\"id\":14,\"vendor_id\":null,\"category_id\":9,\"name\":\"Zara Floral Print Midi Dress\",\"slug\":\"zara-floral-print-midi-dress\",\"sku\":\"ZARA-FLORAL-MIDI\",\"price\":\"79.99\",\"sale_price\":null,\"stock_quantity\":80,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/ec4899\\/ffffff?text=Floral+Dress\",\"is_new\":true,\"is_featured\":false,\"description\":\"Elegant midi dress with floral print, perfect for summer occasions.\"}','{\"id\":14,\"vendor_id\":null,\"category_id\":\"9\",\"name\":\"Zara Floral Print Midi Dress\",\"slug\":\"zara-floral-print-midi-dress\",\"sku\":\"ZARA-FLORAL-MIDI\",\"price\":\"79.99\",\"sale_price\":\"59.99\",\"stock_quantity\":80,\"image_url\":\"img\\/products\\/1771418526_71SwF9fkS1L._AC_SY550_.jpg\",\"is_new\":1,\"is_featured\":0,\"description\":\"Elegant midi dress with floral print, perfect for summer occasions.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:42:06','2026-02-18 05:42:06'),(16,2,'updated','App\\Models\\Product',15,'{\"id\":15,\"vendor_id\":null,\"category_id\":9,\"name\":\"H&M Oversized Knit Sweater\",\"slug\":\"hm-oversized-knit-sweater\",\"sku\":\"HM-KNIT-SWEATER\",\"price\":\"49.99\",\"sale_price\":null,\"stock_quantity\":120,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/8b5cf6\\/ffffff?text=Knit+Sweater\",\"is_new\":false,\"is_featured\":false,\"description\":\"Cozy oversized sweater, soft knit fabric, versatile styling.\"}','{\"id\":15,\"vendor_id\":null,\"category_id\":\"9\",\"name\":\"H&M Oversized Knit Sweater\",\"slug\":\"hm-oversized-knit-sweater\",\"sku\":\"HM-KNIT-SWEATER\",\"price\":\"49.99\",\"sale_price\":null,\"stock_quantity\":120,\"image_url\":\"img\\/products\\/1771418637_719WKNqhA3L._AC_SX522_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Cozy oversized sweater, soft knit fabric, versatile styling.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:43:57','2026-02-18 05:43:57'),(17,2,'updated','App\\Models\\Product',16,'{\"id\":16,\"vendor_id\":null,\"category_id\":10,\"name\":\"Nike Air Max 270 Sneakers\",\"slug\":\"nike-air-max-270-sneakers\",\"sku\":\"NIKE-AM270-BLK\",\"price\":\"159.99\",\"sale_price\":null,\"stock_quantity\":90,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/000000\\/ffffff?text=Air+Max+270\",\"is_new\":false,\"is_featured\":true,\"description\":\"Iconic sneakers with Max Air unit, comfortable all-day wear.\"}','{\"id\":16,\"vendor_id\":null,\"category_id\":\"10\",\"name\":\"Nike Air Max 270 Sneakers\",\"slug\":\"nike-air-max-270-sneakers\",\"sku\":\"NIKE-AM270-BLK\",\"price\":\"159.99\",\"sale_price\":\"139.99\",\"stock_quantity\":90,\"image_url\":\"img\\/products\\/1771418749_716F1sqKwnL._AC_SY500_.jpg\",\"is_new\":0,\"is_featured\":1,\"description\":\"Iconic sneakers with Max Air unit, comfortable all-day wear.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:45:49','2026-02-18 05:45:49'),(18,2,'updated','App\\Models\\Product',17,'{\"id\":17,\"vendor_id\":null,\"category_id\":10,\"name\":\"Adidas Ultraboost 23 Running Shoes\",\"slug\":\"adidas-ultraboost-23-running-shoes\",\"sku\":\"ADIDAS-UB23\",\"price\":\"189.99\",\"sale_price\":null,\"stock_quantity\":75,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/000000\\/ffffff?text=Ultraboost\",\"is_new\":true,\"is_featured\":true,\"description\":\"Premium running shoes with Boost cushioning, responsive energy return.\"}','{\"id\":17,\"vendor_id\":null,\"category_id\":\"10\",\"name\":\"Adidas Ultraboost 23 Running Shoes\",\"slug\":\"adidas-ultraboost-23-running-shoes\",\"sku\":\"ADIDAS-UB23\",\"price\":\"189.99\",\"sale_price\":null,\"stock_quantity\":75,\"image_url\":\"img\\/products\\/1771418842_51iDY0KxMNL._AC_SY575_.jpg\",\"is_new\":1,\"is_featured\":1,\"description\":\"Premium running shoes with Boost cushioning, responsive energy return.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:47:22','2026-02-18 05:47:22'),(19,2,'updated','App\\Models\\Product',18,'{\"id\":18,\"vendor_id\":null,\"category_id\":13,\"name\":\"IKEA PO\\u00c4NG Armchair with Cushion\",\"slug\":\"ikea-poang-armchair-with-cushion\",\"sku\":\"IKEA-POANG-ARM\",\"price\":\"129.00\",\"sale_price\":null,\"stock_quantity\":40,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/8b4513\\/ffffff?text=Armchair\",\"is_new\":false,\"is_featured\":false,\"description\":\"Comfortable armchair with bentwood frame, removable cushion cover.\"}','{\"id\":18,\"vendor_id\":null,\"category_id\":\"13\",\"name\":\"Vepping Lude Multi Colored Armchair Replacement Cover, Fits IKEA Po\\u00e4ng Armchair, Cushion not Included (Cushion Design 3, Cotton - White)\",\"slug\":\"ikea-poang-armchair-with-cushion\",\"sku\":\"IKEA-POANG-ARM\",\"price\":\"129.00\",\"sale_price\":\"99.00\",\"stock_quantity\":40,\"image_url\":\"img\\/products\\/1771418975_61fz6UrwqnL._AC_SL1172_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Comfortable armchair with bentwood frame, removable cushion cover.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:49:35','2026-02-18 05:49:35'),(20,2,'updated','App\\Models\\Product',19,'{\"id\":19,\"vendor_id\":null,\"category_id\":13,\"name\":\"Modern L-Shaped Sectional Sofa\",\"slug\":\"modern-l-shaped-sectional-sofa\",\"sku\":\"SOFA-LSHAPE-GRY\",\"price\":\"899.00\",\"sale_price\":null,\"stock_quantity\":15,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/6b7280\\/ffffff?text=Sectional+Sofa\",\"is_new\":true,\"is_featured\":true,\"description\":\"Spacious sectional sofa with premium fabric upholstery, perfect for living rooms.\"}','{\"id\":19,\"vendor_id\":null,\"category_id\":\"13\",\"name\":\"ZeeFu Convertible Sectional Sofa Couch,Classic 3 Seat L-Shaped Sofa with Movable Ottoman, Modern Dark Grey Velvet Fabric Upholstered Small Sectional Sofa Couch for Small Apartment Living Room Office\",\"slug\":\"modern-l-shaped-sectional-sofa\",\"sku\":\"SOFA-LSHAPE-GRY\",\"price\":\"899.00\",\"sale_price\":null,\"stock_quantity\":15,\"image_url\":\"img\\/products\\/1771419068_81cRkQ1MURL._AC_SL1500_.jpg\",\"is_new\":1,\"is_featured\":1,\"description\":\"Spacious sectional sofa with premium fabric upholstery, perfect for living rooms.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:51:08','2026-02-18 05:51:08'),(21,2,'updated','App\\Models\\Product',20,'{\"id\":20,\"vendor_id\":null,\"category_id\":14,\"name\":\"Ninja Air Fryer Max XL 5.5 Qt\",\"slug\":\"ninja-air-fryer-max-xl-55-qt\",\"sku\":\"NINJA-AF-XL\",\"price\":\"129.99\",\"sale_price\":null,\"stock_quantity\":55,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/1a1a1a\\/ffffff?text=Air+Fryer\",\"is_new\":false,\"is_featured\":true,\"description\":\"Large capacity air fryer with 7 cooking functions, easy to clean.\"}','{\"id\":20,\"vendor_id\":null,\"category_id\":\"14\",\"name\":\"Ninja Air Fryer Max XL 5.5 Qt\",\"slug\":\"ninja-air-fryer-max-xl-55-qt\",\"sku\":\"NINJA-AF-XL\",\"price\":\"129.99\",\"sale_price\":\"99.99\",\"stock_quantity\":55,\"image_url\":\"img\\/products\\/1771419230_61YhIy9LUvL._AC_SL1500_.jpg\",\"is_new\":0,\"is_featured\":1,\"description\":\"Large capacity air fryer with 7 cooking functions, easy to clean.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:53:50','2026-02-18 05:53:50'),(22,2,'updated','App\\Models\\Product',21,'{\"id\":21,\"vendor_id\":null,\"category_id\":14,\"name\":\"KitchenAid Stand Mixer 5-Quart\",\"slug\":\"kitchenaid-stand-mixer-5-quart\",\"sku\":\"KA-MIXER-5QT\",\"price\":\"449.99\",\"sale_price\":null,\"stock_quantity\":30,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/ef4444\\/ffffff?text=Stand+Mixer\",\"is_new\":false,\"is_featured\":false,\"description\":\"Professional stand mixer with 10 speeds, includes multiple attachments.\"}','{\"id\":21,\"vendor_id\":null,\"category_id\":\"14\",\"name\":\"KitchenAid Stand Mixer 5-Quart\",\"slug\":\"kitchenaid-stand-mixer-5-quart\",\"sku\":\"KA-MIXER-5QT\",\"price\":\"449.99\",\"sale_price\":null,\"stock_quantity\":30,\"image_url\":\"img\\/products\\/1771419305_71dwD1MdoSL._AC_SL1500_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Professional stand mixer with 10 speeds, includes multiple attachments.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:55:05','2026-02-18 05:55:05'),(23,2,'deleted','App\\Models\\Product',22,'{\"id\":22,\"vendor_id\":null,\"category_id\":17,\"name\":\"Bowflex Adjustable Dumbbells 52.5 lbs\",\"slug\":\"bowflex-adjustable-dumbbells-525-lbs\",\"sku\":\"BOWFLEX-DB-52\",\"price\":\"349.00\",\"sale_price\":\"299.00\",\"stock_quantity\":25,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/1a1a1a\\/ffffff?text=Dumbbells\",\"is_new\":0,\"is_featured\":1,\"description\":\"Space-saving adjustable dumbbells, replaces 15 sets of weights.\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:55:39','2026-02-18 05:55:39'),(24,2,'deleted','App\\Models\\Product',23,'{\"id\":23,\"vendor_id\":null,\"category_id\":17,\"name\":\"Peloton Bike+ Indoor Cycling\",\"slug\":\"peloton-bike-indoor-cycling\",\"sku\":\"PELOTON-BIKE-PLUS\",\"price\":\"2495.00\",\"sale_price\":null,\"stock_quantity\":10,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/000000\\/ffffff?text=Peloton+Bike\",\"is_new\":1,\"is_featured\":1,\"description\":\"Premium indoor bike with rotating screen, live and on-demand classes.\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:55:45','2026-02-18 05:55:45'),(25,2,'deleted','App\\Models\\Product',24,'{\"id\":24,\"vendor_id\":null,\"category_id\":20,\"name\":\"CeraVe Hydrating Facial Cleanser\",\"slug\":\"cerave-hydrating-facial-cleanser\",\"sku\":\"CERAVE-CLEANSER\",\"price\":\"14.99\",\"sale_price\":\"12.99\",\"stock_quantity\":200,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/4ade80\\/ffffff?text=Cleanser\",\"is_new\":0,\"is_featured\":0,\"description\":\"Gentle hydrating cleanser with ceramides and hyaluronic acid.\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:55:49','2026-02-18 05:55:49'),(26,2,'deleted','App\\Models\\Product',25,'{\"id\":25,\"vendor_id\":null,\"category_id\":21,\"name\":\"Fenty Beauty Pro Filt\'r Foundation\",\"slug\":\"fenty-beauty-pro-filtr-foundation\",\"sku\":\"FENTY-FOUNDATION\",\"price\":\"39.00\",\"sale_price\":null,\"stock_quantity\":150,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/000000\\/ffffff?text=Foundation\",\"is_new\":0,\"is_featured\":1,\"description\":\"Soft matte longwear foundation, 50 shades for all skin tones.\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:55:54','2026-02-18 05:55:54'),(27,2,'deleted','App\\Models\\Product',26,'{\"id\":26,\"vendor_id\":null,\"category_id\":23,\"name\":\"Atomic Habits by James Clear\",\"slug\":\"atomic-habits-by-james-clear\",\"sku\":\"BOOK-ATOMIC-HABITS\",\"price\":\"27.00\",\"sale_price\":\"19.99\",\"stock_quantity\":100,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/1e40af\\/ffffff?text=Atomic+Habits\",\"is_new\":0,\"is_featured\":1,\"description\":\"Bestselling book on building good habits and breaking bad ones.\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:55:58','2026-02-18 05:55:58'),(28,2,'updated','App\\Models\\Product',27,'{\"id\":27,\"vendor_id\":null,\"category_id\":23,\"name\":\"The Psychology of Money by Morgan Housel\",\"slug\":\"the-psychology-of-money-by-morgan-housel\",\"sku\":\"BOOK-PSY-MONEY\",\"price\":\"24.00\",\"sale_price\":null,\"stock_quantity\":80,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/059669\\/ffffff?text=Psychology+Money\",\"is_new\":false,\"is_featured\":false,\"description\":\"Timeless lessons on wealth, greed, and happiness.\"}','{\"id\":27,\"vendor_id\":null,\"category_id\":\"23\",\"name\":\"The Psychology of Money by Morgan Housel\",\"slug\":\"the-psychology-of-money-by-morgan-housel\",\"sku\":\"BOOK-PSY-MONEY\",\"price\":\"24.00\",\"sale_price\":null,\"stock_quantity\":80,\"image_url\":\"img\\/products\\/1771419399_81gC3mdNi5L._SL1500_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Timeless lessons on wealth, greed, and happiness.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:56:39','2026-02-18 05:56:39'),(29,2,'updated','App\\Models\\Product',28,'{\"id\":28,\"vendor_id\":null,\"category_id\":24,\"name\":\"LEGO Star Wars Millennium Falcon\",\"slug\":\"lego-star-wars-millennium-falcon\",\"sku\":\"LEGO-SW-MF\",\"price\":\"169.99\",\"sale_price\":null,\"stock_quantity\":35,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/fbbf24\\/000000?text=LEGO\",\"is_new\":false,\"is_featured\":true,\"description\":\"Iconic LEGO set with 1,351 pieces, includes minifigures.\"}','{\"id\":28,\"vendor_id\":null,\"category_id\":\"24\",\"name\":\"LEGO Star Wars Millennium Falcon\",\"slug\":\"lego-star-wars-millennium-falcon\",\"sku\":\"LEGO-SW-MF\",\"price\":\"169.99\",\"sale_price\":\"149.99\",\"stock_quantity\":35,\"image_url\":\"img\\/products\\/1771419453_81PhO-kyPuL._AC_SL1500_.jpg\",\"is_new\":0,\"is_featured\":1,\"description\":\"Iconic LEGO set with 1,351 pieces, includes minifigures.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:57:33','2026-02-18 05:57:33'),(30,2,'updated','App\\Models\\Product',29,'{\"id\":29,\"vendor_id\":null,\"category_id\":25,\"name\":\"Garmin Dash Cam 67W\",\"slug\":\"garmin-dash-cam-67w\",\"sku\":\"GARMIN-DASH-67W\",\"price\":\"249.99\",\"sale_price\":null,\"stock_quantity\":40,\"image_url\":\"https:\\/\\/via.placeholder.com\\/400x400\\/000000\\/ffffff?text=Dash+Cam\",\"is_new\":true,\"is_featured\":false,\"description\":\"Ultra-wide 180\\u00b0 field of view, voice control, GPS tracking.\"}','{\"id\":29,\"vendor_id\":null,\"category_id\":\"25\",\"name\":\"Garmin Dash Cam 67W\",\"slug\":\"garmin-dash-cam-67w\",\"sku\":\"GARMIN-DASH-67W\",\"price\":\"249.99\",\"sale_price\":null,\"stock_quantity\":40,\"image_url\":\"img\\/products\\/1771419512_614VoTbsIBL._AC_SL1200_.jpg\",\"is_new\":1,\"is_featured\":0,\"description\":\"Ultra-wide 180\\u00b0 field of view, voice control, GPS tracking.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 05:58:32','2026-02-18 05:58:32'),(31,2,'updated','App\\Models\\User',3,'{\"id\":3,\"role_id\":2,\"name\":\"Staff Demo\",\"email\":\"staff@demo.com\",\"google_id\":null,\"notification_preferences\":null,\"address\":null,\"email_verified_at\":\"2026-02-17T14:23:45.000000Z\",\"password\":\"$2y$12$BGPtf.FdpllASVPaVn5ULOziYXNA.TFqOZl.ST1k4g5vQIXz32WhW\",\"date_of_birth\":null,\"is_active\":1,\"phone_number\":\"+1-555-0102\",\"status\":\"active\"}','{\"id\":3,\"role_id\":2,\"name\":\"Staff Demo\",\"email\":\"staff@demo.com\",\"google_id\":null,\"notification_preferences\":null,\"address\":null,\"email_verified_at\":\"2026-02-17 14:23:45\",\"password\":\"$2y$12$BGPtf.FdpllASVPaVn5ULOziYXNA.TFqOZl.ST1k4g5vQIXz32WhW\",\"date_of_birth\":null,\"is_active\":false,\"phone_number\":\"+1-555-0102\",\"status\":\"active\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 06:39:43','2026-02-18 06:39:43'),(32,2,'updated','App\\Models\\User',3,'{\"id\":3,\"role_id\":2,\"name\":\"Staff Demo\",\"email\":\"staff@demo.com\",\"google_id\":null,\"notification_preferences\":null,\"address\":null,\"email_verified_at\":\"2026-02-17T14:23:45.000000Z\",\"password\":\"$2y$12$BGPtf.FdpllASVPaVn5ULOziYXNA.TFqOZl.ST1k4g5vQIXz32WhW\",\"date_of_birth\":null,\"is_active\":0,\"phone_number\":\"+1-555-0102\",\"status\":\"active\"}','{\"id\":3,\"role_id\":2,\"name\":\"Staff Demo\",\"email\":\"staff@demo.com\",\"google_id\":null,\"notification_preferences\":null,\"address\":null,\"email_verified_at\":\"2026-02-17 14:23:45\",\"password\":\"$2y$12$BGPtf.FdpllASVPaVn5ULOziYXNA.TFqOZl.ST1k4g5vQIXz32WhW\",\"date_of_birth\":null,\"is_active\":true,\"phone_number\":\"+1-555-0102\",\"status\":\"active\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','2026-02-18 06:39:45','2026-02-18 06:39:45'),(33,14,'updated','App\\Models\\Product',12,'{\"id\":12,\"vendor_id\":null,\"category_id\":8,\"name\":\"Nike Dri-FIT Training T-Shirt\",\"slug\":\"nike-dri-fit-training-t-shirt\",\"sku\":\"NIKE-DFIT-TEE-M\",\"price\":\"29.99\",\"sale_price\":null,\"stock_quantity\":200,\"image_url\":\"https:\\/\\/e-commerce.app\\/img\\/products\\/1771418301_51VCdmBI8XL._AC_SX679_.jpg\",\"is_new\":false,\"is_featured\":false,\"description\":\"Moisture-wicking performance t-shirt, lightweight and breathable fabric.\"}','{\"id\":12,\"vendor_id\":null,\"category_id\":8,\"name\":\"Nike Dri-FIT Training T-Shirt\",\"slug\":\"nike-dri-fit-training-t-shirt\",\"sku\":\"NIKE-DFIT-TEE-M\",\"price\":\"29.99\",\"sale_price\":\"24.99\",\"stock_quantity\":199,\"image_url\":\"img\\/products\\/1771418301_51VCdmBI8XL._AC_SX679_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Moisture-wicking performance t-shirt, lightweight and breathable fabric.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','2026-02-19 04:35:56','2026-02-19 04:35:56'),(34,14,'created','App\\Models\\Order',1,NULL,'{\"user_id\":14,\"first_name\":\"John\",\"last_name\":\"Smith\",\"email\":\"hihihihi13245768@gmail.com\",\"phone\":\"0292144445\",\"address\":\"thanh,  \\u1ea1vadcbzklzabn, \\u00e1ds, aa, Untied\",\"note\":null,\"total\":32.989999999999995,\"order_status\":\"pending\",\"payment_method\":\"cod\",\"payment_status\":\"unpaid\",\"id\":1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','2026-02-19 04:35:57','2026-02-19 04:35:57'),(35,14,'updated','App\\Models\\Product',28,'{\"id\":28,\"vendor_id\":null,\"category_id\":24,\"name\":\"LEGO Star Wars Millennium Falcon\",\"slug\":\"lego-star-wars-millennium-falcon\",\"sku\":\"LEGO-SW-MF\",\"price\":\"169.99\",\"sale_price\":null,\"stock_quantity\":35,\"image_url\":\"https:\\/\\/e-commerce.app\\/img\\/products\\/1771419453_81PhO-kyPuL._AC_SL1500_.jpg\",\"is_new\":false,\"is_featured\":true,\"description\":\"Iconic LEGO set with 1,351 pieces, includes minifigures.\"}','{\"id\":28,\"vendor_id\":null,\"category_id\":24,\"name\":\"LEGO Star Wars Millennium Falcon\",\"slug\":\"lego-star-wars-millennium-falcon\",\"sku\":\"LEGO-SW-MF\",\"price\":\"169.99\",\"sale_price\":\"149.99\",\"stock_quantity\":34,\"image_url\":\"img\\/products\\/1771419453_81PhO-kyPuL._AC_SL1500_.jpg\",\"is_new\":0,\"is_featured\":1,\"description\":\"Iconic LEGO set with 1,351 pieces, includes minifigures.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','2026-02-19 04:36:09','2026-02-19 04:36:09'),(36,14,'created','App\\Models\\Order',2,NULL,'{\"user_id\":14,\"first_name\":\"John\",\"last_name\":\"Smith\",\"email\":\"hihihihi13245768@gmail.com\",\"phone\":\"0292144445\",\"address\":\"thanh,  \\u1ea1vadcbzklzabn, \\u00e1ds, aa, Untied\",\"note\":null,\"total\":172.99,\"order_status\":\"pending\",\"payment_method\":\"cod\",\"payment_status\":\"unpaid\",\"id\":2}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','2026-02-19 04:36:09','2026-02-19 04:36:09'),(37,14,'updated','App\\Models\\Product',21,'{\"id\":21,\"vendor_id\":null,\"category_id\":14,\"name\":\"KitchenAid Stand Mixer 5-Quart\",\"slug\":\"kitchenaid-stand-mixer-5-quart\",\"sku\":\"KA-MIXER-5QT\",\"price\":\"449.99\",\"sale_price\":null,\"stock_quantity\":30,\"image_url\":\"https:\\/\\/e-commerce.app\\/img\\/products\\/1771419305_71dwD1MdoSL._AC_SL1500_.jpg\",\"is_new\":false,\"is_featured\":false,\"description\":\"Professional stand mixer with 10 speeds, includes multiple attachments.\"}','{\"id\":21,\"vendor_id\":null,\"category_id\":14,\"name\":\"KitchenAid Stand Mixer 5-Quart\",\"slug\":\"kitchenaid-stand-mixer-5-quart\",\"sku\":\"KA-MIXER-5QT\",\"price\":\"449.99\",\"sale_price\":null,\"stock_quantity\":29,\"image_url\":\"img\\/products\\/1771419305_71dwD1MdoSL._AC_SL1500_.jpg\",\"is_new\":0,\"is_featured\":0,\"description\":\"Professional stand mixer with 10 speeds, includes multiple attachments.\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','2026-02-19 04:36:22','2026-02-19 04:36:22'),(38,14,'created','App\\Models\\Order',3,NULL,'{\"user_id\":14,\"first_name\":\"John\",\"last_name\":\"Smith\",\"email\":\"hihihihi13245768@gmail.com\",\"phone\":\"0292144445\",\"address\":\"thanh,  \\u1ea1vadcbzklzabn, \\u00e1ds, aa, Untied\",\"note\":null,\"total\":452.99,\"order_status\":\"pending\",\"payment_method\":\"cod\",\"payment_status\":\"unpaid\",\"id\":3}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','2026-02-19 04:36:22','2026-02-19 04:36:22');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_logs`
--

DROP TABLE IF EXISTS `auth_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_fingerprint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `geo_location` json DEFAULT NULL,
  `risk_score` double DEFAULT NULL,
  `risk_level` enum('low','medium','high','critical') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_decision` enum('passive_auth_allow','challenge_otp','challenge_biometric','block_access') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_successful` tinyint(1) NOT NULL DEFAULT '0',
  `login_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `auth_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_logs`
--

LOCK TABLES `auth_logs` WRITE;
/*!40000 ALTER TABLE `auth_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-home_products','a:2:{s:11:\"newProducts\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:8:{i:0;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:1;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:23:\"iPhone 15 Pro Max 256GB\";s:4:\"slug\";s:23:\"iphone-15-pro-max-256gb\";s:3:\"sku\";s:11:\"IPH15PM-256\";s:5:\"price\";s:7:\"1199.00\";s:10:\"sale_price\";s:7:\"1099.00\";s:14:\"stock_quantity\";i:50;s:9:\"image_url\";s:85:\"img/products/1771414452_iphone-15-pro-max-titan-xanh-2-638629415445427350-750x500.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:121:\"Latest iPhone with A17 Pro chip, titanium design, and advanced camera system. Features 6.7-inch Super Retina XDR display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 11:34:12\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:1;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:23:\"iPhone 15 Pro Max 256GB\";s:4:\"slug\";s:23:\"iphone-15-pro-max-256gb\";s:3:\"sku\";s:11:\"IPH15PM-256\";s:5:\"price\";s:7:\"1199.00\";s:10:\"sale_price\";s:7:\"1099.00\";s:14:\"stock_quantity\";i:50;s:9:\"image_url\";s:85:\"img/products/1771414452_iphone-15-pro-max-titan-xanh-2-638629415445427350-750x500.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:121:\"Latest iPhone with A17 Pro chip, titanium design, and advanced camera system. Features 6.7-inch Super Retina XDR display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 11:34:12\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:1;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:2;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:30:\"Samsung Galaxy S24 Ultra 512GB\";s:4:\"slug\";s:30:\"samsung-galaxy-s24-ultra-512gb\";s:3:\"sku\";s:10:\"SGS24U-512\";s:5:\"price\";s:7:\"1299.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:35;s:9:\"image_url\";s:66:\"img/products/1771416024_samsung-galaxy-s24-ultra-xam-1-750x500.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:100:\"Premium Android flagship with S Pen, 200MP camera, and AI features. 6.8-inch Dynamic AMOLED display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:00:24\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:2;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:30:\"Samsung Galaxy S24 Ultra 512GB\";s:4:\"slug\";s:30:\"samsung-galaxy-s24-ultra-512gb\";s:3:\"sku\";s:10:\"SGS24U-512\";s:5:\"price\";s:7:\"1299.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:35;s:9:\"image_url\";s:66:\"img/products/1771416024_samsung-galaxy-s24-ultra-xam-1-750x500.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:100:\"Premium Android flagship with S Pen, 200MP camera, and AI features. 6.8-inch Dynamic AMOLED display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:00:24\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:2;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:3;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:23:\"iPad Pro 12.9\" M2 256GB\";s:4:\"slug\";s:21:\"ipad-pro-129-m2-256gb\";s:3:\"sku\";s:14:\"IPADPRO-M2-256\";s:5:\"price\";s:7:\"1099.00\";s:10:\"sale_price\";s:6:\"999.00\";s:14:\"stock_quantity\";i:25;s:9:\"image_url\";s:50:\"img/products/1771416282_619G-Sa2NSL._AC_SX522_.jpg\";s:6:\"is_new\";i:0;s:11:\"is_featured\";i:1;s:11:\"description\";s:82:\"Powerful tablet with M2 chip, Liquid Retina XDR display, and Apple Pencil support.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:04:42\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:3;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:23:\"iPad Pro 12.9\" M2 256GB\";s:4:\"slug\";s:21:\"ipad-pro-129-m2-256gb\";s:3:\"sku\";s:14:\"IPADPRO-M2-256\";s:5:\"price\";s:7:\"1099.00\";s:10:\"sale_price\";s:6:\"999.00\";s:14:\"stock_quantity\";i:25;s:9:\"image_url\";s:50:\"img/products/1771416282_619G-Sa2NSL._AC_SX522_.jpg\";s:6:\"is_new\";i:0;s:11:\"is_featured\";i:1;s:11:\"description\";s:82:\"Powerful tablet with M2 chip, Liquid Retina XDR display, and Apple Pencil support.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:04:42\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:3;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:4;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:26:\"MacBook Pro 16\" M3 Max 1TB\";s:4:\"slug\";s:25:\"macbook-pro-16-m3-max-1tb\";s:3:\"sku\";s:15:\"MBP16-M3MAX-1TB\";s:5:\"price\";s:7:\"3499.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:15;s:9:\"image_url\";s:51:\"img/products/1771417342_41H8TjeAcwL._AC_SL1200_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:101:\"Professional laptop with M3 Max chip, 16-inch Liquid Retina XDR display, up to 22 hours battery life.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:22:22\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:4;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:26:\"MacBook Pro 16\" M3 Max 1TB\";s:4:\"slug\";s:25:\"macbook-pro-16-m3-max-1tb\";s:3:\"sku\";s:15:\"MBP16-M3MAX-1TB\";s:5:\"price\";s:7:\"3499.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:15;s:9:\"image_url\";s:51:\"img/products/1771417342_41H8TjeAcwL._AC_SL1200_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:101:\"Professional laptop with M3 Max chip, 16-inch Liquid Retina XDR display, up to 22 hours battery life.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:22:22\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:4;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:5;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:29:\"Dell XPS 15 Intel i9 32GB RAM\";s:4:\"slug\";s:29:\"dell-xps-15-intel-i9-32gb-ram\";s:3:\"sku\";s:12:\"DELLXPS15-I9\";s:5:\"price\";s:7:\"2299.00\";s:10:\"sale_price\";s:7:\"1999.00\";s:14:\"stock_quantity\";i:20;s:9:\"image_url\";s:51:\"img/products/1771417500_61Ks9X44eVL._AC_SL1181_.jpg\";s:6:\"is_new\";i:0;s:11:\"is_featured\";i:0;s:11:\"description\";s:89:\"Premium Windows laptop with 15.6\" 4K OLED display, NVIDIA RTX 4060, perfect for creators.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:25:00\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:5;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:29:\"Dell XPS 15 Intel i9 32GB RAM\";s:4:\"slug\";s:29:\"dell-xps-15-intel-i9-32gb-ram\";s:3:\"sku\";s:12:\"DELLXPS15-I9\";s:5:\"price\";s:7:\"2299.00\";s:10:\"sale_price\";s:7:\"1999.00\";s:14:\"stock_quantity\";i:20;s:9:\"image_url\";s:51:\"img/products/1771417500_61Ks9X44eVL._AC_SL1181_.jpg\";s:6:\"is_new\";i:0;s:11:\"is_featured\";i:0;s:11:\"description\";s:89:\"Premium Windows laptop with 15.6\" 4K OLED display, NVIDIA RTX 4060, perfect for creators.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:25:00\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:5;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:6;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:35:\"ASUS ROG Zephyrus G16 Gaming Laptop\";s:4:\"slug\";s:35:\"asus-rog-zephyrus-g16-gaming-laptop\";s:3:\"sku\";s:12:\"ASUS-ROG-G16\";s:5:\"price\";s:7:\"2499.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:12;s:9:\"image_url\";s:51:\"img/products/1771417709_81n1T4CYfmL._AC_SL1500_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:0;s:11:\"description\";s:75:\"High-performance gaming laptop with RTX 4080, Intel Core i9, 240Hz display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:28:29\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:6;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:35:\"ASUS ROG Zephyrus G16 Gaming Laptop\";s:4:\"slug\";s:35:\"asus-rog-zephyrus-g16-gaming-laptop\";s:3:\"sku\";s:12:\"ASUS-ROG-G16\";s:5:\"price\";s:7:\"2499.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:12;s:9:\"image_url\";s:51:\"img/products/1771417709_81n1T4CYfmL._AC_SL1500_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:0;s:11:\"description\";s:75:\"High-performance gaming laptop with RTX 4080, Intel Core i9, 240Hz display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:28:29\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:6;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:7;s:9:\"vendor_id\";N;s:11:\"category_id\";i:5;s:4:\"name\";s:35:\"Sony WH-1000XM5 Wireless Headphones\";s:4:\"slug\";s:35:\"sony-wh-1000xm5-wireless-headphones\";s:3:\"sku\";s:14:\"SONY-WH1000XM5\";s:5:\"price\";s:6:\"399.00\";s:10:\"sale_price\";s:6:\"349.00\";s:14:\"stock_quantity\";i:60;s:9:\"image_url\";s:51:\"img/products/1771417817_61BGLYEN-xL._AC_SL1500_.jpg\";s:6:\"is_new\";i:0;s:11:\"is_featured\";i:1;s:11:\"description\";s:85:\"Industry-leading noise cancellation, exceptional sound quality, 30-hour battery life.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:30:17\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:7;s:9:\"vendor_id\";N;s:11:\"category_id\";i:5;s:4:\"name\";s:35:\"Sony WH-1000XM5 Wireless Headphones\";s:4:\"slug\";s:35:\"sony-wh-1000xm5-wireless-headphones\";s:3:\"sku\";s:14:\"SONY-WH1000XM5\";s:5:\"price\";s:6:\"399.00\";s:10:\"sale_price\";s:6:\"349.00\";s:14:\"stock_quantity\";i:60;s:9:\"image_url\";s:51:\"img/products/1771417817_61BGLYEN-xL._AC_SL1500_.jpg\";s:6:\"is_new\";i:0;s:11:\"is_featured\";i:1;s:11:\"description\";s:85:\"Industry-leading noise cancellation, exceptional sound quality, 30-hour battery life.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:30:17\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:7;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:8;s:9:\"vendor_id\";N;s:11:\"category_id\";i:5;s:4:\"name\";s:39:\"AirPods Pro 2nd Generation with MagSafe\";s:4:\"slug\";s:39:\"airpods-pro-2nd-generation-with-magsafe\";s:3:\"sku\";s:12:\"AIRPODS-PRO2\";s:5:\"price\";s:6:\"249.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:100;s:9:\"image_url\";s:51:\"img/products/1771417919_41QztOA1UuL._AC_SL1200_.jpg\";s:6:\"is_new\";i:0;s:11:\"is_featured\";i:1;s:11:\"description\";s:98:\"Premium wireless earbuds with active noise cancellation, spatial audio, and adaptive transparency.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:31:59\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:8;s:9:\"vendor_id\";N;s:11:\"category_id\";i:5;s:4:\"name\";s:39:\"AirPods Pro 2nd Generation with MagSafe\";s:4:\"slug\";s:39:\"airpods-pro-2nd-generation-with-magsafe\";s:3:\"sku\";s:12:\"AIRPODS-PRO2\";s:5:\"price\";s:6:\"249.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:100;s:9:\"image_url\";s:51:\"img/products/1771417919_41QztOA1UuL._AC_SL1200_.jpg\";s:6:\"is_new\";i:0;s:11:\"is_featured\";i:1;s:11:\"description\";s:98:\"Premium wireless earbuds with active noise cancellation, spatial audio, and adaptive transparency.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:31:59\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:8:\"arrivals\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:8:{i:0;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:1;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:23:\"iPhone 15 Pro Max 256GB\";s:4:\"slug\";s:23:\"iphone-15-pro-max-256gb\";s:3:\"sku\";s:11:\"IPH15PM-256\";s:5:\"price\";s:7:\"1199.00\";s:10:\"sale_price\";s:7:\"1099.00\";s:14:\"stock_quantity\";i:50;s:9:\"image_url\";s:85:\"img/products/1771414452_iphone-15-pro-max-titan-xanh-2-638629415445427350-750x500.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:121:\"Latest iPhone with A17 Pro chip, titanium design, and advanced camera system. Features 6.7-inch Super Retina XDR display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 11:34:12\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:1;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:23:\"iPhone 15 Pro Max 256GB\";s:4:\"slug\";s:23:\"iphone-15-pro-max-256gb\";s:3:\"sku\";s:11:\"IPH15PM-256\";s:5:\"price\";s:7:\"1199.00\";s:10:\"sale_price\";s:7:\"1099.00\";s:14:\"stock_quantity\";i:50;s:9:\"image_url\";s:85:\"img/products/1771414452_iphone-15-pro-max-titan-xanh-2-638629415445427350-750x500.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:121:\"Latest iPhone with A17 Pro chip, titanium design, and advanced camera system. Features 6.7-inch Super Retina XDR display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 11:34:12\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:1;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:2;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:30:\"Samsung Galaxy S24 Ultra 512GB\";s:4:\"slug\";s:30:\"samsung-galaxy-s24-ultra-512gb\";s:3:\"sku\";s:10:\"SGS24U-512\";s:5:\"price\";s:7:\"1299.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:35;s:9:\"image_url\";s:66:\"img/products/1771416024_samsung-galaxy-s24-ultra-xam-1-750x500.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:100:\"Premium Android flagship with S Pen, 200MP camera, and AI features. 6.8-inch Dynamic AMOLED display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:00:24\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:2;s:9:\"vendor_id\";N;s:11:\"category_id\";i:3;s:4:\"name\";s:30:\"Samsung Galaxy S24 Ultra 512GB\";s:4:\"slug\";s:30:\"samsung-galaxy-s24-ultra-512gb\";s:3:\"sku\";s:10:\"SGS24U-512\";s:5:\"price\";s:7:\"1299.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:35;s:9:\"image_url\";s:66:\"img/products/1771416024_samsung-galaxy-s24-ultra-xam-1-750x500.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:100:\"Premium Android flagship with S Pen, 200MP camera, and AI features. 6.8-inch Dynamic AMOLED display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:00:24\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:2;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:4;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:26:\"MacBook Pro 16\" M3 Max 1TB\";s:4:\"slug\";s:25:\"macbook-pro-16-m3-max-1tb\";s:3:\"sku\";s:15:\"MBP16-M3MAX-1TB\";s:5:\"price\";s:7:\"3499.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:15;s:9:\"image_url\";s:51:\"img/products/1771417342_41H8TjeAcwL._AC_SL1200_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:101:\"Professional laptop with M3 Max chip, 16-inch Liquid Retina XDR display, up to 22 hours battery life.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:22:22\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:4;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:26:\"MacBook Pro 16\" M3 Max 1TB\";s:4:\"slug\";s:25:\"macbook-pro-16-m3-max-1tb\";s:3:\"sku\";s:15:\"MBP16-M3MAX-1TB\";s:5:\"price\";s:7:\"3499.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:15;s:9:\"image_url\";s:51:\"img/products/1771417342_41H8TjeAcwL._AC_SL1200_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:101:\"Professional laptop with M3 Max chip, 16-inch Liquid Retina XDR display, up to 22 hours battery life.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:22:22\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:3;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:6;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:35:\"ASUS ROG Zephyrus G16 Gaming Laptop\";s:4:\"slug\";s:35:\"asus-rog-zephyrus-g16-gaming-laptop\";s:3:\"sku\";s:12:\"ASUS-ROG-G16\";s:5:\"price\";s:7:\"2499.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:12;s:9:\"image_url\";s:51:\"img/products/1771417709_81n1T4CYfmL._AC_SL1500_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:0;s:11:\"description\";s:75:\"High-performance gaming laptop with RTX 4080, Intel Core i9, 240Hz display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:28:29\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:6;s:9:\"vendor_id\";N;s:11:\"category_id\";i:4;s:4:\"name\";s:35:\"ASUS ROG Zephyrus G16 Gaming Laptop\";s:4:\"slug\";s:35:\"asus-rog-zephyrus-g16-gaming-laptop\";s:3:\"sku\";s:12:\"ASUS-ROG-G16\";s:5:\"price\";s:7:\"2499.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:12;s:9:\"image_url\";s:51:\"img/products/1771417709_81n1T4CYfmL._AC_SL1500_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:0;s:11:\"description\";s:75:\"High-performance gaming laptop with RTX 4080, Intel Core i9, 240Hz display.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:28:29\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:4;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:9;s:9:\"vendor_id\";N;s:11:\"category_id\";i:5;s:4:\"name\";s:31:\"Bose QuietComfort Ultra Earbuds\";s:4:\"slug\";s:31:\"bose-quietcomfort-ultra-earbuds\";s:3:\"sku\";s:9:\"BOSE-QCUE\";s:5:\"price\";s:6:\"299.00\";s:10:\"sale_price\";s:6:\"279.00\";s:14:\"stock_quantity\";i:45;s:9:\"image_url\";s:51:\"img/products/1771417997_51HYcr7W1QL._AC_SL1500_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:0;s:11:\"description\";s:74:\"World-class noise cancellation, immersive audio, comfortable all-day wear.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:33:17\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:9;s:9:\"vendor_id\";N;s:11:\"category_id\";i:5;s:4:\"name\";s:31:\"Bose QuietComfort Ultra Earbuds\";s:4:\"slug\";s:31:\"bose-quietcomfort-ultra-earbuds\";s:3:\"sku\";s:9:\"BOSE-QCUE\";s:5:\"price\";s:6:\"299.00\";s:10:\"sale_price\";s:6:\"279.00\";s:14:\"stock_quantity\";i:45;s:9:\"image_url\";s:51:\"img/products/1771417997_51HYcr7W1QL._AC_SL1500_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:0;s:11:\"description\";s:74:\"World-class noise cancellation, immersive audio, comfortable all-day wear.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:33:17\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:5;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:14;s:9:\"vendor_id\";N;s:11:\"category_id\";i:9;s:4:\"name\";s:28:\"Zara Floral Print Midi Dress\";s:4:\"slug\";s:28:\"zara-floral-print-midi-dress\";s:3:\"sku\";s:16:\"ZARA-FLORAL-MIDI\";s:5:\"price\";s:5:\"79.99\";s:10:\"sale_price\";s:5:\"59.99\";s:14:\"stock_quantity\";i:80;s:9:\"image_url\";s:50:\"img/products/1771418526_71SwF9fkS1L._AC_SY550_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:0;s:11:\"description\";s:67:\"Elegant midi dress with floral print, perfect for summer occasions.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:42:06\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:14;s:9:\"vendor_id\";N;s:11:\"category_id\";i:9;s:4:\"name\";s:28:\"Zara Floral Print Midi Dress\";s:4:\"slug\";s:28:\"zara-floral-print-midi-dress\";s:3:\"sku\";s:16:\"ZARA-FLORAL-MIDI\";s:5:\"price\";s:5:\"79.99\";s:10:\"sale_price\";s:5:\"59.99\";s:14:\"stock_quantity\";i:80;s:9:\"image_url\";s:50:\"img/products/1771418526_71SwF9fkS1L._AC_SY550_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:0;s:11:\"description\";s:67:\"Elegant midi dress with floral print, perfect for summer occasions.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:42:06\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:6;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:17;s:9:\"vendor_id\";N;s:11:\"category_id\";i:10;s:4:\"name\";s:34:\"Adidas Ultraboost 23 Running Shoes\";s:4:\"slug\";s:34:\"adidas-ultraboost-23-running-shoes\";s:3:\"sku\";s:11:\"ADIDAS-UB23\";s:5:\"price\";s:6:\"189.99\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:75;s:9:\"image_url\";s:50:\"img/products/1771418842_51iDY0KxMNL._AC_SY575_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:70:\"Premium running shoes with Boost cushioning, responsive energy return.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:47:22\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:17;s:9:\"vendor_id\";N;s:11:\"category_id\";i:10;s:4:\"name\";s:34:\"Adidas Ultraboost 23 Running Shoes\";s:4:\"slug\";s:34:\"adidas-ultraboost-23-running-shoes\";s:3:\"sku\";s:11:\"ADIDAS-UB23\";s:5:\"price\";s:6:\"189.99\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:75;s:9:\"image_url\";s:50:\"img/products/1771418842_51iDY0KxMNL._AC_SY575_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:70:\"Premium running shoes with Boost cushioning, responsive energy return.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:47:22\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}i:7;O:18:\"App\\Models\\Product\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:16:{s:2:\"id\";i:19;s:9:\"vendor_id\";N;s:11:\"category_id\";i:13;s:4:\"name\";s:198:\"ZeeFu Convertible Sectional Sofa Couch,Classic 3 Seat L-Shaped Sofa with Movable Ottoman, Modern Dark Grey Velvet Fabric Upholstered Small Sectional Sofa Couch for Small Apartment Living Room Office\";s:4:\"slug\";s:30:\"modern-l-shaped-sectional-sofa\";s:3:\"sku\";s:15:\"SOFA-LSHAPE-GRY\";s:5:\"price\";s:6:\"899.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:15;s:9:\"image_url\";s:51:\"img/products/1771419068_81cRkQ1MURL._AC_SL1500_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:81:\"Spacious sectional sofa with premium fabric upholstery, perfect for living rooms.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:51:08\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:16:{s:2:\"id\";i:19;s:9:\"vendor_id\";N;s:11:\"category_id\";i:13;s:4:\"name\";s:198:\"ZeeFu Convertible Sectional Sofa Couch,Classic 3 Seat L-Shaped Sofa with Movable Ottoman, Modern Dark Grey Velvet Fabric Upholstered Small Sectional Sofa Couch for Small Apartment Living Room Office\";s:4:\"slug\";s:30:\"modern-l-shaped-sectional-sofa\";s:3:\"sku\";s:15:\"SOFA-LSHAPE-GRY\";s:5:\"price\";s:6:\"899.00\";s:10:\"sale_price\";N;s:14:\"stock_quantity\";i:15;s:9:\"image_url\";s:51:\"img/products/1771419068_81cRkQ1MURL._AC_SL1500_.jpg\";s:6:\"is_new\";i:1;s:11:\"is_featured\";i:1;s:11:\"description\";s:81:\"Spacious sectional sofa with premium fabric upholstery, perfect for living rooms.\";s:10:\"created_at\";s:19:\"2026-02-17 14:55:54\";s:10:\"updated_at\";s:19:\"2026-02-18 12:51:08\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:6:\"is_new\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:11:\"category_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:3:\"sku\";i:4;s:5:\"price\";i:5;s:10:\"sale_price\";i:6;s:14:\"stock_quantity\";i:7;s:9:\"image_url\";i:8;s:6:\"is_new\";i:9;s:11:\"is_featured\";i:10;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0scoutMetadata\";a:0:{}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}',1771773660),('laravel-cache-john@example.com|127.0.0.1','i:1;',1771421818),('laravel-cache-john@example.com|127.0.0.1:timer','i:1771421818;',1771421818),('laravel-cache-khoa@example.com|127.0.0.1','i:1;',1771421832),('laravel-cache-khoa@example.com|127.0.0.1:timer','i:1771421832;',1771421832),('laravel-cache-risk_rules','a:0:{}',2086860956),('laravel-cache-settings.allow_vendor_registration','N;',2087130138),('laravel-cache-settings.currency_symbol','N;',2087130138),('laravel-cache-settings.enable_paypal','N;',2087130138),('laravel-cache-settings.enable_stripe','N;',2087130138),('laravel-cache-settings.mail_from_address','N;',2087130138),('laravel-cache-settings.mail_from_name','N;',2087130138),('laravel-cache-settings.mail_host','N;',2087130138),('laravel-cache-settings.mail_port','N;',2087130138),('laravel-cache-settings.mail_username','N;',2087130138),('laravel-cache-settings.paypal_client_id','N;',2087130138),('laravel-cache-settings.require_email_verification','N;',2087130138),('laravel-cache-settings.site_name','N;',2087130138),('laravel-cache-settings.stripe_key','N;',2087130138),('laravel-cache-settings.support_email','N;',2087130138),('laravel-cache-settings.timezone','N;',2087130138),('laravel-cache-test@example.com|127.0.0.1','i:1;',1771500960),('laravel-cache-test@example.com|127.0.0.1:timer','i:1771500960;',1771500960);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (2,NULL,'Electronics','electronics','Latest electronic devices and gadgets','img/categories/1771345661_electronics.png',1,NULL,'2026-02-17 07:54:33','2026-02-17 09:27:41'),(3,2,'Smartphones & Tablets','smartphones-tablets','Mobile phones, tablets and accessories','img/categories/1771413046_smartphones-tablets.png',1,NULL,'2026-02-17 07:54:33','2026-02-18 06:28:28'),(4,2,'Laptops & Computers','laptops-computers','Laptops, desktops, and computer accessories','img/categories/1771413060_laptops-computers.png',1,NULL,'2026-02-17 07:54:33','2026-02-18 06:35:28'),(5,2,'Audio & Headphones','audio-headphones','Headphones, earbuds, speakers and audio equipment','img/categories/1771345333_audio-headphones.png',1,NULL,'2026-02-17 07:54:33','2026-02-18 06:28:39'),(6,2,'Cameras & Photography','cameras-photography','Digital cameras, lenses, and photography gear','img/categories/1771345545_cameras-photography.png',1,NULL,'2026-02-17 07:54:33','2026-02-18 06:27:54'),(7,NULL,'Fashion','fashion','Clothing and fashion accessories','img/categories/1771345588_fashion.png',1,NULL,'2026-02-17 07:54:33','2026-02-17 09:26:28'),(8,7,'Men\'s Clothing','mens-clothing','Men\'s fashion and apparel',NULL,1,NULL,'2026-02-17 07:54:33','2026-02-18 06:32:47'),(9,7,'Women\'s Clothing','womens-clothing','Women\'s fashion and apparel',NULL,1,NULL,'2026-02-17 07:54:33','2026-02-18 06:33:04'),(10,2,'Shoes & Footwear','shoes-footwear','Shoes, sneakers, boots and sandals',NULL,1,NULL,'2026-02-17 07:54:33','2026-02-18 06:33:24'),(12,NULL,'Home & Living','home-living','Home decor and living essentials','img/categories/1771412458_home-living.png',1,NULL,'2026-02-17 07:54:33','2026-02-18 04:00:58'),(13,NULL,'Furniture','furniture','Home and office furniture','img/categories/1771345759_furniture.png',1,NULL,'2026-02-17 07:54:33','2026-02-17 09:29:19'),(14,12,'Kitchen & Dining','kitchen-dining','Kitchen appliances and dining essentials','img/categories/1771412588_kitchen-dining.png',1,NULL,'2026-02-17 07:54:33','2026-02-18 06:34:05'),(15,12,'Home Decor','home-decor','Decorative items for your home','img/categories/1771412553_home-decor.png',1,NULL,'2026-02-17 07:54:33','2026-02-18 06:33:49'),(16,NULL,'Sports & Outdoors','sports-outdoors','Sports equipment and outdoor gear',NULL,1,NULL,'2026-02-17 07:54:33','2026-02-17 07:54:33'),(17,NULL,'Fitness & Gym','fitness-gym','Fitness equipment and gym accessories','img/categories/1771345614_fitness-gym.png',1,NULL,'2026-02-17 07:54:33','2026-02-17 09:26:54'),(18,NULL,'Outdoor Recreation','outdoor-recreation','Camping, hiking and outdoor activities',NULL,1,NULL,'2026-02-17 07:54:33','2026-02-17 07:54:33'),(19,NULL,'Beauty & Personal Care','beauty-personal-care','Beauty products and personal care items','img/categories/1771345297_beauty-personal-care.png',1,NULL,'2026-02-17 07:54:33','2026-02-17 09:21:37'),(20,19,'Skincare','skincare','Skincare products and treatments',NULL,1,NULL,'2026-02-17 07:54:33','2026-02-18 06:31:44'),(21,19,'Makeup & Cosmetics','makeup-cosmetics','Makeup and cosmetic products',NULL,1,NULL,'2026-02-17 07:54:33','2026-02-18 06:31:21'),(22,NULL,'Media','media','Books, magazines and media','img/categories/1771345505_media.png',1,NULL,'2026-02-17 07:54:33','2026-02-17 09:25:05'),(23,NULL,'Books','books','Physical and digital books','img/categories/1771345376_books.png',1,NULL,'2026-02-17 07:54:33','2026-02-17 09:22:56'),(24,NULL,'Toys & Games','toys-games','Toys, games and entertainment',NULL,1,NULL,'2026-02-17 07:54:33','2026-02-17 07:54:33'),(25,NULL,'Automotive','automotive','Car accessories and automotive parts','img/categories/1771345187_automotive.png',1,NULL,'2026-02-17 07:54:33','2026-02-17 09:19:47');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commission_settings`
--

DROP TABLE IF EXISTS `commission_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commission_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT '8.50',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commission_settings_vendor_id_unique` (`vendor_id`),
  CONSTRAINT `commission_settings_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commission_settings`
--

LOCK TABLES `commission_settings` WRITE;
/*!40000 ALTER TABLE `commission_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `commission_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commissions`
--

DROP TABLE IF EXISTS `commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commissions_order_id_foreign` (`order_id`),
  KEY `commissions_status_created_at_index` (`status`,`created_at`),
  KEY `commissions_vendor_id_index` (`vendor_id`),
  CONSTRAINT `commissions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commissions_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commissions`
--

LOCK TABLES `commissions` WRITE;
/*!40000 ALTER TABLE `commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_order` decimal(10,2) DEFAULT NULL,
  `max_usage` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `per_user_limit` int DEFAULT NULL,
  `starts_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`),
  KEY `coupons_code_index` (`code`),
  KEY `coupons_is_active_index` (`is_active`),
  KEY `coupons_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disputes`
--

DROP TABLE IF EXISTS `disputes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disputes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_response` text COLLATE utf8mb4_unicode_ci,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disputes_order_id_foreign` (`order_id`),
  KEY `disputes_user_id_foreign` (`user_id`),
  KEY `disputes_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `disputes_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `disputes_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `disputes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disputes`
--

LOCK TABLES `disputes` WRITE;
/*!40000 ALTER TABLE `disputes` DISABLE KEYS */;
/*!40000 ALTER TABLE `disputes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flash_sales`
--

DROP TABLE IF EXISTS `flash_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flash_sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `quantity_limit` int DEFAULT NULL,
  `quantity_sold` int NOT NULL DEFAULT '0',
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `flash_sales_product_id_starts_at_ends_at_unique` (`product_id`,`starts_at`,`ends_at`),
  KEY `flash_sales_product_id_index` (`product_id`),
  KEY `flash_sales_is_active_index` (`is_active`),
  KEY `flash_sales_starts_at_ends_at_index` (`starts_at`,`ends_at`),
  CONSTRAINT `flash_sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flash_sales`
--

LOCK TABLES `flash_sales` WRITE;
/*!40000 ALTER TABLE `flash_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `flash_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"78d96ada-31ce-4192-9042-c4b42d6c2fdd\",\"displayName\":\"App\\\\Events\\\\OrderPlaced\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:22:\\\"App\\\\Events\\\\OrderPlaced\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:6:\\\"amount\\\";d:32.989999999999995;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1771500958,\"delay\":null}',0,NULL,1771500959,1771500959),(2,'default','{\"uuid\":\"79c3473e-b77e-4a4c-801a-0d9c6811d26f\",\"displayName\":\"App\\\\Events\\\\OrderPlaced\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:22:\\\"App\\\\Events\\\\OrderPlaced\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:6:\\\"amount\\\";d:172.99;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1771500969,\"delay\":null}',0,NULL,1771500969,1771500969),(3,'default','{\"uuid\":\"bb1c2ac3-50a4-44aa-8ab2-16ad44e6469b\",\"displayName\":\"App\\\\Events\\\\OrderPlaced\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:22:\\\"App\\\\Events\\\\OrderPlaced\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:6:\\\"amount\\\";d:452.99;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1771500982,\"delay\":null}',0,NULL,1771500982,1771500982);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_15_222810_create_roles_table',1),(5,'2026_01_15_222903_create_permissions_table',1),(6,'2026_01_15_222932_create_role_permissions_table',1),(7,'2026_01_15_223000_add_fields_to_users_table',1),(8,'2026_01_15_223057_create_addresses_table',1),(9,'2026_01_15_223128_create_categories_table',1),(10,'2026_01_15_223152_create_products_table',1),(11,'2026_01_15_223219_create_orders_table',1),(12,'2026_01_15_223239_create_order_items_table',1),(13,'2026_01_15_223301_create_reviews_table',1),(14,'2026_01_15_223325_create_auth_logs_table',1),(15,'2026_01_15_223346_create_user_behavior_profiles_table',1),(16,'2026_01_15_223415_create_ai_feature_store_table',1),(17,'2026_01_15_232818_create_product_ratings_table',1),(18,'2026_01_16_231530_add_role_to_users_table',1),(19,'2026_01_20_130610_update_orders_table_and_create_histories_table',1),(20,'2026_01_20_185852_add_vendor_id_to_products_table',1),(21,'2026_01_22_063100_add_phone_to_users_table',1),(22,'2026_01_22_065120_update_users_table_cleanup',1),(23,'2026_01_22_070136_add_is_active_to_users_table',1),(24,'2026_01_23_195206_create_price_suggestions_table',1),(25,'2026_01_24_000000_create_audit_logs_table',1),(26,'2026_01_24_000001_create_risk_rules_table',1),(27,'2026_01_24_create_coupons_table',1),(28,'2026_01_24_create_flash_sales_table',1),(29,'2026_01_24_create_products_fulltext_index',1),(30,'2026_01_24_create_user_coupons_table',1),(31,'2026_01_27_163513_create_wishlists_table',1),(32,'2026_01_28_000000_add_fields_to_categories_table',1),(33,'2026_01_28_155740_add_columns_to_categories_table',1),(34,'2026_01_28_183434_add_confidence_to_price_suggestions_table',1),(35,'2026_01_28_185602_add_risk_level_to_risk_rules_table',1),(36,'2026_02_05_055531_add_google_id_to_users_table',1),(37,'2026_02_09_081457_add_slug_to_products_table',1),(38,'2026_02_12_135059_add_timestamps_to_auth_logs_table',1),(39,'2026_02_12_140507_create_disputes_table',1),(40,'2026_02_12_140509_create_refunds_table',1),(41,'2026_02_13_120308_create_commission_settings_table',1),(42,'2026_02_13_120310_create_commissions_table',1),(43,'2026_02_13_120313_create_vendor_payouts_table',1),(44,'2026_02_13_131550_create_support_tickets_table',1),(45,'2026_02_13_131550_create_ticket_messages_table',1),(46,'2026_02_13_133035_create_settings_table',1),(47,'2026_02_14_152752_add_date_of_birth_to_users_table',1),(48,'2026_02_15_133737_add_fields_to_addresses_table',1),(49,'2026_02_15_133740_create_payment_methods_table',1),(50,'2026_02_15_133743_add_notification_preferences_to_users_table',1),(51,'2026_02_17_132909_add_soft_deletes_to_products_table',1),(52,'2026_02_17_135139_add_product_id_and_rating_to_support_tickets_table',1),(53,'2026_02_17_142250_create_user_permissions_table',1),(54,'2026_02_18_112823_create_product_images_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_histories`
--

DROP TABLE IF EXISTS `order_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_histories_order_id_foreign` (`order_id`),
  KEY `order_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `order_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_histories`
--

LOCK TABLES `order_histories` WRITE;
/*!40000 ALTER TABLE `order_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,12,'Nike Dri-FIT Training T-Shirt',1,29.99,29.99,'2026-02-19 04:35:57','2026-02-19 04:35:57'),(2,2,28,'LEGO Star Wars Millennium Falcon',1,169.99,169.99,'2026-02-19 04:36:09','2026-02-19 04:36:09'),(3,3,21,'KitchenAid Stand Mixer 5-Quart',1,449.99,449.99,'2026-02-19 04:36:22','2026-02-19 04:36:22');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `order_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shipping_carrier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,14,'John','Smith','hihihihi13245768@gmail.com','0292144445','thanh,  ạvadcbzklzabn, áds, aa, Untied',NULL,'pending',NULL,NULL,'unpaid','cod',32.99,'2026-02-19 04:35:57','2026-02-19 04:35:57',NULL),(2,14,'John','Smith','hihihihi13245768@gmail.com','0292144445','thanh,  ạvadcbzklzabn, áds, aa, Untied',NULL,'pending',NULL,NULL,'unpaid','cod',172.99,'2026-02-19 04:36:09','2026-02-19 04:36:09',NULL),(3,14,'John','Smith','hihihihi13245768@gmail.com','0292144445','thanh,  ạvadcbzklzabn, áds, aa, Untied',NULL,'pending',NULL,NULL,'unpaid','cod',452.99,'2026-02-19 04:36:22','2026-02-19 04:36:22',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `cardholder_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visa',
  `last_four` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_month` tinyint unsigned NOT NULL,
  `expiry_year` smallint unsigned NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_methods_user_id_foreign` (`user_id`),
  CONSTRAINT `payment_methods_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_methods`
--

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'user.view','View users',NULL,NULL),(2,'user.create','Create user',NULL,NULL),(3,'user.update','Update user',NULL,NULL),(4,'user.delete','Delete user',NULL,NULL),(5,'staff.view','View staff members',NULL,NULL),(6,'staff.create','Create staff accounts',NULL,NULL),(7,'staff.update','Update staff accounts',NULL,NULL),(8,'staff.delete','Delete staff accounts',NULL,NULL),(9,'staff.permissions','Manage staff permissions',NULL,NULL),(10,'staff.activate','Activate/Deactivate staff',NULL,NULL),(11,'product.view','View products',NULL,NULL),(12,'product.create','Create product',NULL,NULL),(13,'product.update','Update product',NULL,NULL),(14,'product.delete','Delete product',NULL,NULL),(15,'category.view','View categories',NULL,NULL),(16,'category.create','Create categories',NULL,NULL),(17,'category.update','Update categories',NULL,NULL),(18,'category.delete','Delete categories',NULL,NULL),(19,'order.view','View orders',NULL,NULL),(20,'order.update','Update order status',NULL,NULL),(21,'order.cancel','Cancel orders',NULL,NULL),(22,'vendor.view','View vendors',NULL,NULL),(23,'vendor.manage','Manage vendors',NULL,NULL),(24,'finance.view','View finance reports',NULL,NULL),(25,'finance.manage','Manage finance settings',NULL,NULL),(26,'support.view','View support tickets',NULL,NULL),(27,'support.reply','Reply to tickets',NULL,NULL),(28,'dispute.view','View disputes',NULL,NULL),(29,'dispute.resolve','Resolve disputes',NULL,NULL),(30,'report.view','View reports',NULL,NULL),(31,'report.export','Export reports',NULL,NULL),(32,'settings.view','View settings',NULL,NULL),(33,'settings.update','Update settings',NULL,NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `price_suggestions`
--

DROP TABLE IF EXISTS `price_suggestions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `price_suggestions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `new_price` decimal(10,2) NOT NULL,
  `confidence` decimal(3,2) DEFAULT '0.50',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_suggestions_product_id_foreign` (`product_id`),
  CONSTRAINT `price_suggestions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `price_suggestions`
--

LOCK TABLES `price_suggestions` WRITE;
/*!40000 ALTER TABLE `price_suggestions` DISABLE KEYS */;
/*!40000 ALTER TABLE `price_suggestions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'img/products/gallery/1771414452_6995a3b4e94aa_iphone-15-pro-max-blue-1-1-750x500.jpg','2026-02-18 04:34:12','2026-02-18 04:34:12'),(2,1,'img/products/gallery/1771414452_6995a3b4eaad7_iphone-15-pro-max-blue-3-1-750x500.jpg','2026-02-18 04:34:12','2026-02-18 04:34:12'),(3,1,'img/products/gallery/1771414452_6995a3b4eb657_iphone-15-pro-max-blue-4-1-750x500.jpg','2026-02-18 04:34:12','2026-02-18 04:34:12'),(4,2,'img/products/gallery/1771416024_6995a9d8b8c71_samsung-galaxy-s24-ultra-xam-4-750x500.jpg','2026-02-18 05:00:24','2026-02-18 05:00:24'),(5,2,'img/products/gallery/1771416024_6995a9d8b9ad4_samsung-galaxy-s24-ultra-xam-9-750x500.jpg','2026-02-18 05:00:24','2026-02-18 05:00:24'),(6,2,'img/products/gallery/1771416024_6995a9d8ba575_samsung-galaxy-s24-ultra-xam-12-750x500.jpg','2026-02-18 05:00:24','2026-02-18 05:00:24'),(10,3,'img/products/gallery/1771416448_6995ab80445c1_812UO7Ja2nL._AC_SX522_.jpg','2026-02-18 05:07:28','2026-02-18 05:07:28'),(11,3,'img/products/gallery/1771417121_6995ae21bbbe1_714-7INRdwL._AC_SL1500_.jpg','2026-02-18 05:18:41','2026-02-18 05:18:41'),(12,3,'img/products/gallery/1771417185_6995ae617297c_71XYcPrgeQL._AC_SL1500_.jpg','2026-02-18 05:19:45','2026-02-18 05:19:45'),(13,3,'img/products/gallery/1771417185_6995ae61754fc_81+su75aXjL._AC_SL1500_.jpg','2026-02-18 05:19:45','2026-02-18 05:19:45'),(14,3,'img/products/gallery/1771417195_6995ae6b706db_71Qo-D9kahL._AC_SL1500_.jpg','2026-02-18 05:19:55','2026-02-18 05:19:55'),(15,4,'img/products/gallery/1771417342_6995aefe2db4f_61HhoQIcvCL._AC_SL1200_.jpg','2026-02-18 05:22:22','2026-02-18 05:22:22'),(16,4,'img/products/gallery/1771417342_6995aefe2eda0_61YQrK9b8rL._AC_SL1200_.jpg','2026-02-18 05:22:22','2026-02-18 05:22:22'),(18,4,'img/products/gallery/1771417342_6995aefe33c0e_31PaVBycS-L._AC_SL1200_ - Copy.jpg','2026-02-18 05:22:22','2026-02-18 05:22:22'),(19,4,'img/products/gallery/1771417342_6995aefe34666_51DjnlZ33DL._AC_SL1200_ - Copy.jpg','2026-02-18 05:22:22','2026-02-18 05:22:22'),(20,4,'img/products/gallery/1771417342_6995aefe351ca_51fBvAm5Y+L._AC_SL1200_ - Copy.jpg','2026-02-18 05:22:22','2026-02-18 05:22:22'),(21,4,'img/products/gallery/1771417342_6995aefe35d03_51-GoqtT7bL._AC_SL1200_ - Copy.jpg','2026-02-18 05:22:22','2026-02-18 05:22:22'),(22,4,'img/products/gallery/1771417342_6995aefe36695_61bidH4+hjL._AC_SL1200_ - Copy.jpg','2026-02-18 05:22:22','2026-02-18 05:22:22'),(23,5,'img/products/gallery/1771417500_6995af9ce7957_51-vYBOyRYL._AC_SL1229_.jpg','2026-02-18 05:25:00','2026-02-18 05:25:00'),(24,5,'img/products/gallery/1771417500_6995af9ce8f7e_61+UI1-skyL._AC_SL1008_.jpg','2026-02-18 05:25:00','2026-02-18 05:25:00'),(25,5,'img/products/gallery/1771417500_6995af9ce9e7c_61VgYuBcICL._AC_SL1121_.jpg','2026-02-18 05:25:00','2026-02-18 05:25:00'),(26,6,'img/products/gallery/1771417709_6995b06de6184_71gTJNxmBAL._AC_SL1500_.jpg','2026-02-18 05:28:29','2026-02-18 05:28:29'),(27,6,'img/products/gallery/1771417709_6995b06de712e_71N7r+FQEiL._AC_SL1500_.jpg','2026-02-18 05:28:29','2026-02-18 05:28:29'),(28,6,'img/products/gallery/1771417709_6995b06de7f51_81qlWx19nYL._AC_SL1500_.jpg','2026-02-18 05:28:29','2026-02-18 05:28:29'),(29,7,'img/products/gallery/1771417817_6995b0d93a5c7_51pFYV7FHdL._AC_SL1200_.jpg','2026-02-18 05:30:17','2026-02-18 05:30:17'),(30,7,'img/products/gallery/1771417817_6995b0d93b65b_61Gdpfwb4VL._AC_SL1500_.jpg','2026-02-18 05:30:17','2026-02-18 05:30:17'),(31,7,'img/products/gallery/1771417817_6995b0d93bee4_71GMFf-4cwL._AC_SL1500_.jpg','2026-02-18 05:30:17','2026-02-18 05:30:17'),(32,8,'img/products/gallery/1771417919_6995b13f4330d_31JhG9beSPL._AC_SL1200_.jpg','2026-02-18 05:31:59','2026-02-18 05:31:59'),(33,8,'img/products/gallery/1771417919_6995b13f44418_41bBMMwTXlL._AC_SL1200_.jpg','2026-02-18 05:31:59','2026-02-18 05:31:59'),(34,8,'img/products/gallery/1771417919_6995b13f452e4_518qvKaJeTL._AC_SL1200_.jpg','2026-02-18 05:31:59','2026-02-18 05:31:59'),(35,9,'img/products/gallery/1771417997_6995b18da74bc_51BhMm-uUnL._AC_SL1500_.jpg','2026-02-18 05:33:17','2026-02-18 05:33:17'),(36,9,'img/products/gallery/1771417997_6995b18da8850_51Bvor+ZF0L._AC_SL1500_.jpg','2026-02-18 05:33:17','2026-02-18 05:33:17'),(37,9,'img/products/gallery/1771417997_6995b18da98fb_51Y-9iLkCCL._AC_SL1500_.jpg','2026-02-18 05:33:17','2026-02-18 05:33:17'),(38,10,'img/products/gallery/1771418108_6995b1fcc8e14_71kHYhqWyHL._AC_SL1500_.jpg','2026-02-18 05:35:08','2026-02-18 05:35:08'),(39,10,'img/products/gallery/1771418108_6995b1fcca555_71-zKY-dTXL._AC_SL1500_.jpg','2026-02-18 05:35:08','2026-02-18 05:35:08'),(40,11,'img/products/gallery/1771418171_6995b23b72552_81Bc-pZhbtL._AC_SL1500_.jpg','2026-02-18 05:36:11','2026-02-18 05:36:11'),(41,11,'img/products/gallery/1771418171_6995b23b733c3_81snx8RBjxL._AC_SL1500_.jpg','2026-02-18 05:36:11','2026-02-18 05:36:11'),(42,12,'img/products/gallery/1771418301_6995b2bd0701b_515y1u+j0HL._AC_SY550_.jpg','2026-02-18 05:38:21','2026-02-18 05:38:21'),(43,13,'img/products/gallery/1771418428_6995b33c757e4_71WDxWAx-sL._AC_SX569_.jpg','2026-02-18 05:40:28','2026-02-18 05:40:28'),(44,13,'img/products/gallery/1771418428_6995b33c76648_91Mf37jbSvL._AC_SY550_.jpg','2026-02-18 05:40:28','2026-02-18 05:40:28'),(45,14,'img/products/gallery/1771418526_6995b39e74f31_71kwMlYtocL._AC_SY550_.jpg','2026-02-18 05:42:06','2026-02-18 05:42:06'),(46,14,'img/products/gallery/1771418526_6995b39e75e6b_71ZUZxpDDLL._AC_SY550_.jpg','2026-02-18 05:42:06','2026-02-18 05:42:06'),(47,15,'img/products/gallery/1771418637_6995b40d31424_71vyAC+kJdL._AC_SX522_.jpg','2026-02-18 05:43:57','2026-02-18 05:43:57'),(48,15,'img/products/gallery/1771418637_6995b40d3290a_81pk9FXbwWL._AC_SX522_.jpg','2026-02-18 05:43:57','2026-02-18 05:43:57'),(49,16,'img/products/gallery/1771418749_6995b47da66fa_61+P163hJ0L._AC_SY575_.jpg','2026-02-18 05:45:49','2026-02-18 05:45:49'),(50,16,'img/products/gallery/1771418749_6995b47da7744_61kdpd0ZDgL._AC_SY575_.jpg','2026-02-18 05:45:49','2026-02-18 05:45:49'),(51,16,'img/products/gallery/1771418749_6995b47da81ae_61PlbQT2fGL._AC_SY575_.jpg','2026-02-18 05:45:49','2026-02-18 05:45:49'),(52,17,'img/products/gallery/1771418842_6995b4dab6bfd_61BiugCbFeL._AC_SY575_.jpg','2026-02-18 05:47:22','2026-02-18 05:47:22'),(53,17,'img/products/gallery/1771418842_6995b4dab7b60_61cEj7S8jCL._AC_SY575_.jpg','2026-02-18 05:47:22','2026-02-18 05:47:22'),(54,17,'img/products/gallery/1771418842_6995b4dab8671_71DDN+0b+5L._AC_SX695_.jpg','2026-02-18 05:47:22','2026-02-18 05:47:22'),(55,18,'img/products/gallery/1771418975_6995b55fc405d_61ou2eo1RFL._AC_SL1130_.jpg','2026-02-18 05:49:35','2026-02-18 05:49:35'),(56,18,'img/products/gallery/1771418975_6995b55fc5274_71mVxUSZ-0L._AC_SL1417_.jpg','2026-02-18 05:49:35','2026-02-18 05:49:35'),(57,19,'img/products/gallery/1771419068_6995b5bcdb090_61wbYe-SSIL._AC_SL1500_.jpg','2026-02-18 05:51:08','2026-02-18 05:51:08'),(58,19,'img/products/gallery/1771419068_6995b5bcdc822_81gFkebMWDL._AC_SL1500_.jpg','2026-02-18 05:51:08','2026-02-18 05:51:08'),(59,19,'img/products/gallery/1771419068_6995b5bcdd907_91B5ioTFkHL._AC_SL1500_.jpg','2026-02-18 05:51:08','2026-02-18 05:51:08'),(60,20,'img/products/gallery/1771419230_6995b65e654ca_61OVuiI0HUL._AC_SL1152_.jpg','2026-02-18 05:53:50','2026-02-18 05:53:50'),(61,20,'img/products/gallery/1771419230_6995b65e66313_71awLZVQfRL._AC_SL1152_.jpg','2026-02-18 05:53:50','2026-02-18 05:53:50'),(62,20,'img/products/gallery/1771419230_6995b65e66eea_615ou9k0MdL._AC_SL1152_.jpg','2026-02-18 05:53:50','2026-02-18 05:53:50'),(63,21,'img/products/gallery/1771419305_6995b6a9f38cc_71Tp+EgXZAL._AC_SL1500_.jpg','2026-02-18 05:55:06','2026-02-18 05:55:06'),(64,21,'img/products/gallery/1771419306_6995b6aa00e82_813e5B4I9dL._AC_SL1500_.jpg','2026-02-18 05:55:06','2026-02-18 05:55:06'),(65,28,'img/products/gallery/1771419453_6995b73d7a37b_71lVMk4XJhL._AC_SL1500_.jpg','2026-02-18 05:57:33','2026-02-18 05:57:33'),(66,28,'img/products/gallery/1771419453_6995b73d7b9f5_912mQT+7tML._AC_SL1500_.jpg','2026-02-18 05:57:33','2026-02-18 05:57:33'),(67,29,'img/products/gallery/1771419512_6995b7785bf7e_61Y0y8yWXqS._AC_SL1200_.jpg','2026-02-18 05:58:32','2026-02-18 05:58:32'),(68,29,'img/products/gallery/1771419512_6995b7785da2f_61zuMRz61sL._AC_SL1200_.jpg','2026-02-18 05:58:32','2026-02-18 05:58:32');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_ratings`
--

DROP TABLE IF EXISTS `product_ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `rating` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_ratings_product_id_user_id_unique` (`product_id`,`user_id`),
  KEY `product_ratings_user_id_foreign` (`user_id`),
  CONSTRAINT `product_ratings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_ratings`
--

LOCK TABLES `product_ratings` WRITE;
/*!40000 ALTER TABLE `product_ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_vendor_id_foreign` (`vendor_id`),
  FULLTEXT KEY `products_name_description_fulltext` (`name`,`description`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,NULL,3,'iPhone 15 Pro Max 256GB','iphone-15-pro-max-256gb','IPH15PM-256',1199.00,1099.00,50,'img/products/1771414452_iphone-15-pro-max-titan-xanh-2-638629415445427350-750x500.jpg',1,1,'Latest iPhone with A17 Pro chip, titanium design, and advanced camera system. Features 6.7-inch Super Retina XDR display.','2026-02-17 07:55:54','2026-02-18 04:34:12',NULL),(2,NULL,3,'Samsung Galaxy S24 Ultra 512GB','samsung-galaxy-s24-ultra-512gb','SGS24U-512',1299.00,NULL,35,'img/products/1771416024_samsung-galaxy-s24-ultra-xam-1-750x500.jpg',1,1,'Premium Android flagship with S Pen, 200MP camera, and AI features. 6.8-inch Dynamic AMOLED display.','2026-02-17 07:55:54','2026-02-18 05:00:24',NULL),(3,NULL,3,'iPad Pro 12.9\" M2 256GB','ipad-pro-129-m2-256gb','IPADPRO-M2-256',1099.00,999.00,25,'img/products/1771416282_619G-Sa2NSL._AC_SX522_.jpg',0,1,'Powerful tablet with M2 chip, Liquid Retina XDR display, and Apple Pencil support.','2026-02-17 07:55:54','2026-02-18 05:04:42',NULL),(4,NULL,4,'MacBook Pro 16\" M3 Max 1TB','macbook-pro-16-m3-max-1tb','MBP16-M3MAX-1TB',3499.00,NULL,15,'img/products/1771417342_41H8TjeAcwL._AC_SL1200_.jpg',1,1,'Professional laptop with M3 Max chip, 16-inch Liquid Retina XDR display, up to 22 hours battery life.','2026-02-17 07:55:54','2026-02-18 05:22:22',NULL),(5,NULL,4,'Dell XPS 15 Intel i9 32GB RAM','dell-xps-15-intel-i9-32gb-ram','DELLXPS15-I9',2299.00,1999.00,20,'img/products/1771417500_61Ks9X44eVL._AC_SL1181_.jpg',0,0,'Premium Windows laptop with 15.6\" 4K OLED display, NVIDIA RTX 4060, perfect for creators.','2026-02-17 07:55:54','2026-02-18 05:25:00',NULL),(6,NULL,4,'ASUS ROG Zephyrus G16 Gaming Laptop','asus-rog-zephyrus-g16-gaming-laptop','ASUS-ROG-G16',2499.00,NULL,12,'img/products/1771417709_81n1T4CYfmL._AC_SL1500_.jpg',1,0,'High-performance gaming laptop with RTX 4080, Intel Core i9, 240Hz display.','2026-02-17 07:55:54','2026-02-18 05:28:29',NULL),(7,NULL,5,'Sony WH-1000XM5 Wireless Headphones','sony-wh-1000xm5-wireless-headphones','SONY-WH1000XM5',399.00,349.00,60,'img/products/1771417817_61BGLYEN-xL._AC_SL1500_.jpg',0,1,'Industry-leading noise cancellation, exceptional sound quality, 30-hour battery life.','2026-02-17 07:55:54','2026-02-18 05:30:17',NULL),(8,NULL,5,'AirPods Pro 2nd Generation with MagSafe','airpods-pro-2nd-generation-with-magsafe','AIRPODS-PRO2',249.00,NULL,100,'img/products/1771417919_41QztOA1UuL._AC_SL1200_.jpg',0,1,'Premium wireless earbuds with active noise cancellation, spatial audio, and adaptive transparency.','2026-02-17 07:55:54','2026-02-18 05:31:59',NULL),(9,NULL,5,'Bose QuietComfort Ultra Earbuds','bose-quietcomfort-ultra-earbuds','BOSE-QCUE',299.00,279.00,45,'img/products/1771417997_51HYcr7W1QL._AC_SL1500_.jpg',1,0,'World-class noise cancellation, immersive audio, comfortable all-day wear.','2026-02-17 07:55:54','2026-02-18 05:33:17',NULL),(10,NULL,6,'Canon EOS R5 Mirrorless Camera Body','canon-eos-r5-mirrorless-camera-body','CANON-R5-BODY',3899.00,NULL,8,'img/products/1771418108_71hpUUcC5uL._AC_SL1500_.jpg',0,1,'Professional full-frame mirrorless camera with 45MP sensor, 8K video recording.','2026-02-17 07:55:54','2026-02-18 05:35:08',NULL),(11,NULL,6,'Sony A7 IV Full Frame Camera','sony-a7-iv-full-frame-camera','SONY-A7IV',2499.00,2299.00,10,'img/products/1771418171_61kkhm9rT4L._AC_SL1200_.jpg',0,0,'Versatile hybrid camera with 33MP sensor, 4K 60p video, advanced autofocus.','2026-02-17 07:55:54','2026-02-18 05:36:11',NULL),(12,NULL,8,'Nike Dri-FIT Training T-Shirt','nike-dri-fit-training-t-shirt','NIKE-DFIT-TEE-M',29.99,24.99,199,'img/products/1771418301_51VCdmBI8XL._AC_SX679_.jpg',0,0,'Moisture-wicking performance t-shirt, lightweight and breathable fabric.','2026-02-17 07:55:54','2026-02-19 04:35:56',NULL),(13,NULL,8,'Levi\'s 501 Original Fit Jeans','levis-501-original-fit-jeans','LEVIS-501-BLUE',89.99,NULL,150,'img/products/1771418428_51sY1YmXtRL._AC_SX569_.jpg',0,0,'Classic straight fit jeans, 100% cotton denim, timeless style.','2026-02-17 07:55:54','2026-02-18 05:40:28',NULL),(14,NULL,9,'Zara Floral Print Midi Dress','zara-floral-print-midi-dress','ZARA-FLORAL-MIDI',79.99,59.99,80,'img/products/1771418526_71SwF9fkS1L._AC_SY550_.jpg',1,0,'Elegant midi dress with floral print, perfect for summer occasions.','2026-02-17 07:55:54','2026-02-18 05:42:06',NULL),(15,NULL,9,'H&M Oversized Knit Sweater','hm-oversized-knit-sweater','HM-KNIT-SWEATER',49.99,NULL,120,'img/products/1771418637_719WKNqhA3L._AC_SX522_.jpg',0,0,'Cozy oversized sweater, soft knit fabric, versatile styling.','2026-02-17 07:55:54','2026-02-18 05:43:57',NULL),(16,NULL,10,'Nike Air Max 270 Sneakers','nike-air-max-270-sneakers','NIKE-AM270-BLK',159.99,139.99,90,'img/products/1771418749_716F1sqKwnL._AC_SY500_.jpg',0,1,'Iconic sneakers with Max Air unit, comfortable all-day wear.','2026-02-17 07:55:54','2026-02-18 05:45:49',NULL),(17,NULL,10,'Adidas Ultraboost 23 Running Shoes','adidas-ultraboost-23-running-shoes','ADIDAS-UB23',189.99,NULL,75,'img/products/1771418842_51iDY0KxMNL._AC_SY575_.jpg',1,1,'Premium running shoes with Boost cushioning, responsive energy return.','2026-02-17 07:55:54','2026-02-18 05:47:22',NULL),(18,NULL,13,'Vepping Lude Multi Colored Armchair Replacement Cover, Fits IKEA Poäng Armchair, Cushion not Included (Cushion Design 3, Cotton - White)','ikea-poang-armchair-with-cushion','IKEA-POANG-ARM',129.00,99.00,40,'img/products/1771418975_61fz6UrwqnL._AC_SL1172_.jpg',0,0,'Comfortable armchair with bentwood frame, removable cushion cover.','2026-02-17 07:55:54','2026-02-18 05:49:35',NULL),(19,NULL,13,'ZeeFu Convertible Sectional Sofa Couch,Classic 3 Seat L-Shaped Sofa with Movable Ottoman, Modern Dark Grey Velvet Fabric Upholstered Small Sectional Sofa Couch for Small Apartment Living Room Office','modern-l-shaped-sectional-sofa','SOFA-LSHAPE-GRY',899.00,NULL,15,'img/products/1771419068_81cRkQ1MURL._AC_SL1500_.jpg',1,1,'Spacious sectional sofa with premium fabric upholstery, perfect for living rooms.','2026-02-17 07:55:54','2026-02-18 05:51:08',NULL),(20,NULL,14,'Ninja Air Fryer Max XL 5.5 Qt','ninja-air-fryer-max-xl-55-qt','NINJA-AF-XL',129.99,99.99,55,'img/products/1771419230_61YhIy9LUvL._AC_SL1500_.jpg',0,1,'Large capacity air fryer with 7 cooking functions, easy to clean.','2026-02-17 07:55:54','2026-02-18 05:53:50',NULL),(21,NULL,14,'KitchenAid Stand Mixer 5-Quart','kitchenaid-stand-mixer-5-quart','KA-MIXER-5QT',449.99,NULL,29,'img/products/1771419305_71dwD1MdoSL._AC_SL1500_.jpg',0,0,'Professional stand mixer with 10 speeds, includes multiple attachments.','2026-02-17 07:55:54','2026-02-19 04:36:22',NULL),(22,NULL,17,'Bowflex Adjustable Dumbbells 52.5 lbs','bowflex-adjustable-dumbbells-525-lbs','BOWFLEX-DB-52',349.00,299.00,25,'https://via.placeholder.com/400x400/1a1a1a/ffffff?text=Dumbbells',0,1,'Space-saving adjustable dumbbells, replaces 15 sets of weights.','2026-02-17 07:55:54','2026-02-18 05:55:39','2026-02-18 05:55:39'),(23,NULL,17,'Peloton Bike+ Indoor Cycling','peloton-bike-indoor-cycling','PELOTON-BIKE-PLUS',2495.00,NULL,10,'https://via.placeholder.com/400x400/000000/ffffff?text=Peloton+Bike',1,1,'Premium indoor bike with rotating screen, live and on-demand classes.','2026-02-17 07:55:54','2026-02-18 05:55:45','2026-02-18 05:55:45'),(24,NULL,20,'CeraVe Hydrating Facial Cleanser','cerave-hydrating-facial-cleanser','CERAVE-CLEANSER',14.99,12.99,200,'https://via.placeholder.com/400x400/4ade80/ffffff?text=Cleanser',0,0,'Gentle hydrating cleanser with ceramides and hyaluronic acid.','2026-02-17 07:55:54','2026-02-18 05:55:49','2026-02-18 05:55:49'),(25,NULL,21,'Fenty Beauty Pro Filt\'r Foundation','fenty-beauty-pro-filtr-foundation','FENTY-FOUNDATION',39.00,NULL,150,'https://via.placeholder.com/400x400/000000/ffffff?text=Foundation',0,1,'Soft matte longwear foundation, 50 shades for all skin tones.','2026-02-17 07:55:54','2026-02-18 05:55:54','2026-02-18 05:55:54'),(26,NULL,23,'Atomic Habits by James Clear','atomic-habits-by-james-clear','BOOK-ATOMIC-HABITS',27.00,19.99,100,'https://via.placeholder.com/400x400/1e40af/ffffff?text=Atomic+Habits',0,1,'Bestselling book on building good habits and breaking bad ones.','2026-02-17 07:55:54','2026-02-18 05:55:58','2026-02-18 05:55:58'),(27,NULL,23,'The Psychology of Money by Morgan Housel','the-psychology-of-money-by-morgan-housel','BOOK-PSY-MONEY',24.00,NULL,80,'img/products/1771419399_81gC3mdNi5L._SL1500_.jpg',0,0,'Timeless lessons on wealth, greed, and happiness.','2026-02-17 07:55:54','2026-02-18 05:56:39',NULL),(28,NULL,24,'LEGO Star Wars Millennium Falcon','lego-star-wars-millennium-falcon','LEGO-SW-MF',169.99,149.99,34,'img/products/1771419453_81PhO-kyPuL._AC_SL1500_.jpg',0,1,'Iconic LEGO set with 1,351 pieces, includes minifigures.','2026-02-17 07:55:54','2026-02-19 04:36:09',NULL),(29,NULL,25,'Garmin Dash Cam 67W','garmin-dash-cam-67w','GARMIN-DASH-67W',249.99,NULL,40,'img/products/1771419512_614VoTbsIBL._AC_SL1200_.jpg',1,0,'Ultra-wide 180° field of view, voice control, GPS tracking.','2026-02-17 07:55:54','2026-02-18 05:58:32',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refunds`
--

DROP TABLE IF EXISTS `refunds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refunds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `dispute_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `processed_by` bigint unsigned DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `refunds_order_id_foreign` (`order_id`),
  KEY `refunds_dispute_id_foreign` (`dispute_id`),
  KEY `refunds_processed_by_foreign` (`processed_by`),
  CONSTRAINT `refunds_dispute_id_foreign` FOREIGN KEY (`dispute_id`) REFERENCES `disputes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `refunds_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `refunds_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refunds`
--

LOCK TABLES `refunds` WRITE;
/*!40000 ALTER TABLE `refunds` DISABLE KEYS */;
/*!40000 ALTER TABLE `refunds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `risk_rules`
--

DROP TABLE IF EXISTS `risk_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `risk_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rule_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `risk_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `settings` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `risk_rules_rule_key_unique` (`rule_key`),
  KEY `risk_rules_rule_key_index` (`rule_key`),
  KEY `risk_rules_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risk_rules`
--

LOCK TABLES `risk_rules` WRITE;
/*!40000 ALTER TABLE `risk_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `risk_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(2,11),(3,11),(1,12),(2,12),(1,13),(2,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(2,19),(3,19),(1,20),(2,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(2,30),(1,31),(1,32),(1,33);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','System Administrator',NULL,NULL),(2,'Staff','Staff / Operator',NULL,NULL),(3,'Customer','End User / Buyer',NULL,NULL),(4,'Vendor','Shop Owner / Seller',NULL,NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('qT3wjSWzl3IK2rUSJzPYYdWc6p9jYoQYfz7KZuyM',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWDRkaTlPWlhwcnhPa0FaZjEzc2ZJbTFQNzVib0VpQTBUNmdvYmdFaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHBzOi8vZS1jb21tZXJjZS5hcHAvc3RhZmYiO3M6NToicm91dGUiO3M6MTU6InN0YWZmLmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==',1771503173),('Tz3ubWWmflFjVh05d5aFsDLedNPbogqBTLrg1M4l',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmJMTHRDYnlyeU5TV0N2TWgydmE0cnZCaEpZWjd2R2RVenZjWEg3aCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vZS1jb21tZXJjZS5hcHAvc2hvcD9tYXhfcHJpY2U9Mzg5OSZtaW5fcHJpY2U9MjQiO3M6NToicm91dGUiO3M6MTA6InNob3AuaW5kZXgiO319',1771770216),('uCYTZb3NdC5gPsHknxra1E4MZvgPqF6ZFlv6bbny',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVUpDaTdLUFBOc1FRb0g2aUNjMGhVMkhCMEFZNkc1cmUzUUYwZkNHUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHBzOi8vZS1jb21tZXJjZS5hcHAvdmVuZG9yL3Byb2R1Y3RzL2NyZWF0ZSI7czo1OiJyb3V0ZSI7czoyMjoidmVuZG9yLnByb2R1Y3RzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==',1771595523);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `order_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `satisfaction_rating` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `support_tickets_user_id_foreign` (`user_id`),
  KEY `support_tickets_order_id_foreign` (`order_id`),
  KEY `support_tickets_assigned_to_foreign` (`assigned_to`),
  KEY `support_tickets_product_id_foreign` (`product_id`),
  CONSTRAINT `support_tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `support_tickets_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `support_tickets_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_tickets`
--

LOCK TABLES `support_tickets` WRITE;
/*!40000 ALTER TABLE `support_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_messages`
--

DROP TABLE IF EXISTS `ticket_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_messages_ticket_id_foreign` (`ticket_id`),
  KEY `ticket_messages_user_id_foreign` (`user_id`),
  CONSTRAINT `ticket_messages_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_messages`
--

LOCK TABLES `ticket_messages` WRITE;
/*!40000 ALTER TABLE `ticket_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_behavior_profiles`
--

DROP TABLE IF EXISTS `user_behavior_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_behavior_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `frequent_ips` json DEFAULT NULL,
  `frequent_locations` json DEFAULT NULL,
  `trusted_devices` json DEFAULT NULL,
  `avg_login_time_start` time DEFAULT NULL,
  `avg_login_time_end` time DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_behavior_profiles_user_id_unique` (`user_id`),
  CONSTRAINT `user_behavior_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_behavior_profiles`
--

LOCK TABLES `user_behavior_profiles` WRITE;
/*!40000 ALTER TABLE `user_behavior_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_behavior_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_coupons`
--

DROP TABLE IF EXISTS `user_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `coupon_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_coupons_user_id_coupon_id_used_at_unique` (`user_id`,`coupon_id`,`used_at`),
  KEY `user_coupons_coupon_id_index` (`coupon_id`),
  KEY `user_coupons_order_id_index` (`order_id`),
  CONSTRAINT `user_coupons_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_coupons_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `user_coupons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_coupons`
--

LOCK TABLES `user_coupons` WRITE;
/*!40000 ALTER TABLE `user_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_permissions` (
  `user_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `granted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `granted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`,`permission_id`),
  KEY `user_permissions_granted_by_foreign` (`granted_by`),
  KEY `user_permissions_user_id_index` (`user_id`),
  KEY `user_permissions_permission_id_index` (`permission_id`),
  CONSTRAINT `user_permissions_granted_by_foreign` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `user_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permissions`
--

LOCK TABLES `user_permissions` WRITE;
/*!40000 ALTER TABLE `user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_preferences` json DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','locked','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_google_id_unique` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Super Admin','admin@ecommerce.local',NULL,NULL,NULL,'2026-02-17 07:23:45','$2y$12$PGhsDT/0/WLMB2nIgXchNOOp66VDpHxiyg9zIUA4Kvm1mWcbNBfra',NULL,1,NULL,'2026-02-17 07:23:45','2026-02-17 07:23:45',NULL,'active'),(2,1,'Admin Demo','admin@demo.com',NULL,NULL,NULL,'2026-02-17 07:23:45','$2y$12$cnHmNeU1T005SNATTldJ9.b5uLP/aOoCK6/mY6s4CEdwdGfSgeDqG',NULL,1,NULL,'2026-02-17 07:23:45','2026-02-17 07:23:45','+1-555-0101','active'),(3,2,'Staff Demo','staff@demo.com',NULL,NULL,NULL,'2026-02-17 07:23:45','$2y$12$BGPtf.FdpllASVPaVn5ULOziYXNA.TFqOZl.ST1k4g5vQIXz32WhW',NULL,1,NULL,'2026-02-17 07:23:45','2026-02-18 06:39:45','+1-555-0102','active'),(4,3,'Customer Demo','customer@demo.com',NULL,NULL,NULL,'2026-02-17 07:23:45','$2y$12$Pm8qNPTAyYX4Amkv6XLBsO1/eIVpWRma0BMPVjEIuUwCBy9.HKLSS',NULL,1,NULL,'2026-02-17 07:23:45','2026-02-17 07:23:45','+1-555-0103','active'),(5,4,'Vendor Demo','vendor@demo.com',NULL,NULL,NULL,'2026-02-17 07:23:45','$2y$12$6.5u5mIn7CPF3Gm9of1xa.yvdbnvGEpyMDiaQJABldd9cs9FZj6TW',NULL,1,NULL,'2026-02-17 07:23:46','2026-02-17 07:23:46','+1-555-0104','active'),(6,3,'John Smith','john@example.com',NULL,NULL,NULL,'2026-02-17 07:23:46','$2y$12$D.HTt3AsP4PjYbxuz8JFL.mOGuW1aglYt7D2Ps2M2pxcTgcAOAy7m',NULL,1,NULL,'2026-02-17 07:23:46','2026-02-17 07:23:46','+1-555-0201','active'),(7,3,'Sarah Johnson','sarah@example.com',NULL,NULL,NULL,'2026-02-17 07:23:46','$2y$12$h3CyV1HzjC29dWSiNVFN3uwQjj55URylMGllQt0OYAdeiAlZ/CsN2',NULL,1,NULL,'2026-02-17 07:23:46','2026-02-17 07:23:46','+1-555-0202','active'),(8,3,'Mike Wilson','mike@example.com',NULL,NULL,NULL,'2026-02-17 07:23:46','$2y$12$o.h5VKb/hm4Icuy8PXIVHe/ZYV2DJ2h.0FQXBHNNXZB.u4sJUUyHG',NULL,1,NULL,'2026-02-17 07:23:46','2026-02-17 07:23:46','+1-555-0203','active'),(9,3,'Emily Davis','emily@example.com',NULL,NULL,NULL,'2026-02-17 07:23:46','$2y$12$qIRGv1oG5Dr3j/gIpI/k3elmAg9ZNUualOMXFYTd.Gyp6D8wwyNSK',NULL,1,NULL,'2026-02-17 07:23:46','2026-02-17 07:23:46','+1-555-0204','active'),(10,3,'David Brown','david@example.com',NULL,NULL,NULL,'2026-02-17 07:23:46','$2y$12$gVgQGaKH.ta6ndqYEdaQreaDzSWTRGWowJwTpa9j.eC6kCRGHjope',NULL,1,NULL,'2026-02-17 07:23:47','2026-02-17 07:23:47','+1-555-0205','active'),(11,4,'TechStore Pro','techstore@example.com',NULL,NULL,NULL,'2026-02-17 07:23:47','$2y$12$Ut22dw5SgskKYhPwAnhScedLTt1PYJTAJ5Yv.5tesZ6BrV.AETXTq',NULL,1,NULL,'2026-02-17 07:23:47','2026-02-17 07:23:47','+1-555-0301','active'),(12,4,'Fashion Hub','fashionhub@example.com',NULL,NULL,NULL,'2026-02-17 07:23:47','$2y$12$jS3Q9jIhFjghubYNGFW6BOeNn.zeM1FiFrw3ubUkUxtXeqqZx444y',NULL,1,NULL,'2026-02-17 07:23:47','2026-02-17 07:23:47','+1-555-0302','active'),(13,4,'Home & Garden','homegarden@example.com',NULL,NULL,NULL,'2026-02-17 07:23:47','$2y$12$IVLIFsj.xrZH0rAgIH8wDeyhYaz6E1m4IT2ol/Zxw67oBRgoEGKL2',NULL,1,NULL,'2026-02-17 07:23:47','2026-02-17 07:23:47','+1-555-0303','active'),(14,3,'Nguyen Dat','hihihihi13245768@gmail.com','103222369643418386800',NULL,NULL,'2026-02-17 07:40:16','$2y$12$zASoqN4K47hCe4xA/DlD/eAzNDH6byalKnB1SlrqcaZuCj4yBQa7y',NULL,1,NULL,'2026-02-17 07:40:16','2026-02-17 07:40:16',NULL,'active');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_payouts`
--

DROP TABLE IF EXISTS `vendor_payouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendor_payouts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `commission_total` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed_by` bigint unsigned DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_payouts_processed_by_foreign` (`processed_by`),
  KEY `vendor_payouts_vendor_id_status_index` (`vendor_id`,`status`),
  CONSTRAINT `vendor_payouts_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `vendor_payouts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_payouts`
--

LOCK TABLES `vendor_payouts` WRITE;
/*!40000 ALTER TABLE `vendor_payouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendor_payouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlists_product_id_foreign` (`product_id`),
  CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
INSERT INTO `wishlists` VALUES (4,2,1,'2026-02-18 04:46:18','2026-02-18 04:46:18');
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'e-commerce'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-22 21:27:05
