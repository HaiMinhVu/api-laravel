<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTagTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'product_tag';

    /**
     * Run the migrations.
     * @table product_tag
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tag_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->index(["tag_id"], 'tag_id_idx');
            $table->index(["product_id"], 'product_tag_product_id_idx');
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('tag_id', 'tag_id_idx')
                ->references('id')->on('tags')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('product_id', 'product_tag_product_id_idx')
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
