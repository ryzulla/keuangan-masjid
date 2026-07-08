<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Add standalone index on ipl_period_id so the FK can survive dropping the composite unique key
        DB::statement('ALTER TABLE ipl_billings ADD INDEX ipl_billings_period_id_idx (ipl_period_id)');
        // Drop the composite unique key (safe now because ipl_period_id has its own index)
        DB::statement('ALTER TABLE ipl_billings DROP INDEX ipl_billings_ipl_period_id_resident_id_unique');
        // Drop the standalone index on resident_id
        DB::statement('ALTER TABLE ipl_billings DROP INDEX ipl_billings_resident_id_foreign');
        // Rename resident_id -> responsible_resident_id and make nullable
        DB::statement('ALTER TABLE ipl_billings CHANGE `resident_id` `responsible_resident_id` BIGINT UNSIGNED NULL');
        // Drop existing nullable FK on house_block_id and re-add as NOT NULL
        DB::statement('ALTER TABLE ipl_billings DROP FOREIGN KEY ipl_billings_house_block_id_foreign');
        DB::statement('ALTER TABLE ipl_billings MODIFY `house_block_id` BIGINT UNSIGNED NOT NULL');
        // Add new FKs
        DB::statement('ALTER TABLE ipl_billings ADD CONSTRAINT ipl_billings_responsible_resident_id_foreign FOREIGN KEY (responsible_resident_id) REFERENCES residents(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE ipl_billings ADD CONSTRAINT ipl_billings_house_block_id_foreign FOREIGN KEY (house_block_id) REFERENCES house_blocks(id) ON DELETE CASCADE');
        // New unique constraint
        DB::statement('ALTER TABLE ipl_billings ADD UNIQUE KEY ipl_billings_period_block_unique (ipl_period_id, house_block_id)');
        // Drop the temporary standalone index (ipl_period_id is now covered by the new unique key)
        DB::statement('ALTER TABLE ipl_billings DROP INDEX ipl_billings_period_id_idx');
    }
    public function down(): void {
        DB::statement('ALTER TABLE ipl_billings DROP FOREIGN KEY ipl_billings_responsible_resident_id_foreign');
        DB::statement('ALTER TABLE ipl_billings DROP FOREIGN KEY ipl_billings_house_block_id_foreign2');
        DB::statement('ALTER TABLE ipl_billings DROP INDEX ipl_billings_period_block_unique');
        DB::statement('ALTER TABLE ipl_billings CHANGE `responsible_resident_id` `resident_id` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE ipl_billings MODIFY `house_block_id` BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE ipl_billings ADD CONSTRAINT ipl_billings_resident_id_foreign FOREIGN KEY (resident_id) REFERENCES residents(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE ipl_billings ADD UNIQUE KEY ipl_billings_ipl_period_id_resident_id_unique (ipl_period_id, resident_id)');
    }
};
