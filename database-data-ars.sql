-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 02, 2020 at 01:42 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ars`
--

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `id` int(11) NOT NULL,
  `taught_by` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `section_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`id`, `taught_by`, `course_id`, `section_id`) VALUES
(1, 1, 1, '001'),
(2, 3, 2, '12'),
(3, 1, 3, '213'),
(4, 1, 4, 'dsafds'),
(5, 1, 5, 'zxcvzxv');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `course_code`, `description`, `category`) VALUES
(1, 'Intro to web programming I', 'COSC360', '', NULL),
(2, 'Intro to web programming II', 'COSC361', 'dasfak', NULL),
(3, 'Intro to web programming III', 'COSC3221', '', NULL),
(4, 'daszxc', '2113', '', NULL),
(5, 'dafdsfdas', 'dasfa', 'dafsdsa', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrolledStudents`
--

CREATE TABLE `enrolledStudents` (
  `id` int(11) NOT NULL,
  `classroom_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `enrolledStudents`
--

INSERT INTO `enrolledStudents` (`id`, `classroom_id`, `student_id`) VALUES
(1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `questionInstance`
--

CREATE TABLE `questionInstance` (
  `id` int(11) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `question_meta_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questionInstance`
--

INSERT INTO `questionInstance` (`id`, `time_created`, `question_meta_id`) VALUES
(76, '2020-02-11 11:58:25', 20),
(77, '2020-02-11 12:14:35', 20),
(78, '2020-02-11 12:14:40', 20),
(79, '2020-02-11 12:15:32', 20),
(80, '2020-02-11 12:17:04', 20),
(81, '2020-02-11 12:23:37', 22),
(82, '2020-02-11 13:04:47', 20),
(83, '2020-02-11 13:06:51', 20),
(84, '2020-02-11 13:07:00', 20),
(85, '2020-02-11 13:07:06', 20),
(86, '2020-02-11 13:08:34', 20),
(87, '2020-02-11 13:09:08', 20),
(88, '2020-02-11 13:09:13', 20),
(89, '2020-02-11 13:10:04', 20),
(90, '2020-02-11 13:10:36', 20),
(91, '2020-02-11 13:10:42', 20),
(92, '2020-02-11 13:11:20', 20),
(93, '2020-02-11 13:12:11', 20),
(94, '2020-02-11 13:13:21', 20),
(95, '2020-02-11 13:13:25', 20),
(96, '2020-02-11 13:13:31', 22),
(97, '2020-02-11 13:15:45', 22),
(98, '2020-02-11 13:15:50', 22),
(99, '2020-02-11 13:16:28', 22),
(100, '2020-02-11 13:20:10', 22),
(101, '2020-02-11 13:20:13', 22),
(102, '2020-02-11 13:20:18', 20),
(103, '2020-02-11 13:20:54', 20),
(104, '2020-02-11 13:21:23', 20),
(105, '2020-02-11 13:21:46', 20),
(106, '2020-02-11 13:26:49', 20),
(107, '2020-02-11 13:27:20', 20),
(108, '2020-02-11 13:27:22', 20),
(109, '2020-02-11 13:28:08', 20),
(110, '2020-02-11 13:28:23', 20),
(111, '2020-02-11 13:28:56', 20),
(112, '2020-02-11 13:30:01', 20),
(113, '2020-02-11 13:33:21', 20),
(114, '2020-02-11 13:33:55', 20),
(115, '2020-02-11 13:34:40', 20),
(116, '2020-02-11 13:34:51', 20),
(117, '2020-02-11 13:35:00', 20),
(118, '2020-02-11 13:35:08', 20),
(119, '2020-02-11 13:35:34', 20),
(120, '2020-02-11 13:36:56', 20),
(121, '2020-02-11 13:37:15', 20),
(122, '2020-02-11 13:37:32', 20),
(123, '2020-02-11 13:41:42', 20),
(124, '2020-02-11 13:43:57', 20),
(125, '2020-02-11 13:46:20', 20),
(126, '2020-02-11 13:49:26', 20),
(127, '2020-02-11 13:49:59', 20),
(128, '2020-02-11 13:54:29', 20),
(129, '2020-02-11 13:54:31', 20),
(130, '2020-02-11 13:54:36', 20),
(131, '2020-02-11 13:55:23', 20),
(132, '2020-02-11 14:01:22', 20),
(133, '2020-02-11 14:02:49', 20),
(134, '2020-02-11 14:03:52', 20),
(135, '2020-02-11 14:05:12', 20),
(136, '2020-02-11 14:05:33', 20),
(137, '2020-02-11 14:05:51', 20),
(138, '2020-02-11 14:06:21', 20),
(139, '2020-02-11 14:06:34', 20),
(140, '2020-02-11 14:06:50', 20),
(141, '2020-02-11 14:07:22', 20),
(142, '2020-02-11 14:09:57', 20),
(143, '2020-02-11 14:10:10', 20),
(144, '2020-02-11 14:10:24', 20),
(145, '2020-02-11 14:10:42', 20),
(146, '2020-02-11 14:11:11', 20),
(147, '2020-02-11 14:11:42', 20),
(148, '2020-02-11 14:12:03', 20),
(149, '2020-02-11 14:15:13', 20),
(150, '2020-02-11 14:15:35', 20),
(151, '2020-02-11 14:15:48', 20),
(152, '2020-02-11 14:15:57', 20),
(153, '2020-02-11 14:16:14', 20),
(154, '2020-02-11 14:17:56', 20),
(155, '2020-02-11 14:21:20', 20),
(156, '2020-02-11 14:21:39', 22),
(157, '2020-02-11 14:21:49', 22),
(158, '2020-02-11 14:26:38', 20),
(159, '2020-02-11 14:27:03', 20),
(160, '2020-02-11 14:28:07', 20),
(161, '2020-02-11 14:29:58', 20),
(162, '2020-02-11 14:30:06', 20),
(163, '2020-02-11 14:30:22', 20),
(164, '2020-02-11 14:31:58', 20),
(165, '2020-02-11 14:31:58', 20),
(166, '2020-02-11 14:35:21', 20),
(167, '2020-02-11 14:38:34', 20),
(168, '2020-02-11 14:38:58', 20),
(169, '2020-02-11 14:39:46', 20),
(170, '2020-02-11 14:40:56', 20),
(171, '2020-02-11 14:41:16', 20),
(172, '2020-02-11 14:41:19', 20),
(173, '2020-02-11 14:42:36', 20),
(174, '2020-02-11 14:42:52', 20),
(175, '2020-02-11 14:54:54', 20),
(176, '2020-02-11 14:56:02', 20),
(177, '2020-02-11 14:56:29', 20),
(178, '2020-02-11 14:56:35', 22),
(179, '2020-02-11 14:57:04', 22),
(180, '2020-02-11 14:57:19', 22),
(181, '2020-02-11 14:57:37', 20),
(182, '2020-02-11 14:57:46', 20),
(183, '2020-02-11 14:58:33', 22),
(184, '2020-02-11 15:00:16', 22),
(185, '2020-02-11 15:05:47', 20),
(186, '2020-02-11 15:06:55', 20),
(187, '2020-02-11 15:07:00', 22),
(188, '2020-02-11 15:07:39', 20),
(189, '2020-02-11 15:07:53', 20),
(190, '2020-02-11 15:08:13', 20),
(191, '2020-02-11 15:08:45', 20),
(192, '2020-02-11 15:10:44', 20),
(193, '2020-02-11 15:11:00', 20),
(194, '2020-02-11 15:11:46', 20),
(195, '2020-02-11 15:12:31', 20),
(196, '2020-02-11 15:12:44', 20),
(197, '2020-02-11 15:13:39', 20),
(198, '2020-02-11 15:14:26', 20),
(199, '2020-02-11 15:14:29', 20),
(200, '2020-02-11 15:15:31', 20),
(201, '2020-02-11 15:15:47', 20),
(202, '2020-02-11 15:17:12', 20),
(203, '2020-02-11 15:17:17', 20),
(204, '2020-02-11 15:17:54', 20),
(205, '2020-02-11 15:18:18', 20),
(206, '2020-02-11 15:18:44', 20),
(207, '2020-02-11 15:19:03', 20),
(208, '2020-02-11 15:23:51', 20),
(209, '2020-02-11 15:24:17', 20),
(210, '2020-02-11 15:25:38', 20),
(211, '2020-02-11 15:26:19', 20),
(212, '2020-02-11 15:26:46', 22),
(213, '2020-02-11 15:27:03', 22),
(214, '2020-02-11 16:17:47', 22),
(215, '2020-02-11 16:18:05', 20),
(216, '2020-02-11 16:18:24', 20),
(217, '2020-02-11 16:18:35', 22),
(218, '2020-02-11 16:20:22', 22),
(219, '2020-02-11 16:20:37', 20),
(220, '2020-02-11 16:20:53', 20),
(221, '2020-02-11 16:21:28', 20),
(222, '2020-02-11 16:21:44', 22),
(223, '2020-02-11 16:26:28', 22),
(224, '2020-02-11 16:31:21', 22),
(225, '2020-02-11 16:31:49', 22),
(226, '2020-02-11 16:32:25', 22),
(227, '2020-02-11 16:33:05', 22),
(228, '2020-02-11 16:33:30', 22),
(229, '2020-02-11 16:33:38', 20),
(230, '2020-02-11 16:34:06', 20),
(231, '2020-02-11 16:35:24', 20),
(232, '2020-02-11 16:36:22', 20),
(233, '2020-02-11 16:36:57', 20),
(234, '2020-02-11 16:37:12', 22),
(235, '2020-02-11 16:39:53', 20),
(236, '2020-02-11 16:40:11', 20),
(237, '2020-02-11 16:42:57', 20),
(238, '2020-02-11 16:42:59', 20),
(239, '2020-02-11 16:43:22', 20),
(240, '2020-02-11 16:43:42', 20),
(241, '2020-02-11 16:45:58', 20),
(242, '2020-02-11 16:49:53', 20),
(243, '2020-02-11 16:50:09', 20),
(244, '2020-02-11 16:50:43', 20),
(245, '2020-02-11 16:52:24', 20),
(246, '2020-02-11 16:53:07', 20),
(247, '2020-02-11 16:55:39', 20),
(248, '2020-02-11 16:56:16', 20),
(249, '2020-02-11 16:56:41', 20),
(250, '2020-02-11 16:57:41', 20),
(251, '2020-02-11 16:59:34', 20),
(252, '2020-02-11 17:00:02', 20),
(253, '2020-02-11 17:00:12', 22),
(254, '2020-02-11 17:02:55', 20),
(255, '2020-02-11 17:04:30', 20),
(256, '2020-02-11 17:04:52', 20),
(257, '2020-02-11 17:10:10', 20),
(258, '2020-02-11 17:10:42', 20),
(259, '2020-02-11 17:10:59', 22),
(260, '2020-02-11 17:13:46', 22),
(261, '2020-02-11 17:14:26', 22),
(262, '2020-02-11 17:15:41', 22),
(263, '2020-02-11 17:16:45', 22),
(264, '2020-02-11 17:19:59', 22),
(265, '2020-02-11 17:20:47', 22),
(266, '2020-02-11 17:22:21', 20),
(267, '2020-02-11 17:26:27', 20),
(268, '2020-02-11 17:27:23', 20),
(269, '2020-02-11 17:27:49', 20),
(270, '2020-02-11 17:28:12', 20),
(271, '2020-02-11 17:29:35', 20),
(272, '2020-02-11 17:30:58', 20),
(273, '2020-02-11 17:31:15', 20),
(274, '2020-02-11 17:31:44', 20),
(275, '2020-02-11 17:36:17', 20),
(276, '2020-02-11 17:36:29', 20),
(277, '2020-02-11 17:36:31', 20),
(278, '2020-02-11 17:36:44', 20),
(279, '2020-02-11 17:37:36', 20),
(280, '2020-02-11 17:38:16', 22),
(281, '2020-02-11 17:38:42', 22),
(282, '2020-02-11 17:39:14', 22),
(283, '2020-02-11 17:40:51', 22),
(284, '2020-02-11 17:42:10', 22),
(285, '2020-02-11 17:42:43', 22),
(286, '2020-02-11 17:45:23', 22),
(287, '2020-02-11 17:46:17', 22),
(288, '2020-02-11 17:47:10', 22),
(289, '2020-02-11 17:49:29', 22),
(290, '2020-02-11 17:50:27', 22),
(291, '2020-02-11 17:50:55', 22),
(292, '2020-02-11 17:51:12', 22),
(293, '2020-02-11 17:51:36', 22),
(294, '2020-02-11 17:53:21', 22),
(295, '2020-02-11 18:03:54', 20),
(296, '2020-02-11 18:21:00', 20),
(297, '2020-02-11 18:27:54', 20),
(298, '2020-02-11 18:28:57', 22),
(299, '2020-02-11 18:32:55', 20),
(300, '2020-02-11 18:48:42', 20),
(301, '2020-02-11 18:50:59', 20),
(302, '2020-02-11 22:23:04', 20),
(303, '2020-02-11 22:23:31', 20),
(304, '2020-02-11 22:25:39', 20),
(305, '2020-02-11 22:25:48', 20),
(306, '2020-02-11 22:26:13', 20),
(307, '2020-02-11 22:26:24', 20),
(308, '2020-02-11 22:26:29', 20),
(309, '2020-02-11 22:30:50', 20),
(310, '2020-02-11 22:31:52', 20),
(311, '2020-02-11 22:31:56', 20),
(312, '2020-02-11 22:31:56', 20),
(313, '2020-02-11 22:32:03', 20),
(314, '2020-02-11 22:32:43', 20),
(315, '2020-02-11 22:43:03', 20),
(316, '2020-02-11 22:43:24', 20),
(317, '2020-02-11 22:43:27', 20),
(318, '2020-02-11 22:44:23', 20),
(319, '2020-02-11 22:45:07', 20),
(320, '2020-02-11 22:45:10', 22),
(321, '2020-02-11 22:46:02', 20),
(322, '2020-02-11 22:46:41', 22),
(323, '2020-02-11 22:47:28', 22),
(324, '2020-02-11 22:47:43', 22),
(325, '2020-02-11 22:50:48', 22),
(326, '2020-02-11 22:52:02', 20),
(327, '2020-02-11 22:52:21', 20),
(328, '2020-02-11 22:52:39', 20),
(329, '2020-02-11 22:54:18', 20),
(330, '2020-02-11 22:55:37', 20),
(331, '2020-02-11 23:12:38', 20),
(332, '2020-02-11 23:19:01', 20),
(333, '2020-02-11 23:19:25', 20),
(334, '2020-02-11 23:19:28', 20),
(335, '2020-02-11 23:20:21', 20),
(336, '2020-02-11 23:20:54', 20),
(337, '2020-02-11 23:22:37', 20),
(338, '2020-02-11 23:23:16', 20),
(339, '2020-02-11 23:24:00', 20),
(340, '2020-02-11 23:24:44', 20),
(341, '2020-02-11 23:25:15', 20),
(342, '2020-02-11 23:26:12', 22),
(343, '2020-02-11 23:26:42', 22),
(344, '2020-02-11 23:41:04', 22),
(345, '2020-02-11 23:45:06', 20),
(346, '2020-02-11 23:45:14', 22),
(347, '2020-02-11 23:50:45', 20),
(348, '2020-02-11 23:52:33', 20),
(349, '2020-02-11 23:57:11', 20),
(350, '2020-02-12 00:00:05', 20),
(351, '2020-02-12 00:00:20', 20),
(352, '2020-02-12 00:00:24', 20),
(353, '2020-02-12 00:00:29', 20),
(354, '2020-02-12 00:02:25', 20),
(355, '2020-02-17 21:13:31', 20),
(356, '2020-02-17 21:14:42', 20),
(357, '2020-02-17 21:20:56', 20),
(358, '2020-02-17 21:22:42', 22),
(359, '2020-02-17 21:31:57', 20),
(360, '2020-02-17 21:32:16', 20),
(361, '2020-02-17 21:41:05', 20),
(362, '2020-02-17 21:41:35', 20),
(363, '2020-02-17 21:42:24', 20),
(364, '2020-02-17 21:42:25', 20),
(365, '2020-02-17 21:42:26', 20),
(366, '2020-02-17 21:42:26', 20),
(367, '2020-02-17 21:42:26', 20),
(368, '2020-02-17 21:42:26', 20),
(369, '2020-02-17 21:42:26', 20),
(370, '2020-02-17 21:42:27', 20),
(371, '2020-02-17 21:43:51', 20),
(372, '2020-02-17 21:45:19', 20),
(373, '2020-02-17 21:45:47', 20),
(374, '2020-02-17 21:53:09', 20),
(375, '2020-02-17 21:54:37', 20),
(376, '2020-02-17 22:00:25', 20),
(377, '2020-02-17 22:01:23', 20),
(378, '2020-02-17 22:11:48', 22),
(379, '2020-02-17 22:14:58', 22),
(380, '2020-02-17 22:23:57', 22),
(381, '2020-02-17 22:25:02', 22),
(382, '2020-02-17 22:25:42', 22),
(383, '2020-02-17 22:26:11', 22),
(384, '2020-02-17 22:26:32', 22),
(385, '2020-02-17 22:27:17', 22),
(386, '2020-02-17 22:28:18', 22),
(387, '2020-02-17 22:29:14', 22),
(388, '2020-02-17 22:30:23', 22),
(389, '2020-02-17 22:31:00', 22),
(390, '2020-02-17 22:33:31', 20),
(391, '2020-02-17 22:33:35', 20),
(392, '2020-02-17 22:34:44', 20),
(393, '2020-02-17 22:58:10', 20),
(394, '2020-02-17 22:58:22', 20),
(395, '2020-02-17 22:59:01', 20),
(396, '2020-02-17 23:00:14', 20),
(397, '2020-02-17 23:06:50', 20),
(398, '2020-02-17 23:08:31', 20),
(399, '2020-02-17 23:09:08', 20),
(400, '2020-02-17 23:09:18', 20),
(401, '2020-02-17 23:10:35', 20),
(402, '2020-02-17 23:11:14', 20),
(403, '2020-02-17 23:11:25', 20),
(404, '2020-02-17 23:12:08', 20),
(405, '2020-02-17 23:14:31', 22),
(406, '2020-02-17 23:14:48', 22),
(407, '2020-02-17 23:15:03', 22),
(408, '2020-02-17 23:17:42', 20),
(409, '2020-02-17 23:22:54', 22),
(410, '2020-02-18 04:05:56', 20),
(411, '2020-02-18 04:06:04', 20),
(412, '2020-02-18 04:06:38', 20),
(413, '2020-02-18 04:07:38', 20),
(414, '2020-02-18 04:08:02', 20),
(415, '2020-02-18 04:08:11', 20),
(416, '2020-02-18 04:19:13', 20),
(417, '2020-02-18 04:19:22', 22),
(418, '2020-02-18 04:19:31', 22),
(419, '2020-02-18 04:19:42', 22),
(420, '2020-02-18 04:22:00', 22),
(421, '2020-02-18 04:22:41', 22),
(422, '2020-02-18 04:22:44', 22),
(423, '2020-02-18 04:22:47', 20),
(424, '2020-02-18 04:22:52', 22),
(425, '2020-02-18 04:22:58', 22),
(426, '2020-02-18 04:23:04', 20),
(427, '2020-02-18 04:23:37', 20),
(428, '2020-02-18 04:23:41', 22),
(429, '2020-02-18 04:23:45', 22),
(430, '2020-02-18 04:29:07', 22),
(431, '2020-02-18 04:32:29', 22),
(432, '2020-02-18 04:32:50', 22),
(433, '2020-02-18 04:34:12', 22),
(434, '2020-02-18 04:35:25', 22),
(435, '2020-02-18 04:36:34', 22),
(436, '2020-02-18 04:36:52', 22),
(437, '2020-02-18 04:40:09', 22),
(438, '2020-02-18 04:40:39', 22),
(439, '2020-02-18 04:40:47', 20),
(440, '2020-02-18 04:41:28', 20),
(441, '2020-02-18 04:41:34', 20),
(442, '2020-02-18 04:41:47', 20),
(443, '2020-02-18 04:49:46', 20),
(444, '2020-02-18 04:50:09', 22),
(445, '2020-02-18 04:50:29', 22),
(446, '2020-02-18 04:50:34', 20),
(447, '2020-02-18 04:50:52', 22),
(448, '2020-02-18 04:59:19', 22),
(449, '2020-02-18 05:00:10', 22),
(450, '2020-02-18 05:00:41', 22),
(451, '2020-02-18 05:05:47', 22),
(452, '2020-02-18 05:06:10', 22),
(453, '2020-02-18 05:11:30', 22),
(454, '2020-02-18 05:11:39', 22),
(455, '2020-02-18 05:11:59', 22),
(456, '2020-02-18 05:13:32', 20),
(457, '2020-02-18 05:13:45', 20),
(458, '2020-02-18 05:14:44', 22),
(459, '2020-02-18 05:31:38', 22),
(460, '2020-02-18 05:31:57', 22),
(461, '2020-02-18 05:39:07', 22),
(462, '2020-02-18 05:39:44', 22),
(463, '2020-02-18 05:41:12', 22),
(464, '2020-02-18 05:41:38', 22),
(465, '2020-02-18 05:42:05', 22),
(466, '2020-02-18 05:47:11', 22),
(467, '2020-02-18 05:47:19', 22),
(468, '2020-02-18 05:47:48', 22),
(469, '2020-02-18 05:48:14', 22),
(470, '2020-02-18 05:49:00', 22),
(471, '2020-02-18 05:51:09', 22),
(472, '2020-02-18 05:51:30', 22),
(473, '2020-02-18 05:52:28', 22),
(474, '2020-02-18 05:53:10', 22),
(475, '2020-02-18 05:59:34', 22),
(476, '2020-02-18 06:01:10', 22),
(477, '2020-02-18 06:01:57', 22),
(478, '2020-02-18 06:06:38', 22),
(479, '2020-02-18 06:06:44', 22),
(480, '2020-02-18 06:07:20', 22),
(481, '2020-02-18 06:07:36', 22),
(482, '2020-02-18 06:07:55', 22),
(483, '2020-02-18 06:08:29', 22),
(484, '2020-02-18 10:45:17', 20),
(485, '2020-02-18 11:06:40', 20),
(486, '2020-02-18 12:06:15', 20),
(487, '2020-02-18 12:07:48', 20),
(488, '2020-02-18 12:08:07', 20),
(489, '2020-02-18 12:17:47', 20),
(490, '2020-02-18 12:17:53', 20),
(491, '2020-02-18 12:18:39', 20),
(492, '2020-02-18 12:20:09', 20),
(493, '2020-02-18 12:37:49', 20),
(494, '2020-02-18 12:39:23', 22),
(495, '2020-02-18 12:41:46', 20),
(496, '2020-02-18 12:46:16', 20),
(497, '2020-02-18 13:22:48', 20),
(498, '2020-02-18 13:23:34', 20),
(499, '2020-02-18 13:24:10', 20),
(500, '2020-02-18 13:24:44', 20),
(501, '2020-02-18 13:25:55', 20),
(502, '2020-02-18 13:26:36', 20),
(503, '2020-02-18 13:29:33', 20),
(504, '2020-02-18 21:58:13', 20),
(505, '2020-02-19 02:57:17', 20),
(506, '2020-02-19 02:58:44', 20),
(507, '2020-02-19 02:59:24', 20),
(508, '2020-02-19 03:06:28', 20),
(509, '2020-02-19 03:09:26', 20),
(510, '2020-02-19 03:12:56', 20),
(511, '2020-02-19 03:14:41', 20),
(512, '2020-02-19 03:33:04', 20),
(513, '2020-02-19 03:33:16', 22),
(514, '2020-02-19 03:37:09', 20),
(515, '2020-02-19 03:46:49', 20),
(516, '2020-02-19 03:47:39', 20),
(517, '2020-02-19 03:47:43', 20),
(518, '2020-02-19 07:29:31', 20),
(519, '2020-02-19 13:11:29', 20),
(520, '2020-02-19 13:14:53', 22),
(521, '2020-02-19 13:16:11', 22),
(522, '2020-02-19 13:17:07', 22),
(523, '2020-02-19 13:18:02', 22),
(524, '2020-02-19 13:32:50', 22),
(525, '2020-02-19 13:33:15', 22),
(526, '2020-02-19 13:50:56', 20),
(527, '2020-02-19 13:55:39', 22),
(528, '2020-02-19 13:56:51', 20),
(529, '2020-02-19 13:57:50', 20),
(530, '2020-02-19 13:58:22', 20),
(531, '2020-02-19 13:58:46', 22),
(532, '2020-02-19 14:00:23', 20),
(533, '2020-02-19 14:01:37', 20),
(534, '2020-02-19 14:02:07', 20),
(535, '2020-02-19 14:02:30', 20),
(536, '2020-02-19 14:02:46', 20),
(537, '2020-02-19 14:03:28', 20),
(538, '2020-02-19 14:04:07', 20),
(539, '2020-02-19 14:04:29', 20),
(540, '2020-02-19 14:06:27', 20),
(541, '2020-02-19 14:06:48', 20),
(542, '2020-02-19 14:07:15', 20),
(543, '2020-02-19 14:07:39', 22),
(544, '2020-02-19 14:08:11', 20),
(545, '2020-02-19 14:08:59', 20),
(546, '2020-02-19 14:11:43', 20),
(547, '2020-02-19 14:12:19', 22),
(548, '2020-02-20 17:32:19', 20),
(549, '2020-02-20 17:41:39', 22),
(550, '2020-02-20 17:43:17', 23),
(551, '2020-02-20 17:43:54', 23),
(552, '2020-02-24 07:37:43', 20),
(553, '2020-02-24 07:39:11', 20),
(554, '2020-02-24 07:40:13', 20),
(555, '2020-02-24 07:40:35', 20),
(556, '2020-02-24 07:42:08', 20),
(557, '2020-02-25 01:47:24', 20),
(558, '2020-02-25 02:02:10', 20),
(559, '2020-02-25 02:06:22', 20),
(560, '2020-02-25 02:10:27', 20),
(561, '2020-02-25 02:11:08', 20),
(562, '2020-02-25 02:12:17', 20),
(563, '2020-02-25 02:14:25', 20),
(564, '2020-02-25 02:14:46', 20),
(565, '2020-02-25 02:16:14', 20),
(566, '2020-02-25 02:16:37', 20),
(567, '2020-02-25 02:41:33', 20),
(568, '2020-02-25 02:52:39', 20),
(569, '2020-02-25 02:53:13', 20),
(570, '2020-02-25 02:53:36', 22),
(571, '2020-02-25 05:26:17', 22),
(572, '2020-02-25 06:06:02', 20),
(573, '2020-02-25 06:06:12', 20),
(574, '2020-02-25 06:16:20', 20),
(575, '2020-02-25 06:21:53', 20),
(576, '2020-02-25 06:22:11', 20),
(577, '2020-02-25 08:17:07', 20),
(578, '2020-02-25 08:54:34', 20),
(579, '2020-02-25 08:56:13', 20),
(580, '2020-02-25 08:57:23', 20),
(581, '2020-02-25 09:27:26', 20),
(582, '2020-02-25 09:31:44', 20),
(583, '2020-02-25 09:33:23', 20),
(584, '2020-02-25 09:33:46', 20),
(585, '2020-02-25 09:39:49', 20),
(586, '2020-02-25 09:40:11', 20),
(587, '2020-02-25 09:41:02', 20),
(588, '2020-02-25 09:42:01', 20),
(589, '2020-02-25 09:44:20', 20),
(590, '2020-02-25 09:44:30', 20),
(591, '2020-02-25 09:44:42', 20),
(592, '2020-02-25 09:46:48', 20),
(593, '2020-02-25 09:47:12', 20),
(594, '2020-02-25 09:47:17', 20),
(595, '2020-02-25 09:47:32', 20),
(596, '2020-02-25 09:50:21', 20),
(597, '2020-02-25 09:56:14', 20),
(598, '2020-02-25 09:57:15', 20),
(599, '2020-02-25 09:57:26', 20),
(600, '2020-02-25 09:57:42', 20),
(601, '2020-02-25 09:57:49', 20),
(602, '2020-02-25 09:58:08', 20),
(603, '2020-02-25 09:58:34', 20),
(604, '2020-02-25 10:01:41', 20),
(605, '2020-02-25 10:01:53', 20),
(606, '2020-02-25 10:06:48', 20),
(607, '2020-02-25 10:07:05', 20),
(608, '2020-02-25 10:09:10', 20),
(609, '2020-02-25 10:14:15', 20),
(610, '2020-02-25 10:14:53', 20),
(611, '2020-02-25 10:27:57', 20),
(612, '2020-02-25 10:30:07', 20),
(613, '2020-02-25 10:31:08', 20),
(614, '2020-02-25 10:34:29', 20),
(615, '2020-02-25 10:43:22', 20),
(616, '2020-02-25 10:44:30', 20),
(617, '2020-02-25 10:46:56', 20),
(618, '2020-02-25 10:48:22', 20),
(619, '2020-02-25 10:51:04', 20),
(620, '2020-02-25 10:51:10', 20),
(621, '2020-02-25 10:52:24', 20),
(622, '2020-02-25 10:53:03', 20),
(623, '2020-02-25 10:53:20', 20),
(624, '2020-02-25 10:53:38', 20),
(625, '2020-02-25 10:57:33', 20),
(626, '2020-02-25 10:57:48', 20),
(627, '2020-02-25 10:59:01', 20),
(628, '2020-02-25 10:59:36', 20),
(629, '2020-02-25 11:02:29', 20),
(630, '2020-02-25 11:02:32', 20),
(631, '2020-02-25 11:04:16', 20),
(632, '2020-02-25 11:05:39', 20),
(633, '2020-02-25 11:06:23', 20),
(634, '2020-02-25 11:09:43', 20),
(635, '2020-02-25 11:10:38', 20),
(636, '2020-02-25 11:12:51', 20),
(637, '2020-02-25 11:13:58', 20),
(638, '2020-02-25 11:15:14', 20),
(639, '2020-02-25 11:15:35', 20),
(640, '2020-02-25 11:16:00', 20),
(641, '2020-02-25 11:16:36', 20),
(642, '2020-02-25 11:17:14', 20),
(643, '2020-02-25 12:41:33', 20),
(644, '2020-02-25 13:28:36', 20),
(645, '2020-02-25 13:29:29', 20),
(646, '2020-02-25 13:30:29', 20),
(647, '2020-02-25 13:31:05', 20),
(648, '2020-02-25 13:42:47', 20),
(649, '2020-02-25 13:45:36', 20),
(650, '2020-02-25 13:46:50', 20),
(651, '2020-02-25 13:47:43', 20),
(652, '2020-02-25 13:53:42', 20),
(653, '2020-02-25 13:55:33', 20),
(654, '2020-02-25 14:35:51', 20),
(655, '2020-02-25 14:36:37', 20),
(656, '2020-02-25 14:37:52', 20),
(657, '2020-02-25 14:40:40', 20),
(658, '2020-02-25 14:42:49', 20),
(659, '2020-02-25 14:52:25', 20),
(660, '2020-02-25 14:56:43', 20),
(661, '2020-02-25 16:14:03', 20),
(662, '2020-02-25 16:14:50', 20),
(663, '2020-02-25 16:20:49', 20),
(664, '2020-02-25 16:33:06', 20),
(665, '2020-02-25 16:52:07', 20),
(666, '2020-02-25 16:53:06', 20),
(667, '2020-02-25 16:56:55', 20),
(668, '2020-02-25 16:59:14', 20),
(669, '2020-02-25 17:05:55', 20),
(670, '2020-02-25 17:06:49', 20),
(671, '2020-02-25 17:08:55', 20),
(672, '2020-02-25 17:10:10', 20),
(673, '2020-02-25 17:11:33', 20),
(674, '2020-02-25 17:11:42', 20),
(675, '2020-02-25 17:13:10', 20),
(676, '2020-02-25 17:13:30', 20),
(677, '2020-02-25 17:14:35', 20),
(678, '2020-02-25 17:14:56', 20),
(679, '2020-02-25 17:16:37', 20),
(680, '2020-02-25 17:17:13', 20),
(681, '2020-02-25 17:19:01', 20),
(682, '2020-02-25 17:19:24', 20),
(683, '2020-02-25 17:20:41', 20),
(684, '2020-02-25 17:21:31', 20),
(685, '2020-02-25 17:27:43', 20),
(686, '2020-02-25 17:28:58', 20),
(687, '2020-02-25 17:30:56', 20),
(688, '2020-02-25 17:31:47', 20),
(689, '2020-02-25 17:31:56', 20),
(690, '2020-02-25 17:32:22', 20),
(691, '2020-02-25 17:32:53', 20),
(692, '2020-02-25 17:33:16', 20),
(693, '2020-02-25 17:38:05', 20),
(694, '2020-02-25 17:39:32', 20),
(695, '2020-02-25 17:42:28', 20),
(696, '2020-02-25 17:42:53', 20),
(697, '2020-02-25 17:43:43', 20),
(698, '2020-02-25 17:46:48', 20),
(699, '2020-02-25 17:48:10', 20),
(700, '2020-02-25 17:49:24', 20),
(701, '2020-02-25 17:58:31', 20),
(702, '2020-02-25 17:58:53', 20),
(703, '2020-02-25 17:59:26', 22),
(704, '2020-02-25 18:05:12', 20),
(705, '2020-02-25 18:07:24', 20),
(706, '2020-02-25 18:07:55', 20),
(707, '2020-02-25 18:23:27', 20),
(708, '2020-02-25 18:23:49', 20);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `choices` varchar(255) DEFAULT NULL,
  `is_public` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `difficulty` varchar(255) NOT NULL,
  `timer_type` varchar(255) NOT NULL,
  `question_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `duration`, `content`, `answer`, `choices`, `is_public`, `category`, `difficulty`, `timer_type`, `question_type`) VALUES
(20, 10, 10, '<p>dafasd</p><p>1231<em>daasf</em></p>', '[\"a\",\"d\",\"ade\"]', '[\"a\",\"b\",\"c\",\"d\",\"ade\"]', 'false', 'test', 'easy', 'timeup', 'multi-answer'),
(21, 30, 123, 'dafadsfa\n', '[\"a\"]', '[\"a\",\"b\"]', 'false', 'test', 'easy', 'timeup', 'one-answer'),
(22, 10, 12, '<p>adfa</p><p><strong>bcddasaa</strong></p><p><em>dfaadfsa</em></p>', '[\"a\"]', '[\"a\",\"b\"]', 'true', 'test', 'easy', 'timedown', 'one-answer'),
(23, 10, 60, '<p>this is a test</p>', '[\"a\",\"c\"]', '[\"a\",\"b\",\"c\",\"d\"]', 'true', 'test', 'easy', 'timedown', 'two-answer');

-- --------------------------------------------------------

--
-- Table structure for table `quizs`
--

CREATE TABLE `quizs` (
  `id` int(11) NOT NULL,
  `classroom_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quizs`
--

INSERT INTO `quizs` (`id`, `classroom_id`, `created_at`) VALUES
(6, 2, '2020-02-04 08:10:26'),
(7, 2, '2020-02-04 08:11:32'),
(8, 2, '2020-02-04 08:12:15'),
(10, 1, '2020-02-06 22:05:57'),
(11, 1, '2020-02-06 22:05:59'),
(28, 3, '2020-02-11 01:38:16'),
(30, 1, '2020-02-11 05:19:35'),
(31, 1, '2020-02-11 18:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `studentResponse`
--

CREATE TABLE `studentResponse` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `question_instance_id` int(11) NOT NULL,
  `time_answered` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `studentResponse`
--

INSERT INTO `studentResponse` (`id`, `student_id`, `question_instance_id`, `time_answered`, `answer`) VALUES
(10, 2, 104, '2020-02-11 13:21:29', '[]'),
(11, 2, 105, '2020-02-11 13:21:49', '[]'),
(12, 2, 123, '2020-02-11 13:41:49', '[]'),
(13, 2, 127, '2020-02-11 13:50:08', '[]'),
(14, 2, 163, '2020-02-11 14:31:03', '[]'),
(16, 2, 289, '2020-02-11 17:49:35', '[]'),
(17, 2, 292, '2020-02-11 17:51:18', '[]'),
(19, 2, 397, '2020-02-17 23:06:56', '[]'),
(20, 2, 398, '2020-02-17 23:08:38', '[]'),
(21, 2, 399, '2020-02-17 23:09:13', '[]'),
(22, 2, 400, '2020-02-17 23:09:21', '[]'),
(23, 2, 401, '2020-02-17 23:10:42', '[]'),
(24, 2, 402, '2020-02-17 23:11:17', '[]'),
(25, 2, 403, '2020-02-17 23:11:28', '[]'),
(26, 2, 404, '2020-02-17 23:12:11', '[\"a\"]'),
(27, 2, 404, '2020-02-17 23:14:20', '[\"a\"]'),
(28, 2, 405, '2020-02-17 23:14:35', '[\"b\"]'),
(29, 2, 405, '2020-02-17 23:14:36', '[\"b\"]'),
(30, 2, 405, '2020-02-17 23:14:37', '[\"b\"]'),
(31, 2, 405, '2020-02-17 23:14:37', '[\"b\"]'),
(32, 2, 405, '2020-02-17 23:14:38', '[\"b\"]'),
(33, 2, 405, '2020-02-17 23:14:39', '[\"b\"]'),
(34, 2, 405, '2020-02-17 23:14:42', '[\"b\"]'),
(35, 2, 405, '2020-02-17 23:14:43', '[\"b\"]'),
(37, 2, 406, '2020-02-17 23:14:51', '[\"a\"]'),
(38, 2, 406, '2020-02-17 23:14:52', '[\"a\"]'),
(39, 2, 406, '2020-02-17 23:14:52', '[\"a\"]'),
(40, 2, 406, '2020-02-17 23:14:53', '[\"a\"]'),
(41, 2, 407, '2020-02-17 23:15:09', '[\"b\"]'),
(44, 2, 418, '2020-02-18 04:19:38', '[]'),
(45, 2, 424, '2020-02-18 04:22:54', '[]'),
(47, 2, 426, '2020-02-18 04:23:05', '[]'),
(48, 2, 426, '2020-02-18 04:23:11', '[]'),
(60, 2, 439, '2020-02-18 04:40:52', '[]'),
(61, 2, 446, '2020-02-18 04:50:38', '[]'),
(62, 2, 446, '2020-02-18 04:50:43', '[]'),
(64, 2, 448, '2020-02-18 04:59:22', '[]'),
(75, 2, 458, '2020-02-18 05:14:58', '[]'),
(91, 2, 480, '2020-02-18 06:07:24', '[null]'),
(94, 2, 483, '2020-02-18 06:08:32', '[\"a\",\"b\"]'),
(95, 2, 483, '2020-02-18 06:08:36', '[\"b\"]'),
(97, 2, 490, '2020-02-18 12:17:56', '[\"a\"]'),
(98, 2, 490, '2020-02-18 12:18:00', '[\"a\",\"c\"]'),
(99, 2, 491, '2020-02-18 12:18:42', '[\"a\"]'),
(100, 2, 493, '2020-02-18 12:37:51', '[\"a\"]'),
(101, 2, 494, '2020-02-18 12:39:31', '[\"b\"]'),
(103, 2, 495, '2020-02-18 12:41:55', '[\"b\"]'),
(104, 2, 496, '2020-02-18 12:46:20', '[\"a\"]'),
(105, 2, 497, '2020-02-18 13:22:51', '[\"c\"]'),
(106, 2, 498, '2020-02-18 13:23:39', '[\"d\"]'),
(107, 2, 499, '2020-02-18 13:24:13', '[\"b\"]'),
(108, 2, 500, '2020-02-18 13:24:47', '[\"b\"]'),
(109, 2, 501, '2020-02-18 13:25:57', '[\"a\"]'),
(110, 2, 501, '2020-02-18 13:26:02', '[\"a\",\"c\"]'),
(111, 2, 502, '2020-02-18 13:26:39', '[\"a\"]'),
(112, 2, 502, '2020-02-18 13:26:39', '[\"a\"]'),
(113, 2, 502, '2020-02-18 13:26:40', '[\"a\"]'),
(114, 2, 503, '2020-02-18 13:29:35', '[\"a\"]'),
(115, 2, 503, '2020-02-18 13:29:36', '[\"a\"]'),
(116, 2, 503, '2020-02-18 13:29:38', '[\"a\",\"c\"]'),
(119, 2, 511, '2020-02-19 03:14:45', '[\"a\",\"b\"]'),
(120, 2, 511, '2020-02-19 03:14:51', '[\"a\"]'),
(121, 2, 511, '2020-02-19 03:14:54', '[\"a\",\"c\"]'),
(122, 2, 511, '2020-02-19 03:14:59', '[\"a\",\"c\",\"d\"]'),
(123, 2, 512, '2020-02-19 03:33:08', '[\"a\"]'),
(124, 2, 513, '2020-02-19 03:33:21', '[\"a\"]'),
(125, 2, 514, '2020-02-19 03:37:11', '[\"a\"]'),
(126, 2, 515, '2020-02-19 03:46:51', '[\"a\"]'),
(127, 2, 517, '2020-02-19 03:47:45', '[\"b\"]'),
(128, 2, 518, '2020-02-19 07:29:36', '[\"b\",\"c\"]'),
(129, 2, 519, '2020-02-19 13:11:34', '[\"a\"]'),
(130, 2, 519, '2020-02-19 13:11:41', '[\"c\"]'),
(131, 2, 519, '2020-02-19 13:11:44', '[\"c\"]'),
(132, 2, 519, '2020-02-19 13:11:46', '[\"b\",\"c\"]'),
(133, 2, 519, '2020-02-19 13:11:53', '[\"b\",\"c\"]'),
(134, 2, 520, '2020-02-19 13:14:57', '[\"b\"]'),
(135, 2, 520, '2020-02-19 13:15:02', '[\"a\",\"b\"]'),
(136, 2, 520, '2020-02-19 13:15:04', '[\"a\"]'),
(137, 2, 520, '2020-02-19 13:15:04', '[\"a\"]'),
(138, 2, 520, '2020-02-19 13:15:05', '[\"a\"]'),
(139, 2, 520, '2020-02-19 13:15:05', '[\"a\"]'),
(140, 2, 520, '2020-02-19 13:15:05', '[\"a\"]'),
(141, 2, 520, '2020-02-19 13:15:06', '[\"a\"]'),
(142, 2, 521, '2020-02-19 13:16:13', '[\"a\"]'),
(147, 2, 522, '2020-02-19 13:17:09', '[\"a\"]'),
(148, 2, 522, '2020-02-19 13:17:12', '[\"b\"]'),
(149, 2, 523, '2020-02-19 13:18:04', '[\"a\"]'),
(150, 2, 523, '2020-02-19 13:18:07', '[\"b\"]'),
(153, 2, 524, '2020-02-19 13:32:52', '[\"a\"]'),
(154, 2, 524, '2020-02-19 13:32:56', '[\"b\"]'),
(160, 2, 525, '2020-02-19 13:33:17', '[\"a\"]'),
(161, 2, 525, '2020-02-19 13:33:20', '[\"b\"]'),
(162, 2, 525, '2020-02-19 13:33:22', '[\"a\"]'),
(163, 2, 526, '2020-02-19 13:51:04', '[\"a\"]'),
(165, 2, 527, '2020-02-19 13:55:45', '[\"a\"]'),
(166, 2, 527, '2020-02-19 13:55:51', '[\"b\"]'),
(167, 2, 527, '2020-02-19 13:55:52', '[\"b\"]'),
(168, 2, 528, '2020-02-19 13:57:02', '[\"b\"]'),
(169, 2, 530, '2020-02-19 13:58:31', '[\"b\"]'),
(170, 2, 531, '2020-02-19 13:58:50', '[\"a\"]'),
(171, 2, 531, '2020-02-19 13:58:58', '[\"b\"]'),
(172, 2, 531, '2020-02-19 13:58:59', '[\"b\"]'),
(173, 2, 532, '2020-02-19 14:00:27', '[\"a\"]'),
(176, 2, 534, '2020-02-19 14:02:18', '[\"a\"]'),
(177, 2, 538, '2020-02-19 14:04:15', '[\"a\"]'),
(178, 2, 539, '2020-02-19 14:04:34', '[\"b\"]'),
(179, 2, 543, '2020-02-19 14:07:42', '[\"a\"]'),
(183, 2, 545, '2020-02-19 14:09:04', '[\"a\"]'),
(184, 2, 545, '2020-02-19 14:09:10', '[\"b\"]'),
(185, 2, 545, '2020-02-19 14:09:14', '[\"d\"]'),
(188, 2, 546, '2020-02-19 14:11:52', '[\"a\"]'),
(189, 2, 547, '2020-02-19 14:12:22', '[\"a\"]'),
(190, 2, 547, '2020-02-19 14:12:28', '[\"b\"]'),
(195, 2, 549, '2020-02-20 17:41:52', '[]'),
(196, 2, 550, '2020-02-20 17:43:22', '[\"a\"]'),
(197, 2, 550, '2020-02-20 17:43:28', '[\"a\",\"c\"]'),
(198, 2, 551, '2020-02-20 17:44:02', '[\"a\"]'),
(199, 2, 551, '2020-02-20 17:44:11', '[\"b\"]'),
(200, 2, 551, '2020-02-20 17:44:12', '[\"b\"]'),
(201, 2, 556, '2020-02-24 07:42:11', '[\"a\"]'),
(203, 2, 567, '2020-02-25 02:41:36', '[\"a\"]'),
(204, 2, 567, '2020-02-25 02:41:42', '[\"b\",\"c\",\"d\"]'),
(205, 2, 567, '2020-02-25 02:41:48', '[\"a\",\"d\"]'),
(207, 2, 568, '2020-02-25 02:52:46', '[\"a\",\"b\"]'),
(210, 2, 570, '2020-02-25 02:53:39', '[\"a\"]'),
(213, 2, 572, '2020-02-25 06:06:05', '[\"b\"]'),
(214, 2, 573, '2020-02-25 06:06:14', '[\"a\"]'),
(218, 2, 648, '2020-02-25 13:42:50', '[\"a\"]'),
(224, 2, 652, '2020-02-25 13:53:49', '[\"a\",\"b\"]'),
(225, 2, 660, '2020-02-25 14:56:56', '[\"a\"]'),
(226, 2, 660, '2020-02-25 14:57:06', '[\"b\"]'),
(227, 2, 660, '2020-02-25 14:57:09', '[\"b\",\"c\"]'),
(228, 2, 660, '2020-02-25 16:06:46', '[\"b\",\"d\"]'),
(229, 2, 662, '2020-02-25 16:15:23', '[\"a\"]'),
(230, 2, 663, '2020-02-25 16:20:54', '[\"a\"]'),
(231, 2, 663, '2020-02-25 16:25:05', '[\"a\",\"c\"]'),
(232, 2, 672, '2020-02-25 17:10:19', '[\"a\",\"b\"]'),
(233, 2, 683, '2020-02-25 17:20:45', '[\"a\",\"b\"]'),
(234, 2, 684, '2020-02-25 17:21:36', '[\"a\",\"b\"]'),
(235, 2, 703, '2020-02-25 18:01:05', '[\"a\"]');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `role`) VALUES
(1, 't1', '202cb962ac59075b964b07152d234b70', '2020-02-03 19:28:53', 'teacher'),
(2, 's1', '202cb962ac59075b964b07152d234b70', '2020-02-03 20:33:06', 'student'),
(3, 't2', '202cb962ac59075b964b07152d234b70', '2020-02-03 22:54:03', 'teacher');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`id`,`taught_by`,`course_id`,`section_id`),
  ADD KEY `taught_by` (`taught_by`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrolledStudents`
--
ALTER TABLE `enrolledStudents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_id` (`classroom_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `questionInstance`
--
ALTER TABLE `questionInstance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questionInstance_ibfk_1` (`question_meta_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`,`quiz_id`),
  ADD KEY `questions_ibfk_1` (`quiz_id`);

--
-- Indexes for table `quizs`
--
ALTER TABLE `quizs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_id` (`classroom_id`);

--
-- Indexes for table `studentResponse`
--
ALTER TABLE `studentResponse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studentResponse_ibfk_1` (`student_id`),
  ADD KEY `studentResponse_ibfk_2` (`question_instance_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enrolledStudents`
--
ALTER TABLE `enrolledStudents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questionInstance`
--
ALTER TABLE `questionInstance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=709;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `quizs`
--
ALTER TABLE `quizs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `studentResponse`
--
ALTER TABLE `studentResponse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD CONSTRAINT `classrooms_ibfk_1` FOREIGN KEY (`taught_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `classrooms_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `enrolledStudents`
--
ALTER TABLE `enrolledStudents`
  ADD CONSTRAINT `enrolledStudents_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`),
  ADD CONSTRAINT `enrolledStudents_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `questionInstance`
--
ALTER TABLE `questionInstance`
  ADD CONSTRAINT `questionInstance_ibfk_1` FOREIGN KEY (`question_meta_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizs`
--
ALTER TABLE `quizs`
  ADD CONSTRAINT `quizs_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`);

--
-- Constraints for table `studentResponse`
--
ALTER TABLE `studentResponse`
  ADD CONSTRAINT `studentResponse_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studentResponse_ibfk_2` FOREIGN KEY (`question_instance_id`) REFERENCES `questionInstance` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;