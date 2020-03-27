<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'files';

    /**
     * Run the migrations.
     * @table files
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->bigInteger('brand_id')->unsigned();
            $table->bigInteger('file_type_id')->unsigned();
            $table->index(["brand_id"], 'brand_id_idx');
            $table->index(["file_type_id"], 'file_type_id_idx');
            $table->softDeletes();
            $table->nullableTimestamps();
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('brand_id', 'brand_id_idx')
                ->references('id')->on('brands')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('file_type_id', 'file_type_id_idx')
                ->references('id')->on('file_types')
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
