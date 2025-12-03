-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for rpg_game
CREATE DATABASE IF NOT EXISTS `rpg_game` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `rpg_game`;

-- Dumping structure for table rpg_game.accessories
CREATE TABLE IF NOT EXISTS `accessories` (
  `item_id` int NOT NULL,
  `bonus_str` int DEFAULT '0',
  `bonus_agi` int DEFAULT '0',
  `bonus_int` int DEFAULT '0',
  PRIMARY KEY (`item_id`),
  CONSTRAINT `accessories_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rpg_game.accessories: ~2 rows (approximately)
INSERT INTO `accessories` (`item_id`, `bonus_str`, `bonus_agi`, `bonus_int`) VALUES
	(3, 20, 0, 0),
	(4, 0, 0, 25);

-- Dumping structure for table rpg_game.consumables
CREATE TABLE IF NOT EXISTS `consumables` (
  `item_id` int NOT NULL,
  `recover_hp` int DEFAULT '0',
  `recover_mana` int DEFAULT '0',
  PRIMARY KEY (`item_id`),
  CONSTRAINT `consumables_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rpg_game.consumables: ~2 rows (approximately)
INSERT INTO `consumables` (`item_id`, `recover_hp`, `recover_mana`) VALUES
	(5, 500, 0),
	(6, 0, 300);

-- Dumping structure for table rpg_game.heroes
CREATE TABLE IF NOT EXISTS `heroes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `job_class` varchar(50) DEFAULT NULL,
  `level` int DEFAULT '1',
  `xp` int DEFAULT '0',
  `base_str` int DEFAULT '10',
  `base_agi` int DEFAULT '10',
  `base_int` int DEFAULT '10',
  `current_hp` int DEFAULT '100',
  `max_hp` int DEFAULT '100',
  `current_mana` int DEFAULT '50',
  `max_mana` int DEFAULT '50',
  `image_url` varchar(255) DEFAULT NULL,
  `gold` int DEFAULT '1000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rpg_game.heroes: ~4 rows (approximately)
INSERT INTO `heroes` (`id`, `name`, `job_class`, `level`, `xp`, `base_str`, `base_agi`, `base_int`, `current_hp`, `max_hp`, `current_mana`, `max_mana`, `image_url`, `gold`) VALUES
	(1, 'Arthur Pendragon', 'Paladin', 15, 1838, 75, 30, 20, 1687, 1500, 0, 300, NULL, 11060),
	(2, 'Merlin the Wise', 'Archmage', 10, 402, 5, 15, 60, 100, 400, 1275, 1200, NULL, 1152),
	(3, 'Nafisaint', 'Mage', 1, 0, 5, 10, 15, 100, 100, 225, 225, NULL, 900),
	(4, 'Matcha Latto', 'Warrior', 7, 920, 55, 27, 22, 125, 1100, 0, 330, NULL, 4785),
	(7, 'Kuroissaint', 'Mage', 4, 359, 25, 21, 31, 106, 500, 390, 465, NULL, 1495);

-- Dumping structure for table rpg_game.inventory
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hero_id` int NOT NULL,
  `item_id` int NOT NULL,
  `is_equipped` tinyint(1) DEFAULT '0',
  `quantity` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `hero_id` (`hero_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `heroes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rpg_game.inventory: ~9 rows (approximately)
INSERT INTO `inventory` (`id`, `hero_id`, `item_id`, `is_equipped`, `quantity`) VALUES
	(1, 1, 1, 1, 2),
	(2, 1, 3, 1, 1),
	(4, 2, 2, 1, 1),
	(5, 2, 4, 1, 1),
	(6, 2, 6, 0, 4),
	(12, 1, 5, 0, 1),
	(13, 1, 4, 0, 2),
	(17, 3, 5, 0, 1),
	(20, 4, 3, 1, 1),
	(24, 7, 9, 1, 1);

-- Dumping structure for table rpg_game.items
CREATE TABLE IF NOT EXISTS `items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `price` int DEFAULT '0',
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rpg_game.items: ~7 rows (approximately)
INSERT INTO `items` (`id`, `name`, `type`, `price`, `image_url`) VALUES
	(1, 'Excalibur', 'Weapon', 5000, 'sword.png'),
	(2, 'Staff of Void', 'Weapon', 4500, 'staff.png'),
	(3, 'Ring of Titan', 'Accessory', 2000, 'ring_red.png'),
	(4, 'Necklace of Wisdom', 'Accessory', 2500, 'necklace_blue.png'),
	(5, 'Mega Potion', 'Consumable', 100, 'potion_red.png'),
	(6, 'Elixir of Mana', 'Consumable', 150, 'potion_blue.png'),
	(7, 'God Slayer Dagger', 'Weapon', 99999, 'dagger.png'),
	(9, 'Ice Staff', 'Weapon', 1000, 'sword.png');

-- Dumping structure for table rpg_game.weapons
CREATE TABLE IF NOT EXISTS `weapons` (
  `item_id` int NOT NULL,
  `attack_power` int NOT NULL,
  `element` varchar(20) DEFAULT 'None',
  PRIMARY KEY (`item_id`),
  CONSTRAINT `weapons_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rpg_game.weapons: ~3 rows (approximately)
INSERT INTO `weapons` (`item_id`, `attack_power`, `element`) VALUES
	(1, 150, 'Holy'),
	(2, 60, 'Dark'),
	(7, 999, 'Chaos'),
	(9, 50, 'Ice');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
