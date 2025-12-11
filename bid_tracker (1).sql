-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2024 at 04:16 PM
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
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`bid_id`, `bid_title`, `bid_description`, `submission_deadline`, `client_name`, `created_by`, `department_id`, `status`, `created_at`, `updated_by`, `updated_at`, `deleted`) VALUES
(1, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:47:20', NULL, '2024-10-03 15:49:27', 0),
(3, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:51:26', NULL, '2024-10-03 15:51:26', 0),
(5, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:52:59', NULL, '2024-10-03 15:52:59', 0),
(6, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:53:50', NULL, '2024-10-03 15:53:50', 0),
(7, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:55:07', NULL, '2024-10-03 15:55:07', 0),
(8, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:55:40', NULL, '2024-10-03 15:55:40', 0),
(9, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 15:56:12', NULL, '2024-10-03 15:56:12', 0),
(10, 'Supply of ICT Equipment', 'Supply and installation of ICT equipment for the IT department.', '2024-12-01', 'XYZ Corporation', 1, 1, 'Open', '2024-10-03 16:01:42', NULL, '2024-10-03 16:01:42', 0);

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
  `stage_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bid_stages`
--

INSERT INTO `bid_stages` (`stage_id`, `stage_name`, `description`) VALUES
(1, 'Initial Review', 'First stage of the bid review process'),
(2, 'Initial Review', 'The bid documents are reviewed for completeness.'),
(3, 'Initial Review', 'Initial review of the bid documents.'),
(4, 'Technical Evaluation', 'Technical evaluation of the proposals.'),
(5, 'Financial Evaluation', 'Evaluation of the financial proposals.');

-- --------------------------------------------------------

--
-- Table structure for table `bid_workflow`
--

CREATE TABLE `bid_workflow` (
  `workflow_id` int(11) NOT NULL,
  `bid_id` int(11) DEFAULT NULL,
  `current_stage_id` int(11) DEFAULT NULL,
  `stage_duration_days` int(11) DEFAULT 0,
  `previous_stage_id` int(11) DEFAULT NULL,
  `next_stage_id` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `date_assigned` date DEFAULT NULL,
  `date_completed` date DEFAULT NULL,
  `approval_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bid_workflow`
--

INSERT INTO `bid_workflow` (`workflow_id`, `bid_id`, `current_stage_id`, `stage_duration_days`, `previous_stage_id`, `next_stage_id`, `assigned_to`, `date_assigned`, `date_completed`, `approval_status`) VALUES
(5, 8, 1, 7, NULL, 2, 1, '2024-10-03', NULL, 'Pending'),
(6, 10, 1, 5, NULL, 2, 1, '2024-10-03', NULL, 'Pending');

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
  ADD PRIMARY KEY (`stage_id`);

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
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT;

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
