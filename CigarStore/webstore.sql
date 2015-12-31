-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2015 at 10:51 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `webstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `cigar`
--

CREATE TABLE IF NOT EXISTS `cigar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `units` int(11) NOT NULL,
  `price` double NOT NULL,
  `image` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `cigar`
--

INSERT INTO `cigar` (`id`, `brand`, `name`, `units`, `price`, `image`) VALUES
(1, 'Privada Capa Habano', 'A Flores 1975 Serie', 30, 2316, 'images/privada.jpg'),
(2, 'Montecristo Jacopo', 'Monte', 30, 2658, 'images/monte.jpg'),
(3, 'Ashton Estate', 'Sun Grown 22-Year Salute', 30, 2836, 'images/ashton.jpg'),
(4, 'Padron Family Reserve', '50 Years Maduro', 30, 3287, 'images/padron.jpg'),
(5, 'Fuente Fuente', 'OpusX PerfecXion X', 30, 3564, 'images/opus.png'),
(6, 'Rocky Patel', 'Royale Toro', 30, 2632, 'images/royale.jpg'),
(7, 'Hoyo de Monterrey', 'Epicure Especial', 30, 2879, 'images/especial.png'),
(8, 'Illusione Fume', 'dAmour Clementes', 30, 3164, 'images/illusione.jpg'),
(9, 'Montecristo Jacopo', 'Oliva Serie V', 30, 5682, 'images/oliva.jpg'),
(10, 'E.P. Carrillo', 'La Historia', 30, 5689, 'images/carrillo.jpg'),
(11, 'Camel', 'Lights', 30, 315, 'images/lights.jpg'),
(12, 'Camel', 'Filters', 30, 330, 'images/filters.jpg'),
(13, 'Marlboro', 'Gold', 30, 350, 'images/gold.jpg'),
(14, 'Marlboro', 'White Menthol', 30, 370, 'images/white.jpg'),
(15, 'Dunhill', 'Light', 30, 400, 'images/dlight.jpg'),
(16, 'Dunhill', 'Switch', 30, 430, 'images/switch.jpg'),
(17, 'Rothmans', 'Red', 30, 280, 'images/rred.jpg'),
(18, 'Rothmans', 'Blue', 30, 280, 'images/rblue.jpg'),
(19, 'Zippo', 'Genuine Zippo W', 30, 429, 'images/zippo.jpg'),
(20, 'Xikar', 'Xi2', 30, 742, 'images/xikar.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` varchar(15) NOT NULL,
  `delivery_date` varchar(15) NOT NULL,
  `order_ref` varchar(15) NOT NULL,
  `total` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_line`
--

CREATE TABLE IF NOT EXISTS `order_line` (
  `order_line_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `cigar_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `in_process` int(5) NOT NULL,
  PRIMARY KEY (`order_line_id`),
  KEY `order_id` (`order_id`,`cigar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(45) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(30) NOT NULL,
  `postal_code` int(11) NOT NULL,
  `password` varchar(30) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
