-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2025 at 04:21 PM
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
-- Database: `inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `adjustment`
--

CREATE TABLE `adjustment` (
  `adjustment_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `type` int(1) NOT NULL COMMENT '0 = inventory\r\n1 = value',
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(20) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `status`) VALUES
(1, 'esther ', 1),
(2, 'Ji3', 1),
(3, 'f', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(5) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `cname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '0 = disable\r\n1 = enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `fname`, `lname`, `cname`, `email`, `phone`, `address`, `status`) VALUES
(1, 'ZDU1N3hBakhMNEc0NjFNb2lrc2Z6UT09OjriJxMVJWmnwcacTWM7h5KT', 'NW8vS1puV09QQmNzVEU2MFZlc3B1dz09OjqoviHczX8UamJSXsfcwYXV', 'V3JwTE1uVzNzVUh4RDVWREpUUlVoUT09OjoH4EPYYKtz8jhOy3z+WKj4', 'WTRIM2tGcmxzd29uWWtaa2JYUXF2VnBhazE1QTVvQ1VYRmc5T0k5Z0xDbz06Opl7vm0PB7lg+AI8KbzXniw=', 'MGY1SkxJRU9SSDVTR2lFY1FPbDBhZz09OjrP8oSY71smgcFAw0UXaC6L', 'eDlmSlVxMWR1aElCR0cyaTVRQUZ1QT09OjpSLzrrfkxIUHO4zm7Cbd4m', 1);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expenses_id` int(5) NOT NULL,
  `supplier_id` int(5) NOT NULL,
  `payment_status` int(1) NOT NULL COMMENT '0 = unpaid\r\n1 = paid',
  `expenses_amount` float NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expenses_id`, `supplier_id`, `payment_status`, `expenses_amount`, `date`) VALUES
(3, 6, 0, 3125, '2025-01-22');

-- --------------------------------------------------------

--
-- Table structure for table `expenses_item`
--

CREATE TABLE `expenses_item` (
  `expenses_item_id` int(11) NOT NULL,
  `expenses_id` int(5) NOT NULL,
  `item_id` int(5) NOT NULL,
  `quantity` int(5) NOT NULL,
  `item_cost` float NOT NULL,
  `total_cost` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses_item`
--

INSERT INTO `expenses_item` (`expenses_item_id`, `expenses_id`, `item_id`, `quantity`, `item_cost`, `total_cost`) VALUES
(5, 3, 2, 25, 55, 1375),
(6, 3, 3, 50, 30, 1500),
(7, 3, 1, 10, 25, 250);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `item_id` int(5) NOT NULL,
  `opening_stock` int(5) NOT NULL,
  `current_stock` int(5) NOT NULL,
  `reserved_stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `item_id`, `opening_stock`, `current_stock`, `reserved_stock`) VALUES
(1, 1, 555, 500, 20),
(2, 2, 24, 50, 0),
(3, 3, 252, 255, 0);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_adjustment`
--

CREATE TABLE `inventory_adjustment` (
  `inventory_adjustment_id` int(5) NOT NULL,
  `item_id` int(5) NOT NULL,
  `adjustment_id` int(5) NOT NULL,
  `adjustment_amount` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `order_id` int(5) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(20) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` float NOT NULL,
  `item_image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(20) NOT NULL,
  `item_cost` float NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `item_name`, `item_price`, `item_image`, `description`, `category_id`, `item_cost`, `status`) VALUES
(1, 'Milo ', 150, 'Court.jpg', '255', 1, 25, 1),
(2, 'Coffee', 59, 'TCC.png', '25', 2, 55, 1),
(3, 'Nescafe', 44, 'TCC Logo.png', '25', 3, 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int(5) NOT NULL,
  `order_id` int(5) NOT NULL,
  `item_id` int(5) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `reserved_quantity` int(5) NOT NULL,
  `quantity` int(5) NOT NULL,
  `item_price` float NOT NULL,
  `total_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_id`, `item_id`, `item_name`, `reserved_quantity`, `quantity`, `item_price`, `total_price`) VALUES
(1, 1, 1, 'Milo ', 0, 200, 150, 30000),
(2, 2, 1, 'Milo ', 0, 20, 150, 3000);

-- --------------------------------------------------------

--
-- Table structure for table `price_adjustment`
--

CREATE TABLE `price_adjustment` (
  `price_adjustment_id` int(5) NOT NULL,
  `item_id` int(11) NOT NULL,
  `adjustment_id` int(11) NOT NULL,
  `adjustment_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order`
--

CREATE TABLE `sales_order` (
  `sales_order_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `order_status` int(1) NOT NULL COMMENT '0 = unchecked\r\n1 = invoiced',
  `payment_status` int(1) NOT NULL COMMENT '0 = upaid\r\n1 = paid',
  `package_status` int(1) NOT NULL DEFAULT 0 COMMENT '0 = No Shipped\r\n1 = Shipped\r\n2 = Delivered',
  `sales_order_amount` float NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_order`
--

INSERT INTO `sales_order` (`sales_order_id`, `customer_id`, `customer_name`, `shipping_address`, `order_status`, `payment_status`, `package_status`, `sales_order_amount`, `date`) VALUES
(1, 1, 'ekhkVlF5L3JQWnltb3kvSFNJV2c3UT09OjoprW9iEReFQOYKXKu7860n', 'K0J0L0ozdlBHZ1hObUpVQVMrbE5NMnJRbGh3SmZDSjRzZ2p1QlFWN2o3bzV6dlBVeisydzJlL083alNMQ3JFeVVreWkybnE1QnRZV2dKYmpqRytxVHc9PTo6jsPZk2sa7tjZZxdhddKwMA==', 1, 0, 0, 30000, '2025-01-08'),
(2, 1, 'SHBGdjh4SUVVWTRLV28yVzRKemJ0Zz09OjoZDsovvDfWMmIVhur2+Tm0', 'WTRuU0NhM0Q5V216cFVxdmQ1cEhxL005UXB4TG1rTFNodWpRTFdzdE1WYkhJU3lZZjl5c1ZkcGhRQlFybEtKb0RpY1ZVVHdnK2srbno3R1VYMEtQUWc9PTo62a1+sTQNPUyhlQ/UcVCm7Q==', 1, 0, 0, 3000, '2025-01-08');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(5) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `cname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `role` int(1) NOT NULL COMMENT '0 = Admin\r\n1 = Supplier',
  `token` varchar(255) NOT NULL,
  `verify_status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fname`, `lname`, `cname`, `email`, `pass`, `phone`, `address`, `role`, `token`, `verify_status`) VALUES
(6, 'Esther', 'Liong', 'd1Y4dmhhcUgyUzB2eHNKckoxbjM3QT09OjrTnBk2XhX8KCqie95rRg7J', 'UkVPMTQ3R1dMci9hVTZIM0lhcjY3Zz09OjoOgxdbJ/aYMsrmEztV5sq8', '$2y$10$0SdRwmYcNygU.ODgwPqkfO7OQdTJHLtoZQoZ3IKwJRqCY0whhs7/.', 'UUp6d3ZnZCtHWDE4Y01WWGI2K1VQZz09OjoPcU70K663AiCd966KeRi6', 'MGFXMkViZXRwOE5MRktpNTRaLzBtbTJCYlNYN212NklMYitMRWd0VEUzdUQ4aEd0eEFSUEZjZDZzRitudmJjMzo6q54yt3a9Ti4N7sZGDQhtdA==', 1, 'b9b9c34537672af533c93593c0aa3bd9', 1),
(7, 'Admin', 'Liong', '', 'am9aUGU2V1VJSk1LOWhRQ0V6aC9WRE5jbTA0RXhGVGpJZkZPU3Zzc3hFMD06Ol9pifyT4ZGO3V3SfTAXp7o=', '$2y$10$Eo0i6KH6fespINUDd.zENOuEohGNYE3/yn6m/XTe4q7Zo4eOLx9L.', '', '', 0, '', 1),
(8, 'Jun ', 'Ming', 'cDRYMGRIM2tmT0VudGRJa0Rab3ZnUT09OjpkojNWmkrCGTKtQXeAE0E3', 'T3ZNTGFCRDVmTUh4SWtSRkdQTmZ0ZFB0Mm5NQlkvMGlXSlV3Zi8xdlg0cz06OvQxVolo7eT29wMOZ9p3Fvc=', '$2y$10$E7qB3PGvgjRcpX292wvqn.goZ.f7l6Tzt3pkrOq3ABEcgA3po/6xu', 'RUFNeVBhOENHTmtYSDM1QUM2NlRSQT09Ojqz5fFuXqAX5JdnkScpwTlR', 'WEpSSnU3WkQ1ZW44c1VGNk9EcndCUT09OjqTctBfuipv+59xkKKavq1Y', 1, '908f74e2c331277e3d852ad94d61e1e1', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adjustment`
--
ALTER TABLE `adjustment`
  ADD PRIMARY KEY (`adjustment_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenses_id`);

--
-- Indexes for table `expenses_item`
--
ALTER TABLE `expenses_item`
  ADD PRIMARY KEY (`expenses_item_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`);

--
-- Indexes for table `inventory_adjustment`
--
ALTER TABLE `inventory_adjustment`
  ADD PRIMARY KEY (`inventory_adjustment_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `price_adjustment`
--
ALTER TABLE `price_adjustment`
  ADD PRIMARY KEY (`price_adjustment_id`);

--
-- Indexes for table `sales_order`
--
ALTER TABLE `sales_order`
  ADD PRIMARY KEY (`sales_order_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adjustment`
--
ALTER TABLE `adjustment`
  MODIFY `adjustment_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenses_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expenses_item`
--
ALTER TABLE `expenses_item`
  MODIFY `expenses_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_adjustment`
--
ALTER TABLE `inventory_adjustment`
  MODIFY `inventory_adjustment_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `price_adjustment`
--
ALTER TABLE `price_adjustment`
  MODIFY `price_adjustment_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order`
--
ALTER TABLE `sales_order`
  MODIFY `sales_order_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
