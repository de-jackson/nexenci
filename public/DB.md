ALTER TABLE `clients` CHANGE `account_type` `account_type` ENUM('Client','Stakeholder','Non Stakeholder','Exited') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Client';