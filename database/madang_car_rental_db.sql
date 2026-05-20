
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2026 at 07:14 AM
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
-- Database: `madang_car_rental_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `CarID` int(11) NOT NULL,
  `CarModel` varchar(100) DEFAULT NULL,
  `Brand` varchar(50) DEFAULT NULL,
  `CarType` varchar(50) DEFAULT NULL,
  `RegistrationNumber` varchar(50) DEFAULT NULL,
  `RentalPrice` decimal(10,2) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Address` varchar(150) DEFAULT NULL,
  `DriverLicenseNo` varchar(100) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` varchar(20) DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `MaintenanceID` int(11) NOT NULL,
  `CarID` int(11) DEFAULT NULL,
  `StaffID` int(11) DEFAULT NULL,
  `ServiceDate` date DEFAULT NULL,
  `ServiceType` varchar(100) DEFAULT NULL,
  `Cost` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL,
  `RentalID` int(11) DEFAULT NULL,
  `PaymentDate` date DEFAULT NULL,
  `AmountPaid` decimal(10,2) DEFAULT NULL,
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `PaymentStatus` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `RentalID` int(11) NOT NULL,
  `ReservationID` int(11) DEFAULT NULL,
  `RouteID` int(11) DEFAULT NULL,
  `RentalStartDate` date DEFAULT NULL,
  `RentalEndDate` date DEFAULT NULL,
  `TotalCost` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `ReservationID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `CarID` int(11) DEFAULT NULL,
  `ReservationDate` date DEFAULT NULL,
  `PickupDATE` date DEFAULT NULL,
  `ReturnDate` date DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `RouteID` int(11) NOT NULL,
  `StartLocation` varchar(100) DEFAULT NULL,
  `EndLocation` varchar(100) DEFAULT NULL,
  `Distance` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffID` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Role` varchar(50) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`CarID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`MaintenanceID`),
  ADD KEY `CarID` (`CarID`),
  ADD KEY `StaffID` (`StaffID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `RentalID` (`RentalID`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`RentalID`),
  ADD KEY `ReservationID` (`ReservationID`),
  ADD KEY `RouteID` (`RouteID`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ReservationID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `CarID` (`CarID`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`RouteID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`StaffID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `CarID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `MaintenanceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rental`
--
ALTER TABLE `rental`
  MODIFY `RentalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ReservationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `RouteID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `StaffID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`),
  ADD CONSTRAINT `maintenance_ibfk_2` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`StaffID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`RentalID`) REFERENCES `rental` (`RentalID`);

--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `rental_ibfk_1` FOREIGN KEY (`ReservationID`) REFERENCES `reservation` (`ReservationID`),
  ADD CONSTRAINT `rental_ibfk_2` FOREIGN KEY (`RouteID`) REFERENCES `route` (`RouteID`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`);
COMMIT;


--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `LastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- 1. Safely remove the temporary users table you added at the bottom
DROP TABLE IF EXISTS `users`;

-- 2. Drop the existing staff and customer tables to clear old constraints
-- (We drop these first because reservation/maintenance depend on them)
DROP TABLE IF EXISTS `maintenance`;
DROP TABLE IF EXISTS `reservation`;
DROP TABLE IF EXISTS `customer`;
DROP TABLE IF EXISTS `staff`;

-- 3. Re-create Customer with the correct columns and login fields built-in
CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Address` varchar(150) DEFAULT NULL,
  `DriverLicenseNo` varchar(100) DEFAULT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` varchar(20) DEFAULT 'customer',
  PRIMARY KEY (`CustomerID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Re-create Staff with login fields built-in
CREATE TABLE `staff` (
  `StaffID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` varchar(50) DEFAULT 'staff',
  PRIMARY KEY (`StaffID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. Put back the dependent tables (Reservation and Maintenance)
CREATE TABLE `reservation` (
  `ReservationID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) DEFAULT NULL,
  `CarID` int(11) DEFAULT NULL,
  `ReservationDate` date DEFAULT NULL,
  `PickupDATE` date DEFAULT NULL,
  `ReturnDate` date DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ReservationID`),
  KEY `CustomerID` (`CustomerID`),
  KEY `CarID` (`CarID`),
  CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `maintenance` (
  `MaintenanceID` int(11) NOT NULL AUTO_INCREMENT,
  `CarID` int(11) DEFAULT NULL,
  `StaffID` int(11) DEFAULT NULL,
  `ServiceDate` date DEFAULT NULL,
  `ServiceType` varchar(100) DEFAULT NULL,
  `Cost` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`MaintenanceID`),
  KEY `CarID` (`CarID`),
  KEY `StaffID` (`StaffID`),
  CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`),
  CONSTRAINT `maintenance_ibfk_2` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`StaffID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
