-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 03:13 PM
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
-- Database: `bid_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` int(11) NOT NULL,
  `alert_type` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bid_id` int(11) DEFAULT NULL,
  `alert_message` text DEFAULT NULL,
  `alert_date` date DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`alert_id`, `alert_type`, `user_id`, `bid_id`, `alert_message`, `alert_date`, `is_read`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted`) VALUES
(1, 'Bid Stage Alert', 1, 8, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:12:44', NULL, '2024-10-04 14:12:44', 0),
(2, 'Bid Stage Alert', 1, 10, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:12:44', NULL, '2024-10-04 14:12:44', 0),
(3, 'Bid Stage Alert', 1, 8, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:12:59', NULL, '2024-10-04 14:12:59', 0),
(4, 'Bid Stage Alert', 1, 10, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:12:59', NULL, '2024-10-04 14:12:59', 0),
(5, 'Bid Stage Alert', 1, 8, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:12:59', NULL, '2024-10-04 14:12:59', 0),
(6, 'Bid Stage Alert', 1, 10, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:13:00', NULL, '2024-10-04 14:13:00', 0),
(7, 'Bid Stage Alert', 1, 8, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:16:24', NULL, '2024-10-04 14:16:24', 0),
(8, 'Bid Stage Alert', 1, 10, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:16:24', NULL, '2024-10-04 14:16:24', 0),
(9, 'Bid Stage Alert', 1, 8, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:16:45', NULL, '2024-10-04 14:16:45', 0),
(10, 'Bid Stage Alert', 1, 10, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:16:45', NULL, '2024-10-04 14:16:45', 0),
(11, 'Bid Stage Alert', 1, 8, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:16:45', NULL, '2024-10-04 14:16:45', 0),
(12, 'Bid Stage Alert', 1, 10, 'The bid \'Supply of ICT Equipment\' has expired in stage \'Initial Review\'', NULL, 0, 1, '2024-10-04 14:16:45', NULL, '2024-10-04 14:16:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `audit_id` int(11) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `action_type` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`audit_id`, `table_name`, `record_id`, `action_type`, `user_id`, `timestamp`, `details`) VALUES
(1, 'bids', 8, '', 1, '2024-10-04 13:21:19', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(2, 'bids', 10, '', 1, '2024-10-04 13:21:19', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(3, 'bids', 8, '', 1, '2024-10-04 13:21:28', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(4, 'bids', 10, '', 1, '2024-10-04 13:21:28', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(5, 'bids', 8, '', 1, '2024-10-04 13:21:28', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(6, 'bids', 10, '', 1, '2024-10-04 13:21:28', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(7, 'bids', 8, '', 1, '2024-10-04 13:23:38', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(8, 'bids', 10, '', 1, '2024-10-04 13:23:38', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(9, 'bids', 8, '', 1, '2024-10-04 13:23:39', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(10, 'bids', 10, '', 1, '2024-10-04 13:23:39', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(11, 'bids', 8, '', 1, '2024-10-04 13:24:50', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(12, 'bids', 10, '', 1, '2024-10-04 13:24:50', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(13, 'bids', 8, '', 1, '2024-10-04 13:24:50', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(14, 'bids', 10, '', 1, '2024-10-04 13:24:50', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(15, 'bids', 8, '', 1, '2024-10-04 13:28:56', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(16, 'bids', 10, '', 1, '2024-10-04 13:28:56', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(17, 'bids', 8, '', 1, '2024-10-04 13:29:00', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(18, 'bids', 10, '', 1, '2024-10-04 13:29:00', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(19, 'bids', 8, '', 1, '2024-10-04 13:29:00', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(20, 'bids', 10, '', 1, '2024-10-04 13:29:00', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(21, 'bids', 8, '', 1, '2024-10-04 13:38:59', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(22, 'bids', 10, '', 1, '2024-10-04 13:38:59', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(23, 'bids', 8, '', 1, '2024-10-04 14:12:44', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(24, 'bids', 10, '', 1, '2024-10-04 14:12:44', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(25, 'bids', 8, '', 1, '2024-10-04 14:12:59', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(26, 'bids', 10, '', 1, '2024-10-04 14:12:59', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(27, 'bids', 8, '', 1, '2024-10-04 14:12:59', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(28, 'bids', 10, '', 1, '2024-10-04 14:13:00', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(29, 'bids', 8, '', 1, '2024-10-04 14:16:24', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(30, 'bids', 10, '', 1, '2024-10-04 14:16:24', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(31, 'bids', 8, '', 1, '2024-10-04 14:16:44', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(32, 'bids', 10, '', 1, '2024-10-04 14:16:45', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(33, 'bids', 8, '', 1, '2024-10-04 14:16:45', 'Viewed bid Supply of ICT Equipment in stage Initial Review'),
(34, 'bids', 10, '', 1, '2024-10-04 14:16:45', 'Viewed bid Supply of ICT Equipment in stage Initial Review');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `bid_id` int(11) NOT NULL,
  `bid_title` varchar(255) NOT NULL,
  `bid_description` text DEFAULT NULL,
  `submission_deadline` date DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0,
  `budget` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`bid_id`, `bid_title`, `bid_description`, `submission_deadline`, `client_name`, `created_by`, `department_id`, `status`, `created_at`, `updated_by`, `updated_at`, `deleted`, `budget`) VALUES
(1, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:47:20', NULL, '2024-10-03 15:49:27', 0, NULL),
(3, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:51:26', NULL, '2024-10-03 15:51:26', 0, NULL),
(5, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:52:59', NULL, '2024-10-03 15:52:59', 0, NULL),
(6, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:53:50', NULL, '2024-10-03 15:53:50', 0, NULL),
(7, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:55:07', NULL, '2024-10-03 15:55:07', 0, NULL),
(8, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:55:40', NULL, '2024-10-03 15:55:40', 0, NULL),
(9, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:56:12', NULL, '2024-10-03 15:56:12', 0, NULL),
(10, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department.', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 16:01:42', NULL, '2024-10-03 16:01:42', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bid_documents`
--

CREATE TABLE `bid_documents` (
  `document_id` int(11) NOT NULL,
  `bid_id` int(11) DEFAULT NULL,
  `document_type` varchar(100) DEFAULT NULL,
  `document_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bid_documents`
--

INSERT INTO `bid_documents` (`document_id`, `bid_id`, `document_type`, `document_link`) VALUES
(3, 5, 'Technical Proposal', 'https://example.com/docs/technical_proposal.pdf'),
(4, 6, 'Technical Proposal', 'https://example.com/docs/technical_proposal.pdf'),
(5, 7, 'Technical Proposal', 'https://example.com/docs/technical_proposal.pdf'),
(6, 8, 'Technical Proposal', 'https://example.com/docs/technical_proposal.pdf'),
(7, 8, 'Technical Proposal', 'https://example.com/docs/technical_proposal.pdf'),
(8, 10, 'Technical Proposal', 'https://example.com/documents/technical_proposal.pdf'),
(9, 10, 'Financial Proposal', 'https://example.com/documents/financial_proposal.pdf'),
(10, 10, 'Project Plan', 'https://example.com/documents/project_plan.pdf'),
(11, 10, 'Risk Assessment', 'https://example.com/documents/risk_assessment.pdf'),
(12, 10, 'Implementation Timeline', 'https://example.com/documents/implementation_timeline.pdf'),
(13, 10, 'Vendor Certification', 'https://example.com/documents/vendor_certification.pdf'),
(14, 10, 'Compliance Documentation', 'https://example.com/documents/compliance_documentation.pdf'),
(15, 10, 'Warranty Information', 'https://example.com/documents/warranty_information.pdf'),
(16, 10, 'Support Plan', 'https://example.com/documents/support_plan.pdf'),
(17, 10, 'Cost Breakdown', 'https://example.com/documents/cost_breakdown.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `bid_responses`
--

CREATE TABLE `bid_responses` (
  `response_id` int(11) NOT NULL,
  `bid_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `response_text` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bid_responses`
--

INSERT INTO `bid_responses` (`response_id`, `bid_id`, `user_id`, `response_text`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted`) VALUES
(1, 8, 1, 'Our response to the bid includes a detailed cost breakdown and technical proposal.', 1, '2024-10-03 15:56:12', NULL, '2024-10-03 15:56:12', 0),
(2, 10, 1, 'Response from User 1 regarding the bid.', 1, '2024-10-03 16:03:53', NULL, NULL, 0),
(3, 10, 1, 'Response from User 2 with additional details.', 1, '2024-10-03 16:03:53', NULL, NULL, 0),
(4, 10, 1, 'Response from User 3 confirming participation.', 1, '2024-10-03 16:03:53', NULL, NULL, 0),
(5, 10, 1, 'Response from User 4 with questions about the bid.', 1, '2024-10-03 16:03:53', NULL, NULL, 0),
(6, 10, 1, 'Response from User 5 addressing concerns.', 1, '2024-10-03 16:03:53', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bid_stages`
--

CREATE TABLE `bid_stages` (
  `stage_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `stage_order` int(11) NOT NULL,
  `stage_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `stage_duration_days` int(11) NOT NULL DEFAULT 0,
  `is_final_stage` tinyint(1) NOT NULL DEFAULT 0,
  `next_stage_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bid_stages`
--

INSERT INTO `bid_stages` (`stage_id`, `department_id`, `stage_order`, `stage_name`, `description`, `stage_duration_days`, `is_final_stage`, `next_stage_id`) VALUES
(1, 1, 1, 'Initial Review', 'First stage of the bid review process', 1, 0, NULL),
(2, 1, 2, 'Initial Review', 'The bid documents are reviewed for completeness.', 1, 0, NULL),
(3, 1, 3, 'Initial Review', 'Initial review of the bid documents.', 2, 0, NULL),
(4, 1, 4, 'Technical Evaluation', 'Technical evaluation of the proposals.', 3, 0, NULL),
(5, 1, 5, 'Financial Evaluation', 'Evaluation of the financial proposals.', 2, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bid_workflow`
--

CREATE TABLE `bid_workflow` (
  `workflow_id` int(11) NOT NULL,
  `bid_id` int(11) DEFAULT NULL,
  `current_stage_id` int(11) DEFAULT NULL,
  `previous_stage_id` int(11) DEFAULT NULL,
  `next_stage_id` int(11) DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `date_assigned` date DEFAULT NULL,
  `date_completed` date DEFAULT NULL,
  `approval_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bid_workflow`
--

INSERT INTO `bid_workflow` (`workflow_id`, `bid_id`, `current_stage_id`, `previous_stage_id`, `next_stage_id`, `completed_at`, `assigned_to`, `date_assigned`, `date_completed`, `approval_status`) VALUES
(5, 8, 1, NULL, 2, NULL, 1, '2024-10-03', NULL, 'Pending'),
(6, 10, 1, NULL, 2, NULL, 1, '2024-10-03', NULL, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `manager_id`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted`) VALUES
(1, 'ICT Department', 1, 1, '2024-10-03 15:46:10', 1, '2024-10-03 15:46:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0,
  `enabled` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `department_id`, `role`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted`, `enabled`) VALUES
(1, 'jpkalombo', '$2y$10$QhxdC39YpOcNrjZMCz1MZuB5xFizN8JCiBqBZd8DNpZu3nL63lvDm', 'jpkalombo99@gmail.com', 1, 'Admin', NULL, '2024-10-02 13:55:33', 1, '2024-10-02 15:26:56', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `workflows`
--

CREATE TABLE `workflows` (
  `workflow_id` int(11) NOT NULL,
  `bid_id` int(11) DEFAULT NULL,
  `current_stage` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bid_id` (`bid_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `bid_documents`
--
ALTER TABLE `bid_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `bid_id` (`bid_id`);

--
-- Indexes for table `bid_responses`
--
ALTER TABLE `bid_responses`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `bid_id` (`bid_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `bid_stages`
--
ALTER TABLE `bid_stages`
  ADD PRIMARY KEY (`stage_id`),
  ADD KEY `next_stage_id` (`next_stage_id`);

--
-- Indexes for table `bid_workflow`
--
ALTER TABLE `bid_workflow`
  ADD PRIMARY KEY (`workflow_id`),
  ADD KEY `bid_id` (`bid_id`),
  ADD KEY `current_stage_id` (`current_stage_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `fk_manager` (`manager_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `workflows`
--
ALTER TABLE `workflows`
  ADD PRIMARY KEY (`workflow_id`),
  ADD KEY `bid_id` (`bid_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `bid_documents`
--
ALTER TABLE `bid_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `bid_responses`
--
ALTER TABLE `bid_responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bid_stages`
--
ALTER TABLE `bid_stages`
  MODIFY `stage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bid_workflow`
--
ALTER TABLE `bid_workflow`
  MODIFY `workflow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workflows`
--
ALTER TABLE `workflows`
  MODIFY `workflow_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `alerts_ibfk_2` FOREIGN KEY (`bid_id`) REFERENCES `bids` (`bid_id`),
  ADD CONSTRAINT `alerts_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `alerts_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD CONSTRAINT `audit_trail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `bids_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bids_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bid_documents`
--
ALTER TABLE `bid_documents`
  ADD CONSTRAINT `bid_documents_ibfk_1` FOREIGN KEY (`bid_id`) REFERENCES `bids` (`bid_id`);

--
-- Constraints for table `bid_responses`
--
ALTER TABLE `bid_responses`
  ADD CONSTRAINT `bid_responses_ibfk_1` FOREIGN KEY (`bid_id`) REFERENCES `bids` (`bid_id`),
  ADD CONSTRAINT `bid_responses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bid_responses_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bid_responses_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bid_stages`
--
ALTER TABLE `bid_stages`
  ADD CONSTRAINT `bid_stages_ibfk_1` FOREIGN KEY (`next_stage_id`) REFERENCES `bid_stages` (`stage_id`);

--
-- Constraints for table `bid_workflow`
--
ALTER TABLE `bid_workflow`
  ADD CONSTRAINT `bid_workflow_ibfk_1` FOREIGN KEY (`bid_id`) REFERENCES `bids` (`bid_id`),
  ADD CONSTRAINT `bid_workflow_ibfk_2` FOREIGN KEY (`current_stage_id`) REFERENCES `bid_stages` (`stage_id`),
  ADD CONSTRAINT `bid_workflow_ibfk_3` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `departments_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_manager` FOREIGN KEY (`manager_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `workflows`
--
ALTER TABLE `workflows`
  ADD CONSTRAINT `workflows_ibfk_1` FOREIGN KEY (`bid_id`) REFERENCES `bids` (`bid_id`),
  ADD CONSTRAINT `workflows_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `workflows_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
