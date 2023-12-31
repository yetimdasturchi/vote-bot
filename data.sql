-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 31, 2023 at 09:20 PM
-- Server version: 8.0.35-0ubuntu0.22.04.1
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bot`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_fields`
--

CREATE TABLE `additional_fields` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `city` varchar(255) DEFAULT NULL COMMENT 'Shahar',
  `gender` varchar(255) DEFAULT NULL COMMENT 'Jins',
  `age` varchar(255) DEFAULT NULL COMMENT 'Yosh',
  `model` varchar(255) DEFAULT NULL COMMENT 'Telefon modeli'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE `channels` (
  `channel_id` int NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  `channel_chat_id` bigint NOT NULL,
  `channel_chat_username` varchar(255) NOT NULL,
  `channel_subscription` int NOT NULL,
  `channel_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `chat_id` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `logged` bigint NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `chat_id`, `name`, `logged`, `status`) VALUES
(1, 441307831, 'Manuchehr', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `commands`
--

CREATE TABLE `commands` (
  `command_id` int NOT NULL,
  `command_set` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `command_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prent_command` int DEFAULT '0',
  `sort` int DEFAULT NULL,
  `inline_keyboard` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `chunk` int NOT NULL DEFAULT '1',
  `first_command` int DEFAULT '0',
  `function` varchar(255) DEFAULT NULL,
  `language` varchar(52) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `commands`
--

INSERT INTO `commands` (`command_id`, `command_set`, `command_message`, `prent_command`, `sort`, `inline_keyboard`, `file`, `chunk`, `first_command`, `function`, `language`) VALUES
(33, '/main', 'Salom dunyo', 0, 1, '', '', 1, 1, '', 'uzbek');

-- --------------------------------------------------------

--
-- Table structure for table `contests`
--

CREATE TABLE `contests` (
  `id` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_uzbek` varchar(255) NOT NULL,
  `name_uzbek_cyr` varchar(255) NOT NULL,
  `name_russian` varchar(255) NOT NULL,
  `name_english` varchar(255) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description_uzbek` text NOT NULL,
  `description_uzbek_cyr` text NOT NULL,
  `description_russian` text NOT NULL,
  `description_english` text NOT NULL,
  `expire` bigint NOT NULL,
  `polls` text NOT NULL,
  `polls_uzbek` text NOT NULL,
  `polls_uzbek_cyr` text NOT NULL,
  `polls_russian` text NOT NULL,
  `polls_english` text NOT NULL,
  `polls_check` int NOT NULL DEFAULT '1',
  `max_votes` int NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contest_queue`
--

CREATE TABLE `contest_queue` (
  `id` bigint NOT NULL,
  `chat_id` bigint NOT NULL,
  `contest` bigint NOT NULL,
  `polls_count` int NOT NULL DEFAULT '0',
  `poll_uzbek` bigint DEFAULT NULL,
  `poll_uzbek_cyr` bigint DEFAULT NULL,
  `poll_russian` bigint DEFAULT NULL,
  `poll_eglish` bigint DEFAULT NULL,
  `expire` bigint NOT NULL DEFAULT '0',
  `send_date` bigint NOT NULL DEFAULT '0',
  `sended` int NOT NULL DEFAULT '0',
  `answered` bigint NOT NULL DEFAULT '0',
  `tg_status` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `contest_votes`
--

CREATE TABLE `contest_votes` (
  `id` bigint NOT NULL,
  `chat_id` bigint NOT NULL,
  `contest` bigint NOT NULL,
  `nomination` bigint NOT NULL,
  `member` bigint NOT NULL,
  `date` bigint NOT NULL,
  `thankful` int NOT NULL DEFAULT '0',
  `check_status` int NOT NULL DEFAULT '0',
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` bigint UNSIGNED NOT NULL,
  `manager_telegram` bigint DEFAULT NULL,
  `manager_name` varchar(255) DEFAULT NULL,
  `manager_created` bigint DEFAULT NULL,
  `manager_logged` bigint DEFAULT NULL,
  `manager_modules` text,
  `manager_status` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `manager_telegram`, `manager_name`, `manager_created`, `manager_logged`, `manager_modules`, `manager_status`) VALUES
(1, 441307831, 'Manuchehr', 1689278661, 1697778948, '{\"stats\":{\"view\":true},\"polls\":{\"view\":true,\"add\":true,\"edit\":true,\"delete\":true},\"notifications\":{\"view\":true,\"add\":true,\"edit\":true,\"delete\":true},\"channels\":{\"view\":true,\"add\":true,\"edit\":true,\"delete\":true},\"users\":{\"view\":true,\"add\":true,\"edit\":true,\"delete\":true},\"managers\":{\"view\":true,\"add\":true,\"edit\":true,\"delete\":true},\"telegram_bot\":{\"view\":true,\"add\":true,\"edit\":true,\"delete\":true},\"modules\":{\"view\":true,\"add\":true,\"edit\":true,\"delete\":true}}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` bigint NOT NULL,
  `contest` bigint NOT NULL,
  `nomination` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_uzbek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name_uzbek_cyr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name_russian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name_english` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description_uzbek` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `description_uzbek_cyr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `description_russian` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `description_english` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `buttons` text NOT NULL,
  `sort_uzbek` int NOT NULL DEFAULT '0',
  `sort_uzbek_cyr` int NOT NULL DEFAULT '0',
  `sort_russian` int NOT NULL DEFAULT '0',
  `sort_english` int NOT NULL DEFAULT '0',
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nominations`
--

CREATE TABLE `nominations` (
  `id` bigint NOT NULL,
  `contest` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_uzbek` varchar(255) NOT NULL,
  `name_uzbek_cyr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name_russian` varchar(255) NOT NULL,
  `name_english` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description_uzbek` text NOT NULL,
  `description_uzbek_cyr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `description_russian` text NOT NULL,
  `description_english` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `max_votes` int NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint NOT NULL,
  `source` int NOT NULL DEFAULT '0',
  `chat_id` bigint NOT NULL,
  `date` bigint NOT NULL,
  `message` text NOT NULL,
  `file` text NOT NULL,
  `buttons` text NOT NULL,
  `template` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications_templates`
--

CREATE TABLE `notifications_templates` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `inline_keyboard` text NOT NULL,
  `file` text NOT NULL,
  `date` bigint NOT NULL,
  `last_send` bigint NOT NULL DEFAULT '0',
  `all` bigint NOT NULL DEFAULT '0',
  `success` bigint NOT NULL DEFAULT '0',
  `error` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poll`
--

CREATE TABLE `poll` (
  `id` bigint NOT NULL,
  `answer_id` bigint NOT NULL,
  `question_id` bigint NOT NULL,
  `chat_id` bigint NOT NULL,
  `date` bigint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poll_answers`
--

CREATE TABLE `poll_answers` (
  `id` bigint NOT NULL,
  `answer` text NOT NULL,
  `question_id` bigint NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poll_questions`
--

CREATE TABLE `poll_questions` (
  `id` bigint NOT NULL,
  `groups` int NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `question` text NOT NULL,
  `file` text NOT NULL,
  `buttons` text,
  `expire` bigint NOT NULL,
  `additional_field` varchar(255) DEFAULT NULL,
  `type` int NOT NULL DEFAULT '0',
  `language` varchar(52) DEFAULT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poll_type`
--

CREATE TABLE `poll_type` (
  `id` bigint NOT NULL,
  `question_id` bigint NOT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `chat_id` bigint NOT NULL,
  `date` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint NOT NULL,
  `chat_id` bigint NOT NULL,
  `owner_id` bigint NOT NULL,
  `date` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `chat_id` bigint NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(52) DEFAULT NULL,
  `registered` bigint NOT NULL,
  `last_action` bigint NOT NULL,
  `last_command` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `language` varchar(52) DEFAULT NULL,
  `active_function` varchar(255) DEFAULT NULL,
  `active_function_args` text,
  `active_function_steps` int DEFAULT NULL,
  `hash` text NOT NULL,
  `offer` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_archive_categories`
--

CREATE TABLE `users_archive_categories` (
  `users_archive_category_id` int NOT NULL,
  `users_archive_category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_archive_users`
--

CREATE TABLE `users_archive_users` (
  `users_archive_users_id` int NOT NULL,
  `users_archive_users_category` int NOT NULL,
  `users_archive_users_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `users_archive_users_telegram` bigint NOT NULL,
  `users_archive_users_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_fields`
--
ALTER TABLE `additional_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`user_id`,`age`);

--
-- Indexes for table `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`channel_id`),
  ADD KEY `channel_chat_id` (`channel_chat_id`),
  ADD KEY `channel_chat_username` (`channel_chat_username`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commands`
--
ALTER TABLE `commands`
  ADD PRIMARY KEY (`command_id`);

--
-- Indexes for table `contests`
--
ALTER TABLE `contests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contest_queue`
--
ALTER TABLE `contest_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contest_votes`
--
ALTER TABLE `contest_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`,`check_status`,`nomination`,`member`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD KEY `manager_telegram` (`manager_telegram`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nominations`
--
ALTER TABLE `nominations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`date`,`template`),
  ADD KEY `id_2` (`id`,`chat_id`,`date`,`template`),
  ADD KEY `id_3` (`id`,`chat_id`,`date`,`template`),
  ADD KEY `chat_id` (`chat_id`,`source`,`date`,`template`);

--
-- Indexes for table `notifications_templates`
--
ALTER TABLE `notifications_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll`
--
ALTER TABLE `poll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_answers`
--
ALTER TABLE `poll_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_questions`
--
ALTER TABLE `poll_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_type`
--
ALTER TABLE `poll_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`);

--
-- Indexes for table `users_archive_categories`
--
ALTER TABLE `users_archive_categories`
  ADD PRIMARY KEY (`users_archive_category_id`);

--
-- Indexes for table `users_archive_users`
--
ALTER TABLE `users_archive_users`
  ADD PRIMARY KEY (`users_archive_users_id`),
  ADD KEY `users_archive_users_telegram` (`users_archive_users_telegram`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_fields`
--
ALTER TABLE `additional_fields`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `channels`
--
ALTER TABLE `channels`
  MODIFY `channel_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `commands`
--
ALTER TABLE `commands`
  MODIFY `command_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `contests`
--
ALTER TABLE `contests`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contest_queue`
--
ALTER TABLE `contest_queue`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contest_votes`
--
ALTER TABLE `contest_votes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nominations`
--
ALTER TABLE `nominations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications_templates`
--
ALTER TABLE `notifications_templates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `poll`
--
ALTER TABLE `poll`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poll_answers`
--
ALTER TABLE `poll_answers`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poll_questions`
--
ALTER TABLE `poll_questions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poll_type`
--
ALTER TABLE `poll_type`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_archive_categories`
--
ALTER TABLE `users_archive_categories`
  MODIFY `users_archive_category_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_archive_users`
--
ALTER TABLE `users_archive_users`
  MODIFY `users_archive_users_id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
