-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 20, 2026 at 11:18 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moodhelperdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `anonymous_messages`
--

DROP TABLE IF EXISTS `anonymous_messages`;
CREATE TABLE IF NOT EXISTS `anonymous_messages` (
  `message_id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `mood` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anonymous_messages`
--

INSERT INTO `anonymous_messages` (`message_id`, `sender_id`, `receiver_id`, `mood`, `message_text`, `is_read`, `created_at`) VALUES
(1, 6, 5, 'anxious', 'you are doing okayy', 1, '2026-04-11 23:23:48'),
(2, 8, 7, 'angry', 'i hope you feel better', 1, '2026-04-18 14:51:12'),
(3, 5, 7, 'happy', 'yayy', 0, '2026-04-20 07:48:09'),
(4, 5, 7, 'happy', 'hiii', 0, '2026-04-20 14:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

DROP TABLE IF EXISTS `badges`;
CREATE TABLE IF NOT EXISTS `badges` (
  `badge_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required_streak` int NOT NULL,
  PRIMARY KEY (`badge_id`),
  UNIQUE KEY `badge_id` (`badge_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`badge_id`, `name`, `description`, `required_streak`) VALUES
(1, '7-day streak', 'Logged in 7 consecutive days', 7),
(2, '1-month streak', 'Logged in 30 consecutive days', 30),
(3, '6-month streak', 'Logged in 180 consecutive days', 180),
(4, '1-year streak', 'Logged in 365 consecutive days', 365);

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `user_id`, `role`, `message`, `created_at`) VALUES
(1, 1, 'user', 'F3pCizFuTyPVY3Qk4lO1cA==', '2026-04-17 07:35:14'),
(2, 1, 'assistant', 'kEMxL92k2qy7fErpUc+XEXg+GePfoZ7OAEoDytEgqZ+dNPYnBfGnfhfrmKAbfrAozoUF6fO10SSw1s9zo275Qw==', '2026-04-17 07:35:14'),
(3, 1, 'user', 'UGLrjLGUXPtWizK2NTLt4XhXB6sMtRpoBKyua1ogTVrqcdMfnIXBoSD9PXbUD5QQ', '2026-04-17 07:35:26'),
(4, 1, 'assistant', 'AkakaDyYmii0ECWc4JQHZQVysXy++Oe8l+Y2XhcF1caJroo5uJfFCPM+YdXhgZCoGrzLuYuM+0wHanrNcNHCkeUIJpUZKsMBq9KE6AxdSzWrTihWebScOGX4kXnSEjx3yiUXvUo8hpV6yzQQngdCJVSIhadPKhG5Cf6RQLfxIus=', '2026-04-17 07:35:26'),
(5, 1, 'user', 'BJoAXqw4cq/EH5RemsQlEA==', '2026-04-17 07:40:15'),
(6, 1, 'assistant', 'vIXx9XHh5ryhgPeyidnicPlWAu2Jr+0rzaEe0f5aNc3Woc/kvAU0ZeNLgjs0FX/A/Zs6nYffRUDN77bXghQlP0+A2VbRa/U1yKkL7ECmQ3o=', '2026-04-17 07:40:15'),
(7, 1, 'user', 'f0i2q0pNn/43n3S73d2fZg==', '2026-04-17 08:07:48'),
(8, 1, 'assistant', 'igwCeHQq+WO74jQ4LkvLkp3iKzUYP4zVqcvrNI6G/mCn7mzsjSO+Gib1RZ73/5RGBO25OEjhHmJjOQLXXc9zI6KHP7ZBNwZxr8fxMsN9DjWjdVB0IsbV2gDA6IYfm7U9', '2026-04-17 08:07:48'),
(9, 1, 'user', '8g0G875HEJkjjLm30ub6+vSaMrKSWRFw3V1zKHjB8QE=', '2026-04-17 08:08:02'),
(10, 1, 'assistant', 'xA4yS8fdezzEvj9fxkwxbFG4EXuckDKCo2f3Z44fGVW9XACR3zN63BAe1JFuz1lFye8aSQyhWD1lc63pd9f9OjvDhgbJGIVtk4LLOOKeUugkzBJlGE11T00IKWtP9exEtzYVyQFIp/IMagRnKhMAQQLYvRuYV/w3QE+6mI6iQms=', '2026-04-17 08:08:02'),
(11, 1, 'user', 'Zd0NzYhb+1qlyJZPPF1Hyg==', '2026-04-17 08:08:09'),
(12, 1, 'assistant', 'mLgI9yjanSiV24o7OFyqdj3Jn3IalPhsOaa3KjyTnBmV/HoRHDpB2kbyjjix5k5Btej51HbMmRUM/kXM7II+YRg41wulkf+C3REmWZlPBIfxN2jyhbhOn2jMSpkAtQUWcf5icogjkDZgEhZLN/Q7baQV96DW0xzQu3T6uo6AW8+4S0cNteOtwQaWtsOqhRwW+jbT199bKg3nYxv1zgfmXRNNNm11vWjzUz2pgExuZTPuZkXa73fE2md8LH18uFCz6CnE/bqZMxhaTd77pHpUog==', '2026-04-17 08:08:09'),
(13, 1, 'user', 'F5qeGJkQtBGTsX9bj46g9YaZ4BadeMmtbKfsPzv8+yk=', '2026-04-17 08:08:24'),
(14, 1, 'assistant', 'wx03LUv8KFm7ARH85nOisyO4C7oKr7ztZYM0zS+C2O75U/oYgDu8PdKTpr7RubOsK6AsPt4LIoPYa86muSLyUrQrLzH+6dii5lBphahQqXuqNSn6n3duSHY3BEH07AyILo0r3YUO2ydWmp7c/HDGLpK6PBbYFYgAq905Z4Ii71HDMVQmLD5y0o9rQJZBQVEXrdM7p48TLeMBDh2aslKXNiE8H1uLt7LQ8oCd7Ot0IQbdP8JG8AbHRyT+DJPcmYMQD7VYrInemLCm0i0bX5nL/X5mtCdXkWlq0GMsQMPyQXg=', '2026-04-17 08:08:24'),
(15, 1, 'user', 'uHxXoa+F6WnSGmAmb9Hw8Vh+h+lCIPUvE1yn3wmrGEckT+oNPPAp1M8bIEEm6bCb', '2026-04-17 08:08:45'),
(16, 1, 'assistant', 'ZvxvLQ0zgYIiUKI049OjYEZeFseHSghwQi05L71n3VUP9lfbWEDwKuryB6ZYPhOL1bdk6+NR7SagMSjybUAB9DpQ5WlLnJbOnugBNgpEA2yZ4smLC7DmaNJW6Vh3gXCU5tHH0ZvDoSDmHY1PqpyE1tWuiaTGvVvXM5p2vbojUDpIcdhzkdOrDlYZ6zyXECygrS3f4+JX6AZITUdNlbpb1VBK91ii2dl1M3X3E2GLil8I3Z5l4rZ4Wylv0XF2EUQqC3Jyzf0WBpcm5r2x/YsR/27GB7ge/ifc6yfufw9xKOE2/Oi98hoaT2ipv+SWYpJm288sNME6h1vea5ORc9w7Iwgmf4QYcUzzJsSBDjnyX2zvgendEOSkEuo8zHn51EmB+QTq+FFPie9TonXyTVSYsw==', '2026-04-17 08:08:45'),
(17, 1, 'user', 'F3pCizFuTyPVY3Qk4lO1cA==', '2026-04-17 08:30:58'),
(18, 1, 'assistant', 'V+7TuFv8ifFee2HFgxG3wWW32mq/mUyAdcQfa8VumZKxL6eKl4W4UGZOjT7PJwUmzU9WR/XOYOvTaQ3ctINg0kTPB41O1o4Kt3soQbn+aKCHT/qNSs1fc+oWfj1deI3QELbs22HWge8HeAOkgXvVFQ==', '2026-04-17 08:30:58'),
(19, 1, 'user', 'ak0y7eST95ivw7r5lm84GqPoLjKb62fZr4bef03d2O8=', '2026-04-17 16:18:57'),
(20, 1, 'assistant', '8SiQPQFmUDguQyjNi1oYJl+KfqGlLexrAUZk46WeG+XG/Jcn/XFyON/kEySn47rW6Dg+qRpNKYM8/BW/oxBkAK6v/u66QNwQZeOUJQACETnbUafpU7viGw6DAwmOmzRDIr3gzyLqXp9oX1Oja7GaKf8tnOTMpG2m3/NjywnCrGpZ+vBpIHyMFGc9uyDUxrrww9u1C0e8XmQUiGLvqit2m6ad1TrlBUxsR9dhgMGuQuftT9TFPIwYVdpfVEBtvetLz02KPc7Xxb2ZH7eJ/917hg==', '2026-04-17 16:18:57'),
(21, 1, 'user', 'aZsa1+KNNgmjn0BZMvE4vacTQFTrb8fK/5KwuB2Nkpd/Ekk9hExaI7p5tg9M0JuY', '2026-04-17 16:19:09'),
(22, 1, 'assistant', 'GVs05551jYc0SfuqL+PYN0VvLuDNAZkgIDas3RH2J0JuiIJzvgAJemLSXUSPMExS6nx9JhIkDMd8+cyIT+wGY7OigSQgZ5SRfIBbvV3otfoCNctmayFlnFEn7m0PEcUdb5SGdBuWW3F1omH1Ut/AnNjXvl6M91gya/oMLOPVdrlAB8Q7yIv8qgtdxRVhI9H1d87bphDVoL3Z7r/ZKid5fao7RRyWPdiogF0aG7RkCy/5k5vQdPM9ZIuwsYc8V4N3DLD2VeuO/uU0QSvZqAeAo/94fnkzNIuBuFWecOwvemS1368w5/cAcp0vi7gkQao4zPGK2z5Q5flRPMXFpkkqsIRvz/qVVfyKixDNenaqFmlSsl7ltPWDsc54DFxYk0qHs3SZgvyOEg8RZ2Kr9xA4yQ==', '2026-04-17 16:19:09'),
(23, 1, 'user', 'pudUAlt67XFTypZBBzt0Qf7BiE4zT4WzlGB8pUWHeOw=', '2026-04-17 16:19:27'),
(24, 1, 'assistant', '+NGGP5a9x2jjY+OQhA27zmz1UTqc4+g/PpwryMNs0hVuD2NX4WlCRmIycz6e+gsm7Ki3ybPmIzYACb/qgahvV5kZJ0/E5+zOj034SVKh5nNKgh+5cPp4KXm9fr4z7wLLY9lvx5afRtSNg1DVtcvkmiLK7Ix5OvSK1FHXuDLYWsDhO/K66nHJ8QJxGmnQj04X+VvkEysRgWPLOLiq/NPJL6/V18BIas3kfetyhJGr2Ha3VzCEZDH+gkkiAmxqU6SG0dG05V/A6tHINsOu8gtYB+uUgRS2nxZFnQMtUMH/D/c=', '2026-04-17 16:19:27'),
(25, 1, 'user', 'biER+uCjGSzJFLPCZGTd2DWxgBIICL3v3zxVHST4HLLyrOckXQ/4G2++zRN66fG/eBldv46H0XQyc+5QukRfrjCWSPzIq+ltv4vqKdiB3C3XQ/sIeF7FxQbD+bhFV1Az', '2026-04-17 16:20:27'),
(26, 1, 'assistant', 'Be+jttUUWv8P82xKsRv0PXVH5+nBaffUzznpHExW/M4+RSbgcQQgNnOfC+kbrn7a/Nhf5QAfRacMDNVW3IMGla32vgBnQ8ZBhUUq9AZcva0jKeKgejHWgCPWWdlq/jxhHU2rJjJJOd5fJx0UfdjAoNZNJ1RiW55X0ODtCcbSWPw1g2JdVS97oI626VzHhdbPgPZdZodVWCo5mNLC+ISTCMiQoiyf91zyp6qbbIPgbn5jOYuo2KD7U0tIYRQUs4USWt68WBiUCV9LHJM5dTx/itSRyINdrlZmO0V44l3vuSVEEQhA8HgSk+qtGx2ks9lSlJxWWAdXG+3qX8WwQdDbQVF+zW8chrhYsOhMsNP73lwELAjAToh9myqEi06qlXMkeywyMdENmdvWBRqh0J3bFg==', '2026-04-17 16:20:27'),
(27, 1, 'user', 'F3pCizFuTyPVY3Qk4lO1cA==', '2026-04-17 16:22:24'),
(28, 1, 'assistant', 'V+7TuFv8ifFee2HFgxG3wR6eA1OUBgWh5f7JE04aPrzy+wRnmqwQXomtr+PqbpXbCqZPAoSIOx34/mnL6gio6+4QwrPYabUFkwWs3xjO1X/e9aHuWwsQn1wtINgSpLm5h7ODj1QfT58Z67/shlhdiTvQhBdJXVOygAi2XEew4azqxcB+SiVlrzZZd8Pvm3ET', '2026-04-17 16:22:24'),
(29, 8, 'user', 'iryuQqvgDEWG+VmQc6H7zGtRY/GKBVd2swWoZD4LI64=', '2026-04-18 11:51:22'),
(30, 8, 'assistant', 'AwvxyhVCV+NaoNw0581Mv/e3eEBZBkvwV2wBCu/Rd079sVKEIYOB8z7FM+KPDjH7+sKJSzaKq1UhD5890L2RsffJW4WEn9as/sxrBpMlyim79nl50iRwrp/i/pfLYA1R', '2026-04-18 11:51:22'),
(31, 8, 'user', 'C0q0qhbNj1yAlFJAC04q+hrZCf2aooMj+uWIw5g3HKQ=', '2026-04-18 11:52:08'),
(32, 8, 'assistant', '+7i7lgd7ucjOjH1o0KPpcgLQO1OnE923HT/eG7cYFt5bPJ9fxXkbhKI9GIvjrZuqM80T9rWavCdeh9VK0Cltp9+SdTogiwryua1yaECBCHcKSOLuHoqXVUBUATG0VBlKAmlZp63zVAVJ1ZzmC7UFqyGK8ymOPX1VMgVzZJ8tKulT5+27RjYdhpjWyYdcCwMkj+wQjKZGwO9anakf5V5UesE+FB16+P3f8lpLYdc/OUB6waOQ/CiE7S3PMdyGAqmTfk5QGn63yyMwLF/miN7/lw==', '2026-04-18 11:52:08'),
(33, 5, 'user', 'n+D7mLzea9VmNgp/uf9WLw==', '2026-04-20 04:51:46'),
(34, 5, 'assistant', 't4jocVgyvgp0/rKgjkdeOiJ9fe4DUplHmQ7B7gLdRNe7/hHcCv3NwkQ52pHl2ROjCWR33awS+jQ4nMMYrDgYycPYfugIzrV/v7D8+T1b4RZWh2iixFi+Ju1b7JwA4j+pySjvem4Vu3SqD8CnFohisLDELY1kXCnCguKaT5EZedw=', '2026-04-20 04:51:46'),
(35, 5, 'user', 'F3pCizFuTyPVY3Qk4lO1cA==', '2026-04-20 04:54:12'),
(36, 5, 'assistant', '880zgcRdFSOdeyx85tlhA3SSG6A6GdJfQSfHxuay0ZGCBMjV55J5zqjWK2nHCcv6GbNn4CzMXZTWlf67/QQ+XXeH+KgUZ63ibOdG44/MPEE=', '2026-04-20 04:54:12'),
(37, 5, 'user', 'F3pCizFuTyPVY3Qk4lO1cA==', '2026-04-20 08:32:33'),
(38, 5, 'assistant', 'pz/e04ZTVits9vwh+4qG3+Yrp6nRwZow9yyQWnmCiKg/+yksOXUcfxkRTuDAfrz3zmZK+N5SISUMM8f0o7gk7o/9GXEk0s2tEcdmtVAKD8A=', '2026-04-20 08:32:33'),
(39, 5, 'user', 'F3pCizFuTyPVY3Qk4lO1cA==', '2026-04-20 08:33:13'),
(40, 5, 'assistant', 'v4Aqbf3K625XmaViarYUGCuq3DcFHlNtxuTzw5tzlg4ZSWMbfjqIWBgN6DPBkiBm7IHxaVc40jqqNAVaVpOu/sLICs7+9phxF4UzHWrBpiBg9t2dWWoV/SfhFiRdOjlKbd15AFh2amVxTSaxHntS1M6tysN3a0liHs/KxBI2TGBFsodPYkseZrdEvmzqVl52xch1WJxY9AFLT+dUIbXc7AlIHTjtnAS0hozuronjFDQ=', '2026-04-20 08:33:13'),
(41, 5, 'user', 'F3pCizFuTyPVY3Qk4lO1cA==', '2026-04-20 08:46:46'),
(42, 5, 'assistant', 'kiCmNDXR2zN6ffuvWwTBiChVCOy1U3U7R0VUyqooNRCG1bU5xkZOCyBwRnygxgIk6MOkoWAPikmH7fFB8M0igluMUMN/NP9awqr7+BKs69DJq6RowUye1g24Hx96IIoHy/vM5TGaYthfD7zOeD7h2Euiewh5/2ISDtrfhH/wkeyLWoWXlSn49daCfxuS93m+', '2026-04-20 08:46:46'),
(43, 5, 'user', 'nkdJENQom1M9VtpWHyFX6w==', '2026-04-20 09:00:33'),
(44, 5, 'assistant', 'zQhGMvWpVwxm8UehuaBC87zt+8bFd4deO40uRYDJ+jPuvbo+HBs1C8t6y+rV3R1yCBcGPA0FOLF+l6YIgzB11hPNeTyxpy/faNASqtYmlGlM5MyKjY6tSwpcn0XxDXO+N+e9Chgd3yf7HYkax8y5RA==', '2026-04-20 09:00:33'),
(45, 5, 'user', 'zQhGMvWpVwxm8UehuaBC87zt+8bFd4deO40uRYDJ+jPuvbo+HBs1C8t6y+rV3R1yCBcGPA0FOLF+l6YIgzB11hPNeTyxpy/faNASqtYmlGlM5MyKjY6tSwpcn0XxDXO+3SpsrcKletwlfnv9VYrKHh6hAT87ZY+yYA5YLY06tycL1dp6WyOdGxOL6MS/UzKVvbhmICXK0Y811Vml7nMZsFxWot+DHd+6jQlYmG4x0abyWD+t2kTbqouwZsirL/4BM+lKyghJETnaeoxatmhL/w==', '2026-04-20 09:26:47'),
(46, 5, 'assistant', 'iboFVHPpp2ZLHBCw3ITjTu23IIRBm8ib4Q3IYKkAeepEJk/qJDyxFMEs9itb2XvLQzk7B03oyGK7dFVEFHwLU9m237pSp5/uw7PD4e9S4Fth17i6CD1hzuZLX48SqaX0oqrrRAOLVN4MxkKRXmmGHlVJVcbFvno6EVmEZoAdFXTmCkyAHzNUORWpHiAJZ56nXsE2UnL6qg2VujiulSRN/uGWUaU+C63ROYFwq9PQ1MiFhd9xtO/5kDLi5OGg8+1L', '2026-04-20 09:26:47'),
(47, 5, 'user', 'z9IjJSyphSaS9y6Fp5g0/Pj0h2t4WnwCC6YptygaYfR8nVQDTWIB6zwCxQIqe1el7PhcrVex//6fN/okAvihoL1ZFBiCY+kF2ei6cWSKong/KYZ4+CA0S8P/9t3D5grwIkdbHZnZAkXSHAKDwEbGBQowbZODUC1v8uvM4xAAhkmLDSffht7eD7kVF8lx+0gwCwXnztffPMx5/3TLBd00LjbkAX396p0AssqrsqQf81FTIym1hbJNboTlJh59qNfdjlA4IEi2cmc/vnJfR9i1GrPvJxkc9EuUqC8aX28/pTTVhnk/KOopHRMi7oBpgr/IVckgsj+Nv0M90OJUecjHvA==', '2026-04-20 09:50:49'),
(48, 5, 'assistant', 'lDbZCTAlFbUW19ohvjnrbHcRzor1TqQJkZ4+TY5zP98TzKiVOHiBFEEqNNL9GOlkWx4DpDtUDVUHrGU2KLYJYzPLnyFYodKGNV3WyO7xFJMqyUG7LOpfztqA5V59Z0CL4Ozj78nk3iWV7N5XYZGbnJXGmQMSEvz9m7hYSt75v7xzvnpD7cQkdX4JVRDWXuDoHN+ksVhglrI42aQaJab7cTLXZX3o8XJuhYcqUCdrX7uZ+Zaz/IAmOCJCsJldUjyh7PcpOAI/4WJ4ezmOc4dEUqkYqnG/+a2O8L2Odj4ADok=', '2026-04-20 09:50:49'),
(49, 5, 'user', 'XEjxrntv71+G5yrsVXxXp2mNnehHgpX04XxG/1sv0qiZvsQ9/KTeGgIJCQtEWgZ4dN0/mrqMgUngcqmJscEZnqq59W7K0KH79QzL+MS8I/X6zZc3Y1nootCzlqPiQEs4r7DH2U+/MF5GfOWRq/sfBUyZUmNebHtC6eBZIczGNqfkOfr2jPDGmi6Azb65QVsEYNa6F6BTmntLpf75gejxY2zP56MPtJthH32TWP6LQT/P7EV9YYTCyv1QOgRGPgvq76U9O+MrBMQDiRR78ufy3C4iJAfVx+S3phjmBP7osoA=', '2026-04-20 10:31:55'),
(50, 5, 'assistant', '90jOpwcOEV9EIGZYxvJtdr/Rd1RTz9PaYxQSHn70Ruuw42ImCI/2kYlRNNtmRSU6s1Zyb/WOzJ/kwvz40lF59zaYWQB5+TLUL6SpF/j/fk6D5Jm2YofwvfI4yBbrtb0aiBo2qo6uXpi9tBl+QIVIm2ZZl9k6A+YXWu9fHzfpWZU3jaLVu20LzA2uiKvbwCmBMhRBlX5bpvfHSwt9pjRDvFgXrfGbhUhNVEUHWg9rElhJW12qIfBZTQN312g52U9MN/RcQ6hxfw5M3go2mntJFg==', '2026-04-20 10:31:55'),
(51, 5, 'user', 'Za1ui5vZTaePASKe8bTSFQ==', '2026-04-20 10:32:29'),
(52, 5, 'assistant', 'FTFxIcIYcS68GO45AuPD6eb+rWEhmUUghrNSG8proZQeqdOkHGDiLCS8vl0wQOMp6TeF8D2rO6AutUeZm/IrMsq4JuTfcHXFIJ4VwAueUiMSPlegkVv6BJxB0y9rdKbFgPkYSNpAP4T5qE5zgJsktp4mkPW9x1O7rukjVre7wpQB2JyoyFySF469cgzl3eD9iBRNHNEBrOHiSI+U54mWt4Bd5sE9mVzYQQMmcm3iqns=', '2026-04-20 10:32:29'),
(53, 5, 'user', 'XEjxrntv71+G5yrsVXxXp2mNnehHgpX04XxG/1sv0qiZvsQ9/KTeGgIJCQtEWgZ4dN0/mrqMgUngcqmJscEZnqq59W7K0KH79QzL+MS8I/X6zZc3Y1nootCzlqPiQEs4r7DH2U+/MF5GfOWRq/sfBUyZUmNebHtC6eBZIczGNqfkOfr2jPDGmi6Azb65QVsEYNa6F6BTmntLpf75gejxY2zP56MPtJthH32TWP6LQT/P7EV9YYTCyv1QOgRGPgvq76U9O+MrBMQDiRR78ufy3C4iJAfVx+S3phjmBP7osoA=', '2026-04-20 10:32:39'),
(54, 5, 'assistant', '90jOpwcOEV9EIGZYxvJtdix5Fy8AMKlG2KW+RsYEPBeNHvwXsUUmHPiC+KEvLhjkh/7HQOkh/bDco7odneGD8QXlzVR705jZDxFzH9jUSzDIzuGPAd3kximrw2yjScdi7WK1S5mO0rsCRccCwu0b2Yd8umbpABwJzL9teQUdfUfe8UNskKxf0BUAx0OkbX+dLZWJ/6CQ6MIEHzo1XKs93uAExEe6owY/d0O0UT9DsHgR2fvuLtMoMIni0w3EHwANwVJShFu/WWT84X2IRHKDxQ==', '2026-04-20 10:32:39'),
(55, 5, 'user', '90jOpwcOEV9EIGZYxvJtdix5Fy8AMKlG2KW+RsYEPBeNHvwXsUUmHPiC+KEvLhjkh/7HQOkh/bDco7odneGD8QXlzVR705jZDxFzH9jUSzDIzuGPAd3kximrw2yjScdi7WK1S5mO0rsCRccCwu0b2Yd8umbpABwJzL9teQUdfUfe8UNskKxf0BUAx0OkbX+dLZWJ/6CQ6MIEHzo1XKs93uAExEe6owY/d0O0UT9DsHgR2fvuLtMoMIni0w3EHwAN8p9USCr9K2GBhfTYR/eSmGDtnozjRei1UgWPKk4Xx+0=', '2026-04-20 10:39:45'),
(56, 5, 'assistant', 'KgiB+02Uz668TedKY6dI2zxS6H8UNiy8E/JZo/ITKWZXVdO03ZFAaOEB8J7bsjd/P8dQAQ4Ga3TJ1Z8T6+RHB9sDOCtzwNZUqK+oyFFBCbwNt2WOE2WFQE53LoAh0E8oS/PyXkkGEQt/W3mtVDk+TWF5VmeLze2j5scoxo4Few6jmPHr08a+i9np5Yb7YkmAZnQVj7oGJ7/UZtGEWs98q31QS3w7UywObvz2zfbsSJ51vnle0iYH+abb22gxYBnCPrtM2i5jfCJZObDnBwQAGqP8qmfFzDeHnt+HHq3aIrGMD9sG73nxy69G+4MgDgJc8cjyJ+cRf2ax4/w5JW3cTpPHFAvqkZqg5gAbAY968ELZ11dtGDsNqNCsQNt7KG4i', '2026-04-20 10:39:45'),
(57, 5, 'user', 'FpBXRps8/lcNklYe0Ivj0w==', '2026-04-20 10:39:58'),
(58, 5, 'assistant', 'qydIb+kEEr74oMGDq8uLW6dN0/6YlhE8h5lOPQRFgS49PN9oJ7+6psaNrZS2BXMOviONwgRwpUxKO6Kk60JlqNbPl6ssGgh6rz7+drPn801PPfO0ENNvM0+FrwwjCLdiVEJKsZN4MGuRhgmCNXbNeQ==', '2026-04-20 10:39:58'),
(59, 5, 'user', 'HOtyeEei83sqVOh54esziUKq8sgPl9ZVKAYD+ITuyKw=', '2026-04-20 10:41:04'),
(60, 5, 'assistant', 's08L2z7yMY3H7K+7Wff3ZO1RsP1L3VmloIg96k1cYyl1ovTDUpkqga24eVZCXV4Pt6MPqbjcZk0O2mjgvD3FqF1yEPEgxCnGKFdXWZ64Jld5o/x1TSprCGxVyOoJeSO40OhQzaJeZyKb82W2UVxocSwzQzr83G72gtaDJrGwzpaUyDThDmCHwnZY9KYsk/8hrSOyljd4K/0fbJ232lLCXMr/2ogvobIZXt66QrJASVfNk/C3NCUnVd6V4MXMz5wR4ypMgu1OmdxC3nysEPtGMQ==', '2026-04-20 10:41:04'),
(61, 5, 'user', 'cD8GEntgziK6bqVxxue+7OZQJbDOyr5xEHw4uqTW0ADj8yOedT2j3iYWc+k6DQri', '2026-04-20 10:41:20'),
(62, 5, 'assistant', 'ddxX3YOFbnd+mbew3//MHt+sEu1XcWAhUQpdw8Rkfdv69arMfmft0ADLEr9+dDbHXKIqyVNkd/bWMwaBnzDLE4IKedaAOukvOozMtJLn+OZMfXfFUfh4WpyeDzUpUKlmofESYvbUcKo2Xe8vhzZ2fweB7OAgpG7+CAhxVmGhtOY/6gERnafXveLfxpybsQ0Jg2veCRU8HNm2kpakUzs50jyY+e7EDnz/rHQsyouU3kd8zcOdsM7h2u8DUQtw1T90x4qXRljhcsiQOHwPbA4IsswtJxqAzxegQsU9WfTHQYbJ8VS9Qu6dmW2rnVaUA82HaI6WBdInl/8Y2K+AzfoK/g==', '2026-04-20 10:41:20'),
(63, 5, 'user', 'XMtUIqRPd/QEMfZULILVMg==', '2026-04-20 11:09:37'),
(64, 5, 'assistant', '9A7lbdevkKHfCBhic5u3hHCWSXIRoS20uWII/kpJrzWjcy/jYrgP1AQLmKDKoH8NFwKiQGJcjU3vklUEYrAtbTIv9FMiEAvEB8Mr7zYdc7cqbDqfS9PJ6lwezysHzoFvCM9yyC7/OujRukBWWw7qaxWtSiHIfnoJv6Vy2o9nuangnrvidaAMOAMbP+mAfDAF', '2026-04-20 11:09:37');

-- --------------------------------------------------------

--
-- Table structure for table `dailypromptanswers`
--

DROP TABLE IF EXISTS `dailypromptanswers`;
CREATE TABLE IF NOT EXISTS `dailypromptanswers` (
  `answer_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `prompt_id` int NOT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `answered_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`answer_id`),
  KEY `user_id` (`user_id`),
  KEY `prompt_id` (`prompt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dailypromptanswers`
--

INSERT INTO `dailypromptanswers` (`answer_id`, `user_id`, `prompt_id`, `answer`, `answered_at`) VALUES
(8, 5, 5, 'i took a long shower to relax my nerves', '2026-04-11 22:42:16'),
(5, 5, 4, 'my health', '2026-04-10 16:39:39'),
(9, 7, 11, 's', '2026-04-17 19:06:49'),
(10, 7, 12, 'fine', '2026-04-18 15:14:52'),
(18, 5, 14, 'abkxabd', '2026-04-20 14:06:26');

-- --------------------------------------------------------

--
-- Table structure for table `dailyprompts`
--

DROP TABLE IF EXISTS `dailyprompts`;
CREATE TABLE IF NOT EXISTS `dailyprompts` (
  `prompt_id` int NOT NULL AUTO_INCREMENT,
  `prompt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`prompt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dailyprompts`
--

INSERT INTO `dailyprompts` (`prompt_id`, `prompt_text`, `created_at`) VALUES
(1, 'What made you smile today?', '2026-04-08 05:19:18'),
(2, 'What made you smile today?', '2026-04-08 05:40:01'),
(3, 'What challenged you today?', '2026-04-08 05:40:01'),
(4, 'What are you grateful for today?', '2026-04-08 05:40:01'),
(5, 'How did you take care of yourself today?', '2026-04-08 05:40:01'),
(6, 'What is one thing you learned today?', '2026-04-08 05:40:01'),
(7, 'What is something you are looking forward to?', '2026-04-08 05:40:01'),
(8, 'What made you feel stressed today?', '2026-04-08 05:40:01'),
(9, 'How did you overcome a difficulty today?', '2026-04-08 05:40:01'),
(10, 'What made today meaningful?', '2026-04-08 05:40:01'),
(11, 'What would you do differently today?', '2026-04-08 05:40:01'),
(12, 'How did you help someone today?', '2026-04-08 05:40:01'),
(13, 'What emotion did you feel the most today?', '2026-04-08 05:40:01'),
(14, 'What inspired you today?', '2026-04-08 05:40:01'),
(15, 'What is one small win you had today?', '2026-04-08 05:40:01'),
(16, 'What drained your energy today?', '2026-04-08 05:40:01');

-- --------------------------------------------------------

--
-- Table structure for table `diaryentries`
--

DROP TABLE IF EXISTS `diaryentries`;
CREATE TABLE IF NOT EXISTS `diaryentries` (
  `entry_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diaryentries`
--

INSERT INTO `diaryentries` (`entry_id`, `user_id`, `title`, `content`, `entry_date`, `created_at`) VALUES
(1, 5, '', 'hi', '2026-04-12 00:00:00', '2026-04-12 13:42:57'),
(2, 5, '', 'this is a test', '2026-04-12 00:00:00', '2026-04-12 13:43:19'),
(3, 5, '1O7zil6kE4WfLbKa0t2R8Aq/nom53jZG87dwOAyNXWA=', 'gI5LpDHnAOLT6NRB6aNb5v/TY6Am80F0Kc4BLPtyDTo=', '2026-04-12 00:00:00', '2026-04-12 13:56:47'),
(6, 5, '8Ra2EmDa8CEfvpr6pjkQB5FtZybRPzI4JHYbgxuvDGc=', '+/VTfC84p3qjib9DynkoVCudGN1oa1FhaDCj1BdB3GwVniSHThTKIMqUMWrm3HEgg90mxlu0R0g/B8OejW1Ja9vU1NMMQdvu/Fr7yqhuvQ8gVONUWPJjlQx+3l1CowJ+J6n/ANpsrj7BppszTn3c10m+3WNc6+3Vrco+28PBr7GkDapk4pcOeeN7rXmaBMfO9e97Hagjf4ZcUtjqMVD0VX1GsCLSS47SGBe4SO3oX4shA3wNNUOa5ke85yCn8HszMGvcZk+z0mIa3CdkXKEZAgEEcGslza152LxUCx3BGwxGbSdIWRMyOoeXCi/hIKKtkB8EPf18T7F6Ctx/qm2VH0we2nmJvmVTpNaJP70CjPmiwr/6aPyIY/au+EPqCJv+9sfuUbm/RRNr/FNtJjnH1TPL7yVn61ree0L1RDg4YSUiojZr9wIqvQOrEnl1g1YUgJQqs06EQQ/aWi1eP95tS8iiu2oT8pYeKur+12dogGfd/IsRMfo71jpXav6HcP0QrajWPTZOzgt/17cVbG9d6FbVpj9bGBWyJwLXJ8rx9+w8/07ZC8rOLlapR/RUcfvA2FvzMCFwjWnEwBkn90LguQx8QXQlcCLFBfZ9xw5NbPhQCPWC/j69kWxQE3NwyhDKsH2p9XKiVomaLWYYXj1yOoHRjWbBjbQ+fTPLL+lqxCS9xHnYE/30vuTxlHYP3wHqwG8TyZoe2kYSQNKCGTPka3x0gMQyAAe5nCUKUefcw3fXZ8YPTWWA9olJIuVLRe4tpXBNhZU27r5un0Pd0/H+qgAZP/inFnGWcl1QYg43WNlOWrdOTZZ/9e6N1n4DiIeeZJPD5HloR+1Ya4CCNbhDpe5RdX1Fgi31KJJyqbL+PToZhHmvZS1WCFqqGumckkIaSa33xGYru/WqAYFNfBbxdR6veDuj+LmY8PyyYqJH/VaET7rBWlEtaPukHOxowtp6e1OfpR98suMePoB737rrf3KZyCAY6/CAMKJrg1vylQ9GG4yRqXvB8uRVSKx+TsCtmGXfAqXkGUH/4X37BEDaHFWZxWoGIo9jbfiVbcbLFf+LUlzx7nnqaocOl/KxJZ1BclfUXYggONOt/N9pyFpji0odYq+x3rz5MgkpHv1FMNyf4gkqf1/ph7mQJWhMWVXMQE2fBi4SyCDiofQiM4/9Fpu6/pcm1zIlenmgndmZJw7eictLe4lnF1b5G4QmOk5OQDAiqf/rhWQV0D+EQoOaxD48GVRtT/J3FBtX3anDXq3FDmHyhCf3wnb5jSZmif1u6x/hSEu2jJFH1xepK+KOijz6a9AWUAsp+N8t0GzfvQN8AWDUoFi7pKgVhyHFJr79ZBjw5k1JlvrDqyNm3aovFv8hKBgK14Iig94hg7rY7C5pjn5aasrK4tBxVl7r/RvNCwKpRTSDlErJIhZ0Nvxf0TsFlfbfZDEtIajLP8go8cTkKATo4ydxkmZcbfL6Yrd/hKzB/raw9EkJIeG4LOH80/9ms9GChp5FBWtr/v82GkeyCwYAebLOev8BtmKxqcCzRzSFyxD5bQQiPD41q9w0DCjD0ZDn2T/F8BYM26QzqN1vXAvBB/evoMl+TGeq4nJkO6mST2zjs9NTfkkDhCbyhbRRp7KJERLtNHllRRcinqoEcc9Ct9iwnBs1XaDfuHWGdx/MLJjoLZR4WKq387uGizxL8duDPVxegIk+V0aImegqMrOf5T5VXD2TrPWsV0+R3BL8JG7alcoGEWleQKyzQ9soVNMrYbvkLFzYEcDdjdjCk8xeTXTL3U5ZVfjjjBDMErXGl/A080w82mZjpk6LogTd7zXR/EeiDFET+a8yE7KHK3bKXmtUDYc1xoTKymJMinSiB3YarN/KgUhTv6TRLJbyo/fTT7MygIehhejZCQTnvTGFYLGKvuX2HczdGlhaMtUHXErLtVle9r3buuphv/XBUy4TwWkyTjHw2u5Edf3cJY8Tq6wSgtlb4PxwQaMuxSCZxvOpZaetBorTSRVr9FFMX10NhroFHsGPZZTCZAcoI2oYoyREebpT3HBo8oeBl2RGfuYFXX8NI4tZl4zxdPbcIoOBYS9UeD3Q9UIlcXLgBSWcCDH1aw0745u8fv3xpY7pPesOJhMhxFAOL4zhyeZ3Tg1j6bdFWIT8BX4WyRWpyCIpBVJIm7PqwTEzJ/4ImHSlhc8L0z59egn8P7yuUwhLw2YGmYhLbgf7C6Xu2XHVY66IBXe/oSoXNBMIVDgwemKZCZ7ExOXgXWl3Uq5eq1j13B+R+92RQfBeahIsfbUQKJuvVqe8e3vPW19NQZPnVwjuA4byCaaTvupmW3WJ/G94MzIiz8Wvq/6gNTLt4G0gNBn/3Wc0aSm0AIcNLza9eR7Tu5R9jhFF1epugvj22/8GbLDtVDUh2LnHnVLkidNtkBXonfNQkf7ZEZfhjWQeYlmN5/VK4sDfdZpd1MqACA/mwBtl6okmZ6Pghx3m5i1hQmMeliFW9fu5vF34Pgf344Sdd8hY79ugbe6qz8rqA5GsAJZNwaTbpRbbiWbI91/hqaSogJY+/fdl5m8U9gpz1rT+qUdJHE7SsZtedE/r9lAPY6mCTPWUEfpnU81E0/wEpIJDi2+KcP7+SlF9GmkTDphpeWq7sUv/bKv+fSL/gIQsUgHAV52Ss2LuZO1wy6WMkOuJ34KsC/iIZ7Hcjd/SO51Iu6W1EOpGGGrEGvjacL6vv3xhFqVeN1P2DA7dHUk=', '2026-04-12 00:00:00', '2026-04-12 14:00:43'),
(7, 7, 'Hjc4s2sDhDbF0mvJZGEM6Z4CRsvSwWaATuFcTNil5EhQWYyFDayufB1ucLi+yZpL', 'fqidxXAvBFIXEIBdpizNG/s3GLb3SZ8OqEmB1E3hJ04=', '2026-04-18 00:00:00', '2026-04-18 11:54:18'),
(8, 7, '1UcWk1ZviKSL+dbVHThmvcyzxtBacWo8jGth5b+0oIY=', 'uh0P8KMwAVbZdCtg8VS3vT/HypOPgL+GJZWE9i3e8rc=', '2026-04-18 00:00:00', '2026-04-18 11:54:27'),
(9, 5, 'RKrOci/72EYnCR7EZ8FJjivyb9JQueDGKBcS52tc0H8=', 'lZ4U1KipWIbSev9WfGvG4lpqyVDKLOzzm9zvHXJVycY=', '2026-04-20 00:00:00', '2026-04-20 08:03:37'),
(10, 5, 'Q2RmTGCDNbvNZ9Ojy+Apm9Mcyv688VLSaeM0GNNp0hg=', 'OJKtccNuENwhGkyoIqeQQ1r17uTw36XgMj8QwfPBljM=', '2026-04-20 00:00:00', '2026-04-20 08:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `group_posts`
--

DROP TABLE IF EXISTS `group_posts`;
CREATE TABLE IF NOT EXISTS `group_posts` (
  `post_id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `hearts_count` int DEFAULT '0',
  `replies_count` int DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_posts`
--

INSERT INTO `group_posts` (`post_id`, `group_id`, `user_id`, `title`, `content`, `created_at`, `hearts_count`, `replies_count`) VALUES
(9, 1, 5, '', 'hello', '2026-04-20 07:50:11', 0, 0),
(10, 2, 5, '', 'hi', '2026-04-20 14:09:18', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `group_post_hearts`
--

DROP TABLE IF EXISTS `group_post_hearts`;
CREATE TABLE IF NOT EXISTS `group_post_hearts` (
  `heart_id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`heart_id`),
  UNIQUE KEY `unique_heart` (`post_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_replies`
--

DROP TABLE IF EXISTS `group_replies`;
CREATE TABLE IF NOT EXISTS `group_replies` (
  `reply_id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `hearts_count` int DEFAULT '0',
  PRIMARY KEY (`reply_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moodentries`
--

DROP TABLE IF EXISTS `moodentries`;
CREATE TABLE IF NOT EXISTS `moodentries` (
  `mood_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `mood` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mood_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mood_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `moodentries`
--

INSERT INTO `moodentries` (`mood_id`, `user_id`, `mood`, `notes`, `mood_date`, `created_at`) VALUES
(1, 5, 'happy', '', '2026-04-11 00:00:00', '2026-04-11 20:19:05'),
(2, 5, 'sad', '', '2026-04-11 00:00:00', '2026-04-11 20:19:09'),
(3, 5, 'anxious', '', '2026-04-11 00:00:00', '2026-04-11 20:20:19'),
(4, 5, 'sad', 'this will pass', '2026-04-11 00:00:00', '2026-04-11 20:20:44'),
(5, 5, 'sad', 'this will pass', '2026-04-11 00:00:00', '2026-04-11 20:20:53'),
(6, 5, 'anxious', '', '2026-04-11 00:00:00', '2026-04-11 20:22:27'),
(7, 6, 'anxious', 'you are doing okayy', '2026-04-11 00:00:00', '2026-04-11 20:23:48'),
(8, 5, 'sad', '', '2026-04-11 00:00:00', '2026-04-11 20:35:25'),
(9, 5, 'happy', 'hii', '2026-04-11 00:00:00', '2026-04-11 20:35:35'),
(10, 5, 'happy', 'Diary entry mood', '2026-04-12 00:00:00', '2026-04-12 13:56:47'),
(11, 7, 'sad', 'I hope you feel better', '2026-04-17 00:00:00', '2026-04-17 16:06:26'),
(12, 7, 'anxious', '', '2026-04-17 00:00:00', '2026-04-17 16:06:34'),
(13, 7, 'anxious', '', '2026-04-17 00:00:00', '2026-04-17 16:18:31'),
(14, 7, 'anxious', '', '2026-04-17 00:00:00', '2026-04-17 16:28:23'),
(15, 7, 'happy', '', '2026-04-17 00:00:00', '2026-04-17 16:38:03'),
(16, 7, 'neutral', 'I\'m feeling okay', '2026-04-18 00:00:00', '2026-04-18 11:46:10'),
(17, 7, 'angry', 'sddsd', '2026-04-18 00:00:00', '2026-04-18 11:46:18'),
(18, 8, 'neutral', 'im fine i think?', '2026-04-18 00:00:00', '2026-04-18 11:49:30'),
(19, 8, 'angry', 'im just feeling angry', '2026-04-18 00:00:00', '2026-04-18 11:51:12'),
(20, 7, 'angry', '', '2026-04-18 00:00:00', '2026-04-18 11:53:01'),
(21, 7, 'grateful', 'loving life', '2026-04-18 00:00:00', '2026-04-18 16:17:11'),
(22, 7, 'grateful', '', '2026-04-18 00:00:00', '2026-04-18 16:21:30'),
(23, 7, 'anxious', '', '2026-04-18 00:00:00', '2026-04-18 16:43:25'),
(24, 7, 'anxious', '', '2026-04-18 00:00:00', '2026-04-18 16:43:30'),
(25, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:42'),
(26, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:44'),
(27, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:45'),
(28, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:47'),
(29, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:49'),
(30, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:50'),
(31, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:52'),
(32, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:53'),
(33, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:54'),
(34, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:56'),
(35, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:58'),
(36, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:44:59'),
(37, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:45:01'),
(38, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:45:02'),
(39, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:45:03'),
(40, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:45:05'),
(41, 7, 'happy', '', '2026-04-18 00:00:00', '2026-04-18 16:45:07'),
(42, 5, 'happy', '', '2026-04-20 00:00:00', '2026-04-20 04:48:09'),
(43, 5, 'sad', '', '2026-04-20 00:00:00', '2026-04-20 05:41:34'),
(44, 5, 'happy', '', '2026-04-20 00:00:00', '2026-04-20 11:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `page` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_anonymous` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`post_id`),
  UNIQUE KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `page`, `content`, `created_at`, `is_anonymous`) VALUES
(14, 5, 'reflection-board', 'hii', '2026-04-20 04:48:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `post_hearts`
--

DROP TABLE IF EXISTS `post_hearts`;
CREATE TABLE IF NOT EXISTS `post_hearts` (
  `heart_id` int NOT NULL AUTO_INCREMENT,
  `post_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`heart_id`),
  UNIQUE KEY `unique_heart` (`post_id`,`user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_replies`
--

DROP TABLE IF EXISTS `post_replies`;
CREATE TABLE IF NOT EXISTS `post_replies` (
  `reply_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reply_id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `post_replies`
--

INSERT INTO `post_replies` (`reply_id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(27, 14, 5, 'um', '2026-04-20 04:49:10'),
(28, 14, 5, 'heyyy', '2026-04-20 04:49:18'),
(29, 14, 5, 'kdnxkjb', '2026-04-20 04:49:44');

-- --------------------------------------------------------

--
-- Table structure for table `reply_hearts`
--

DROP TABLE IF EXISTS `reply_hearts`;
CREATE TABLE IF NOT EXISTS `reply_hearts` (
  `heart_id` int NOT NULL AUTO_INCREMENT,
  `reply_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`heart_id`),
  KEY `reply_id` (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userbadges`
--

DROP TABLE IF EXISTS `userbadges`;
CREATE TABLE IF NOT EXISTS `userbadges` (
  `user_id` int NOT NULL,
  `badge_id` int NOT NULL,
  `unlocked_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`badge_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `login_streak` int DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password_hash`, `created_at`, `last_login`, `login_streak`) VALUES
(2, '1234', '1234', '1234', '123@gmail.com', '$2y$10$t73nr4KhZ2dh9oJn5SBwmOg5bNPAbX8N/xgEtf0.Sd4o9mbY31w7m', '2026-04-01 19:22:47', '2026-04-01 20:00:47', 0),
(6, 'Ranimmmm', 'ib', 'testerrr', 'ranimibrahim145@gmail.com', '$2y$10$O7G..cyW7yRB9AHQwde5VuLK7AlB8lkhm3B38nw.We8e05JUf/C/e', '2026-04-11 20:23:07', '2026-04-11 20:23:25', 1),
(5, 'Ranim', 'Ibrahim', 'rm_ib', 'ranimibrahiim145@gmail.com', '$2y$10$ZUpS3TBd04cSSfrV.wbgje8uwJSwNKxZc.uQPyibn2o093bY9hIf6', '2026-04-10 13:16:07', '2026-04-20 11:06:15', 1),
(7, 'Antonio', 'Karam', 'antoniokaram06', 'antoniokaram06@gmail.com', '$2y$10$n/KWI.hCvtFM7F1v/oog9OfDRBjlvygOhsgUvhRAt3LgjIquM261u', '2026-04-17 16:05:51', '2026-04-18 17:01:25', 1),
(8, 'Antonio', 'Karam', 'antoniokaram07', 'antoniokaram07@gmail.com', '$2y$10$RZokDMEymAxc8JGpXFPsd.M9bKVFoPmfBKV0JixU3yh/KkChcz4CS', '2026-04-18 11:49:11', '2026-04-18 11:49:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `group_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`group_id`, `name`, `description`, `icon`) VALUES
(1, 'Sexual Assault Survivors', 'A safe space for survivors to share and heal', '🫂'),
(2, 'Grief & Loss', 'Support for loss and grieving', '🕊️'),
(3, 'Bullying & Harassment', 'Support for bullying victims', '🛡️'),
(4, 'Addiction & Recovery', 'Recovery support', '🌱'),
(5, 'Caregivers Support', 'Support for caregivers', '💙'),
(6, 'Eating Disorders', 'Recovery community', '🦋'),
(7, 'Pregnancy Loss', 'Support for miscarriage', '🤍'),
(8, 'Chronic Illness', 'Support for chronic illness', '💪'),
(9, 'New Parents', 'Support for new parents', '👶'),
(10, 'Lebanese War PTSD', 'Support space for people affected by war trauma and PTSD in Lebanon', '🇱🇧');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
