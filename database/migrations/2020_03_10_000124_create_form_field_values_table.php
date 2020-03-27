<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldValuesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'form_field_values';

    /**
     * Run the migrations.
     * @table form_field_values
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('name');
            $table->bigInteger('form_field_id')->unsigned();
            $table->bigInteger('form_field_submission_id')->unsigned();
            $table->index("form_field_submission_id");
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('form_field_submission_id')
                ->references('id')->on('form_field_submissions')
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
       Schema::connection('cms')->dropIfExists($this->tableName);
     }
}
