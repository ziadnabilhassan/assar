<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('variants') || ! Schema::hasTable('sizes')) {
            return;
        }

        if (Schema::hasColumn('variants', 'size')) {
            $indexExists = function (string $indexName): bool {
                return ! empty(DB::select('SHOW INDEX FROM `variants` WHERE Key_name = ?', [$indexName]));
            };

            if (! Schema::hasColumn('variants', 'size_id')) {
                DB::statement('ALTER TABLE `variants` ADD `size_id` BIGINT UNSIGNED NULL AFTER `color_id`');
            }

            $variantSizes = DB::table('variants')
                ->select('size')
                ->whereNotNull('size')
                ->distinct()
                ->pluck('size');

            foreach ($variantSizes as $sizeName) {
                $sizeId = DB::table('sizes')->where('name', $sizeName)->value('id');

                if (! $sizeId) {
                    $sizeId = DB::table('sizes')->insertGetId([
                        'name' => $sizeName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('variants')->where('size', $sizeName)->update(['size_id' => $sizeId]);
            }

            if (! $indexExists('variants_product_id_foreign')) {
                DB::statement('ALTER TABLE `variants` ADD INDEX `variants_product_id_foreign` (`product_id`)');
            }

            if ($indexExists('variants_product_id_color_id_size_unique')) {
                DB::statement('ALTER TABLE `variants` DROP INDEX `variants_product_id_color_id_size_unique`');
            }

            DB::statement('ALTER TABLE `variants` DROP COLUMN `size`');
            DB::statement('ALTER TABLE `variants` MODIFY `size_id` BIGINT UNSIGNED NOT NULL');

            if (! $indexExists('variants_product_id_color_id_size_id_unique')) {
                DB::statement('ALTER TABLE `variants` ADD UNIQUE `variants_product_id_color_id_size_id_unique` (`product_id`, `color_id`, `size_id`)');
            }

            if (! $indexExists('variants_size_id_foreign')) {
                DB::statement('ALTER TABLE `variants` ADD CONSTRAINT `variants_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE');
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('variants') || ! Schema::hasTable('sizes')) {
            return;
        }

        if (Schema::hasColumn('variants', 'size_id') && ! Schema::hasColumn('variants', 'size')) {
            DB::statement('ALTER TABLE `variants` DROP FOREIGN KEY `variants_size_id_foreign`');
            DB::statement('ALTER TABLE `variants` DROP INDEX `variants_product_id_color_id_size_id_unique`');
            DB::statement('ALTER TABLE `variants` ADD `size` VARCHAR(255) NULL AFTER `color_id`');
            DB::statement('UPDATE `variants` INNER JOIN `sizes` ON `variants`.`size_id` = `sizes`.`id` SET `variants`.`size` = `sizes`.`name`');
            DB::statement('ALTER TABLE `variants` DROP COLUMN `size_id`');
            DB::statement('ALTER TABLE `variants` MODIFY `size` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `variants` ADD UNIQUE `variants_product_id_color_id_size_unique` (`product_id`, `color_id`, `size`)');
        }
    }
};
