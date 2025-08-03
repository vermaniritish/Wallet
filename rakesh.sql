ALTER TABLE `admins`
ADD `shop_id` varchar(255) COLLATE 'utf8_general_ci' NULL AFTER `id`,
CHANGE `modified` `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP AFTER `address`;