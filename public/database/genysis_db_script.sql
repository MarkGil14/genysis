-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2019 at 11:51 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.1.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quickgoods_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `sifcustomer` (
  `Guid` varchar(18) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Channel` varchar(255) NOT NULL,
  `totalAmount` decimal(16,2) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

 
  
CREATE TABLE `sifdsp` (
  `Guid` varchar(18) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `AccountName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
 
 
CREATE TABLE `sifitem` (
  `Guid` varchar(25) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `MaterialGroup` varchar(255) NOT NULL,
  `ProductLine` varchar(255) NOT NULL,
  `Brands` varchar(255) NOT NULL,
  `Variants` varchar(255) NOT NULL,
  `ListPrice` decimal(16,4) NOT NULL,
  `ConversionId` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sas`
--

CREATE TABLE `sifsas` (
  `Guid` varchar(18) NOT NULL,
  `AccountName` varchar(255) NOT NULL,
  `Classification` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sifaccountcontactrelation`
--

CREATE TABLE `sifaccountcontactrelation` (
  `Guid` varchar(36) NOT NULL,
  `AccountId` varchar(18) NOT NULL,
  `ListSasId` longtext NOT NULL,
  `ListDspId` longtext NOT NULL,
  `Status` varchar(100) DEFAULT NULL,
  `ErrorMessage` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `siforder`
--

CREATE TABLE `siforder` (
  `Guid` varchar(36) NOT NULL,
  `Id` varchar(18) DEFAULT NULL,
  `SalesType` varchar(20) NOT NULL,
  `AccountReferenceId` varchar(18) NOT NULL,
  `SASId` varchar(18) NOT NULL,
  `DSPId` varchar(18) NOT NULL,
  `SalesOrderNumber` varchar(100) DEFAULT NULL,
  `OrderDate` date DEFAULT NULL,
  `OrderTotal` float NOT NULL DEFAULT '0',
  `OrderTotalDiscount` float NOT NULL DEFAULT '0',
  `RequestedDeliveryDate` date DEFAULT NULL,
  `PaymentTerm` varchar(100) DEFAULT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `InvoiceTotal` float NOT NULL DEFAULT '0',
  `InvoiceTotalDiscount` float NOT NULL DEFAULT '0',
  `OrderDiscountFromTotal1` float NOT NULL DEFAULT '0',
  `IsOrderDiscountFromTotal1Percent` tinyint(1) NOT NULL DEFAULT '0',
  `OrderDiscountFromTotal1Type` varchar(255) DEFAULT NULL,
  `OrderDiscountFromTotal1Amount` float NOT NULL DEFAULT '0',
  `OrderDiscountFromTotal2` float NOT NULL DEFAULT '0',
  `IsOrderDiscountFromTotal2Percent` tinyint(1) NOT NULL DEFAULT '0',
  `OrderDiscountFromTotal2Type` varchar(255) DEFAULT NULL,
  `OrderDiscountFromTotal2Scheme` varchar(5) DEFAULT NULL,
  `OrderDiscountFromTotal2Amount` float NOT NULL DEFAULT '0',
  `OrderDiscountFromTotal3` float NOT NULL DEFAULT '0',
  `IsOrderDiscountFromTotal3Percent` tinyint(1) NOT NULL DEFAULT '0',
  `OrderDiscountFromTotal3Type` varchar(255) DEFAULT NULL,
  `OrderDiscountFromTotal3Scheme` varchar(5) DEFAULT NULL,
  `OrderDiscountFromTotal3Amount` float NOT NULL DEFAULT '0',
  `OrderDiscountFromTotal4` float NOT NULL DEFAULT '0',
  `IsOrderDiscountFromTotal4Percent` tinyint(1) NOT NULL DEFAULT '0',
  `OrderDiscountFromTotal4Type` varchar(255) DEFAULT NULL,
  `OrderDiscountFromTotal4Scheme` varchar(5) DEFAULT NULL,
  `OrderDiscountFromTotal4Amount` float NOT NULL DEFAULT '0',
  `InvoiceDiscountFromTotal1` float NOT NULL DEFAULT '0',
  `IsInvoiceDiscountFromTotal1Percent` tinyint(1) NOT NULL DEFAULT '0',
  `InvoiceDiscountFromTotal1Type` varchar(255) DEFAULT NULL,
  `InvoiceDiscountFromTotal1Amount` float NOT NULL DEFAULT '0',
  `InvoiceDiscountFromTotal2` float NOT NULL DEFAULT '0',
  `IsInvoiceDiscountFromTotal2Percent` tinyint(1) NOT NULL DEFAULT '0',
  `InvoiceDiscountFromTotal2Type` varchar(255) DEFAULT NULL,
  `InvoiceDiscountFromTotal2Scheme` varchar(5) DEFAULT NULL,
  `InvoiceDiscountFromTotal2Amount` float NOT NULL DEFAULT '0',
  `InvoiceDiscountFromTotal3` float NOT NULL DEFAULT '0',
  `IsInvoiceDiscountFromTotal3Percent` tinyint(1) NOT NULL DEFAULT '0',
  `InvoiceDiscountFromTotal3Type` varchar(255) DEFAULT NULL,
  `InvoiceDiscountFromTotal3Scheme` varchar(5) DEFAULT NULL,
  `InvoiceDiscountFromTotal3Amount` float NOT NULL DEFAULT '0',
  `InvoiceDiscountFromTotal4` float NOT NULL DEFAULT '0',
  `IsInvoiceDiscountFromTotal4Percent` tinyint(1) NOT NULL DEFAULT '0',
  `InvoiceDiscountFromTotal4Scheme` varchar(5) DEFAULT NULL,
  `InvoiceDiscountFromTotal4Amount` float NOT NULL DEFAULT '0',
  `InvoiceDiscountFromTotal4Type` varchar(255) DEFAULT NULL,
  `DiscountFromTotal` decimal(16,2) DEFAULT NULL,
  `IsDiscountFromTotalPercent` tinyint(1) DEFAULT '0',
  `Status` varchar(100) DEFAULT NULL,
  `ErrorMessage` longtext,
  `TransactionId` varchar(20) NOT NULL DEFAULT '',
  `OrderNo` int(11) NOT NULL,
  `IsDelete` tinyint(4) NOT NULL,
  `CreatedByID` varchar(18) DEFAULT NULL,
  `EncodedDate` date DEFAULT NULL,
  `KeyId` varchar(36) NOT NULL,
  `area` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `siforderitem`
--

CREATE TABLE `siforderitem` (
  `Guid` varchar(36) NOT NULL,
  `OrderReferenceGuid` varchar(36) NOT NULL,
  `Id` varchar(18) DEFAULT NULL,
  `OrderId` varchar(18) DEFAULT NULL,
  `ProductId` varchar(100) NOT NULL,
  `ConversionId` varchar(18) NOT NULL,
  `OrderQuantity` float DEFAULT NULL,
  `OrderPrice` decimal(16,2) DEFAULT NULL,
  `OrderDiscount1` decimal(16,2) DEFAULT '0.00',
  `IsOrderDiscount1Percent` tinyint(1) DEFAULT '0',
  `OrderDiscount1Type` varchar(255) DEFAULT NULL,
  `OrderDiscount1Amount` float NOT NULL DEFAULT '0',
  `OrderDiscount2` decimal(16,2) DEFAULT '0.00',
  `IsOrderDiscount2Percent` tinyint(1) DEFAULT '0',
  `OrderDiscount2Type` varchar(255) DEFAULT NULL,
  `OrderDiscount2Scheme` varchar(5) DEFAULT NULL,
  `OrderDiscount2Amount` float NOT NULL DEFAULT '0',
  `OrderDiscount3` decimal(16,2) DEFAULT '0.00',
  `IsOrderDiscount3Percent` tinyint(1) DEFAULT '0',
  `OrderDiscount3Type` varchar(255) DEFAULT NULL,
  `OrderDiscount3Scheme` varchar(5) DEFAULT NULL,
  `OrderDiscount3Amount` float NOT NULL DEFAULT '0',
  `OrderDiscount4` decimal(16,2) DEFAULT '0.00',
  `IsOrderDiscount4Percent` tinyint(1) DEFAULT '0',
  `OrderDiscount4Type` varchar(255) DEFAULT NULL,
  `OrderDiscount4Scheme` varchar(5) DEFAULT NULL,
  `OrderDiscount4Amount` float NOT NULL DEFAULT '0',
  `InvoiceQuantity` decimal(16,0) DEFAULT NULL,
  `InvoicePrice` decimal(16,2) DEFAULT NULL,
  `InvoiceDiscount1` decimal(16,2) DEFAULT '0.00',
  `IsInvoiceDiscount1Percent` tinyint(1) DEFAULT '0',
  `InvoiceDiscount1Type` varchar(255) DEFAULT NULL,
  `InvoiceDiscount1Amount` float NOT NULL DEFAULT '0',
  `InvoiceDiscount2` decimal(16,2) DEFAULT '0.00',
  `IsInvoiceDiscount2Percent` tinyint(1) DEFAULT '0',
  `InvoiceDiscount2Type` varchar(255) DEFAULT NULL,
  `InvoiceDiscount2Scheme` varchar(5) DEFAULT NULL,
  `InvoiceDiscount2Amount` float NOT NULL DEFAULT '0',
  `InvoiceDiscount3` decimal(16,2) DEFAULT '0.00',
  `IsInvoiceDiscount3Percent` tinyint(1) DEFAULT '0',
  `InvoiceDiscount3Type` varchar(255) DEFAULT NULL,
  `InvoiceDiscount3Scheme` varchar(5) DEFAULT NULL,
  `InvoiceDiscount3Amount` float NOT NULL DEFAULT '0',
  `InvoiceDiscount4` decimal(16,2) DEFAULT '0.00',
  `IsInvoiceDiscount4Percent` tinyint(1) DEFAULT '0',
  `InvoiceDiscount4Type` varchar(255) DEFAULT NULL,
  `InvoiceDiscount4Scheme` varchar(5) DEFAULT NULL,
  `InvoiceDiscount4Amount` float NOT NULL DEFAULT '0',
  `WeightUOM` varchar(50) DEFAULT NULL,
  `ActualWeightQuantity` float NOT NULL,
  `TransactionId` varchar(20) NOT NULL DEFAULT '',
  `OrderNo` int(11) NOT NULL,
  `IsDelete` tinyint(4) DEFAULT NULL,
  `ReasonUnequalOrderInvoice` varchar(255) DEFAULT NULL,
  `Status` varchar(100) DEFAULT NULL,
  `ErrorMessage` longtext,
  `ReferenceKeyId` varchar(36) NOT NULL,
  `backorder` int(5) NOT NULL,
  `update_count` int(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `siforderreturn`
--

CREATE TABLE `siforderreturn` (
  `Guid` varchar(36) NOT NULL,
  `Id` varchar(18) DEFAULT NULL,
  `SASId` varchar(18) DEFAULT NULL,
  `DSPId` varchar(18) DEFAULT NULL,
  `AccountId` varchar(18) DEFAULT NULL,
  `TypeOfReturn` enum('Outright','Trade') DEFAULT NULL,
  `CreditMemoNumber` varchar(255) DEFAULT NULL,
  `ReturnDate` date DEFAULT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `EncodedDate` datetime(3) DEFAULT NULL,
  `Status` varchar(100) DEFAULT NULL,
  `ErrorMessage` longtext,
  `TransactionId` varchar(36) DEFAULT NULL,
  `OrderNo` int(11) NOT NULL,
  `IsDelete` tinyint(4) DEFAULT NULL,
  `ReasonOfReturn` varchar(255) DEFAULT NULL,
  `KeyId` varchar(36) NOT NULL,
  `area` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `siforderreturnitem`
--

CREATE TABLE `siforderreturnitem` (
  `Guid` varchar(36) NOT NULL,
  `Id` varchar(18) DEFAULT NULL,
  `OrderReturnGuid` varchar(36) DEFAULT NULL,
  `OrderReturnId` varchar(18) DEFAULT NULL,
  `ProductId` varchar(100) DEFAULT NULL,
  `ConversionId` varchar(18) DEFAULT NULL,
  `ReturnedQuantity` decimal(16,2) DEFAULT NULL,
  `Price` decimal(16,4) DEFAULT NULL,
  `DiscountAmount` decimal(16,2) DEFAULT '0.00',
  `Condition` varchar(255) DEFAULT NULL,
  `ReturnType` varchar(255) DEFAULT NULL,
  `ReasonOfRejection` varchar(255) DEFAULT NULL,
  `Status` varchar(100) DEFAULT NULL,
  `ErrorMessage` longtext,
  `TransactionId` varchar(36) DEFAULT NULL,
  `IsDelete` tinyint(4) DEFAULT NULL,
  `ReferenceKeyId` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

 
  
ALTER TABLE `sifcustomer`
  ADD PRIMARY KEY (`Guid`);

 
--
-- Indexes for table `dsp`
--
ALTER TABLE `sifdsp`
  ADD PRIMARY KEY (`Guid`);

 
--
-- Indexes for table `item`
--
ALTER TABLE `sifitem`
  ADD PRIMARY KEY (`Guid`);

--
-- Indexes for table `sas`
--
ALTER TABLE `sifsas`
  ADD PRIMARY KEY (`Guid`);

--
-- Indexes for table `sifaccountcontactrelation`
--
ALTER TABLE `sifaccountcontactrelation`
  ADD PRIMARY KEY (`Guid`);

--
-- Indexes for table `siforder`
--
ALTER TABLE `siforder`
  ADD PRIMARY KEY (`Guid`),
  ADD UNIQUE KEY `keyId` (`KeyId`),
  ADD UNIQUE KEY `OrderNo` (`OrderNo`);

--
-- Indexes for table `siforderitem`
--
ALTER TABLE `siforderitem`
  ADD PRIMARY KEY (`Guid`),
  ADD KEY `OrderReferenceGuid` (`OrderReferenceGuid`),
  ADD KEY `keyReferenceId` (`ReferenceKeyId`),
  ADD UNIQUE KEY `OrderNo` (`OrderNo`);

--
-- Indexes for table `siforderreturn`
--
ALTER TABLE `siforderreturn`
  ADD PRIMARY KEY (`Guid`),
  ADD UNIQUE KEY `KeyId` (`KeyId`),
  ADD UNIQUE KEY `OrderNo` (`OrderNo`);

--
-- Indexes for table `siforderreturnitem`
--
ALTER TABLE `siforderreturnitem`
  ADD PRIMARY KEY (`Guid`),
  ADD KEY `OrderReturnId` (`OrderReturnId`),
  ADD KEY `OrderReturnGuid` (`OrderReturnGuid`),
  ADD KEY `ReferenceKeyId` (`ReferenceKeyId`);
 
--
-- AUTO_INCREMENT for table `siforder`
--
ALTER TABLE `siforder`
  MODIFY `OrderNo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `siforderitenm`
--
ALTER TABLE `siforderitem`
  MODIFY `OrderNo` int(11) NOT NULL AUTO_INCREMENT;


--
-- AUTO_INCREMENT for table `siforderreturn`
--
ALTER TABLE `siforderreturn`
  MODIFY `OrderNo` int(11) NOT NULL AUTO_INCREMENT;

 

--
-- Constraints for table `siforderitem`
--
ALTER TABLE `siforderitem`
  ADD CONSTRAINT `siforderitem_ibfk_1` FOREIGN KEY (`ReferenceKeyId`) REFERENCES `siforder` (`KeyId`),
  ADD CONSTRAINT `siforderitem_siforder_fk` FOREIGN KEY (`OrderReferenceGuid`) REFERENCES `siforder` (`Guid`) ON UPDATE CASCADE;

--
-- Constraints for table `siforderreturnitem`
--
ALTER TABLE `siforderreturnitem`
  ADD CONSTRAINT `siforderreturnitem_ibfk_1` FOREIGN KEY (`OrderReturnGuid`) REFERENCES `siforderreturn` (`Guid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `siforderreturnitem_ibfk_2` FOREIGN KEY (`ReferenceKeyId`) REFERENCES `siforderreturn` (`KeyId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
