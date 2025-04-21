-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 16, 2025 at 01:11 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbschema_1220446`
--

-- --------------------------------------------------------

--
-- Table structure for table `Files`
--

CREATE TABLE `Files` (
  `file_id` int(11) NOT NULL,
  `project_id` varchar(10) NOT NULL,
  `path` text NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Projects`
--

CREATE TABLE `Projects` (
  `project_id` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `budget` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `manager_id` char(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Tasks`
--

CREATE TABLE `Tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `project_id` varchar(10) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `effort` int(10) UNSIGNED NOT NULL,
  `status` enum('Pending','In Progress','Completed','') NOT NULL DEFAULT 'Pending',
  `priority` enum('Low','Medium','High','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Tasks_Users`
--

CREATE TABLE `Tasks_Users` (
  `task_id` int(11) NOT NULL,
  `user_id` char(11) NOT NULL,
  `role` enum('Developer','Designer','Tester','Analyst','Support') NOT NULL,
  `contribution` int(3) UNSIGNED NOT NULL,
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` char(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `date_of_birth` date NOT NULL,
  `id_number` char(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('Manager','Project Leader','Team Member') NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `qualification` text NOT NULL,
  `skills` text NOT NULL,
  `username` varchar(13) NOT NULL,
  `password` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `name`, `address`, `date_of_birth`, `id_number`, `email`, `role`, `telephone`, `qualification`, `skills`, `username`, `password`) VALUES
('3358314918', 'Tasneem Abusara', '303 Kufar Aqab Jerusalem Palestine', '2004-12-11', '1234567890', 'tasneemabusra@gmail.com', 'Manager', '+970595707969', 'Bachelor Degree', 'Programing', 'tasneem', 'Password1'),
('3580818912', 'Leen Alqazaqi', '301 Al-Quds st. Al-Bireh Palestine', '2004-12-27', '7890123456', 'leen@mail.com', 'Team Member', '+9707777444221', 'Engineering degree', 'Front-end developer', 'leen', 'leen12345'),
('5088421811', 'Member 3', 'Test Test Ramallah Palestine', '2025-01-02', '1234567890', 'testmember@gmail.com', 'Team Member', '1234567890', 'Qual 1', 'Skill 1', 'testmember1', 'testmember1'),
('7058129110', 'Ahmad', '404 J.st Jericho Palestine', '2000-06-07', '987654321', 'mail@mail.com', 'Project Leader', '+970111111111', 'Masters Degree', 'Leading Skills', 'ahmad', 'ahmad123'),
('9909406257', 'Duha', '505 Sateh-Marahbah Ramallah Palestine', '2004-06-03', '1234567890', 'duha@hotmail.com', 'Team Member', '+970987654321', 'Engineering Degree', 'Insane', 'duha', 'duha1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Files`
--
ALTER TABLE `Files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `Files_Project` (`project_id`);

--
-- Indexes for table `Projects`
--
ALTER TABLE `Projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `Project_Manager` (`manager_id`);

--
-- Indexes for table `Tasks`
--
ALTER TABLE `Tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `Task_Project` (`project_id`);

--
-- Indexes for table `Tasks_Users`
--
ALTER TABLE `Tasks_Users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Task` (`task_id`),
  ADD KEY `Task_User` (`user_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Files`
--
ALTER TABLE `Files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Tasks`
--
ALTER TABLE `Tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Tasks_Users`
--
ALTER TABLE `Tasks_Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Files`
--
ALTER TABLE `Files`
  ADD CONSTRAINT `Files_Project` FOREIGN KEY (`project_id`) REFERENCES `Projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Projects`
--
ALTER TABLE `Projects`
  ADD CONSTRAINT `Project_Manager` FOREIGN KEY (`manager_id`) REFERENCES `Users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `Tasks`
--
ALTER TABLE `Tasks`
  ADD CONSTRAINT `Task_Project` FOREIGN KEY (`project_id`) REFERENCES `Projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Tasks_Users`
--
ALTER TABLE `Tasks_Users`
  ADD CONSTRAINT `Task` FOREIGN KEY (`task_id`) REFERENCES `Tasks` (`id`),
  ADD CONSTRAINT `Task_User` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
