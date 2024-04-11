-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2023 at 08:51 PM
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
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, 'admin', 'f865b53623b121fd34ee5426c792e5c33af8c227');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(10) NOT NULL,
  `image_01` varchar(100) NOT NULL,
  `image_02` varchar(100) NOT NULL,
  `image_03` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `image_01`, `image_02`, `image_03`,`quantity`) VALUES
(1, 'Mouse', '1000dpi sensitivity - Smooth & accuracy cursor control.\r\n\r\nEase of setup-Simply plug & Play.\r\n\r\nAmbidexfrous Shaped - Can Be used comfortably in either hand.', 500, 'mouse-1.webp', 'mouse-2.webp', 'mouse-3.webp','10'),
(2, 'Laptop', 'OS: Windows 10 Home\r\nProcessor: Intel Celeron J4125 Quad Core Upto 2.7GHz Quad-Thread 14nm\r\nDisplay: IPS 13.0 inch 2160*1440 Pixel\r\nRAM: LPDDR4 8GB\r\nROM: 256GB SSD support 1TB Max\r\nBattery: 38Wh / 5000mAh (upto 6-8hrs.)\r\nCamera: 1.0MP\r\nWLAN: 2.4/5G Dual Band WiFi\r\nGPU: Intel HD Graphics 600\r\nBrand New Laptop', 60000, 'laptop-1.webp', 'laptop-2.webp', 'laptop-3.webp'),
(3, 'Camera', '30.4MP CMOS\r\nDual Pixel RAW\r\nDual Pixel CMOS AF\r\n7 frame per second\r\n150,000 pixel metering sensor\r\nGPS\r\nWi-Fi / NFC\r\nEOS Movie 4K + Full HD', 100000, 'camera-1.webp', 'camera-2.webp', 'camera-3.webp'),
(4, 'Fridge', 'Smart Inverter Compressor\r\nMulti Air Flow\r\n100~290V LVS\r\nMoist Balance Crisper™\r\nTop LED', 50000, 'fridge-1.webp', 'fridge-2.webp', 'fridge-3.webp'),
(5, 'Mixer', 'Brand: Baltra\r\nModel: Stylo-3 BMG134\r\n2/3 SS Jar\r\n1.2l Blending Jar\r\n800ml Dry Grinding\r\n400ml Chutney Jar\r\n3 Speed Control with Incher\r\nHigh Efficiency SS Blander\r\n500watt\r\n2 years warranty', 5000, 'mixer-1.webp', 'mixer-2.webp', 'mixer-3.webp'),
(6, 'Smartphone', 'Display: 6.71&#34; Dot Drop display\r\nResolution: 1650 × 720 HD+\r\nProcessor: MediaTek Helio G85 12nm chipset, octa-core CPU: up to 2.0GHz\r\nRear Camera: 50MP main camera, f/1.8\r\nFront Camera: 5MP front camera, f/2.2\r\nSecurity & Authentication: Rear fingerprint sensor + AI Face unlock\r\nNetwork & Connectivity: Dual SIM + MicroSD\r\nNavigation & Positioning: GPS | Glonass | Galileo | Beidou\r\nAudio: 3.5mm headphone jack\r\nSensor: Virtual Proximity | Accelerometer\r\nOperating System: MIUI 13 based on Andro', 25000, 'smartphone-1.webp', 'smartphone-2.webp', 'smartphone-3.webp'),
(7, 'Tv', 'A+ Grade Panel\r\nInbuilt Woofer (dolby surround)\r\n20W speakers Dolby™\r\nAudio: 20W, Stereo Sound\r\nUltra wide Viewing Angle nearly 180 degrees\r\nAntenna In\r\n2 Usb In\r\n2 HDMl In\r\nVga In\r\nPc Audio In\r\nA/V(Rca In, 1 Earphone Out)\r\nUp To 4K Video Support\r\nAccessories: Remote Controller, User Manual, 2*AAA battery, 1*wall Mount\r\n3 Years Warranty', 50000, 'tv-01.webp', 'tv-02.webp', 'tv-03.webp'),
(8, 'Washing Machine', 'Capacity: 7KG\r\nControl & System: LED Digital Display, Automatic Touch Panel\r\nFunction: Single Tub, wash & post wash spin dry\r\nAuto Power Off\r\nDynamic Soaking\r\nSmart Design\r\nQuiet/Low Noise Operation System\r\nLow Power Consumption\r\nWater Recycling\r\nChild Lock\r\nMagic Filter\r\nAcousto-Optic Alarm System\r\nInternal Drum Material: Stainless Steel\r\nOuter Drum Material: PP/Glass Fibre\r\nSize: 518*530*865/595*470*845\r\nVoltage: 220V/50Hz\r\nWattage: 2150W\r\nNet Weight: 60 kg\r\nSelf unbalance diagnosis\r\n24 hours ', 125000, 'washing machine-1.webp', 'washing machine-2.webp', 'washing machine-3.webp'),
(9, 'Watch', '100% Genuine (The SELLER guarantees the authenticity of the product)\r\nBrand : Millenium\r\nModel Number : 58014\r\nWater Resistant : 3 ATM\r\nDial Display Type: Analog\r\nWarranty : 1 year', 1000, 'watch-1.webp', 'watch-2.webp', 'watch-3.webp');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
