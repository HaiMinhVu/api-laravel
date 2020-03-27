<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatteryProductTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'battery_product';

    /**
     * Run the migrations.
     * @table battery_product
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('battery_id')->unsigned();
            $table->integer('quantity')->nullable();
            $table->tinyInteger('included')->nullable();
            $table->index(["product_id"], 'product_id_idx');
            $table->index(["battery_id"], 'battery_id_idx');
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('product_id', 'product_id_idx')
                ->references('id')->on('products')
                ->onDelete('no action')
                ->onUpdate('no action');
            $table->foreign('battery_id', 'battery_id_idx')
                ->references('id')->on('batteries')
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
