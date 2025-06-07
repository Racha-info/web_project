-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 07 juin 2025 à 15:26
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `web_project`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `full_name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `full_name`) VALUES
(1, 'admin@exempl.com', '002', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `display` enum('general','computer_science','physics','chemistry','math') NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `display`, `datetime`) VALUES
(13, 'AI Research Assistant Program Open', 'The CS Department has launched the AI Research Assistant Program for top 3rd-year students. Selected participants will work under faculty supervision on real-world machine learning projects. Applications close June 20.', 'computer_science', '2025-06-07 14:56:26'),
(14, 'Math Olympiad Training Camp', 'Get ready for the upcoming National Math Olympiad! The department is hosting a 2-week intensive training camp starting June 17. Open to all students who scored 85%+ in Advanced Calculus.', 'math', '2025-06-07 14:57:09'),
(15, 'Quantum Mechanics Workshop', 'The Physics Department is pleased to announce an exclusive Quantum Mechanics Workshop for senior students and researchers.\r\nThis session will cover recent breakthroughs in quantum entanglement and time-energy uncertainty.', 'physics', '2025-06-07 15:00:39'),
(16, 'Advanced Lab Safety Certification', 'All chemistry students enrolled in Organic Chemistry II must attend the Advanced Lab Safety Certification Workshop on June 19 at Lab C3. Certification is mandatory for lab access this semester.', 'chemistry', '2025-06-07 15:01:20'),
(17, 'ech Startups Internship Panel', 'Interested in tech startups? Attend the Internship Opportunity Panel featuring recruiters from emerging Algerian startups. Takes place in CS Hall A on June 22. Bring your CV and portfolio', 'computer_science', '2025-06-07 15:02:30'),
(18, 'Final Exam Schedule Released', 'The official Final Exam Timetable for all departments has been published. Students are advised to review the schedule carefully on the portal and report any conflicts to their department coordinators before June 10.', 'general', '2025-06-07 15:03:13'),
(20, 'Graduation Ceremony Announcement', 'Graduating students are invited to confirm their participation in the upcoming Graduation Ceremony. Gown reservations and student acknowledgements must be completed via the portal no later than June 15.', 'general', '2025-06-07 15:03:55');

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `department` enum('computer_science','physics','chemistry','Mathematics') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `department`) VALUES
(14, 'AI Chatbot for Student Services', 'Develop an AI-powered chatbot to help students with FAQs and campus information automatically.', 'computer_science'),
(15, 'Secure Online Voting System', 'Create a blockchain-based voting platform ensuring security and transparency for campus elections.', 'computer_science'),
(17, 'Optimization Algorithms for Resource Allocation', 'Study and implement optimization algorithms to improve resource allocation in logistics problems.', 'Mathematics'),
(19, 'Study of Solar Panel Efficiency Under Variable Light', 'Investigate how solar panel output varies with different light intensities and angles.', 'physics'),
(21, 'Water Quality Analysis and Contamination Detection', 'Design a chemical assay for detecting common pollutants in local water sources.', 'chemistry');

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `email`, `password`) VALUES
(1, 'Racha', 'Racha', 'student@example.com', '007'),
(4, 'Lamis', 'Lamis', 'lamis@gmail.com', '003');

-- --------------------------------------------------------

--
-- Structure de la table `student_project_wishlist`
--

DROP TABLE IF EXISTS `student_project_wishlist`;
CREATE TABLE IF NOT EXISTS `student_project_wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `project_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id_index` (`student_id`),
  KEY `project_id_index` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `student_project_wishlist`
--

INSERT INTO `student_project_wishlist` (`id`, `student_id`, `project_id`) VALUES
(14, 1, 14);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `student_project_wishlist`
--
ALTER TABLE `student_project_wishlist`
  ADD CONSTRAINT `student_project_wishlist_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_project_wishlist_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
