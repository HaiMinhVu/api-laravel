<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandIdToFormSubmissionTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'form_submissions';


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->bigInteger('brand_id')->unsigned()->after('id');
            $table->index(["brand_id"], 'brand_id_idx');
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('brand_id')
                ->references('id')->on('brands')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            //
        // });
    }
}
