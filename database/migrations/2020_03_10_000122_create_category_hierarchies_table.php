<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryHierarchiesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'category_hierarchies';

    /**
     * Run the migrations.
     * @table category_hierarchy
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('brand_id')->unsigned();
            $table->bigInteger('parent_category_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->index("parent_category_id");
            $table->index("brand_id");
            $table->index("category_id");
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('brand_id')
                ->references('id')->on('brands')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('parent_category_id')
                ->references('id')->on('categories')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('category_id')
                ->references('id')->on('categories')
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
