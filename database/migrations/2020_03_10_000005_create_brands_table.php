<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'brands';

    /**
     * Run the migrations.
     * @table brands
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('description', 45)->nullable();
            $table->string('slug', 100)->nullable();
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
