-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 16, 2025 lúc 03:43 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `user`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `STT` int(11) NOT NULL,
  `Cart_ID` varchar(10) NOT NULL,
  `CustomerID` varchar(10) DEFAULT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`STT`, `Cart_ID`, `CustomerID`, `create_at`) VALUES
(7, 'CART001', 'KH001', '2025-06-10 13:19:57'),
(8, 'CART002', 'KH002', '2025-06-10 13:19:57'),
(9, 'CART003', 'KH003', '2025-06-10 13:19:57'),
(10, 'CART004', 'KH004', '2025-06-10 13:19:57'),
(11, 'CART005', 'KH005', '2025-06-10 13:19:57'),
(12, 'CART006', 'KH006', '2025-06-10 13:19:57'),
(13, 'CART007', 'KH007', '2025-07-11 00:22:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `ID` int(11) NOT NULL,
  `Cart_ID` varchar(10) NOT NULL,
  `ProductID` varchar(10) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_items`
--

INSERT INTO `cart_items` (`ID`, `Cart_ID`, `ProductID`, `Quantity`) VALUES
(4, 'CART002', 'DRP002', 10),
(5, 'CART002', 'CLA008', 1),
(7, 'CART001', 'MOX102', 2),
(9, 'CART002', 'COM073', 1),
(10, 'CART002', 'PHI003', 1),
(11, 'CART003', 'VEX921', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `CateID` varchar(10) NOT NULL,
  `CateName` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`CateID`, `CateName`, `Description`) VALUES
('BEAUTY', 'Sắc đẹp', 'Các đồ liên quan làm đẹp'),
('CLOTHES', 'Quần áo', 'Quần áo'),
('COMPUTER', 'Máy tính', 'Các loại máy tính'),
('DRINK', 'Đồ uống', 'Cac loai do uong'),
('MOMMY', 'Mẹ và bé', 'Sản phẩm cho mẹ và bé'),
('PHONE', 'Điện Thoại', 'Cac loai dien thoai'),
('VEHICLE', 'Phương tiện giao thông', 'Các phương tiện giao thông');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `CustomerID` varchar(10) NOT NULL,
  `CustomerName` varchar(255) NOT NULL,
  `Phone` varchar(10) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `UserID` int(10) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`CustomerID`, `CustomerName`, `Phone`, `Email`, `Address`, `Gender`, `UserID`, `date`) VALUES
('KH001', 'Nguyen Van Aaaaaaaaaaaa', '0911111111', 'a@gmail.com', 'Ha Nam', 'Khác', 1, '2013-11-28'),
('KH002', 'Tran Thi gok gok', '0922222222', 'b@gmail.com', 'HCM', 'Nam', 3, '2016-11-25'),
('KH003', 'Le Van C', '0933333333', 'c@gmail.com', 'Da Nang', 'Nam', 4, '2006-11-14'),
('KH004', 'Pham Thi D', '0944444444', 'd@gmail.com', 'Can Tho', 'Nữ', 5, '2025-06-18'),
('KH005', 'Do Van Eeeeeeeeeeeeeeeee', '0955555555', 'e@gmail.com', 'Hai Phong', 'Nữ', 7, '2009-06-19'),
('KH006', 'Hoang Thi F', '0966666666', 'f@gmail.com', 'Lam Dong', 'Female', NULL, NULL),
('KH007', 'Nguyen Van Muoi', '0988726122', 'hong123@gmail.com', 'Bac Ninh', 'Male', 10, '2008-06-19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `discounts`
--

CREATE TABLE `discounts` (
  `ID` int(11) NOT NULL,
  `Code` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `Discount_type` enum('percentage','fixed') NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `min_oder_value` decimal(10,2) NOT NULL,
  `valid_from` datetime NOT NULL,
  `valid_to` datetime NOT NULL,
  `usage_limit` int(11) NOT NULL,
  `times_used` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order`
--

CREATE TABLE `order` (
  `OrderID` int(10) NOT NULL,
  `CustomerID` varchar(10) NOT NULL,
  `OrderDate` date NOT NULL,
  `Status` varchar(50) NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `DetailsID` int(10) NOT NULL,
  `OrderID` int(10) NOT NULL,
  `ProductID` varchar(10) NOT NULL,
  `Quantity` int(10) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `Total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bẫy `order_details`
--
DELIMITER $$
CREATE TRIGGER `calculate_total_before_insert` BEFORE INSERT ON `order_details` FOR EACH ROW BEGIN
    SET NEW.Total = NEW.Quantity * NEW.UnitPrice;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_total_before_update` BEFORE UPDATE ON `order_details` FOR EACH ROW BEGIN
    SET NEW.Total = NEW.Quantity * NEW.UnitPrice;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `ProductID` varchar(10) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(10) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `CateID` varchar(10) NOT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_bestseller` tinyint(1) DEFAULT 0,
  `is_sale` tinyint(1) DEFAULT 0,
  `discount_percent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `Price`, `Quantity`, `Description`, `Image`, `CateID`, `is_featured`, `is_bestseller`, `is_sale`, `discount_percent`) VALUES
('BEP019', 'Phấn trang điểm A3', 150000.00, 50, 'Phấn đánh tan mọi lmao', 'phan-phu-cho-da-nhay-cam-4.jpg', 'BEAUTY', 1, 1, 0, 0),
('BES0992', 'Son môi LE1982', 500000.00, 345, 'Son môi ultra pro max', 'img3.jpg', 'BEAUTY', 0, 0, 1, 26),
('CLA008', 'Áo sơ mi trắng', 220000.00, 25, 'Chất liệu cotton', 'ao.jpg', 'CLOTHES', 0, 0, 1, 20),
('COL011', 'Laptop Acer Lmao', 19000000.00, 8, 'Laptop gaming', 'sale19.jpg', 'COMPUTER', 0, 0, 0, NULL),
('COL091', 'Laptop Legion 5', 24000000.00, 4, 'Laptop gaming', 'img17.jpg', 'COMPUTER', 0, 1, 1, 12),
('COM073', 'Máy tính bàn full set A1', 5000000.00, 2, 'gà rán', 'img11.jpg', 'COMPUTER', 1, 0, 0, 0),
('DRC001', 'Coca cola', 10000.00, 125, 'Do uong co ga cuc manh', 'img9.jpg', 'DRINK', 1, 0, 0, 0),
('DRP002', 'Pepsi', 9000.00, 150, 'Đồ uống có ga', 'nuoc-ngot-pepsi-cola-lon-320ml-202407131656260952.jpg', 'DRINK', 1, 0, 0, 0),
('MOB111', 'Bỉm Tukien', 10000.00, 300, 'Bỉm siêu thấm hút giá cực hạt dẻ', 'bim-ta-dan-huggies-size-m-76-mieng-cho-be-6-11kg-10a.jpg', 'MOMMY', 1, 0, 0, 0),
('MOX102', 'Xe đẩy mini', 2000000.00, 6, 'Xe đẩy bé đi chơi', 'img21.jpg', 'MOMMY', 0, 0, 0, NULL),
('PHI003', 'IPhone 13', 17000000.00, 8, 'iPhone 13 128GB', 'iphone-13-midnight-2-600x600.jpg', 'PHONE', 1, 0, 1, 30),
('PHR002', 'Redmi k70', 7500000.00, 4, 'Điện thoại siêu uy tín', 'img5.jpg', 'PHONE', 0, 1, 0, NULL),
('VEO011', 'Ô tô lmao', 99999999.99, 7, 'Ô tô siêu cấp vjp pdi', 'img7.jpg', 'VEHICLE', 0, 0, 0, NULL),
('VEX921', 'Xe máy Autobon', 55000000.00, 1, 'siêu xe ưave', 'Wave-Alpha-110-7370-1649899780.jpg', 'VEHICLE', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `review`
--

CREATE TABLE `review` (
  `id` int(10) NOT NULL,
  `CustomerID` varchar(10) DEFAULT NULL,
  `ProductID` varchar(10) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `review`
--

INSERT INTO `review` (`id`, `CustomerID`, `ProductID`, `rating`, `comment`, `create_at`) VALUES
(2, 'KH003', 'PHR002', 4, 'Điện thoại dùng ổn, pin lâu', '2025-05-20 17:13:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shipping`
--

CREATE TABLE `shipping` (
  `ID` int(10) NOT NULL,
  `OrderID` int(10) NOT NULL,
  `Address` text NOT NULL,
  `Ship_method` varchar(100) NOT NULL,
  `Ship_cost` decimal(10,2) NOT NULL,
  `Ship_at` datetime NOT NULL,
  `Status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `ID` int(10) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`ID`, `Username`, `Password`, `Email`, `create_at`, `role`) VALUES
(1, 'abc', '123', 'abc@gmail.com', '2025-06-09 09:00:23', 'user'),
(3, 'gok', '123', 'gok@gmail.com', '2025-06-09 09:00:23', 'user'),
(4, 'bo', '123', '333e@gmail.com', '2025-06-09 09:00:23', 'user'),
(5, 'ab', '123', 'em@gmail.com', '2025-06-09 09:00:23', 'user'),
(7, 'lao', '123', 'lmao@gmail.com', '2025-06-09 11:08:45', 'user'),
(9, 'admin', 'admin', 'admin@gmail.com', '2025-06-11 10:36:42', 'admin'),
(10, 'nihao', '123', 'hong123@gmail.com', '2025-07-11 00:22:32', 'user');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`Cart_ID`),
  ADD UNIQUE KEY `STT` (`STT`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Cart_ID` (`Cart_ID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CateID`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`),
  ADD KEY `fk_customers_users` (`UserID`);

--
-- Chỉ mục cho bảng `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `fk_order_customers` (`CustomerID`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`DetailsID`),
  ADD KEY `fk_order_details_order` (`OrderID`),
  ADD KEY `fk_order_details_products` (`ProductID`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `fk_products_categories` (`CateID`);

--
-- Chỉ mục cho bảng `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_review_users` (`CustomerID`),
  ADD KEY `fk_review_products` (`ProductID`);

--
-- Chỉ mục cho bảng `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_shipping_order` (`OrderID`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `STT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `discounts`
--
ALTER TABLE `discounts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order`
--
ALTER TABLE `order`
  MODIFY `OrderID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `DetailsID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `review`
--
ALTER TABLE `review`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `shipping`
--
ALTER TABLE `shipping`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`Cart_ID`) REFERENCES `cart` (`Cart_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customers_users` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_customers` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_order` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_details_products` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_categories` FOREIGN KEY (`CateID`) REFERENCES `categories` (`CateID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_products` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `fk_shipping_order` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
