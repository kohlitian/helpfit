-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 07, 2017 at 06:49 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `HELPFit`
--

-- --------------------------------------------------------

--
-- Table structure for table `JoinedSessions`
--

CREATE TABLE `JoinedSessions` (
  `joinID` int(11) NOT NULL,
  `memberID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `JoinedSessions`
--

INSERT INTO `JoinedSessions` (`joinID`, `memberID`, `sessionID`) VALUES
(32, 8, 10),
(34, 8, 6),
(46, 8, 15),
(47, 9, 23),
(48, 8, 7),
(49, 9, 19);

-- --------------------------------------------------------

--
-- Table structure for table `Member`
--

CREATE TABLE `Member` (
  `memberID` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `level` enum('Beginner','Advanced','Expert','') NOT NULL,
  `signupDate` int(11) NOT NULL,
  `loginDate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Member`
--

INSERT INTO `Member` (`memberID`, `email`, `username`, `password`, `fullName`, `level`, `signupDate`, `loginDate`) VALUES
(8, 'tan@gmail.com', 'tan', '1234', 'Mrs Tan', 'Expert', 1508583739, 1510015702),
(9, 'swen@gmail.com', 'swen', '1234', 'Mrs Swen', 'Advanced', 1509462688, 1509977014);

-- --------------------------------------------------------

--
-- Table structure for table `Review`
--

CREATE TABLE `Review` (
  `reviewID` int(11) NOT NULL,
  `timeStamp` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `memberID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Review`
--

INSERT INTO `Review` (`reviewID`, `timeStamp`, `rating`, `comment`, `memberID`, `sessionID`) VALUES
(2, 1509436277, 4, 'Great trainer', 8, 10);

-- --------------------------------------------------------

--
-- Table structure for table `Trainers`
--

CREATE TABLE `Trainers` (
  `trainerID` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `specialty` enum('Sport','MMA','Dance') NOT NULL,
  `signupDate` int(11) NOT NULL,
  `loginDate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Trainers`
--

INSERT INTO `Trainers` (`trainerID`, `email`, `username`, `password`, `fullName`, `specialty`, `signupDate`, `loginDate`) VALUES
(17, 'ben@gmail.com', 'ben', '1234', 'Li Tian', 'Dance', 1508583487, 1509933790),
(18, 'armin@gmail.com', 'armin', '1234', 'Armin Nikdel', 'Sport', 1508745377, 1510025136);

-- --------------------------------------------------------

--
-- Table structure for table `TrainingSessions`
--

CREATE TABLE `TrainingSessions` (
  `sessionID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `datetime` int(11) NOT NULL,
  `fee` double NOT NULL,
  `status` enum('Full','Available','Cancelled','Passed','') NOT NULL,
  `note` text NOT NULL,
  `trainingType` enum('Personal','Group','') NOT NULL,
  `classType` enum('Dance','MMA','Sport','') NOT NULL,
  `maxParticipants` int(11) NOT NULL,
  `trainerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `TrainingSessions`
--

INSERT INTO `TrainingSessions` (`sessionID`, `title`, `datetime`, `fee`, `status`, `note`, `trainingType`, `classType`, `maxParticipants`, `trainerID`) VALUES
(5, 'Plank Day', 1508852520, 1234, 'Passed', '', 'Group', 'Sport', 22, 17),
(6, 'Yugi', 1510162320, 300, 'Available', '', 'Group', 'Dance', 10, 18),
(7, 'Samba', 1511371980, 300, 'Available', '', 'Group', 'Dance', 20, 18),
(9, 'Salsa', 1512150660, 400, 'Cancelled', '', 'Group', 'Dance', 10, 18),
(10, 'Solo Dancer', 1508570618, 100, 'Passed', 'Your dance is improved, no need follow up class', 'Personal', '', 1, 18),
(12, 'Do It!', 1510854960, 51, 'Cancelled', '', 'Group', 'MMA', 14, 18),
(13, 'DIY', 1511373360, 80, 'Available', '', 'Group', 'Dance', 18, 18),
(14, 'KL Run', 1509042360, 60, 'Passed', '', 'Group', 'Sport', 50, 17),
(15, 'Solo MMA', 1506059907, 68, 'Passed', '', 'Personal', '', 1, 17),
(16, 'Zombie Run', 1513171380, 60, 'Available', '', 'Group', 'Sport', 25, 17),
(19, 'RUN FOR LIFE!!!!', 1506059907, 104, 'Passed', '', 'Personal', '', 1, 17),
(20, '2night Dont Back', 1517610060, 130, 'Available', '', 'Group', 'Dance', 14, 18),
(21, 'Breath ~~', 1517882280, 300, 'Available', '', 'Group', 'Sport', 20, 18),
(22, 'Gym make life', 1519092060, 150, 'Available', '', 'Group', 'Sport', 15, 18),
(23, 'Get Dance Life', 1516154520, 230, 'Full', 'Please bring your sport cloths. don\'t come with non-sport cloths', 'Personal', '', 1, 18),
(24, 'HELP Dance', 1504253720, 80, 'Passed', '', 'Group', 'Dance', 25, 18),
(25, 'Hands Up', 1514512980, 120, 'Available', '', 'Group', 'Dance', 22, 17),
(26, 'RUN No Regret', 1514945160, 90, 'Available', '', 'Group', 'Sport', 10, 17),
(27, 'Muscle Training', 1519697280, 130, 'Available', '', 'Personal', '', 1, 17),
(28, 'Do  Tomorrow', 1520993340, 85, 'Available', '', 'Personal', '', 1, 17),
(29, 'Yoga for Dummy', 1519351920, 75, 'Available', '', 'Group', 'Dance', 10, 17),
(30, 'Gym for Dummy', 1523413080, 89, 'Available', '', 'Personal', '', 1, 17),
(31, '5 to 8', 1520994720, 120, 'Available', '', 'Group', 'Dance', 28, 17),
(32, 'Combat Training', 1517970780, 110, 'Available', '', 'Group', 'MMA', 12, 17),
(33, 'Combat Now', 1518057240, 110, 'Available', '', 'Group', 'MMA', 12, 17),
(34, 'Combat Crush', 1518143700, 110, 'Available', '', 'Group', 'MMA', 12, 17),
(35, 'MMA for Dummy', 1524019200, 90, 'Available', '', 'Personal', '', 1, 17),
(36, 'MMA Reps', 1528771260, 150, 'Available', '', 'Group', 'MMA', 50, 17),
(37, 'Don\'t Die', 1511923320, 99, 'Available', '', 'Group', 'MMA', 10, 17);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `JoinedSessions`
--
ALTER TABLE `JoinedSessions`
  ADD PRIMARY KEY (`joinID`),
  ADD KEY `memberID` (`memberID`),
  ADD KEY `sessionID` (`sessionID`);

--
-- Indexes for table `Member`
--
ALTER TABLE `Member`
  ADD PRIMARY KEY (`memberID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `Review`
--
ALTER TABLE `Review`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `memberID` (`memberID`),
  ADD KEY `sessionID` (`sessionID`);

--
-- Indexes for table `Trainers`
--
ALTER TABLE `Trainers`
  ADD PRIMARY KEY (`trainerID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `TrainingSessions`
--
ALTER TABLE `TrainingSessions`
  ADD PRIMARY KEY (`sessionID`),
  ADD KEY `trainerID` (`trainerID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `JoinedSessions`
--
ALTER TABLE `JoinedSessions`
  MODIFY `joinID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `Member`
--
ALTER TABLE `Member`
  MODIFY `memberID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `Review`
--
ALTER TABLE `Review`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Trainers`
--
ALTER TABLE `Trainers`
  MODIFY `trainerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `TrainingSessions`
--
ALTER TABLE `TrainingSessions`
  MODIFY `sessionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
