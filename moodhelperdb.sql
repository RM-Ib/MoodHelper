-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 17, 2026 at 09:19 AM
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
  `mood` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anonymous_messages`
--

INSERT INTO `anonymous_messages` (`message_id`, `sender_id`, `receiver_id`, `mood`, `message_text`, `is_read`, `created_at`) VALUES
(1, 6, 5, 'anxious', 'you are doing okayy', 1, '2026-04-11 23:23:48');

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
  `role` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(18, 1, 'assistant', 'V+7TuFv8ifFee2HFgxG3wWW32mq/mUyAdcQfa8VumZKxL6eKl4W4UGZOjT7PJwUmzU9WR/XOYOvTaQ3ctINg0kTPB41O1o4Kt3soQbn+aKCHT/qNSs1fc+oWfj1deI3QELbs22HWge8HeAOkgXvVFQ==', '2026-04-17 08:30:58');

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dailypromptanswers`
--

INSERT INTO `dailypromptanswers` (`answer_id`, `user_id`, `prompt_id`, `answer`, `answered_at`) VALUES
(8, 5, 5, 'i took a long shower to relax my nerves', '2026-04-11 22:42:16'),
(5, 5, 4, 'my health', '2026-04-10 16:39:39');

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
  `mood` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entry_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diaryentries`
--

INSERT INTO `diaryentries` (`entry_id`, `user_id`, `title`, `content`, `mood`, `entry_date`, `created_at`) VALUES
(1, 5, '', 'hi', '', '2026-04-12 00:00:00', '2026-04-12 13:42:57'),
(2, 5, '', 'this is a test', 'sad', '2026-04-12 00:00:00', '2026-04-12 13:43:19'),
(3, 5, '1O7zil6kE4WfLbKa0t2R8Aq/nom53jZG87dwOAyNXWA=', 'gI5LpDHnAOLT6NRB6aNb5v/TY6Am80F0Kc4BLPtyDTo=', 'happy', '2026-04-12 00:00:00', '2026-04-12 13:56:47'),
(6, 5, '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', '', '2026-04-12 00:00:00', '2026-04-12 14:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `group_posts`
--

DROP TABLE IF EXISTS `group_posts`;
CREATE TABLE IF NOT EXISTS `group_posts` (
  `post_id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `hearts_count` int DEFAULT '0',
  `replies_count` int DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_replies`
--

DROP TABLE IF EXISTS `group_replies`;
CREATE TABLE IF NOT EXISTS `group_replies` (
  `reply_id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `hearts_count` int DEFAULT '0',
  PRIMARY KEY (`reply_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(10, 5, 'happy', 'Diary entry mood', '2026-04-12 00:00:00', '2026-04-12 13:56:47');

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password_hash`, `created_at`, `last_login`, `login_streak`) VALUES
(2, '1234', '1234', '1234', '123@gmail.com', '$2y$10$t73nr4KhZ2dh9oJn5SBwmOg5bNPAbX8N/xgEtf0.Sd4o9mbY31w7m', '2026-04-01 19:22:47', '2026-04-01 20:00:47', 0),
(6, 'Ranimmmm', 'ib', 'testerrr', 'ranimibrahim145@gmail.com', '$2y$10$O7G..cyW7yRB9AHQwde5VuLK7AlB8lkhm3B38nw.We8e05JUf/C/e', '2026-04-11 20:23:07', '2026-04-11 20:23:25', 1),
(5, 'Ranim', 'Ibrahim', 'rm_ib', 'ranimibrahiim145@gmail.com', '$2y$10$ZUpS3TBd04cSSfrV.wbgje8uwJSwNKxZc.uQPyibn2o093bY9hIf6', '2026-04-10 13:16:07', '2026-04-17 07:40:22', 3);

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
