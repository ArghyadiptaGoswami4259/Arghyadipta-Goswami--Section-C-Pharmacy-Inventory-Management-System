-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 06:29 PM
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
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_price`) VALUES
(1, 1, '0000-00-00 00:00:00', 8.40),
(2, 1, '0000-00-00 00:00:00', 32.60),
(3, 1, '0000-00-00 00:00:00', 62.48),
(4, 1, '0000-00-00 00:00:00', 33.93),
(5, 1, '0000-00-00 00:00:00', 43.93),
(6, 1, '0000-00-00 00:00:00', 33.95);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(0, 1, 3, 2, 4),
(0, 2, 4, 4, 5),
(0, 2, 3, 3, 4),
(0, 3, 63, 2, 19),
(0, 3, 64, 2, 12),
(0, 4, 66, 3, 6),
(0, 4, 65, 4, 4),
(0, 5, 67, 4, 8),
(0, 5, 65, 3, 4),
(0, 6, 67, 2, 8),
(0, 6, 66, 3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `description`, `image_url`, `quantity`, `price`, `expiry_date`) VALUES
(1, 'Paracetamol 500mg', 'Used for relief of fever and mild to moderate pain.', 'https://via.placeholder.com/100x100?text=Paracetamol+500mg', 100, 1.99, '2026-12-31'),
(2, 'Ibuprofen 200mg', 'Nonsteroidal anti-inflammatory drug for pain and inflammation.', 'https://via.placeholder.com/100x100?text=Ibuprofen+200mg', 150, 3.50, '2026-06-30'),
(3, 'Cough Syrup', 'Soothes sore throat and relieves cough symptoms.', 'https://via.placeholder.com/100x100?text=Cough+Syrup', 75, 4.20, '2025-11-15'),
(4, 'Vitamin C Tablets', 'Boosts immune system and acts as an antioxidant.', 'https://via.placeholder.com/100x100?text=Vitamin+C+Tablets', 196, 5.00, '2027-01-01'),
(6, 'Aspirin 500mg', 'Used for pain relief, anti-inflammatory, and fever reduction.', 'https://via.placeholder.com/100x100?text=Aspirin', 120, 2.00, '2026-05-30'),
(7, 'Amoxicillin 250mg', 'Antibiotic used for bacterial infections.', 'https://via.placeholder.com/100x100?text=Amoxicillin', 90, 6.00, '2025-09-12'),
(8, 'Antacid Tablets', 'Used to neutralize stomach acid and relieve heartburn.', 'https://via.placeholder.com/100x100?text=Antacid', 200, 3.00, '2026-04-21'),
(9, 'Allergy Relief Tablets', 'Used for relief from seasonal allergies.', 'https://via.placeholder.com/100x100?text=Allergy+Relief', 150, 4.50, '2025-12-11'),
(10, 'Cold and Flu Relief Syrup', 'Helps with flu symptoms like fever, body aches, and congestion.', 'https://via.placeholder.com/100x100?text=Cold+and+Flu', 60, 5.00, '2026-03-10'),
(11, 'Calcium 500mg Tablets', 'Supports bone health and strengthens teeth.', 'https://via.placeholder.com/100x100?text=Calcium', 180, 4.00, '2026-01-19'),
(12, 'Cough Drops', 'Soothes throat and relieves cough symptoms.', 'https://via.placeholder.com/100x100?text=Cough+Drops', 250, 2.20, '2025-07-23'),
(13, 'Multivitamin Tablets', 'Contains a combination of essential vitamins and minerals.', 'https://via.placeholder.com/100x100?text=Multivitamin', 220, 6.50, '2026-02-15'),
(14, 'Pain Reliever Gel', 'Topical gel for pain relief in muscles and joints.', 'https://via.placeholder.com/100x100?text=Pain+Reliever', 100, 7.00, '2026-08-30'),
(15, 'Probiotic Capsules', 'Supports digestive health and immune system.', 'https://via.placeholder.com/100x100?text=Probiotic', 140, 9.99, '2026-07-20'),
(16, 'Melatonin 3mg Tablets', 'Natural sleep aid, helps with insomnia and sleep disorders.', 'https://via.placeholder.com/100x100?text=Melatonin', 130, 8.50, '2025-10-09'),
(17, 'Hair Growth Shampoo', 'Promotes hair growth and prevents hair loss.', 'https://via.placeholder.com/100x100?text=Hair+Growth', 80, 12.99, '2026-09-14'),
(18, 'Hydrocortisone Cream 1%', 'Used for treating skin irritations and rashes.', 'https://via.placeholder.com/100x100?text=Hydrocortisone', 110, 4.99, '2025-04-30'),
(19, 'Eye Drops for Dry Eyes', 'Relieves dryness and irritation in the eyes.', 'https://via.placeholder.com/100x100?text=Eye+Drops', 95, 5.25, '2026-11-22'),
(20, 'Vitamin D3 1000 IU Tablets', 'Supports immune health and bone density.', 'https://via.placeholder.com/100x100?text=Vitamin+D3', 140, 7.49, '2026-05-15'),
(21, 'Loperamide Tablets', 'Used to treat diarrhea.', 'https://via.placeholder.com/100x100?text=Loperamide', 160, 3.75, '2026-02-05'),
(22, 'Nasal Spray for Congestion', 'Relieves nasal congestion due to allergies or a cold.', 'https://via.placeholder.com/100x100?text=Nasal+Spray', 85, 4.25, '2025-12-22'),
(23, 'Pregnancy Test Kit', 'Detects pregnancy by testing urine for hCG.', 'https://via.placeholder.com/100x100?text=Pregnancy+Test', 200, 8.00, '2026-06-10'),
(24, 'Bandaids (Box of 100)', 'For wound protection and healing.', 'https://via.placeholder.com/100x100?text=Bandaids', 300, 2.99, '2026-04-12'),
(25, 'Cold Sore Cream', 'Relieves and treats cold sores.', 'https://via.placeholder.com/100x100?text=Cold+Sore', 150, 6.00, '2025-05-03'),
(26, 'Anti-Itch Lotion', 'Provides relief from itching caused by insect bites and rashes.', 'https://via.placeholder.com/100x100?text=Anti+Itch', 180, 4.30, '2026-07-05'),
(27, 'Burn Relief Gel', 'Helps soothe and heal burns and skin irritations.', 'https://via.placeholder.com/100x100?text=Burn+Relief', 100, 5.50, '2025-08-15'),
(28, 'Sunscreen SPF 50', 'Protects skin from harmful UV rays.', 'https://via.placeholder.com/100x100?text=Sunscreen+SPF+50', 120, 9.00, '2025-06-01'),
(29, 'Pill Organizer (7-day)', 'Organizes medication by day of the week.', 'https://via.placeholder.com/100x100?text=Pill+Organizer', 200, 3.25, '2026-12-31'),
(30, 'Nail Fungus Treatment', 'Treats fungal infections in nails.', 'https://via.placeholder.com/100x100?text=Nail+Fungus', 70, 8.00, '2025-11-10'),
(31, 'Fertility Supplements', 'Supports reproductive health and fertility.', 'https://via.placeholder.com/100x100?text=Fertility+Supplements', 90, 15.00, '2026-01-25'),
(32, 'Bowel Cleanse Tablets', 'Helps detoxify the colon and improve digestion.', 'https://via.placeholder.com/100x100?text=Bowel+Cleanse', 130, 5.99, '2026-06-18'),
(33, 'Joint Pain Relief Tablets', 'Relieves pain and inflammation in joints.', 'https://via.placeholder.com/100x100?text=Joint+Pain+Relief', 110, 7.20, '2025-09-09'),
(34, 'Herbal Tea for Sleep', 'Natural tea to help with sleep and relaxation.', 'https://via.placeholder.com/100x100?text=Herbal+Tea', 150, 4.50, '2026-07-13'),
(35, 'Tinnitus Relief Tablets', 'Helps with ringing in the ears (tinnitus).', 'https://via.placeholder.com/100x100?text=Tinnitus+Relief', 60, 8.50, '2025-10-23'),
(36, 'Anti-Snoring Spray', 'Reduces snoring by soothing the throat.', 'https://via.placeholder.com/100x100?text=Anti+Snoring', 95, 6.20, '2026-02-28'),
(37, 'Digestive Enzymes', 'Improves digestion and nutrient absorption.', 'https://via.placeholder.com/100x100?text=Digestive+Enzymes', 100, 10.00, '2026-12-05'),
(38, 'Herbal Cough Syrup', 'Natural syrup to relieve cough and throat irritation.', 'https://via.placeholder.com/100x100?text=Herbal+Cough', 85, 5.00, '2025-06-20'),
(39, 'Vitamin B12 1000mcg', 'Supports energy production and red blood cell formation.', 'https://via.placeholder.com/100x100?text=Vitamin+B12', 200, 6.00, '2026-04-25'),
(40, 'Zinc Supplements', 'Boosts immune function and promotes skin health.', 'https://via.placeholder.com/100x100?text=Zinc+Supplements', 180, 4.75, '2026-01-17'),
(41, 'Laxatives', 'Helps relieve constipation and promotes regular bowel movements.', 'https://via.placeholder.com/100x100?text=Laxatives', 140, 3.50, '2025-08-05'),
(42, 'Cleansing Facial Wipes', 'Wipes for cleansing and refreshing the skin.', 'https://via.placeholder.com/100x100?text=Cleansing+Wipes', 250, 3.99, '2025-11-28'),
(43, 'Hand Sanitizer Gel', 'Kills germs and viruses, keeps hands sanitized.', 'https://via.placeholder.com/100x100?text=Hand+Sanitizer', 500, 2.99, '2025-06-12'),
(44, 'Nausea Relief Tablets', 'Reduces nausea caused by motion sickness or pregnancy.', 'https://via.placeholder.com/100x100?text=Nausea+Relief', 110, 4.50, '2026-05-07'),
(45, 'Prostate Health Supplements', 'Supports prostate health and urinary function.', 'https://via.placeholder.com/100x100?text=Prostate+Health', 130, 10.50, '2026-03-30'),
(46, 'Petroleum Jelly', 'Used to moisturize dry skin and protect minor cuts and burns.', 'https://via.placeholder.com/100x100?text=Petroleum+Jelly', 170, 2.00, '2026-08-21'),
(47, 'Hand Cream for Dry Skin', 'Nourishes and moisturizes dry hands.', 'https://via.placeholder.com/100x100?text=Hand+Cream', 200, 3.99, '2026-01-06'),
(48, 'Lice Treatment Shampoo', 'Kills lice and relieves itching from head lice infestations.', 'https://via.placeholder.com/100x100?text=Lice+Shampoo', 50, 7.99, '2025-07-09'),
(49, 'Electrolyte Drink Mix', 'Replenishes electrolytes and prevents dehydration.', 'https://via.placeholder.com/100x100?text=Electrolyte+Mix', 120, 5.00, '2026-04-10'),
(50, 'Wart Removal Cream', 'Used to remove warts from the skin.', 'https://via.placeholder.com/100x100?text=Wart+Removal', 85, 6.00, '2025-05-20'),
(51, 'Hemorrhoid Relief Cream', 'Relieves pain and itching from hemorrhoids.', 'https://via.placeholder.com/100x100?text=Hemorrhoid+Relief', 100, 7.50, '2025-06-15'),
(52, 'Fertility Testing Kit', 'Tests for ovulation to help with family planning.', 'https://via.placeholder.com/100x100?text=Fertility+Kit', 75, 9.50, '2025-10-01'),
(53, 'Echinacea Immune Support', 'Supports immune system and reduces cold symptoms.', 'https://via.placeholder.com/100x100?text=Echinacea+Support', 110, 8.00, '2026-03-18'),
(54, 'Ginger Tablets', 'Used for digestion and nausea relief.', 'https://via.placeholder.com/100x100?text=Ginger+Tablets', 150, 5.75, '2025-09-22'),
(55, 'Herbal Sleep Aid', 'Natural remedy for improving sleep quality.', 'https://via.placeholder.com/100x100?text=Herbal+Sleep', 130, 7.00, '2026-07-05'),
(56, 'Pain Relief Roll-On', 'Roll-on gel for targeted pain relief in muscles and joints.', 'https://via.placeholder.com/100x100?text=Pain+Roll-On', 90, 4.99, '2025-12-25'),
(57, 'Cold Compress', 'Reusable compress for treating sprains and swelling.', 'https://via.placeholder.com/100x100?text=Cold+Compress', 70, 3.20, '2026-06-28'),
(58, 'Example Medicine', 'Used for demonstration of image URL storage.', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcROw2Ngv3enuT85CVPAiqOZUVrFQclwvX8yeVqq4LKI1g1StKZ880LTOVhOSUxC3nuwh5UrtRA0dfig1MJyC4usBNQKlk03mRHRBB4r_wsiDNaL9Y86UfMBQQ', 50, 9.99, '2026-12-31'),
(59, 'Cold compress', 'Another example product to demonstrate image URL storage.', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTfXsyYvCLOof7nhbFf3ZaADh2N4g01e-5tYuPSeTRjU1nFEt-P6QXcfHiK7FHdHNkoSnQbPK5sBxJkKmMzGb9tgV3M_KMJ0ZJmYatO6VE4Vgp99TLa8P43', 100, 5.49, '2027-05-10'),
(60, 'Ibuprofen 200', 'Nonsteroidal anti-inflammatory drug (NSAID) used to reduce fever and treat pain or inflammation.', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcS4WG5V7IQzdrKvxwq2JOz-47iUTi0vrsYWGnbd-IwVeSXOsyMTzDMps0BQDxHEb-nuyY6ebhpmpG4jDWNDcoNTbwF8-uO13ZPA1stBj340G3rdqm4HY1_nS1QS', 100, 2.50, '2026-11-30'),
(62, 'Ginger Tablets', 'Herbal supplement tablets made from ginger extract, used to support digestion and reduce nausea.', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQh-7ZAYS3oINwRFbhHtbJ5sMdb3yyUcsX4OqdzQKRSoOzzolvEnD9SqsAATCDdRK1KOHRquYzErhOrQH2dW4HHlGeI6iiRDQ', 85, 4.25, '2027-02-10'),
(63, 'Fertility Testing Kit', 'At-home fertility test kit designed to help individuals monitor ovulation cycles and fertility status.', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQ2CJIctG7iNKtCdnoRkaS7q3oN-1kLjjIVFqcU6XrKbQ_JKmza1pnxB9FdxZK64SUDAuhwVs5E_gsw96s037bK47mN5UhYT0_dfxo8RfjQMsjNEg49uHONQsg', 58, 18.75, '2026-08-31'),
(64, 'Herbal Sleep Aid', 'Natural supplement formulated to promote relaxation and support restful sleep without dependency.', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcS9mfJAihsBIZrW6apmQm7_pUeC3K62TP2HFMCaGOrmur6p2DPbLYf7GwWqqVnjREiDDGVY_ZBR5OE-E_EpHIHXR0exToHvNE4mdd2uzgxOQfJqtEKMoF7MKQ', 83, 12.49, '2027-02-28'),
(65, 'Petroleum Jelly', 'Multipurpose moisturizer that protects dry, chapped skin and helps heal minor cuts and burns.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRobkIQxvQT4225T_fM_AqFqNN0Ac395Df6EQ&s', 193, 3.99, '2028-05-31'),
(66, 'Laxatives', 'Helps relieve constipation by stimulating bowel movements.', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRCrwHD5jp6KzHBZTMfQmfZW2TW3uOTTS23qGJcOwogw8ppPaCp1fBYDy7A88KmyRUghtbNMnfQL7tFIJyeoPSl6PlvUP_PK2NGsmghvVnvNErjt5QbQ2u4K0w', 144, 5.99, '2026-07-15'),
(67, 'Nasal Spray', 'Relieves nasal congestion and helps with sinus issues.', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQIaVOBwGInKzxqrnnNi_32vtIethYrCdPaDh_KPks9eKRn3_W8ShacXpjkAohMfUMxMn82Se6VLD3dXuG9w7DdhM0iTy8-9UnQmLRHFKu4GPq6xRu6mEFf1g', 114, 7.99, '2026-05-10'),
(68, 'Aspirin', '<br /><b>Warning</b>:  Undefined array key ', '<br /><b>Warning</b>:  Undefined array key ', 12, 10.00, '2025-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `return_date` date NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`id`, `order_id`, `product_id`, `user_id`, `return_date`, `status`) VALUES
(1, 2, 3, 1, '2025-04-20', 'Pending'),
(2, 1, 3, 1, '2025-04-20', 'Pending'),
(3, 2, 4, 1, '2025-04-20', 'Pending'),
(4, 3, 63, 1, '2025-04-20', 'Pending'),
(5, 3, 64, 1, '2025-04-20', 'Pending'),
(6, 4, 66, 1, '2025-04-20', 'Pending'),
(7, 4, 65, 1, '2025-04-20', 'Pending'),
(8, 5, 67, 1, '2025-04-20', 'Pending'),
(9, 6, 66, 1, '2025-04-20', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'ARGHYADIPTA GOSWAMI', 'arghyadiptagoswami63157@gmail.com', '$2y$10$u/txq8voTcF10.PfCUEPUOA45p0wcMsFCrOwohHPVBW60gxw7h1dK', 'admin'),
(2, 'ＡｉＫＯ♡', 'dipa435@gmail.com', '$2y$10$y4aB3YcM43wiGFOCScCWQOTVhA6.NOcOd8IN2z2RIIKr1xz0l44Uu', 'user');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
