<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileFormFieldSubmissionTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'file_form_field_submission';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('file_id')->unsigned();
            $table->bigInteger('form_field_submission_id')->unsigned();
            $table->index('file_id');
            $table->index('form_field_submission_id');
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('file_id')
                ->references('id')->on('files')
                ->onDelete('no action')
                ->onUpdate('no action');

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
        //
    }
}
