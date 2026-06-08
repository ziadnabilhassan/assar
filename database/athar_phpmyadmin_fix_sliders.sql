-- Fix for existing Athar databases imported before slider title/text existed.
-- Import this file in phpMyAdmin, or run these SQL statements manually.

ALTER TABLE `sliders`
  ADD COLUMN `title` LONGTEXT NULL AFTER `url`,
  ADD COLUMN `text` LONGTEXT NULL AFTER `title`;
