-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: fsvolunteer
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
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `AccountID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(10) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `VolunteerID` int(11) NOT NULL,
  PRIMARY KEY (`AccountID`),
  UNIQUE KEY `VolunteerID` (`VolunteerID`),
  CONSTRAINT `account_ibfk_1` FOREIGN KEY (`VolunteerID`) REFERENCES `volunteer` (`VolunteerID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1,'B032210001','$2y$10$Q0V0i7uOlGX6Mwy78VwnSOX4iA8sRDdGHu72EEFr0VwfrsRA6S/Cq',1),(2,'B032210002','$2y$10$JqY4VBq9c3n1eoZBjE.Y8uKnRt4JYTbi7NCntiko1U2uBZ2seVg6u',2),(4,'B032210004','$2y$10$YNOvAjRrOoAl42GRYsuKH.sij3v1amzd4u6cVQxfa00oFS1mnIJE6',4),(5,'B032210005','$2y$10$89bUimjJj7RBgvUixA0sMOr98AhOAob4eMOt1XuXoJmK4qUEqqC/G',5),(6,'B032210006','$2y$10$uB/0RBy9lCOjbJtD1ccvp.3Z0B9xL26ZMgIxvmdVaRI5v2jBJDUgq',6),(7,'B032210007','$2y$10$/Qn4zk6B1zWlqj17QPkI7.13bsxhKOb44.1nPIO6xzw/jGvSWia0e',7),(9,'B032210009','$2y$10$OQDMgdGrs4s4SdRUt9aUyu6j3vl.uiuzs9ATDif.RSyLOufvKBpnO',9),(10,'B032210010','$2y$10$Ak6IfU3KPOzqtvvetLJ5P.RBbLD4QYIjmoo.fyO6YqvLo5b/QntZq',10),(11,'B032210011','$2y$10$thWvEfDu8g/a6yfzsmgPd.QZovynrCL4eGZhHgiZfMxgoAzvIxiEq',11),(12,'B032210012','$2y$10$WgnuT7euJArc/b2tPqRzy.HudRsby0Ipf8hzfZZDDlDpjPQcW0eg2',12),(13,'B032210013','$2y$10$xg2qabHiQT/FQn1u2Kk6Ae2Wf5BGHFuVAmsUR.vM3ejsPmbsgM4Ky',13),(14,'B032210014','$2y$10$GVbjoJ.cxP6PHK/Y/Syi6uhp2NDKy4Gt.iFVHuOdtvVtCieOzYn6O',14),(15,'B032210015','$2y$10$hATGe0tBgPsjV51hzaTmceZz3O18a6oLdTgUFoKPRR5/9kapfgoTW',15),(16,'B032210378','$2y$10$D9lfnKrdaIPjHVIJm0juauJJF3CFqSrIQvmwyl7upDV4j6iX/5.mG',16),(17,'B032210016','$2y$10$fs3pZFVt.K.T4l1nXD9.yuVs6Tqf0jKpSCEeaNEEJ.Gh/AEIe9llq',17),(18,'B032210000','$2y$10$5HZcHBql1KWz2WGWpoBaIuyTVuxNuCBw9k3Rx/LZJ6ij7uz7BTIkG',18),(19,'B032210017','$2y$10$YHXxgmqKgjUZqXKzngvdMuqoWUhdpcbtJ86nvD1/XwJC5otUK9baS',19),(20,'B032210018','$2y$10$18gy23FUIWAXo6lfoTbkc./TkGbWqFNqnrv8SBy7Uz/OU5tSUEeTi',20);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`shira`@`%`*/ /*!50003 TRIGGER BeforeAccountUpdate
BEFORE UPDATE ON Account
FOR EACH ROW
BEGIN
    INSERT INTO accountlog (AccountID, VolunteerID, ActionType)
    VALUES (OLD.AccountID, OLD.VolunteerID, 'UPDATE');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `accountlog`
--

DROP TABLE IF EXISTS `accountlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accountlog` (
  `LogID` int(11) NOT NULL AUTO_INCREMENT,
  `AccountID` int(11) DEFAULT NULL,
  `VolunteerID` int(11) DEFAULT NULL,
  `ActionType` varchar(25) DEFAULT NULL,
  `DoneAT` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`LogID`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accountlog`
--

LOCK TABLES `accountlog` WRITE;
/*!40000 ALTER TABLE `accountlog` DISABLE KEYS */;
INSERT INTO `accountlog` VALUES (10,18,18,'UPDATE','2025-01-18 18:30:21'),(11,15,15,'UPDATE','2025-01-18 19:38:57'),(12,15,15,'UPDATE','2025-01-18 19:39:51'),(13,19,19,'FAILED LOGIN','2025-01-18 20:23:32'),(14,18,18,'LOGIN','2025-01-18 21:00:37'),(15,13,13,'FAILED LOGIN','2025-01-18 21:10:22'),(16,18,18,'LOGIN','2025-01-18 21:10:38'),(17,1,1,'UPDATE','2025-01-19 13:40:50'),(18,2,2,'UPDATE','2025-01-19 13:40:50'),(19,4,4,'UPDATE','2025-01-19 13:40:50'),(20,5,5,'UPDATE','2025-01-19 13:40:50'),(21,6,6,'UPDATE','2025-01-19 13:40:50'),(22,7,7,'UPDATE','2025-01-19 13:40:50'),(23,9,9,'UPDATE','2025-01-19 13:40:50'),(24,10,10,'UPDATE','2025-01-19 13:40:50'),(25,11,11,'UPDATE','2025-01-19 13:40:50'),(26,12,12,'UPDATE','2025-01-19 13:40:50'),(27,13,13,'UPDATE','2025-01-19 13:40:50'),(28,14,14,'UPDATE','2025-01-19 13:40:50'),(29,15,15,'UPDATE','2025-01-19 13:40:51'),(30,16,16,'UPDATE','2025-01-19 13:40:51'),(31,17,17,'UPDATE','2025-01-19 13:40:51'),(32,18,18,'UPDATE','2025-01-19 13:40:51'),(33,19,19,'UPDATE','2025-01-19 13:40:51'),(34,20,20,'UPDATE','2025-01-19 13:40:51'),(35,18,18,'FAILED LOGIN','2025-01-19 13:41:22'),(36,18,18,'LOGIN','2025-01-19 13:41:38'),(37,18,18,'UPDATE','2025-01-19 13:42:06'),(38,5,5,'FAILED LOGIN','2025-01-19 13:43:46'),(39,18,18,'LOGIN','2025-01-19 13:44:03'),(40,18,18,'LOGIN','2025-01-19 14:08:04');
/*!40000 ALTER TABLE `accountlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programme`
--

DROP TABLE IF EXISTS `programme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programme` (
  `ProgrammeID` int(11) NOT NULL AUTO_INCREMENT,
  `ProgrammeName` varchar(4) NOT NULL,
  `Description` varchar(100) NOT NULL,
  PRIMARY KEY (`ProgrammeID`),
  UNIQUE KEY `ProgrammeName` (`ProgrammeName`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programme`
--

LOCK TABLES `programme` WRITE;
/*!40000 ALTER TABLE `programme` DISABLE KEYS */;
INSERT INTO `programme` VALUES (1,'BITC','Bachelor of Computer Science (Computer Networking) with Honours'),(2,'BITD','Bachelor of Computer Science (Database Management) with Honours'),(3,'BITI','Bachelor of Computer Science (Artificial Intelligence) with Honours'),(4,'BITM','Bachelor of Computer Science (Interactive Media) with Honours'),(5,'BITS','Bachelor of Computer Science (Software Development) with Honours'),(6,'BITZ','Bachelor of Computer Science (Computer Security) with Honours'),(7,'BITE','Bachelor of Information Technology (Game Technology) with Honours');
/*!40000 ALTER TABLE `programme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `RoleID` int(11) NOT NULL AUTO_INCREMENT,
  `RoleName` varchar(50) NOT NULL,
  `Description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`RoleID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Chief Treasurer','Oversees all financial activities, manages budgets, approves major expenditures, and ensures accurate financial reporting.'),(2,'Assistant Treasurer','Supports the Chief Treasurer by handling daily financial tasks, maintaining records, and preparing reports.'),(4,'Committee Member','Assists with general tasks such as food packing, distribution, and other support activities.'),(5,'Volunteer Manager','Oversees and organizes volunteer activities for specific events or tasks, ensuring smooth execution.'),(6,'Inventory Manager','Tracks and manages foodbank inventory, ensuring proper storage and timely restocking.'),(7,'Sponsorship Manager','Validates sponsor activities, approves registrations, and ensures contributions align with foodbank goals.'),(8,'Operation Manager','Approves user registrations, assigns managers to volunteers or teams, and performs database backups to ensure data security and integrity.'),(9,'ja','');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer`
--

DROP TABLE IF EXISTS `volunteer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteer` (
  `VolunteerID` int(11) NOT NULL AUTO_INCREMENT,
  `MatricNo` varchar(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `ContactNo` varchar(12) NOT NULL,
  `ProgrammeID` int(11) NOT NULL,
  `YearOfStudy` int(2) NOT NULL,
  `JoinDate` date NOT NULL,
  `RoleID` int(11) NOT NULL,
  `Status` varchar(20) DEFAULT NULL,
  `ManagerID` int(11) DEFAULT NULL,
  PRIMARY KEY (`VolunteerID`),
  KEY `ProgrammeID` (`ProgrammeID`),
  KEY `RoleID` (`RoleID`),
  KEY `ManagerID` (`ManagerID`),
  CONSTRAINT `volunteer_ibfk_1` FOREIGN KEY (`ProgrammeID`) REFERENCES `programme` (`ProgrammeID`),
  CONSTRAINT `volunteer_ibfk_2` FOREIGN KEY (`RoleID`) REFERENCES `role` (`RoleID`),
  CONSTRAINT `volunteer_ibfk_3` FOREIGN KEY (`ManagerID`) REFERENCES `volunteer` (`VolunteerID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer`
--

LOCK TABLES `volunteer` WRITE;
/*!40000 ALTER TABLE `volunteer` DISABLE KEYS */;
INSERT INTO `volunteer` VALUES (1,'B032210001','Alice Smith','011-1234567',1,1,'2024-01-15',1,'Active',6),(2,'B032210002','Bob Johnson','018-2345678',2,2,'2023-02-20',2,'Active',6),(4,'B032210004','David Wilson','011-4567890',4,2,'2023-04-05',1,'Resign Approved',5),(5,'B032210005','Eva Davis','018-5678901',5,2,'2024-05-15',5,'Active',NULL),(6,'B032210006','Frank Miller','019-6789012',6,3,'2023-06-25',5,'Active',NULL),(7,'B032210007','Grace Lee','011-7890123',1,1,'2024-07-30',6,'Active',5),(9,'B032210009','Ian Anderson','019-9012345',3,1,'2024-09-10',4,'Active',5),(10,'B032210010','Jack Thomas','011-0123456',4,2,'2023-10-15',4,'Active',5),(11,'B032210011','Kathy Jackson','018-1234568',5,3,'2024-11-20',4,'Resign Approved',5),(12,'B032210012','Leo White','019-2345679',6,1,'2023-12-25',4,'Active',6),(13,'B032210013','Mona Harris','011-3456780',1,2,'2024-01-05',4,'Active',6),(14,'B032210014','Nina Clark','018-4567891',2,3,'2023-02-15',4,'Active',5),(15,'B032210015','Oscar Lewis','019-5678902',3,1,'2024-03-20',4,'Resign Approved',6),(16,'B032210378','Hidayah','011-1212454',4,2,'2025-01-16',7,'Active',6),(17,'B032210016','David Beckham','13',3,2,'2025-01-18',4,'Active',6),(18,'B032210000','Shahira Zainuddin','019-9545506',2,3,'2023-07-27',8,'Active',NULL),(19,'B032210017','Elle Fanning','17',1,1,'2025-01-18',7,'Active',5),(20,'B032210018','Kim Ji Won','012-2763763',4,2,'2025-01-18',4,'Pending',NULL);
/*!40000 ALTER TABLE `volunteer` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`shira`@`%`*/ /*!50003 TRIGGER BeforeMatricNoInsert 
BEFORE INSERT ON volunteer
FOR EACH ROW
BEGIN
    DECLARE managerName VARCHAR(255);
    DECLARE contactNo VARCHAR(255);
    DECLARE errorMessage VARCHAR(512);  
    
    
    IF EXISTS (SELECT 1 
               FROM volunteer 
               WHERE MatricNo = NEW.MatricNo AND Status IS NOT NULL) THEN
        
        SELECT v2.Name, v2.ContactNo 
        INTO managerName, contactNo
        FROM volunteer v1
        JOIN volunteer v2 ON v1.ManagerID = v2.VolunteerID
        WHERE v1.MatricNo = NEW.MatricNo;

        
        SET errorMessage = CONCAT('You already resigned. Do consult ', managerName, ' via ', contactNo, ' for any further assistance.');

        
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = errorMessage;
    END IF;

    
    IF EXISTS (SELECT 1 FROM volunteer WHERE MatricNo = NEW.MatricNo) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Matric Number already exists.';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`shira`@`%`*/ /*!50003 TRIGGER BeforeVolunteerUpdate
BEFORE UPDATE ON Volunteer
FOR EACH ROW
BEGIN
    INSERT INTO volunteerlog (VolunteerID, Name, ContactNo, YearOfStudy, RoleID, ActionType)
    VALUES (OLD.VolunteerID, OLD.Name, OLD.ContactNo, OLD.YearOfStudy, OLD.RoleID, 'UPDATE');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `volunteerlog`
--

DROP TABLE IF EXISTS `volunteerlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteerlog` (
  `LogID` int(11) NOT NULL AUTO_INCREMENT,
  `VolunteerID` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `ContactNo` varchar(50) DEFAULT NULL,
  `YearOfStudy` int(11) DEFAULT NULL,
  `RoleID` int(11) DEFAULT NULL,
  `ActionType` varchar(25) DEFAULT NULL,
  `Note` varchar(200) DEFAULT NULL,
  `DoneAt` datetime DEFAULT current_timestamp(),
  `ManagerID` int(11) DEFAULT NULL,
  `ApprovedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`LogID`),
  KEY `fk_manager` (`ManagerID`),
  CONSTRAINT `fk_manager` FOREIGN KEY (`ManagerID`) REFERENCES `volunteer` (`VolunteerID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteerlog`
--

LOCK TABLES `volunteerlog` WRITE;
/*!40000 ALTER TABLE `volunteerlog` DISABLE KEYS */;
INSERT INTO `volunteerlog` VALUES (1,4,'David Wilson','011-4567890',1,5,'UPDATE',NULL,'2025-01-16 00:50:35',NULL,'2025-01-16 17:55:07'),(2,11,'Kathy Jackson','018-1234568',3,4,'UPDATE',NULL,'2025-01-16 15:13:40',NULL,'2025-01-16 17:55:07'),(3,12,'Leo White','019-2345679',1,4,'UPDATE',NULL,'2025-01-16 15:13:40',NULL,'2025-01-16 17:55:07'),(4,15,'Oscar Lewis','019-5678902',1,4,'UPDATE',NULL,'2025-01-16 15:13:40',NULL,'2025-01-16 17:55:07'),(5,4,'David Wilson','011-4567890',3,5,'UPDATE',NULL,'2025-01-16 15:13:40',NULL,'2025-01-16 17:55:07'),(7,11,'Kathy Jackson','018-1234568',3,4,'UPDATE',NULL,'2025-01-17 02:22:10',NULL,NULL),(8,11,'Kathy Jackson','018-1234568',3,4,'DELETE','Sick.','2025-01-17 02:22:10',5,'2025-01-17 03:27:09'),(12,4,'David Wilson','011-4567890',2,1,'DELETE','Private matter.','2025-01-17 11:51:44',5,'2025-01-17 12:22:41'),(13,17,'David Beckham','13',2,4,'UPDATE',NULL,'2025-01-18 19:04:11',NULL,NULL),(14,15,'Oscar Lewis','019-5678902',1,4,'RESIGN','Sick','2025-01-18 19:40:22',6,'2025-01-18 19:53:57'),(15,19,'Elle Fanning','17',1,7,'UPDATE',NULL,'2025-01-18 21:12:34',NULL,NULL),(16,18,'Shahira Zainuddin','019-9545506',3,8,'UPDATE',NULL,'2025-01-19 13:42:06',NULL,NULL);
/*!40000 ALTER TABLE `volunteerlog` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-19 16:04:24
