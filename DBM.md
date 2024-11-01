ALTER TABLE `settings` ADD `sms` BOOLEAN NULL DEFAULT NULL AFTER `account_id`, ADD `email` BOOLEAN NULL DEFAULT NULL AFTER `sms`;

ALTER TABLE `loanproducts` ADD `product_code` VARCHAR(30) NULL DEFAULT NULL AFTER `product_name`;

INSERT INTO `account_types` (`id`, `name`, `code`, `description`, `status`, `category_id`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'Member Savings', '4008', '', 'Active', '4', current_timestamp(), current_timestamp(), NULL);

ALTER TABLE `charges` ADD `charge_limits` DOUBLE(10,2) NULL DEFAULT NULL AFTER `charge`;

ALTER TABLE `clients` CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `mobile` `mobile` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `alternate_no` `alternate_no` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `gender` `gender` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `religion` `religion` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `marital_status` `marital_status` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `occupation` `occupation` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `job_location` `job_location` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `residence` `residence` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `id_type` `id_type` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `id_number` `id_number` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `next_of_kin_name` `next_of_kin_name` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `next_of_kin_relationship` `next_of_kin_relationship` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `next_of_kin_contact` `next_of_kin_contact` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `next_of_kin_alternate_contact` `next_of_kin_alternate_contact` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL, CHANGE `nok_email` `nok_email` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL;

ALTER TABLE settings CHANGE business_name business_name VARCHAR(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;

ALTER TABLE clients ADD title VARCHAR(20) NULL AFTER name;

ALTER TABLE `useractivities` ADD `location` TINYTEXT NULL DEFAULT NULL AFTER `action`;

ALTER TABLE `useractivities` ADD `ip_address` VARCHAR(60) NULL DEFAULT NULL AFTER `location`;

ALTER TABLE `useractivities` ADD `operating_system` TINYTEXT NULL DEFAULT NULL AFTER `ip_address`;

ALTER TABLE `useractivities` ADD `browser` TINYTEXT NULL DEFAULT NULL AFTER `operating_system`, ADD `browser_version` TINYTEXT NULL DEFAULT NULL AFTER `browser`, ADD `latitude` TINYTEXT NULL DEFAULT NULL AFTER `browser_version`, ADD `longitude` TINYTEXT NULL DEFAULT NULL AFTER `latitude`;
