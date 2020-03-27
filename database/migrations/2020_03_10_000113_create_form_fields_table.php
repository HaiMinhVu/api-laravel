<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'form_fields';

    /**
     * Run the migrations.
     * @table form_fields
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 45)->nullable();
            $table->string('description');
            $table->bigInteger('form_id')->unsigned();
            $table->bigInteger('form_field_type_id')->unsigned();
            $table->index(["form_field_type_id"], 'form_field_type_id_idx');
            $table->index(["form_id"], 'form_id_idx');
        });
        
        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('form_id', 'form_fields_form_id_idx')
                ->references('id')->on('forms')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('form_field_type_id', 'form_fields_form_field_type_id_idx')
                ->references('id')->on('form_field_types')
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
