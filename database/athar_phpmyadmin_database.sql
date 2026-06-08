-- Athar database schema for phpMyAdmin / MySQL.
-- Import this file from phpMyAdmin after selecting your database.
-- It is based on the current Athar Laravel backend tables, with missing
-- dashboard tables such as brands and sizes included.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NULL,
  `is_admin` TINYINT NOT NULL DEFAULT 0,
  `email_verified_at` TIMESTAMP NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` VARCHAR(255) NOT NULL,
  `tokenable_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `token` VARCHAR(64) NOT NULL,
  `abilities` TEXT NULL,
  `last_used_at` TIMESTAMP NULL,
  `expires_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` LONGTEXT NOT NULL,
  `text` LONGTEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone1` VARCHAR(255) NOT NULL,
  `phone2` VARCHAR(255) NULL,
  `email` VARCHAR(255) NOT NULL,
  `time` VARCHAR(255) NOT NULL,
  `address` LONGTEXT NOT NULL,
  `facebook` VARCHAR(255) NULL,
  `whatsapp` VARCHAR(255) NULL,
  `instagram` VARCHAR(255) NULL,
  `linkedin` VARCHAR(255) NULL,
  `twitter` VARCHAR(255) NULL,
  `youtube` VARCHAR(255) NULL,
  `tiktok` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` LONGTEXT NOT NULL,
  `text` LONGTEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sliders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `image` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `title` LONGTEXT NULL,
  `text` LONGTEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `keywords` LONGTEXT NOT NULL,
  `description` LONGTEXT NOT NULL,
  `main_image` VARCHAR(255) NOT NULL,
  `banner_image` VARCHAR(255) NOT NULL,
  `banner_url` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `message` LONGTEXT NOT NULL,
  `read` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `category_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `show` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `brands` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` LONGTEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` LONGTEXT NOT NULL,
  `category_type_id` BIGINT UNSIGNED NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `categories_category_type_id_foreign` (`category_type_id`),
  CONSTRAINT `categories_category_type_id_foreign`
    FOREIGN KEY (`category_type_id`) REFERENCES `category_types` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `colors` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` LONGTEXT NOT NULL,
  `code` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sizes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` LONGTEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `is_featured` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `variants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `color_id` BIGINT UNSIGNED NOT NULL,
  `size_id` BIGINT UNSIGNED NOT NULL,
  `quantity` BIGINT UNSIGNED NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `old_price` DECIMAL(10,2) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `variants_product_id_color_id_size_id_unique` (`product_id`, `color_id`, `size_id`),
  KEY `variants_color_id_foreign` (`color_id`),
  KEY `variants_size_id_foreign` (`size_id`),
  CONSTRAINT `variants_product_id_foreign`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `variants_color_id_foreign`
    FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `variants_size_id_foreign`
    FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `image` VARCHAR(255) NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `color_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  KEY `product_images_color_id_foreign` (`color_id`),
  CONSTRAINT `product_images_product_id_foreign`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `product_images_color_id_foreign`
    FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `deliveries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` LONGTEXT NOT NULL,
  `cost` DECIMAL(6,2) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `addresses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `delivery_id` BIGINT UNSIGNED NOT NULL,
  `city` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  KEY `addresses_delivery_id_foreign` (`delivery_id`),
  CONSTRAINT `addresses_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `addresses_delivery_id_foreign`
    FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `banners` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `image` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `promo_codes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(255) NOT NULL,
  `discount_type` ENUM('percentage', 'fixed') NOT NULL,
  `discount_value` DECIMAL(8,2) NOT NULL,
  `max_uses` INT NOT NULL DEFAULT 1,
  `uses_count` INT NOT NULL DEFAULT 0,
  `start_date` TIMESTAMP NULL,
  `end_date` TIMESTAMP NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promo_codes_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `desgins` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `height` VARCHAR(255) NOT NULL,
  `width` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `color` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` BIGINT NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `user_name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `delivery` VARCHAR(255) NOT NULL,
  `city` VARCHAR(255) NOT NULL,
  `address` TEXT NOT NULL,
  `shipping` DECIMAL(6,2) UNSIGNED NOT NULL DEFAULT 0,
  `total` DECIMAL(12,2) UNSIGNED NOT NULL,
  `status` ENUM('pending', 'processing', 'completed', 'cancelled', 'returned') NOT NULL DEFAULT 'pending',
  `readed` TINYINT(1) NOT NULL DEFAULT 0,
  `note` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order_details` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(255) NOT NULL,
  `color` VARCHAR(255) NOT NULL,
  `size` VARCHAR(255) NOT NULL,
  `quantity` INT UNSIGNED NOT NULL,
  `price` DECIMAL(10,2) UNSIGNED NOT NULL,
  `total_price` DECIMAL(12,2) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_order_id_foreign` (`order_id`),
  KEY `order_details_product_id_foreign` (`product_id`),
  CONSTRAINT `order_details_order_id_foreign`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `order_details_product_id_foreign`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `settings`
  (`keywords`, `description`, `main_image`, `banner_image`, `banner_url`, `created_at`, `updated_at`)
SELECT
  '{"en":"Athar, fashion, ecommerce","ar":"أثر, أزياء, متجر"}',
  '{"en":"Athar online store","ar":"متجر أثر الإلكتروني"}',
  'assets/img/backgrounds/01.png',
  'assets/img/ecommerce/01.jpg',
  '/',
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `settings` LIMIT 1);

INSERT INTO `contacts`
  (`phone1`, `phone2`, `email`, `time`, `address`, `facebook`, `whatsapp`, `instagram`, `linkedin`, `twitter`, `youtube`, `tiktok`, `created_at`, `updated_at`)
SELECT
  '01000000000',
  NULL,
  'info@example.com',
  '10:00 AM - 10:00 PM',
  '{"en":"Cairo, Egypt","ar":"القاهرة، مصر"}',
  NULL,
  NULL,
  NULL,
  NULL,
  NULL,
  NULL,
  NULL,
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `contacts` LIMIT 1);

INSERT INTO `pages`
  (`title`, `text`, `image`, `created_at`, `updated_at`)
SELECT
  '{"en":"About Athar","ar":"عن أثر"}',
  '{"en":"Athar is an online fashion store.","ar":"أثر متجر إلكتروني للأزياء."}',
  'assets/img/ecommerce/02.jpg',
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `pages` LIMIT 1);
