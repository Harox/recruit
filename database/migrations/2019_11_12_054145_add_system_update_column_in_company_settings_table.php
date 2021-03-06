<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSystemUpdateColumnInCompanySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->boolean('system_update')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn('system_update');
        });
    }
}
