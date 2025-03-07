-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 12:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ru_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `EID` varchar(50) NOT NULL,
  `current_address` text NOT NULL,
  `permanent_address` text NOT NULL,
  `DOB` date NOT NULL,
  `profile` varchar(255) DEFAULT 'default.png',
  `password` varchar(255) NOT NULL,
  `user_role` enum('admin','librarian') DEFAULT 'librarian',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`id`, `name`, `email`, `phone`, `EID`, `current_address`, `permanent_address`, `DOB`, `profile`, `password`, `user_role`, `created_at`) VALUES
(1, 'Admin User', 'admin@example.com', '9876543210', 'EID12345', 'Admin Street, City', 'Admin Permanent Address', '1985-08-20', 'default.png', '$2y$10$rcEwAhey7G6xBLkvAY6Qv.egbFNdjFROoQaWT67p6izE9Gri03UM.', 'librarian', '2025-02-12 15:42:16'),
(2, 'Admin', 'admin@gmail.com', '7896541230', 'ADMIN123', 'udaipur', 'udaipur', '2002-02-02', 'default.png', '$2y$10$rcEwAhey7G6xBLkvAY6Qv.egbFNdjFROoQaWT67p6izE9Gri03UM.', 'admin', '2025-02-15 17:41:13');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(50) NOT NULL,
  `publication_year` int(11) NOT NULL,
  `total_issued` int(11) NOT NULL DEFAULT 0,
  `status` enum('available','not_available') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `isbn`, `publication_year`, `total_issued`, `status`, `created_at`) VALUES
