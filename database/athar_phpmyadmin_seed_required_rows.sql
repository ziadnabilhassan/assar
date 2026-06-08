-- Required starter rows for a fresh Athar database.
-- Import this after athar_phpmyadmin_database.sql.
-- It prevents null errors such as "$Setting->banner_url on null".

SET NAMES utf8mb4;

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

INSERT INTO `category_types`
  (`title`, `image`, `show`, `created_at`, `updated_at`)
SELECT
  'Fashion',
  'assets/img/ecommerce/03.jpg',
  1,
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `category_types` LIMIT 1);

INSERT INTO `categories`
  (`title`, `category_type_id`, `image`, `created_at`, `updated_at`)
SELECT
  '{"en":"New Collection","ar":"تشكيلة جديدة"}',
  (SELECT `id` FROM `category_types` ORDER BY `id` ASC LIMIT 1),
  'assets/img/ecommerce/04.jpg',
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `categories` LIMIT 1);

INSERT INTO `colors`
  (`name`, `code`, `created_at`, `updated_at`)
SELECT
  '{"en":"Black","ar":"أسود"}',
  '#000000',
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `colors` LIMIT 1);

INSERT INTO `sizes`
  (`name`, `created_at`, `updated_at`)
SELECT
  'M',
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `sizes` LIMIT 1);

INSERT INTO `deliveries`
  (`name`, `cost`, `created_at`, `updated_at`)
SELECT
  '{"en":"Cairo Delivery","ar":"توصيل القاهرة"}',
  50.00,
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `deliveries` LIMIT 1);

INSERT INTO `sliders`
  (`image`, `url`, `title`, `text`, `created_at`, `updated_at`)
SELECT
  'assets/img/ecommerce/05.jpg',
  '/',
  '{"en":"Athar Collection","ar":"تشكيلة أثر"}',
  '{"en":"Discover the latest styles","ar":"اكتشف أحدث التصميمات"}',
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `sliders` LIMIT 1);

INSERT INTO `banners`
  (`image`, `url`, `created_at`, `updated_at`)
SELECT
  'assets/img/ecommerce/06.jpg',
  '/',
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `banners` LIMIT 1);

INSERT INTO `reviews`
  (`name`, `text`, `image`, `created_at`, `updated_at`)
SELECT
  '{"en":"Athar Customer","ar":"عميل أثر"}',
  '{"en":"Great quality and fast delivery.","ar":"جودة ممتازة وتوصيل سريع."}',
  'assets/img/faces/1.jpg',
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `reviews` LIMIT 1);

