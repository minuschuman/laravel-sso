<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class AddPivotDataToTeamworkSetupTables  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Config::get('teamwork.team_user_table'), function (Blueprint $table) {
            $table->string('role')->nullable()->after('team_id');
            $table->string('status')->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Config::get('teamwork.team_user_table'), function (Blueprint $table) {
            $table->dropColumn('role')->nullable();
            $table->dropColumn('status')->nullable();
        });
    }
}
