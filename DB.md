CREATE TABLE `sand`.`accounts` (`id` INT NOT NULL , `name` VARCHAR(255) NOT NULL , `slug` VARCHAR(255) NOT NULL , `code` VARCHAR(30) NOT NULL , `intro` VARCHAR(255) NOT NULL , `description` TEXT NOT NULL , `account_id` INT(11) UNSIGNED NULL DEFAULT NULL , `status` BOOLEAN NOT NULL , `locked` BOOLEAN NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `deleted_at` TIMESTAMP NULL DEFAULT NULL ) ENGINE = InnoDB;

ALTER TABLE `branches` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `branch_status`;

ALTER TABLE `branches` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `clients` DROP FOREIGN KEY `clients_branch_id_foreign`; ALTER TABLE `clients` ADD CONSTRAINT `clients_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `clients` DROP FOREIGN KEY `clients_staff_id_foreign`; ALTER TABLE `clients` ADD CONSTRAINT `clients_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `clients` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `departments` ADD `account_id` INT(1) UNSIGNED NULL DEFAULT NULL AFTER `department_status`;

ALTER TABLE `departments` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `disbursements` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `date_disbursed`;

ALTER TABLE `disbursements` DROP FOREIGN KEY `disbursements_application_id_foreign`; ALTER TABLE `disbursements` ADD CONSTRAINT `disbursements_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `loanapplications`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `disbursements` DROP FOREIGN KEY `disbursements_branch_id_foreign`; ALTER TABLE `disbursements` ADD CONSTRAINT `disbursements_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `disbursements` DROP FOREIGN KEY `disbursements_client_id_foreign`; ALTER TABLE `disbursements` ADD CONSTRAINT `disbursements_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `disbursements` DROP FOREIGN KEY `disbursements_particular_id_foreign`; ALTER TABLE `disbursements` ADD CONSTRAINT `disbursements_particular_id_foreign` FOREIGN KEY (`particular_id`) REFERENCES `particulars`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `disbursements` DROP FOREIGN KEY `disbursements_payment_id_foreign`; ALTER TABLE `disbursements` ADD CONSTRAINT `disbursements_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `particulars`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `disbursements` DROP FOREIGN KEY `disbursements_product_id_foreign`; ALTER TABLE `disbursements` ADD CONSTRAINT `disbursements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `loanproducts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `disbursements` DROP FOREIGN KEY `disbursements_staff_id_foreign`; ALTER TABLE `disbursements` ADD CONSTRAINT `disbursements_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `disbursements` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `emailattachments` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `size`;

ALTER TABLE `emailattachments` DROP FOREIGN KEY `emailattachments_email_id_foreign`; ALTER TABLE `emailattachments` ADD CONSTRAINT `emailattachments_email_id_foreign` FOREIGN KEY (`email_id`) REFERENCES `emails`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `emailattachments` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `emails` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `status`;

ALTER TABLE `emails` DROP FOREIGN KEY `emails_tag_id_foreign`; ALTER TABLE `emails` ADD CONSTRAINT `emails_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `emailtags`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `emails` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `entries` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `remarks`;

ALTER TABLE `entries` DROP FOREIGN KEY `entries_account_typeId_foreign`; ALTER TABLE `entries` ADD CONSTRAINT `entries_account_typeId_foreign` FOREIGN KEY (`account_typeId`) REFERENCES `account_types`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` DROP FOREIGN KEY `entries_application_id_foreign`; ALTER TABLE `entries` ADD CONSTRAINT `entries_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `loanapplications`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` DROP FOREIGN KEY `entries_branch_id_foreign`; ALTER TABLE `entries` ADD CONSTRAINT `entries_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` DROP FOREIGN KEY `entries_client_id_foreign`; ALTER TABLE `entries` ADD CONSTRAINT `entries_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` DROP FOREIGN KEY `entries_disbursement_id_foreign`; ALTER TABLE `entries` ADD CONSTRAINT `entries_disbursement_id_foreign` FOREIGN KEY (`disbursement_id`) REFERENCES `disbursements`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` DROP FOREIGN KEY `entries_entry_typeId_foreign`; ALTER TABLE `entries` ADD CONSTRAINT `entries_entry_typeId_foreign` FOREIGN KEY (`entry_typeId`) REFERENCES `entrytypes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` DROP FOREIGN KEY `entries_particular_id_foreign`; ALTER TABLE `entries` ADD CONSTRAINT `entries_particular_id_foreign` FOREIGN KEY (`particular_id`) REFERENCES `particulars`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` DROP FOREIGN KEY `entries_payment_id_foreign`; ALTER TABLE `entries` ADD CONSTRAINT `entries_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `particulars`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` DROP FOREIGN KEY `entries_staff_id_foreign`; ALTER TABLE `entries` ADD CONSTRAINT `entries_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `loanapplications` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `loan_agreement`;