('1', 'The Excel Bible', 'John Walkenbach', '978-1118490365', 2013, 2, 'available', '2025-02-18 19:07:57'),
('10', 'Excel Macros For Dummies', 'Dick Kusleika', '978-1119469612', 2018, 5, 'available', '2025-02-18 19:07:57'),
('11', 'Excel for Accountants', 'Conrad George Carlberg', '978-0789739307', 2010, 1, 'available', '2025-02-18 19:07:57'),
('12', 'Advanced Excel Reporting for Management', 'Neale Blackwood', '978-1118657720', 2014, 1, 'available', '2025-02-18 19:07:57'),
('13', 'Excel Quick Start Guide', 'M.L. Humphrey', '978-1979214809', 2017, 0, 'available', '2025-02-18 19:07:57'),
('14', 'Microsoft Excel Inside Out', 'Mark Dodge', '978-0735623210', 2013, 0, 'available', '2025-02-18 19:07:57'),
('15', 'Excel 2019 Basics', 'Lalwani Lokesh', '978-9389611290', 2019, 0, 'available', '2025-02-18 19:07:57'),
('16', 'The Definitive Guide to DAX', 'Marco Russo', '978-1509306978', 2019, 0, 'available', '2025-02-18 19:07:57'),
('17', 'Excel Financial Modeling', 'Danielle Stein Fairhurst', '978-1119520382', 2019, 0, 'available', '2025-02-18 19:07:57'),
('18', 'Excel Automation with VBA', 'Richard Shepherd', '978-0071743729', 2011, 0, 'available', '2025-02-18 19:07:57'),
('19', 'Excel Formulas 101', 'Oz du Soleil', '978-1615470551', 2015, 0, 'available', '2025-02-18 19:07:57'),
('2', 'Excel Formulas &amp; Functions for Dummies', 'Ken Bluttman', '978-1119518259', 2018, 0, 'available', '2025-02-18 19:07:57'),
('20', 'Microsoft Excel Step by Step', 'Curtis Frye', '978-1509307753', 2020, 0, 'available', '2025-02-18 19:07:57'),
('21', 'Excel Power Pivot and Power Query', 'Leila Gharani', '978-1615472319', 2021, 0, 'available', '2025-02-18 19:07:57'),
('22', 'Excel for Engineers and Scientists', 'Ronald E. Walpole', '978-0131481976', 2006, 0, 'available', '2025-02-18 19:07:57'),
('23', 'Microsoft Excel 365 Formulas', 'Paul McFedries', '978-0789758759', 2021, 0, 'available', '2025-02-18 19:07:57'),
('24', 'Excel Data Analysis Made Simple', 'Ankur Jain', '978-1787287706', 2017, 0, 'available', '2025-02-18 19:07:57'),
('25', 'Financial Analysis with Excel', 'Timothy R. Mayes', '978-0357108130', 2019, 0, 'available', '2025-02-18 19:07:57'),
('26', 'Data Visualization with Excel', 'Jonathan Schwabish', '978-0231188651', 2021, 0, 'available', '2025-02-18 19:07:57'),
('27', 'Excel in Depth', 'Bill Jelen', '978-0789747074', 2013, 0, 'available', '2025-02-18 19:07:57'),
('28', 'Excel for Scientists and Engineers', 'E. Joseph Billo', '978-0470381236', 2007, 0, 'available', '2025-02-18 19:07:57'),
('29', 'Excel Statistical Analysis', 'Joseph Schmuller', '978-1119271161', 2017, 0, 'available', '2025-02-18 19:07:57'),
('3', 'Excel Power Query and Power Pivot', 'Chris Dutton', '978-1119518253', 2020, 0, 'available', '2025-02-18 19:07:57'),
('30', 'Excel for Beginners', 'John Wiley', '978-1119476154', 2016, 0, 'available', '2025-02-18 19:07:57'),
('4', 'Excel 2019 Power Programming', 'Michael Alexander', '978-1119514923', 2019, 0, 'available', '2025-02-18 19:07:57'),
('5', 'Excel Data Analysis For Dummies', 'Stephen L. Nelson', '978-1119518167', 2020, 0, 'available', '2025-02-18 19:07:57'),
('6', 'Learn Excel in 24 Hours', 'Alex Nordeen', '978-9389845343', 2021, 0, 'available', '2025-02-18 19:07:57'),
('7', 'Excel 2016 for Beginners', 'M.L. Humphrey', '978-1975685979', 2016, 0, 'available', '2025-02-18 19:07:57'),
('8', 'Microsoft Excel Functions &amp; Formulas', 'Bernd Held', '978-1933241016', 2014, 0, 'available', '2025-02-18 19:07:57'),
('9', 'Excel Dashboards and Reports', 'Michael Alexander', '978-1119514756', 2019, 0, 'available', '2025-02-18 19:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `book_id` varchar(50) NOT NULL,
  `EnrollmentNo` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `issued_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `book_id`, `EnrollmentNo`, `title`, `issued_date`) VALUES
(23, '1', '24MCA009', 'The Excel Bible', '2025-03-07'),
(24, '1', '24MCA009', 'The Excel Bible', '2025-03-07'),
(25, '10', '24MCA009', 'Excel Macros For Dummies', '2025-03-07'),
(26, '10', '24MCA009', 'Excel Macros For Dummies', '2025-03-07'),
(27, '11', '24MCA009', 'Excel for Accountants', '2025-03-07'),
(28, '12', '24MCA009', 'Advanced Excel Reporting for Management', '2025-03-07'),
(29, '10', '24MCA009', 'Excel Macros For Dummies', '2025-03-07'),
(30, '10', '24MCA009', 'Excel Macros For Dummies', '2025-03-07'),
(31, '10', '24MCA009', 'Excel Macros For Dummies', '2025-03-07');

-- --------------------------------------------------------

--
-- Table structure for table `issued_books`
--

CREATE TABLE `issued_books` (
  `id` int(11) NOT NULL,
  `book_id` varchar(50) DEFAULT NULL,
  `EnrollmentNo` varchar(50) DEFAULT NULL,
  `issued_date` date DEFAULT NULL,
  `last_date` date DEFAULT NULL,
  `renew_left` int(11) DEFAULT 3,
  `fine` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `issued_books`
--
DELIMITER $$
CREATE TRIGGER `after_delete_issued_books` AFTER DELETE ON `issued_books` FOR EACH ROW BEGIN
    -- Insert return log into history table
    INSERT INTO history (book_id, EnrollmentNo, title, issued_date)
    SELECT OLD.book_id, u.EnrollmentNo, b.title, OLD.issued_date
    FROM users u
    JOIN books b ON b.book_id = OLD.book_id
    WHERE u.EnrollmentNo = OLD.EnrollmentNo;

    -- Update book status to 'available' when returned
    UPDATE books 
    SET status = 'available' 
    WHERE book_id = OLD.book_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_issued_books` AFTER INSERT ON `issued_books` FOR EACH ROW BEGIN
    -- Update book status to 'unavailable' when issued
    UPDATE books 
    SET status = 'not_available' 
    WHERE book_id = NEW.book_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `EnrollmentNo` varchar(50) NOT NULL,
  `noti_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('unseen','seen') DEFAULT 'unseen',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `EnrollmentNo`, `noti_title`, `description`, `status`, `created_at`, `seen_at`) VALUES
(2, '24MCA009', 'book_issued', 'Your Book ID: 10 has been issued successfully. Due date: 2025-03-21.', 'unseen', '2025-03-07 10:49:31', NULL),
(3, '24MCA009', 'Book_returned', 'Your Book ID: 10 has been returned successfully.', 'unseen', '2025-03-07 10:52:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `EnrollmentNo` varchar(20) NOT NULL,
  `StudentName` varchar(100) NOT NULL,
  `PhoneStudent` varchar(15) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `EmailAlternate` varchar(100) NOT NULL,
  `AcademicYearName` varchar(10) NOT NULL,
  `ProgramName` varchar(50) NOT NULL,
  `Semester` int(11) NOT NULL,
  `password` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`EnrollmentNo`, `StudentName`, `PhoneStudent`, `Email`, `EmailAlternate`, `AcademicYearName`, `ProgramName`, `Semester`, `password`) VALUES
('24MCA009', 'Renish Limbasiya', '8799039344', 'rlimbasiya090@gmail.com', '24MCA009@raiuniversity.edu', '2024-25', 'RSE - MCA', 2, '$2y$10$9dyT9BWjwq/yMwd4H.k22OL8qHsxTqgMVggEo25E7JrBDtL7lZE.6'),
('24MCA021', 'Vikas Meena', '8107557454', 'meena.vikas1102@gmail.com', '24MCA021@raiuniversity.edu', '2024-25', 'RSE-MCA', 2, 'mRFfKtk4'),
('test', 'test', 'test', 'ktopiya027@rku.ac.in', 'test', 'test', 'test', 2, '$2y$10$rcEwAhey7G6xBLkvAY6Qv.egbFNdjFROoQaWT67p6izE9Gri03UM.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `EID` (`EID`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD UNIQUE KEY `book_id` (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `EnrollmentNo` (`EnrollmentNo`);

--
-- Indexes for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `EnrollmentNo` (`EnrollmentNo`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`EnrollmentNo`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `EmailAlternate` (`EmailAlternate`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `issued_books`
--
ALTER TABLE `issued_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `history_ibfk_2` FOREIGN KEY (`EnrollmentNo`) REFERENCES `users` (`EnrollmentNo`) ON DELETE CASCADE;

--
-- Constraints for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD CONSTRAINT `issued_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `issued_books_ibfk_2` FOREIGN KEY (`EnrollmentNo`) REFERENCES `users` (`EnrollmentNo`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `delete_old_notifications` ON SCHEDULE EVERY 1 DAY STARTS '2025-03-07 16:03:30' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM notifications WHERE status = 'seen' AND seen_at <= NOW() - INTERVAL 5 DAY;
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
