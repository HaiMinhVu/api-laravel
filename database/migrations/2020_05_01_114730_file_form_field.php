<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileFormField extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'file_form_field';

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
            $table->bigInteger('form_field_id')->unsigned();
            $table->index('file_id');
            $table->index('form_field_id');
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('file_id')
                ->references('id')->on('files')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('form_field_id')
                ->references('id')->on('form_fields')
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