ALTER TABLE `loanapplications` DROP FOREIGN KEY `loanapplications_branch_id_foreign`; ALTER TABLE `loanapplications` ADD CONSTRAINT `loanapplications_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `loanapplications` DROP FOREIGN KEY `loanapplications_client_id_foreign`; ALTER TABLE `loanapplications` ADD CONSTRAINT `loanapplications_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `loanapplications` DROP FOREIGN KEY `loanapplications_product_id_foreign`; ALTER TABLE `loanapplications` ADD CONSTRAINT `loanapplications_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `loanproducts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `loanapplications` DROP FOREIGN KEY `loanapplications_staff_id_foreign`; ALTER TABLE `loanapplications` ADD CONSTRAINT `loanapplications_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `loanapplications` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `loanproducts` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `status`;

ALTER TABLE `loanproducts` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `particulars` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `cash_flow_typeId`;

ALTER TABLE `particulars` DROP FOREIGN KEY `particulars_account_typeId_foreign`; ALTER TABLE `particulars` ADD CONSTRAINT `particulars_account_typeId_foreign` FOREIGN KEY (`account_typeId`) REFERENCES `account_types`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `particulars` DROP FOREIGN KEY `particulars_cash_flow_typeId_foreign`; ALTER TABLE `particulars` ADD CONSTRAINT `particulars_cash_flow_typeId_foreign` FOREIGN KEY (`cash_flow_typeId`) REFERENCES `cash_flow_types`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `particulars` DROP FOREIGN KEY `particulars_category_id_foreign`; ALTER TABLE `particulars` ADD CONSTRAINT `particulars_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `particulars` DROP FOREIGN KEY `particulars_subcategory_id_foreign`; ALTER TABLE `particulars` ADD CONSTRAINT `particulars_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `particulars` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `positions` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `permissions`;

ALTER TABLE `positions` DROP FOREIGN KEY `positions_department_id_foreign`; ALTER TABLE `positions` ADD CONSTRAINT `positions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `positions` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `settings` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `currency_id`;

ALTER TABLE `settings` DROP FOREIGN KEY `settings_currency_id_foreign`; ALTER TABLE `settings` ADD CONSTRAINT `settings_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `settings` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `staffs` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `access_status`;

ALTER TABLE `staffs` DROP FOREIGN KEY `staffs_branch_id_foreign`; ALTER TABLE `staffs` ADD CONSTRAINT `staffs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `staffs` DROP FOREIGN KEY `staffs_officer_staff_id_foreign`; ALTER TABLE `staffs` ADD CONSTRAINT `staffs_officer_staff_id_foreign` FOREIGN KEY (`officer_staff_id`) REFERENCES `staffs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `staffs` DROP FOREIGN KEY `staffs_position_id_foreign`; ALTER TABLE `staffs` ADD CONSTRAINT `staffs_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `staffs` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `subcategories` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `category_id`;

ALTER TABLE `subcategories` DROP FOREIGN KEY `subcategories_category_id_foreign`; ALTER TABLE `subcategories` ADD CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `subcategories` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `useractivities` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `referrer_id`;

ALTER TABLE `useractivities` DROP FOREIGN KEY `useractivities_client_id_foreign`; ALTER TABLE `useractivities` ADD CONSTRAINT `useractivities_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `useractivities` DROP FOREIGN KEY `useractivities_user_id_foreign`; ALTER TABLE `useractivities` ADD CONSTRAINT `useractivities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `useractivities` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `userlogs` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `account`;

ALTER TABLE `userlogs` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `2fa`;

