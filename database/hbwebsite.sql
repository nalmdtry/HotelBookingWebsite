-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 29, 2026 lúc 08:07 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

--
-- Cơ sở dữ liệu: `hbwebsite`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin_cred`
--

CREATE TABLE `admin_cred` (
  `sr_no` int(11) NOT NULL
  , `admin_name` varchar(150) NOT NULL
  , `admin_pass` varchar(150) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin_cred`
--

INSERT INTO
  `admin_cred` (`sr_no`, `admin_name`, `admin_pass`)
VALUES
  (1, 'nlam', '123')
  , (2, 'ngoclam', '123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_details`
--

CREATE TABLE `booking_details` (
  `sr_no` int(11) NOT NULL
  , `booking_id` int(11) NOT NULL
  , `room_name` varchar(100) NOT NULL
  , `price` int(11) NOT NULL
  , `total_pay` int(11) NOT NULL
  , `room_no` varchar(100) DEFAULT NULL
  , `user_name` varchar(100) NOT NULL
  , `phonenum` varchar(100) NOT NULL
  , `address` varchar(150) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `booking_details`
--

INSERT INTO
  `booking_details` (
    `sr_no`
    , `booking_id`
    , `room_name`
    , `price`
    , `total_pay`
    , `room_no`
    , `user_name`
    , `phonenum`
    , `address`
  )
VALUES
  (
    1
    , 1
    , 'Phòng 1'
    , 750000
    , 1500000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0795624652'
    , 'Duy Trinh, Duy Xuyên, Quảng Nam'
  )
  , (
    4
    , 4
    , 'Phòng 2'
    , 800000
    , 1600000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0795624652'
    , 'Duy Trinh, Duy Xuyên, Quảng Nam'
  )
  , (
    6
    , 6
    , 'Phòng 3'
    , 900000
    , 1800000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0795624652'
    , 'Duy Trinh, Duy Xuyên, Quảng Nam'
  )
  , (
    7
    , 7
    , 'Phòng 1'
    , 750000
    , 2250000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0795624652'
    , 'Duy Trinh, Duy Xuyên, Quảng Nam'
  )
  , (
    8
    , 8
    , 'Phòng 1'
    , 750000
    , 1500000
    , '102'
    , 'Võ Ngọc Lâm'
    , '0795624652'
    , 'Duy Trinh, Duy Xuyên, Quảng Nam'
  )
  , (
    9
    , 9
    , 'Phòng 2'
    , 800000
    , 800000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0795624652'
    , 'Duy Trinh, Duy Xuyên, Quảng Nam'
  )
  , (
    10
    , 10
    , 'Phòng 3'
    , 900000
    , 5400000
    , '103'
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    11
    , 11
    , 'Phòng 3'
    , 900000
    , 2700000
    , '104'
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    12
    , 12
    , 'Phòng 1'
    , 750000
    , 1500000
    , '105'
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    13
    , 13
    , 'Phòng 3'
    , 900000
    , 1800000
    , '113'
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    14
    , 14
    , 'Phòng 3'
    , 900000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    15
    , 15
    , 'Phòng 3'
    , 900000
    , 1800000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    16
    , 16
    , 'Phòng 3'
    , 900000
    , 2700000
    , '116'
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    18
    , 18
    , 'Phòng 3'
    , 900000
    , 1800000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    19
    , 19
    , 'Phòng 3'
    , 900000
    , 1800000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    20
    , 20
    , 'Phòng 3'
    , 900000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    21
    , 21
    , 'Phòng 3'
    , 900000
    , 1800000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    22
    , 22
    , 'Phòng 1'
    , 750000
    , 750000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    23
    , 23
    , 'Phòng 1'
    , 750000
    , 1500000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    24
    , 24
    , 'Phòng 1'
    , 750000
    , 750000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    25
    , 25
    , 'Phòng 1'
    , 750000
    , 1500000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    26
    , 26
    , 'Phòng 3'
    , 900000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    27
    , 27
    , 'Phòng 3'
    , 900000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    28
    , 28
    , 'Phòng 3'
    , 900000
    , 1800000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    29
    , 29
    , 'Phòng 3'
    , 900000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    30
    , 30
    , 'Phòng 3'
    , 900000
    , 1800000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    31
    , 31
    , 'Phòng 3'
    , 900000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    32
    , 32
    , 'Phòng 3'
    , 900000
    , 1800000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    33
    , 33
    , 'Phòng 3'
    , 900000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    34
    , 34
    , 'Phòng 3'
    , 900000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    35
    , 35
    , 'Phòng 3'
    , 900000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    36
    , 36
    , 'Phòng 3'
    , 900000
    , 900000
    , '220'
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  )
  , (
    37
    , 37
    , 'Superior Room'
    , 450000
    , 900000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    38
    , 38
    , 'Suite Room'
    , 800000
    , 1600000
    , '222'
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    39
    , 39
    , 'Superior Room'
    , 450000
    , 450000
    , '223'
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    40
    , 40
    , 'Standard Room'
    , 350000
    , 350000
    , '1001'
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    41
    , 41
    , 'Standard Room'
    , 350000
    , 700000
    , '10001'
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    42
    , 42
    , 'Suite Room'
    , 800000
    , 1600000
    , NULL
    , 'Võ Ngọc Lâm'
    , '0999999988'
    , 'Đà Nẵng'
  )
  , (
    43
    , 43
    , 'Suite Room'
    , 800000
    , 6400000
    , '103'
    , 'Võ Lâm'
    , '0999999999'
    , 'âdasd'
  )
  , (
    44
    , 44
    , 'Deluxe Room'
    , 600000
    , 600000
    , NULL
    , 'Võ Lâm'
    , '0999999999'
    , 'âdasd'
  )
  , (
    45
    , 45
    , 'Superior Room'
    , 450000
    , 450000
    , NULL
    , 'Võ Lâm'
    , '0999999999'
    , 'âdasd'
  )
  , (
    46
    , 46
    , 'Suite Room'
    , 800000
    , 1600000
    , '159'
    , 'Võ Lâm'
    , '0999999999'
    , 'âdasd'
  )
  , (
    47
    , 47
    , 'Suite Room'
    , 800000
    , 800000
    , '106'
    , 'Võ Lâm'
    , '0999999999'
    , 'âdasd'
  )
  , (
    48
    , 48
    , 'Superior Room'
    , 450000
    , 450000
    , '107'
    , 'Võ Ngọc Lâm'
    , '0763023690'
    , 'Duy Xuyên, Đà Nẵng'
  );

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_order`
--

CREATE TABLE `booking_order` (
  `booking_id` int(11) NOT NULL
  , `user_id` int(11) NOT NULL
  , `room_id` int(11) NOT NULL
  , `check_in` date NOT NULL
  , `check_out` date NOT NULL
  , `arrival` int(11) NOT NULL DEFAULT 0
  , `refund` int(11) DEFAULT NULL
  , `booking_status` varchar(100) NOT NULL DEFAULT 'pending'
  , `order_id` varchar(150) NOT NULL
  , `trans_id` varchar(200) DEFAULT NULL
  , `trans_amount` int(11) NOT NULL
  , `trans_status` varchar(200) NOT NULL DEFAULT 'pending'
  , `trans_message` varchar(200) DEFAULT NULL
  , `rate_review` int(11) DEFAULT NULL
  , `datentime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `booking_order`
--

INSERT INTO
  `booking_order` (
    `booking_id`
    , `user_id`
    , `room_id`
    , `check_in`
    , `check_out`
    , `arrival`
    , `refund`
    , `booking_status`
    , `order_id`
    , `trans_id`
    , `trans_amount`
    , `trans_status`
    , `trans_message`
    , `rate_review`
    , `datentime`
  )
VALUES
  (
    1
    , 2
    , 1
    , '2025-12-02'
    , '2025-12-04'
    , 0
    , NULL
    , 'pending'
    , 'ORD_28333358'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2025-12-02 05:54:55'
  )
  , (
    4
    , 2
    , 2
    , '2025-12-02'
    , '2025-12-04'
    , 0
    , NULL
    , 'pending'
    , 'ORD_29758991'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2025-12-02 16:20:30'
  )
  , (
    6
    , 2
    , 3
    , '2025-12-02'
    , '2025-12-04'
    , 0
    , 1
    , 'cancelled'
    , 'ORD_2742130'
    , '4621124640'
    , 1800000
    , 'success'
    , 'Successful.'
    , NULL
    , '2025-12-02 17:16:11'
  )
  , (
    7
    , 2
    , 1
    , '2025-12-02'
    , '2025-12-05'
    , 0
    , NULL
    , 'payment failed'
    , 'ORD_2107440'
    , '4621639600'
    , 2250000
    , 'failed'
    , 'Transaction rejected by the issuers of the payment accounts.'
    , NULL
    , '2025-12-02 20:44:17'
  )
  , (
    8
    , 2
    , 1
    , '2025-12-02'
    , '2025-12-04'
    , 1
    , NULL
    , 'booked'
    , 'ORD_2333062'
    , '4621720348'
    , 1500000
    , 'success'
    , 'Successful.'
    , 1
    , '2025-12-02 21:14:02'
  )
  , (
    9
    , 2
    , 2
    , '2025-12-07'
    , '2025-12-08'
    , 0
    , 1
    , 'cancelled'
    , 'ORD_24347728'
    , '4623091567'
    , 800000
    , 'success'
    , 'Successful.'
    , NULL
    , '2024-12-09 20:09:09'
  )
  , (
    10
    , 2
    , 3
    , '2025-12-07'
    , '2025-12-13'
    , 1
    , NULL
    , 'booked'
    , 'ORD_27485646'
    , '4623425863'
    , 5400000
    , 'success'
    , 'Successful.'
    , 1
    , '2025-12-05 19:06:10'
  )
  , (
    11
    , 2
    , 3
    , '2025-12-11'
    , '2025-12-14'
    , 1
    , NULL
    , 'booked'
    , 'ORD_25192359'
    , '4623435838'
    , 2700000
    , 'success'
    , 'Successful.'
    , 1
    , '2025-12-05 19:08:17'
  )
  , (
    12
    , 2
    , 1
    , '2025-12-14'
    , '2025-12-16'
    , 1
    , NULL
    , 'booked'
    , 'ORD_25162770'
    , '4623463305'
    , 1500000
    , 'success'
    , 'Successful.'
    , 1
    , '2025-12-05 20:05:22'
  )
  , (
    13
    , 5
    , 3
    , '2025-12-14'
    , '2025-12-16'
    , 1
    , NULL
    , 'booked'
    , 'ORD_55048164'
    , '4626326400'
    , 1800000
    , 'success'
    , 'Successful.'
    , 1
    , '2025-12-10 11:09:58'
  )
  , (
    14
    , 5
    , 3
    , '2025-12-24'
    , '2025-12-25'
    , 0
    , NULL
    , 'payment failed'
    , 'ORD_53915083'
    , '1765354566383'
    , 900000
    , 'failed'
    , 'Giao dịch bị từ chối bởi người dùng.'
    , NULL
    , '2025-12-10 15:15:34'
  )
  , (
    15
    , 5
    , 3
    , '2025-12-16'
    , '2025-12-18'
    , 0
    , 1
    , 'cancelled'
    , 'ORD_52239634'
    , '4626377495'
    , 1800000
    , 'success'
    , 'Successful.'
    , NULL
    , '2025-12-10 15:20:55'
  )
  , (
    16
    , 5
    , 3
    , '2025-12-18'
    , '2025-12-21'
    , 1
    , NULL
    , 'booked'
    , 'ORD_58000659'
    , '4630337123'
    , 2700000
    , 'success'
    , 'Successful.'
    , 1
    , '2025-12-16 07:52:05'
  )
  , (
    17
    , 5
    , 3
    , '2026-01-07'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_55616858'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 07:41:59'
  )
  , (
    18
    , 5
    , 3
    , '2026-01-06'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_5495206'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 07:43:57'
  )
  , (
    19
    , 5
    , 3
    , '2026-01-06'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_5220388'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 07:50:39'
  )
  , (
    20
    , 5
    , 3
    , '2026-01-07'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_52461036'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 07:55:47'
  )
  , (
    21
    , 5
    , 3
    , '2026-01-06'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_52194445'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 09:39:34'
  )
  , (
    22
    , 5
    , 1
    , '2026-01-07'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_58422812'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 09:42:44'
  )
  , (
    23
    , 5
    , 1
    , '2026-01-06'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_5613735'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 09:58:22'
  )
  , (
    24
    , 5
    , 1
    , '2026-01-07'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_55706274'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 10:10:16'
  )
  , (
    25
    , 5
    , 1
    , '2026-01-08'
    , '2026-01-10'
    , 0
    , NULL
    , 'pending'
    , 'ORD_58900329'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 10:17:27'
  )
  , (
    26
    , 5
    , 3
    , '2026-01-15'
    , '2026-01-16'
    , 0
    , NULL
    , 'pending'
    , 'ORD_52514547'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 10:25:12'
  )
  , (
    27
    , 5
    , 3
    , '2026-01-07'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_58117256'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 10:40:36'
  )
  , (
    28
    , 2
    , 3
    , '2026-01-06'
    , '2026-01-08'
    , 0
    , NULL
    , 'pending'
    , 'ORD_21261559'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 16:44:52'
  )
  , (
    29
    , 2
    , 3
    , '2026-01-13'
    , '2026-01-14'
    , 0
    , NULL
    , 'pending'
    , 'ORD_29600291'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-06 16:47:41'
  )
  , (
    30
    , 2
    , 3
    , '2026-01-07'
    , '2026-01-09'
    , 0
    , NULL
    , 'pending'
    , 'ORD_2181987'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-07 11:59:51'
  )
  , (
    31
    , 2
    , 3
    , '2026-01-08'
    , '2026-01-09'
    , 0
    , NULL
    , 'pending'
    , 'ORD_27054150'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-07 14:55:14'
  )
  , (
    32
    , 2
    , 3
    , '2026-01-07'
    , '2026-01-09'
    , 0
    , NULL
    , 'pending'
    , 'ORD_22219003'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-07 15:12:24'
  )
  , (
    33
    , 2
    , 3
    , '2026-01-08'
    , '2026-01-09'
    , 0
    , NULL
    , 'pending'
    , 'ORD_25316037'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-07 15:20:39'
  )
  , (
    34
    , 2
    , 3
    , '2026-01-08'
    , '2026-01-09'
    , 0
    , NULL
    , 'pending'
    , 'ORD_21447387'
    , NULL
    , 0
    , 'pending'
    , NULL
    , NULL
    , '2026-01-07 15:25:34'
  )
  , (
    35
    , 2
    , 3
    , '2026-01-08'
    , '2026-01-09'
    , 0
    , 1
    , 'cancelled'
    , 'ORD_21691168'
    , NULL
    , 0
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , NULL
    , '2026-01-07 16:18:48'
  )
  , (
    36
    , 2
    , 3
    , '2026-01-08'
    , '2026-01-09'
    , 1
    , NULL
    , 'booked'
    , 'ORD_22705556'
    , 'TRANS_28758804'
    , 900000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , 1
    , '2026-01-07 17:05:39'
  )
  , (
    37
    , 5
    , 2
    , '2026-01-07'
    , '2026-01-09'
    , 0
    , 1
    , 'cancelled'
    , 'ORD_55616903'
    , 'TRANS_57100348'
    , 900000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , NULL
    , '2026-01-07 22:59:01'
  )
  , (
    38
    , 5
    , 4
    , '2026-01-09'
    , '2026-01-11'
    , 1
    , NULL
    , 'booked'
    , 'ORD_52669623'
    , 'TRANS_55167154'
    , 1600000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , 1
    , '2026-01-08 15:20:18'
  )
  , (
    39
    , 5
    , 2
    , '2026-01-10'
    , '2026-01-11'
    , 1
    , NULL
    , 'booked'
    , 'ORD_56129332'
    , 'TRANS_51796736'
    , 450000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , 1
    , '2026-01-09 06:11:52'
  )
  , (
    40
    , 5
    , 1
    , '2026-01-10'
    , '2026-01-11'
    , 1
    , NULL
    , 'booked'
    , 'ORD_56130802'
    , 'TRANS_55747466'
    , 350000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , 0
    , '2026-01-09 10:01:37'
  )
  , (
    41
    , 5
    , 1
    , '2026-01-10'
    , '2026-01-12'
    , 1
    , NULL
    , 'booked'
    , 'ORD_56792532'
    , 'TRANS_54299424'
    , 700000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , 1
    , '2026-01-09 10:07:30'
  )
  , (
    42
    , 5
    , 4
    , '2026-01-20'
    , '2026-01-22'
    , 0
    , 1
    , 'cancelled'
    , 'ORD_59428012'
    , 'TRANS_55379197'
    , 1600000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , NULL
    , '2026-01-18 12:58:30'
  )
  , (
    43
    , 4
    , 4
    , '2026-03-31'
    , '2026-04-08'
    , 1
    , NULL
    , 'booked'
    , 'ORD_46773420'
    , 'TRANS_46994800'
    , 6400000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , 0
    , '2026-03-27 20:56:44'
  )
  , (
    44
    , 4
    , 3
    , '2026-04-03'
    , '2026-04-04'
    , 0
    , 1
    , 'cancelled'
    , 'ORD_45429613'
    , 'TRANS_49357560'
    , 600000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , NULL
    , '2026-03-27 21:05:37'
  )
  , (
    45
    , 4
    , 2
    , '2026-03-27'
    , '2026-03-28'
    , 0
    , 1
    , 'cancelled'
    , 'ORD_42370061'
    , 'TRANS_4337150'
    , 450000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , NULL
    , '2026-03-27 21:22:24'
  )
  , (
    46
    , 4
    , 4
    , '2026-03-29'
    , '2026-03-31'
    , 1
    , NULL
    , 'booked'
    , 'ORD_4155761'
    , 'TRANS_47498771'
    , 1600000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , 1
    , '2026-03-29 09:49:35'
  )
  , (
    47
    , 4
    , 4
    , '2026-04-01'
    , '2026-04-02'
    , 1
    , NULL
    , 'booked'
    , 'ORD_4747697'
    , 'TRANS_4206418'
    , 800000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , 0
    , '2026-03-29 09:56:08'
  )
  , (
    48
    , 2
    , 2
    , '2026-03-30'
    , '2026-03-31'
    , 1
    , NULL
    , 'booked'
    , 'ORD_28981721'
    , 'TRANS_27775023'
    , 450000
    , 'success'
    , 'Thanh toán trực tiếp thành công'
    , 1
    , '2026-03-29 10:01:42'
  );

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carousel`
--

CREATE TABLE `carousel` (`sr_no` int(11) NOT NULL, `image` varchar(150) NOT NULL) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `carousel`
--

INSERT INTO
  `carousel` (`sr_no`, `image`)
VALUES
  (5, 'IMG_57273.png')
  , (6, 'IMG_89437.png')
  , (7, 'IMG_86759.png')
  , (8, 'IMG_88009.png')
  , (9, 'IMG_11958.png')
  , (10, 'IMG_70980.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_details`
--

CREATE TABLE `contact_details` (
  `sr_no` int(11) NOT NULL
  , `address` varchar(50) NOT NULL
  , `gmap` varchar(100) NOT NULL
  , `pn1` bigint(30) NOT NULL
  , `pn2` bigint(30) NOT NULL
  , `email` varchar(100) NOT NULL
  , `fb` varchar(100) NOT NULL
  , `insta` varchar(100) NOT NULL
  , `tw` varchar(100) NOT NULL
  , `iframe` varchar(300) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contact_details`
--

INSERT INTO
  `contact_details` (
    `sr_no`
    , `address`
    , `gmap`
    , `pn1`
    , `pn2`
    , `email`
    , `fb`
    , `insta`
    , `tw`
    , `iframe`
  )
VALUES
  (
    1
    , 'Duy Trinh, Duy Xuyên, Quảng Nam'
    , 'https://maps.app.goo.gl/Qd2tBatLVFVYe6uP8'
    , 840112223334
    , 84763023690
    , 'lamvn.24it@vku.udn.vn'
    , 'https://www.facebook.com/vo.ngoc.lam.938328'
    , 'https://www.instagram.com/'
    , 'https://x.com/?lang=vi'
    , 'https://www.google.com/maps/embed?pb=!1m13!1m8!1m3!1d7677.056718751282!2d108.217492!3d15.828803999999998!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTXCsDQ5JzQzLjciTiAxMDjCsDEzJzAzLjAiRQ!5e0!3m2!1svi!2sus!4v1762593178114!5m2!1svi!2sus'
  );

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL
  , `icon` varchar(100) NOT NULL
  , `name` varchar(50) NOT NULL
  , `description` varchar(250) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `facilities`
--

INSERT INTO
  `facilities` (`id`, `icon`, `name`, `description`)
VALUES
  (
    5
    , 'IMG_55126.svg'
    , 'Wifi'
    , 'Truy cập internet tốc độ cao ở mọi nơi trong khách sạn. Bạn có thể làm việc hoặc giải trí mà không lo gián đoạn.'
  )
  , (
    8
    , 'IMG_77045.svg'
    , 'Máy điều hòa'
    , 'Truy cập internet tốc độ cao ở mọi nơi trong khách sạn. Bạn có thể làm việc hoặc giải trí mà không lo gián đoạn.'
  )
  , (
    9
    , 'IMG_14440.svg'
    , 'Tivi'
    , 'Truy cập internet tốc độ cao ở mọi nơi trong khách sạn. Bạn có thể làm việc hoặc giải trí mà không lo gián đoạn.'
  )
  , (
    10
    , 'IMG_82782.svg'
    , 'Máy sưởi phòng'
    , 'Truy cập internet tốc độ cao ở mọi nơi trong khách sạn. Bạn có thể làm việc hoặc giải trí mà không lo gián đoạn.'
  )
  , (
    12
    , 'IMG_11292.svg'
    , 'Máy nước nóng'
    , 'Truy cập internet tốc độ cao ở mọi nơi trong khách sạn. Bạn có thể làm việc hoặc giải trí mà không lo gián đoạn.'
  );

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `features`
--

CREATE TABLE `features` (`id` int(11) NOT NULL, `name` varchar(50) NOT NULL) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `features`
--

INSERT INTO
  `features` (`id`, `name`)
VALUES
  (2, 'Phòng ngủ')
  , (4, 'Ban công')
  , (5, 'Phòng bếp');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rating_review`
--

CREATE TABLE `rating_review` (
  `sr_no` int(11) NOT NULL
  , `booking_id` int(11) NOT NULL
  , `room_id` int(11) NOT NULL
  , `user_id` int(11) NOT NULL
  , `rating` int(11) NOT NULL
  , `review` varchar(200) NOT NULL
  , `seen` int(11) NOT NULL DEFAULT 0
  , `datentime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `rating_review`
--

INSERT INTO
  `rating_review` (
    `sr_no`
    , `booking_id`
    , `room_id`
    , `user_id`
    , `rating`
    , `review`
    , `seen`
    , `datentime`
  )
VALUES
  (
    12
    , 48
    , 2
    , 2
    , 5
    , 'Chất lượng phục vụ tuyệt vời'
    , 0
    , '2026-03-29 10:03:12'
  );

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL
  , `name` varchar(150) NOT NULL
  , `area` int(11) NOT NULL
  , `price` int(11) NOT NULL
  , `quantity` int(11) NOT NULL
  , `adult` int(11) NOT NULL
  , `children` int(11) NOT NULL
  , `description` varchar(350) NOT NULL
  , `status` tinyint(4) NOT NULL DEFAULT 1
  , `removed` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `rooms`
--

INSERT INTO
  `rooms` (
    `id`
    , `name`
    , `area`
    , `price`
    , `quantity`
    , `adult`
    , `children`
    , `description`
    , `status`
    , `removed`
  )
VALUES
  (
    1
    , 'Standard Room'
    , 25
    , 350000
    , 8
    , 2
    , 2
    , 'Standard Room là lựa chọn phù hợp cho khách du lịch cá nhân hoặc cặp đôi. Phòng có không gian vừa phải, đầy đủ tiện nghi cơ bản, khu vực bếp nhỏ, mang lại sự thoải mái cho những kỳ nghỉ ngắn ngày.'
    , 1
    , 0
  )
  , (
    2
    , 'Superior Room'
    , 30
    , 450000
    , 6
    , 2
    , 2
    , 'Superior Room mang đến không gian rộng rãi và thoải mái hơn so với phòng tiêu chuẩn. Phòng được thiết kế hiện đại, phù hợp cho khách nghỉ dưỡng muốn trải nghiệm sự tiện nghi với mức chi phí hợp lý.'
    , 1
    , 0
  )
  , (
    3
    , 'Deluxe Room'
    , 35
    , 600000
    , 5
    , 3
    , 3
    , 'Deluxe Room là lựa chọn lý tưởng cho gia đình nhỏ hoặc nhóm khách. Không gian rộng rãi, thiết kế sang trọng cùng khu vực tiếp khách riêng biệt mang lại trải nghiệm nghỉ dưỡng cao cấp và thoải mái.'
    , 1
    , 0
  )
  , (
    4
    , 'Suite Room'
    , 50
    , 800000
    , 3
    , 4
    , 4
    , 'Phòng cao cấp nhất, không gian rộng, có khu vực sinh hoạt riêng biệt, đầy đủ tiện nghi và tính năng'
    , 1
    , 0
  );

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_facilities`
--

CREATE TABLE `room_facilities` (
  `sr_no` int(11) NOT NULL
  , `room_id` int(11) NOT NULL
  , `facilities_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_facilities`
--

INSERT INTO
  `room_facilities` (`sr_no`, `room_id`, `facilities_id`)
VALUES
  (83, 2, 5)
  , (84, 2, 8)
  , (85, 2, 9)
  , (86, 1, 5)
  , (87, 1, 8)
  , (88, 1, 9)
  , (92, 4, 5)
  , (93, 4, 8)
  , (94, 4, 9)
  , (95, 4, 10)
  , (96, 4, 12)
  , (107, 3, 5)
  , (108, 3, 8)
  , (109, 3, 9)
  , (110, 3, 10)
  , (111, 3, 12);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_features`
--

CREATE TABLE `room_features` (
  `sr_no` int(11) NOT NULL
  , `room_id` int(11) NOT NULL
  , `features_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_features`
--

INSERT INTO
  `room_features` (`sr_no`, `room_id`, `features_id`)
VALUES
  (60, 2, 2)
  , (61, 2, 4)
  , (62, 2, 5)
  , (63, 1, 2)
  , (64, 1, 4)
  , (65, 1, 5)
  , (69, 4, 2)
  , (70, 4, 4)
  , (71, 4, 5)
  , (81, 3, 2)
  , (82, 3, 4)
  , (83, 3, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_images`
--

CREATE TABLE `room_images` (
  `sr_no` int(11) NOT NULL
  , `room_id` int(11) NOT NULL
  , `image` varchar(150) NOT NULL
  , `thumb` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_images`
--

INSERT INTO
  `room_images` (`sr_no`, `room_id`, `image`, `thumb`)
VALUES
  (6, 2, 'IMG_42258.jpeg', 1)
  , (11, 3, 'IMG_47870.png', 1)
  , (15, 1, 'IMG_53084.jpeg', 1)
  , (18, 4, 'IMG_50524.jfif', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `sr_no` int(11) NOT NULL
  , `site_title` varchar(50) NOT NULL
  , `site_about` varchar(250) NOT NULL
  , `shutdown` tinyint(1) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO
  `settings` (`sr_no`, `site_title`, `site_about`, `shutdown`)
VALUES
  (
    1
    , 'Khách sạn NLam'
    , 'Chào mừng bạn đến với Khách sạn NLam. Chúng tôi mang đến trải nghiệm nghỉ dưỡng tiện nghi và thoải mái, với dịch vụ tận tâm và không gian sang trọng. Hãy để chúng tôi chăm sóc kỳ nghỉ của bạn thật trọn vẹn.'
    , 0
  );

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `team_details`
--

CREATE TABLE `team_details` (
  `sr_no` int(11) NOT NULL
  , `name` varchar(50) NOT NULL
  , `picture` varchar(200) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `team_details`
--

INSERT INTO
  `team_details` (`sr_no`, `name`, `picture`)
VALUES
  (9, 'Quản lý 1', 'IMG_79853.jpeg')
  , (10, 'Quản lý 2', 'IMG_18303.jpeg')
  , (11, 'Quản lý 3', 'IMG_57964.jpeg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_cred`
--

CREATE TABLE `user_cred` (
  `id` int(11) NOT NULL
  , `name` varchar(100) NOT NULL
  , `email` varchar(150) NOT NULL
  , `address` varchar(120) NOT NULL
  , `phonenum` varchar(100) NOT NULL
  , `pincode` int(11) NOT NULL
  , `dob` date NOT NULL
  , `profile` varchar(100) NOT NULL
  , `password` varchar(200) NOT NULL
  , `is_verified` int(11) NOT NULL DEFAULT 0
  , `token` varchar(200) DEFAULT NULL
  , `t_expire` date DEFAULT NULL
  , `status` int(11) NOT NULL DEFAULT 1
  , `datentime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_cred`
--

INSERT INTO
  `user_cred` (
    `id`
    , `name`
    , `email`
    , `address`
    , `phonenum`
    , `pincode`
    , `dob`
    , `profile`
    , `password`
    , `is_verified`
    , `token`
    , `t_expire`
    , `status`
    , `datentime`
  )
VALUES
  (
    2
    , 'Võ Ngọc Lâm'
    , 'vongoclam000@gmail.com'
    , 'Duy Xuyên, Đà Nẵng'
    , '0763023690'
    , 123
    , '2025-12-04'
    , 'IMG_92822.jpeg'
    , '$2y$10$.uc9remWXBAr31biBdTbOuTJ5eTtVbF5Pfr1JbH0Vg.UGm6npdJZ.'
    , 1
    , NULL
    , NULL
    , 1
    , '2025-11-27 21:29:58'
  )
  , (
    4
    , 'Võ Lâm'
    , 'vongoclam00@gmail.com'
    , 'âdasd'
    , '0999999999'
    , 123123
    , '2025-12-31'
    , 'IMG_46365.jpeg'
    , '$2y$10$nrEXP9LKfzXFmtprToW0zuz.jgBn9TfpMT6te61oXSjgv6fwm2dEe'
    , 1
    , NULL
    , NULL
    , 1
    , '2025-12-08 23:09:20'
  )
  , (
    5
    , 'Võ Ngọc Lâm'
    , 'vongoclam00000@gmail.com'
    , 'Đà Nẵng'
    , '0999999988'
    , 123
    , '2025-12-25'
    , 'IMG_45846.jpeg'
    , '$2y$10$UVE2CNuZaW75Ljz838xBvuQnoB45IVrhDjTit5qOjmWIgg4zdegme'
    , 1
    , NULL
    , NULL
    , 1
    , '2025-12-10 10:29:24'
  );

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_queries`
--

CREATE TABLE `user_queries` (
  `sr_no` int(11) NOT NULL
  , `name` varchar(50) NOT NULL
  , `email` varchar(150) NOT NULL
  , `subject` varchar(200) NOT NULL
  , `message` varchar(500) NOT NULL
  , `datentime` datetime NOT NULL DEFAULT current_timestamp()
  , `seen` tinyint(4) NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_queries`
--

INSERT INTO
  `user_queries` (
    `sr_no`
    , `name`
    , `email`
    , `subject`
    , `message`
    , `datentime`
    , `seen`
  )
VALUES
  (
    20
    , 'Lâm Võ Ngọc'
    , 'vongoclam000@gmail.com'
    , 'abc'
    , 'hello'
    , '2026-03-29 10:03:54'
    , 0
  );

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin_cred`
--
ALTER TABLE `admin_cred`
ADD PRIMARY KEY (`sr_no`);

--
-- Chỉ mục cho bảng `booking_details`
--
ALTER TABLE `booking_details`
ADD PRIMARY KEY (`sr_no`)
, ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `booking_order`
--
ALTER TABLE `booking_order`
ADD PRIMARY KEY (`booking_id`)
, ADD KEY `user_id` (`user_id`)
, ADD KEY `room_id` (`room_id`);

--
-- Chỉ mục cho bảng `carousel`
--
ALTER TABLE `carousel`
ADD PRIMARY KEY (`sr_no`);

--
-- Chỉ mục cho bảng `contact_details`
--
ALTER TABLE `contact_details`
ADD PRIMARY KEY (`sr_no`);

--
-- Chỉ mục cho bảng `facilities`
--
ALTER TABLE `facilities`
ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `features`
--
ALTER TABLE `features`
ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `rating_review`
--
ALTER TABLE `rating_review`
ADD PRIMARY KEY (`sr_no`)
, ADD KEY `booking_id` (`booking_id`)
, ADD KEY `room_id` (`room_id`)
, ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `rooms`
--
ALTER TABLE `rooms`
ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `room_facilities`
--
ALTER TABLE `room_facilities`
ADD PRIMARY KEY (`sr_no`)
, ADD KEY `facilities id` (`facilities_id`)
, ADD KEY `room id` (`room_id`);

--
-- Chỉ mục cho bảng `room_features`
--
ALTER TABLE `room_features`
ADD PRIMARY KEY (`sr_no`)
, ADD KEY `rm id` (`room_id`)
, ADD KEY `features id` (`features_id`);

--
-- Chỉ mục cho bảng `room_images`
--
ALTER TABLE `room_images`
ADD PRIMARY KEY (`sr_no`)
, ADD KEY `room_id` (`room_id`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
ADD PRIMARY KEY (`sr_no`);

--
-- Chỉ mục cho bảng `team_details`
--
ALTER TABLE `team_details`
ADD PRIMARY KEY (`sr_no`);

--
-- Chỉ mục cho bảng `user_cred`
--
ALTER TABLE `user_cred`
ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user_queries`
--
ALTER TABLE `user_queries`
ADD PRIMARY KEY (`sr_no`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin_cred`
--
ALTER TABLE `admin_cred` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 3;

--
-- AUTO_INCREMENT cho bảng `booking_details`
--
ALTER TABLE `booking_details` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 49;

--
-- AUTO_INCREMENT cho bảng `booking_order`
--
ALTER TABLE `booking_order` MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 49;

--
-- AUTO_INCREMENT cho bảng `carousel`
--
ALTER TABLE `carousel` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 12;

--
-- AUTO_INCREMENT cho bảng `contact_details`
--
ALTER TABLE `contact_details` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 2;

--
-- AUTO_INCREMENT cho bảng `facilities`
--
ALTER TABLE `facilities` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 17;

--
-- AUTO_INCREMENT cho bảng `features`
--
ALTER TABLE `features` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 14;

--
-- AUTO_INCREMENT cho bảng `rating_review`
--
ALTER TABLE `rating_review` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 13;

--
-- AUTO_INCREMENT cho bảng `rooms`
--
ALTER TABLE `rooms` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT cho bảng `room_facilities`
--
ALTER TABLE `room_facilities` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 112;

--
-- AUTO_INCREMENT cho bảng `room_features`
--
ALTER TABLE `room_features` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 84;

--
-- AUTO_INCREMENT cho bảng `room_images`
--
ALTER TABLE `room_images` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 19;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 2;

--
-- AUTO_INCREMENT cho bảng `team_details`
--
ALTER TABLE `team_details` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT cho bảng `user_cred`
--
ALTER TABLE `user_cred` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 7;

--
-- AUTO_INCREMENT cho bảng `user_queries`
--
ALTER TABLE `user_queries` MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT
, AUTO_INCREMENT = 21;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `booking_details`
--
ALTER TABLE `booking_details`
ADD CONSTRAINT `booking_details_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`);

--
-- Các ràng buộc cho bảng `booking_order`
--
ALTER TABLE `booking_order`
ADD CONSTRAINT `booking_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`)
, ADD CONSTRAINT `booking_order_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Các ràng buộc cho bảng `rating_review`
--
ALTER TABLE `rating_review`
ADD CONSTRAINT `rating_review_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`)
, ADD CONSTRAINT `rating_review_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
, ADD CONSTRAINT `rating_review_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`);

--
-- Các ràng buộc cho bảng `room_facilities`
--
ALTER TABLE `room_facilities`
ADD CONSTRAINT `facilities id` FOREIGN KEY (`facilities_id`) REFERENCES `facilities` (`id`)
ON
UPDATE
  NO ACTION
, ADD CONSTRAINT `room id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
ON
UPDATE
  NO ACTION;

--
-- Các ràng buộc cho bảng `room_features`
--
ALTER TABLE `room_features`
ADD CONSTRAINT `features id` FOREIGN KEY (`features_id`) REFERENCES `features` (`id`)
ON
UPDATE
  NO ACTION
, ADD CONSTRAINT `rm id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
ON
UPDATE
  NO ACTION;

--
-- Các ràng buộc cho bảng `room_images`
--
ALTER TABLE `room_images`
ADD CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;