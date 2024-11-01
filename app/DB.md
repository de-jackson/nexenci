ALTER TABLE `loanproducts` CHANGE `repayment_period` `repayment_period` INT(10) NULL;

ALTER TABLE `loanproducts` CHANGE `product_desc` `product_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `loanproducts` CHANGE `repayment_freq` `repayment_freq` ENUM('Weekly','Bi-Weekly','Monthly','Bi-Monthly','Quarterly','Termly','Bi-Annual','Annually') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Monthly';

ALTER TABLE `loanproducts` CHANGE `repayment_freq` `repayment_freq` ENUM('Weekly','Bi-Weekly','Monthly','Bi-Monthly','Quarterly','Termly','Bi-Annual','Annually') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `loanproducts` CHANGE `repayment_duration` `repayment_duration` ENUM('day(s)','week(s)','month(s)','year(s)') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


ALTER TABLE `loanproducts` CHANGE `product_charges` `product_charges` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


ALTER TABLE `settings` ADD `loan_min_principal` DOUBLE(10,2) NOT NULL AFTER `tax_rate`, ADD `loan_max_principal` DOUBLE(10,2) NOT NULL AFTER `loan_min_principal`;


ALTER TABLE `particulars` ADD `particular_charges` TEXT NOT NULL AFTER `charged`;

New

ALTER TABLE `loanproducts` ADD `min_savings_balance_application` DOUBLE(10,2) NOT NULL AFTER `max_principal`, ADD `min_savings_balance_disbursement` DOUBLE(10,2) NOT NULL AFTER `min_savings_balance_application`;


INSERT INTO `charges` (`charge_method`, `charge`, `charge_mode`, `frequency`, `effective_date`, `status`, `particular_id`) VALUES
('Amount', 12000, 'Auto', 'One-Time', '2008-01-01', 'Active', 1),
('Amount', 12000, 'Auto', 'One-Time', '2009-01-01', 'Active', 1),
('Amount', 12000, 'Auto', 'One-Time', '2010-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2011-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2012-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2013-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2014-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2015-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2016-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2017-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2018-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2019-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2020-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2021-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2022-01-01', 'Active', 1),
('Amount', 50000, 'Auto', 'One-Time', '2023-01-01', 'Active', 1),
('Amount', 100000, 'Auto', 'One-Time', '2024-01-01', 'Active', 1),
('Amount', 12000, 'Auto', 'One-Time', '2007-01-01', 'Active', 1);




ALTER TABLE `settings` CHANGE `author` `author` VARCHAR(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Nexen Tech', CHANGE `system_abbr` `system_abbr` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'Nexen', CHANGE `business_name` `business_name` VARCHAR(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `business_abbr` `business_abbr` VARCHAR(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `business_slogan` `business_slogan` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `business_contact` `business_contact` VARCHAR(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `business_alt_contact` `business_alt_contact` VARCHAR(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `business_email` `business_email` VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `business_pobox` `business_pobox` VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `business_address` `business_address` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `business_web` `business_web` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `business_about` `business_about` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `description` `description` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `background_logo` `background_logo` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `email_template_logo` `email_template_logo` VARCHAR(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `google_map_iframe` `google_map_iframe` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `whatsapp` `whatsapp` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `facebook` `facebook` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `twitter` `twitter` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `instagram` `instagram` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `youtube` `youtube` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `linkedin` `linkedin` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `tax_rate` `tax_rate` DOUBLE NULL DEFAULT NULL, CHANGE `loan_min_principal` `loan_min_principal` DOUBLE(10,2) NULL DEFAULT NULL, CHANGE `loan_max_principal` `loan_max_principal` DOUBLE(10,2) NULL DEFAULT NULL;