ALTER TABLE `users` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `users` ADD FOREIGN KEY (`staff_id`) REFERENCES `staffs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `users` ADD FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `subcategories` ADD `subcategory_code` VARCHAR(100) NULL DEFAULT NULL AFTER `subcategory_name`;
ALTER TABLE `particulars` ADD `particular_code` VARCHAR(100) NULL DEFAULT NULL AFTER `particular_name`;
ALTER TABLE `emails` CHANGE `tag_id` `tag_id` INT(11) UNSIGNED NULL;

ALTER TABLE `entries` ADD `transaction_reference` VARCHAR(255) NULL DEFAULT NULL AFTER `remarks`;

ALTER TABLE `emails` CHANGE `label` `label` ENUM('draft','spam','important','trash','archive','favorite') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `entrytypes` ADD `type_code` VARCHAR(20) NOT NULL AFTER `account_typeId`;
ALTER TABLE `clients` ADD `savings_products` TEXT NULL DEFAULT NULL AFTER `account_type`;
ALTER TABLE `disbursements` ADD `arrears_info` TEXT NULL DEFAULT NULL AFTER `arrears`;
ALTER TABLE `settings` ADD `website` VARCHAR(100) NOT NULL DEFAULT 'https://nexentech.co/' AFTER `author`;

ALTER TABLE `account_types` ADD `description` TEXT NOT NULL AFTER `code`;
ALTER TABLE `cash_flow_types` ADD `code` VARCHAR(20) NOT NULL AFTER `name`, ADD `description` TEXT NOT NULL AFTER `code`;
ALTER TABLE `products` ADD `product_type` ENUM('loans','savings') NULL DEFAULT NULL AFTER `product_code`;

ALTER TABLE `loanproducts` ADD `min_savings_balance_type_application` ENUM('amount','rate','multiplier') NULL DEFAULT NULL AFTER `max_principal`;
ALTER TABLE `loanproducts` ADD `max_savings_balance_type_application` ENUM('amount','rate','multiplier') NULL DEFAULT NULL AFTER `min_savings_balance_application`;
ALTER TABLE `loanproducts` ADD `max_savings_balance_application` DOUBLE(10,2) NULL DEFAULT NULL AFTER `min_savings_balance_application`;
ALTER TABLE `loanproducts` ADD `min_savings_balance_type_disbursement` ENUM('amount','rate','multiplier') NULL DEFAULT NULL AFTER `max_savings_balance_application`;
ALTER TABLE `loanproducts` ADD `max_savings_balance_type_disbursement` ENUM('amount','rate','multiplier') NULL DEFAULT NULL AFTER `min_savings_balance_disbursement`;
ALTER TABLE `loanproducts` ADD `max_savings_balance_disbursement` DOUBLE(10,2) NULL DEFAULT NULL AFTER `max_savings_balance_type_disbursement`;

ALTER TABLE `entries` ADD `product_id` INT(11) NULL DEFAULT NULL AFTER `client_id`;
ALTER TABLE `settings` ADD `financial_year_start` VARCHAR(20) NOT NULL DEFAULT '01-Jan' AFTER `round_off`;
ALTER TABLE `loanproducts` ADD `product_code` VARCHAR(20) NOT NULL AFTER `product_name`;
ALTER TABLE `loanproducts` CHANGE `min_savings_balance_application` `min_savings_balance_application` DOUBLE(10,2) NULL DEFAULT NULL;
ALTER TABLE `loanproducts` CHANGE `min_savings_balance_disbursement` `min_savings_balance_disbursement` DOUBLE(10,2) NULL DEFAULT NULL;

<!-- 12th Sept, 24 -->
ALTER TABLE `entries` ADD `parent_id` INT(11) NULL DEFAULT NULL AFTER `remarks`;
ALTER TABLE `entries` ADD CONSTRAINT `particulars_id_foreign` FOREIGN KEY (`particular_id`) REFERENCES `particulars`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` ADD CONSTRAINT `payments_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `particulars`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` ADD CONSTRAINT `branches_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` ADD CONSTRAINT `clients_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` ADD CONSTRAINT `applications_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `loanapplications`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` ADD CONSTRAINT `disbursements_id_foreign` FOREIGN KEY (`disbursement_id`) REFERENCES `disbursements`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` ADD CONSTRAINT `account_types_id_foreign` FOREIGN KEY (`account_typeId`) REFERENCES `account_types`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` ADD CONSTRAINT `entry_types_id_foreign` FOREIGN KEY (`entry_typeId`) REFERENCES `entrytypes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `entries` ADD CONSTRAINT `parents_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `entries`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ADD CONSTRAINT `staffs_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
INSERT INTO `entrytypes` (`id`, `type`, `part`, `entry_menu`, `account_typeId`, `type_code`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'Purchase', 'credit', 'financing', '8', '20080', 'active', current_timestamp(), current_timestamp(), NULL), (NULL, 'Dividend', 'debit', 'financing', '8', '20081', 'inactive', current_timestamp(), current_timestamp(), NULL);

<!-- 13th Sept 24 -->
ALTER TABLE applicant_products ADD loan_period INT NULL DEFAULT NULL AFTER interest_period, ADD loan_frequency ENUM('days','weeks','months','years') NULL DEFAULT NULL AFTER loan_period;
INSERT INTO `entrytypes` (`type`, `part`, `entry_menu`, `account_typeId`, `type_code`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES ('Purchase', 'credit', 'financing', 8, '20080', 'active', '2024-09-12 23:42:04', '2024-09-12 23:42:04', NULL), ('Dividend', 'debit', 'financing', 8, '20081', 'inactive', '2024-09-12 23:42:04', '2024-09-12 23:42:04', NULL);

<!-- 17th Sept 24 -->
ALTER TABLE loanproducts ADD loan_period INT NULL DEFAULT NULL AFTER interest_type, ADD loan_frequency ENUM('days','weeks','months','years') NULL DEFAULT NULL AFTER loan_period;
ALTER TABLE loanproducts CHANGE repayment_freq repayment_freq ENUM('Daily','Weekly','Bi-Weekly','Monthly','Bi-Monthly','Quarterly','Termly','Bi-Annual','Annually') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

<!-- 24th Sept 24 -->
ALTER TABLE `clients` ADD `closest_landmark` TEXT NOT NULL AFTER `residence`;
ALTER TABLE `charges` ADD `account_id` INT(11) UNSIGNED NOT NULL AFTER `product_id`;
ALTER TABLE `files` ADD `account_id` INT(11) UNSIGNED NOT NULL AFTER `type`;
ALTER TABLE `products` ADD `account_id` INT(11) UNSIGNED NOT NULL AFTER `status`;
ALTER TABLE `entries` ADD `parent_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `balance`;
ALTER TABLE `entries` ADD FOREIGN KEY (`parent_id`) REFERENCES `entries`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE clients ADD salary DOUBLE(10,2) NULL DEFAULT NULL AFTER signature;

