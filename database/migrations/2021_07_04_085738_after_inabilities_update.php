<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AfterInabilitiesUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        create trigger after_inabilities_update AFTER UPDATE
        on inabilities
        for each row
        begin
            DELETE FROM temp_inabilities
            WHERE id = old.id;
        end
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `after_inabilities_update`');
    }
}
