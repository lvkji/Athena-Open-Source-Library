/*
 Navicat Premium Data Transfer

 Source Server         : mariadb
 Source Server Type    : MariaDB
 Source Server Version : 100421
 Source Host           : localhost:3306
 Source Schema         : athenadb

 Target Server Type    : MariaDB
 Target Server Version : 100421
 File Encoding         : 65001

 Date: 29/03/2025 14:24:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for administrators
-- ----------------------------
DROP TABLE IF EXISTS `administrators`;
CREATE TABLE `administrators`  (
  `AdminID` int(11) NOT NULL,
  `UserID` int(11) NULL DEFAULT NULL,
  `AdminFName` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `AdminLName` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`AdminID`) USING BTREE,
  UNIQUE INDEX `UserID`(`UserID`) USING BTREE,
  CONSTRAINT `administrators_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of administrators
-- ----------------------------

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles`  (
  `ArticleID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Content` blob NOT NULL,
  `UploadDate` datetime NULL DEFAULT current_timestamp(),
  `Verified` tinyint(1) NULL DEFAULT 0,
  `AuthorID` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`ArticleID`) USING BTREE,
  INDEX `AuthorID`(`AuthorID`) USING BTREE,
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`AuthorID`) REFERENCES `authors` (`AuthorID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of articles
-- ----------------------------

-- ----------------------------
-- Table structure for authors
-- ----------------------------
DROP TABLE IF EXISTS `authors`;
CREATE TABLE `authors`  (
  `AuthorID` int(11) NOT NULL,
  `UserID` int(11) NULL DEFAULT NULL,
  `AuthorFName` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `AuthorLName` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`AuthorID`) USING BTREE,
  UNIQUE INDEX `UserID`(`UserID`) USING BTREE,
  CONSTRAINT `authors_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of authors
-- ----------------------------

-- ----------------------------
-- Table structure for books
-- ----------------------------
DROP TABLE IF EXISTS `Books`;
CREATE TABLE Books (
    BookID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Author VARCHAR(255),
    Link VARCHAR(500),
    ISBN VARCHAR(500),
    DownloadLink VARCHAR(500),
    FilePath VARCHAR(255) NOT NULL,
    AuthorID INT,
    Cover VARCHAR(500),
    UploadDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (AuthorID) REFERENCES Authors(AuthorID)
);
-- ----------------------------
-- Records of books
-- ----------------------------

-- ----------------------------
-- Table structure for uploads
-- ----------------------------
DROP TABLE IF EXISTS `uploads`;
CREATE TABLE `uploads`  (
  `uploadid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `documenttype` enum('Book','Article') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Article',
  `documentid` int(11) NOT NULL,
  `uploaddate` timestamp NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uploadid`) USING BTREE,
  INDEX `UserID`(`userid`) USING BTREE,
  CONSTRAINT `uploads_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uploads
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Student','Author','Administrator') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Student',
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userid`) USING BTREE,
  UNIQUE INDEX `Email`(`email`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (9, 'student@gmail.com', '$2y$10$TOVt/AQ39mcltAjdMcARNelzSHH2kffVUcyjCdIN2LLJq.Wg6hHp6', 'Student', '2025-03-29 14:12:59');
INSERT INTO `users` VALUES (10, 'admin@gmail.com', '$2y$10$XTckC9dOk36XDRj/fQRqnONpczT6/K8RN3FFuiGkoIO8eQxTLUkQm', 'Student', '2025-03-29 14:13:07');
INSERT INTO `users` VALUES (11, 'author@gmail.com', '$2y$10$SoIej3YjZf2RM6rcDAI1.OL5K69WPHW8xFOyD.PzB3hago2Doatbq', 'Student', '2025-03-29 14:23:17');

-- ----------------------------
-- Table structure for verifications
-- ----------------------------
DROP TABLE IF EXISTS `verifications`;
CREATE TABLE `verifications`  (
  `VerificationID` int(11) NOT NULL AUTO_INCREMENT,
  `AdminID` int(11) NOT NULL,
  `DocumentType` enum('Book','Article') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `DocumentID` int(11) NOT NULL,
  `Verified` tinyint(1) NULL DEFAULT 0,
  `VerificationDate` datetime NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`VerificationID`) USING BTREE,
  INDEX `AdminID`(`AdminID`) USING BTREE,
  CONSTRAINT `verifications_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `administrators` (`AdminID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of verifications
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
