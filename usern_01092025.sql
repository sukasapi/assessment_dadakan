/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 8.0.30 : Database - aa_dadakan
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`role`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Administrator','admin@assessment.com',NULL,'$2y$12$Gbwy.rQfc2eOGOfapNOLruuUNtf4PaN5HkoeuVm9sr3HxRINYcv12','admin',NULL,'2025-09-02 01:49:26','2025-09-02 01:49:26'),
(2,'Ahmad Rizki','ahmad.rizki@example.com',NULL,'$2y$12$azWJYlCtraak7FIFQZaCXuHw30BLOAYHqnVDUssEYICMZNdHr7Z4W','user',NULL,'2025-09-02 01:49:27','2025-09-02 01:49:27'),
(3,'Siti Nurhaliza','siti.nurhaliza@example.com',NULL,'$2y$12$mpoeUCQ4kVu.L.gpCRhG4.MxbaEiZWJHLhhYdrofagr/n0VuKRg.a','user',NULL,'2025-09-02 01:49:27','2025-09-02 01:49:27'),
(4,'Budi Santoso','budi.santoso@example.com',NULL,'$2y$12$JWCWtdw1X/1GjmqXEUu/yeSQy0vXDmfoI4Xy4ZHO4nuhN29pvDdbK','user',NULL,'2025-09-02 01:49:27','2025-09-02 01:49:27'),
(5,'Dewi Sartika','dewi.sartika@example.com',NULL,'$2y$12$f9k9gbXdfe0884C3F.RnheyBAv0DTDm21Br8fXcfjRJmQE/.apNh6','user',NULL,'2025-09-02 01:49:27','2025-09-02 01:49:27'),
(6,'Eko Prasetyo','eko.prasetyo@example.com',NULL,'$2y$12$AjarXn7OP85pQtWow95SFOoM6XmZ0b9exNu5oRA1a2WWVIh/Yl96G','user',NULL,'2025-09-02 01:49:28','2025-09-02 01:49:28');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
