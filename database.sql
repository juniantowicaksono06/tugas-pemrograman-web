/*
SQLyog Community v13.2.1 (64 bit)
MySQL - 8.0.32 : Database - perpus
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`perpus` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `perpus`;

/*Table structure for table `master_author` */

DROP TABLE IF EXISTS `master_author`;

CREATE TABLE `master_author` (
  `id` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `master_author` */

insert  into `master_author`(`id`,`name`,`status`,`created_at`,`updated_at`) values 
('17fb8bb5-f342-426b-86a3-4bb1948672be','Testing',1,'2024-04-21 23:00:52','2024-04-21 23:01:52');

/*Table structure for table `master_buku` */

DROP TABLE IF EXISTS `master_buku`;

CREATE TABLE `master_buku` (
  `id` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_publisher` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `master_buku` */

/*Table structure for table `master_menu` */

DROP TABLE IF EXISTS `master_menu`;

CREATE TABLE `master_menu` (
  `id` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '#',
  `is_parent` tinyint NOT NULL DEFAULT '1',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `menu_active` tinyint NOT NULL DEFAULT '1',
  `menu_type` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `master_menu` */

insert  into `master_menu`(`id`,`name`,`link`,`is_parent`,`icon`,`menu_active`,`menu_type`,`created_at`,`updated_at`) values 
('6e6d469c-083e-11ef-a0a3-0242ac120003','Dashboard','/admin',0,'nav-icon fas fa-tachometer-alt',1,1,'2024-05-02 11:42:50','2024-05-02 13:10:52'),
('85310cad-083e-11ef-a0a3-0242ac120003','Buku','#',1,'nav-icon fas fa-book',1,1,'2024-05-02 11:43:28','2024-05-02 11:44:43'),
('c7baaab6-083e-11ef-a0a3-0242ac120003','User','#',1,'nav-icon fas fa-user',1,1,'2024-05-02 11:45:20','2024-05-02 11:45:20'),
('e179fdeb-083e-11ef-a0a3-0242ac120003','Logout','/logout',0,'nav-icon fas fa-sign-out-alt',1,1,'2024-05-02 11:46:03','2024-05-02 11:46:03');

/*Table structure for table `master_publisher` */

DROP TABLE IF EXISTS `master_publisher`;

CREATE TABLE `master_publisher` (
  `id` varchar(100) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `master_publisher` */

insert  into `master_publisher`(`id`,`name`,`status`,`created_at`,`updated_at`) values 
('6e9c826d-13ec-4dc2-9c36-598e73026304','Penerbit Informatika',1,'2024-04-21 21:44:23','2024-04-21 22:50:20');

/*Table structure for table `master_sub_menu` */

DROP TABLE IF EXISTS `master_sub_menu`;

CREATE TABLE `master_sub_menu` (
  `id` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `id_menu` varchar(100) NOT NULL,
  `menu_type` tinyint NOT NULL DEFAULT '1',
  `menu_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_ID_MENU` (`id_menu`),
  CONSTRAINT `FK_ID_MENU` FOREIGN KEY (`id_menu`) REFERENCES `master_menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `master_sub_menu` */

insert  into `master_sub_menu`(`id`,`name`,`icon`,`link`,`id_menu`,`menu_type`,`menu_active`,`created_at`,`updated_at`) values 
('19a7a065-0840-11ef-a0a3-0242ac120003','Manajemen Penerbit','far fa-circle nav-icon','/admin/publishers','85310cad-083e-11ef-a0a3-0242ac120003',1,1,'2024-05-02 11:54:47','2024-05-02 11:56:40'),
('31b16773-0840-11ef-a0a3-0242ac120003','Manajemen Pengarang','far fa-circle nav-icon','/admin/authors','85310cad-083e-11ef-a0a3-0242ac120003',1,1,'2024-05-02 11:54:47','2024-05-02 11:57:03'),
('7c04b44f-0840-11ef-a0a3-0242ac120003','Manajemen Buku','far fa-circle nav-icon','/admin/books','85310cad-083e-11ef-a0a3-0242ac120003',1,1,'2024-05-02 11:54:47','2024-05-02 11:57:03'),
('a14cd9a8-0840-11ef-a0a3-0242ac120003','Manajemen User','far fa-circle nav-icon','/admin/users','c7baaab6-083e-11ef-a0a3-0242ac120003',1,1,'2024-05-02 11:54:47','2024-05-02 11:57:03');

/*Table structure for table `master_user` */

DROP TABLE IF EXISTS `master_user`;

CREATE TABLE `master_user` (
  `id` varchar(100) NOT NULL,
  `username` varchar(64) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `no_hp` varchar(13) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` tinyint DEFAULT '2',
  `user_status` tinyint NOT NULL DEFAULT '0',
  `user_activation_token` varchar(255) DEFAULT NULL,
  `user_activation_expired` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `master_user` */

insert  into `master_user`(`id`,`username`,`fullname`,`no_hp`,`email`,`password`,`user_type`,`user_status`,`user_activation_token`,`user_activation_expired`,`created_at`,`updated_at`) values 
('053c8435-17b1-41ba-8923-2d96388f4ffb','admin','Admin',NULL,'juniantowicaksono06@gmail.com','$2y$10$2gN2Q6Xk9TXos0xdvKfOVefLlrS9tSiW67VS7F/Ugl29XHFwGmu6O',1,1,NULL,NULL,'2024-04-04 12:19:41','2024-04-09 22:23:36'),
('12f9d65c-c5be-40f8-9dfd-2b2aab254e36','juniantowicaksono06','Junianto Ichwan Dwi Wicaksono','081354070748','juniantowicaksono98@gmail.com','$2y$10$yLPb1cmoUr1/L6NO0qszkeM5Pl/sZJTqU0n5pikC3oP6RGz8ppY7S',2,0,NULL,NULL,'2024-04-15 22:35:13','2024-04-21 16:25:28'),
('a6dd847f-12ad-486c-9dc9-9fcd351c2337','admintes','Admin Testing','081354070748','admintes@mail.com','$2y$10$wLEaGoPB/iClhVExpxTM3O595E/CmbbWGIXGn2mOlh0UKzb6mbTMm',1,1,NULL,NULL,'2024-04-18 12:50:10','2024-04-21 21:27:10'),
('cdb4501b-bfa0-4a24-9f80-ba3ee1deb328','alya013','Alisa Mikhailovna','081354070748','alya013@gmail.com','$2y$10$F9DtSfEm6GF0/SnTuA1uxeokgVyj8g4rQNXtfI247a7rRd5hWtd36',2,1,NULL,NULL,'2024-04-18 01:25:17','2024-04-21 21:27:28');

/* Trigger structure for table `master_author` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `insert_id_master_author` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'%' */ /*!50003 TRIGGER `insert_id_master_author` BEFORE INSERT ON `master_author` FOR EACH ROW BEGIN
  IF new.id IS NULL THEN
    SET new.id = UUID();
  END IF;
END */$$


DELIMITER ;

/* Trigger structure for table `master_buku` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `insert_id_master_buku` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'%' */ /*!50003 TRIGGER `insert_id_master_buku` BEFORE INSERT ON `master_buku` FOR EACH ROW BEGIN
  IF new.id IS NULL THEN
    SET new.id = UUID();
  END IF;
END */$$


DELIMITER ;

/* Trigger structure for table `master_menu` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `insert_id_master_menu` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'%' */ /*!50003 TRIGGER `insert_id_master_menu` BEFORE INSERT ON `master_menu` FOR EACH ROW BEGIN
  IF new.id IS NULL THEN
    SET new.id = uuid();
  END IF;
END */$$


DELIMITER ;

/* Trigger structure for table `master_publisher` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `insert_id_master_publisher` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'%' */ /*!50003 TRIGGER `insert_id_master_publisher` BEFORE INSERT ON `master_publisher` FOR EACH ROW BEGIN
  IF new.id IS NULL THEN
    SET new.id = UUID();
  END IF;
END */$$


DELIMITER ;

/* Trigger structure for table `master_sub_menu` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `insert_id_master_sub_menu` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'%' */ /*!50003 TRIGGER `insert_id_master_sub_menu` BEFORE INSERT ON `master_sub_menu` FOR EACH ROW BEGIN
  IF new.id IS NULL THEN
    SET new.id = uuid();
  END IF;
END */$$


DELIMITER ;

/* Trigger structure for table `master_user` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `insert_id_master_user` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'%' */ /*!50003 TRIGGER `insert_id_master_user` BEFORE INSERT ON `master_user` FOR EACH ROW BEGIN
  IF new.id IS NULL THEN
    SET new.id = UUID();
  END IF;
END */$$


DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