<!-- 26th Sept 24 -->
ALTER TABLE loanproducts ADD grace_period INT NULL DEFAULT NULL AFTER repayment_freq, ADD grace_frequency ENUM('day(s)','week(s)','month(s)','year(s)') NULL DEFAULT NULL AFTER grace_period, ADD arrears_period INT NULL DEFAULT NULL AFTER grace_frequency, ADD arrears_frequency ENUM('day(s)','week(s)','month(s)','year(s)') NULL DEFAULT NULL AFTER arrears_period, ADD write_off_period INT NULL DEFAULT NULL AFTER arrears_frequency, ADD write_off_frequency ENUM('day(s)','week(s)','month(s)','year(s)') NULL DEFAULT NULL AFTER write_off_period;
ALTER TABLE loanproducts ADD penalty_rate INT NULL DEFAULT NULL AFTER write_off_frequency, ADD penalty_frequency ENUM('day(s)','week(s)','month(s)','year(s)') NULL DEFAULT NULL AFTER penalty_rate;
ALTER TABLE loanproducts ADD penalty_method ENUM('amount','percent') NULL DEFAULT NULL AFTER write_off_frequency;
ALTER TABLE loanproducts CHANGE penalty_rate penalty_rate DOUBLE(10,2) NULL DEFAULT NULL, CHANGE penalty_frequency penalty_period ENUM('day','week','month','year') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL;
INSERT INTO `account_types` (`name`, `code`, `description`, `status`, `category_id`, `created_at`, `updated_at`, `deleted_at`) VALUES ('Capital', '2003', 'Represents ownership interest in the business.', 'Active', '2', current_timestamp(), current_timestamp(), NULL);
ALTER TABLE `charges` ADD `cutoff_date` DATE NULL DEFAULT NULL AFTER `effective_date`;
ALTER TABLE `applicationremarks` ADD `account_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `remarks`;

<!-- 2nd Oct 24 -->
ALTER TABLE `loanapplications` CHANGE `status` `status` ENUM('Pending','Processing','Declined','Approved','Disbursed','Cancelled') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Approved'; <!-- Egowan Financial Services LTD -->

<!-- 15th Oct 24 -->
ALTER TABLE `loanproducts` ADD `principal_particular_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `status`, ADD `interest_particular_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `principal_particular_id`;
ALTER TABLE `products` ADD `savings_particular_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `status`, ADD `withdrawCharges_particular_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `savings_particular_id`;
ALTER TABLE `disbursements` ADD `interest_particular_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `particular_id`;

<!-- 21st Oct 24 -->
INSERT INTO `entrytypes` (`id`, `type`, `part`, `entry_menu`, `account_typeId`, `type_code`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'Withdrawal', 'debit', 'financing', '8', '20082', 'active', current_timestamp(), current_timestamp(), NULL);

<!-- 26th Oct 24 -->
ALTER TABLE `entries` CHANGE `amount` `amount` DOUBLE(20,2) NOT NULL;
ALTER TABLE `particulars` CHANGE `debit` `debit` DOUBLE(20,2) NOT NULL;
ALTER TABLE `particulars` CHANGE `credit` `credit` DOUBLE(20,2) NOT NULL;
ALTER TABLE `staffs` CHANGE `salary_scale` `salary_scale` DOUBLE(20,2) NOT NULL;

 ALTER TABLE `products` ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`savings_particular_id`) REFERENCES `particulars`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
 ALTER TABLE `products` ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`withdrawCharges_particular_id`) REFERENCES `particulars`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;