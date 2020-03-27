<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedProductsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'related_products';

    /**
     * Run the migrations.
     * @table related_products
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->unsigned();
            $table->bigInteger('related_id')->unsigned();
            $table->smallInteger('order')->nullable();
            $table->index(["related_id"], 'related_products_related_id_idx');
            $table->index(["parent_id"], 'related_products_parent_id_idx');
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('parent_id', 'related_products_parent_id_idx')
                ->references('id')->on('products')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('related_id', 'related_products_related_id_idx')
                ->references('id')->on('products')
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
