<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProperTimestampsToFormSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->table('form_submissions', function (Blueprint $table) {
            $table->dropColumn('created_at');
        });

        Schema::connection('cms')->table('form_submissions', function (Blueprint $table) {
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('cms')->table('form_submissions', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->dateTime('created_at')->nullable();
        });
    }
}
