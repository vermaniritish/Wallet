ALTER TABLE `colours`
ADD `title` varchar(255) NOT NULL AFTER `id`;
ALTER TABLE `colours`
ADD `slug` varchar(255) NOT NULL AFTER `id`;
ALTER TABLE `users`
ADD `session_keys` text COLLATE 'utf8mb3_general_ci' NULL AFTER `google_email`;





--please run email
INSERT INTO `email_templates` (`id`, `title`, `slug`, `type`, `subject`, `description`, `short_codes`, `created`, `modified`) VALUES
(7,	'Thanks for contacting',	'thank-you-for-contacting',	'client',	'Thank You For Contacting Us',	'<p>Dear {first_name} {last_name},</p>\r\n\r\n<p>Thank you for contacting us! We have received your message and will get back to you as soon as possible. Your patience is highly appreciated.<br />\r\nBest regards,<br />\r\n{company_name}</p>',	NULL,	NULL,	'2024-04-21 09:08:14'),
(8,	'Contact Us Request Received',	'admin-contact-us-request-received',	'admin',	'Contact Us Request Received',	'<p>Subject: New Contact Us Request Received</p>\r\n\r\n<p>Dear Admin,</p>\r\n\r\n<p>A new contact us request has been received. Here are the details:</p>\r\n\r\n<ul>\r\n	<li>Name: {first_name} {last_name}</li>\r\n	<li>Email: {email}</li>\r\n	<li>Phone Number: {number}</li>\r\n	<li>Message: {message}</li>\r\n</ul>\r\n\r\n<p>Please take appropriate action to respond to the user&#39;s request.</p>\r\n\r\n<p>Best regards,<br />\r\n{company_name}</p>',	NULL,	NULL,	'2024-04-21 09:10:00');

ALTER TABLE `colours`
ADD `code` varchar(255) COLLATE 'utf8mb4_0900_ai_ci' NOT NULL AFTER `title`;

ALTER TABLE `sizes`
CHANGE `type` `type` enum('Male','Female','Unisex','Kids') COLLATE 'utf8mb4_0900_ai_ci' NOT NULL AFTER `id`;

ALTER TABLE `products`
ADD `sku_number` varchar(255) NULL AFTER `service_price`;


ALTER TABLE `order_products`
ADD `brands_id` int NULL AFTER `product_id`,
ADD FOREIGN KEY (`brands_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE;
