<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDetailsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'product_details';

    /**
     * Run the migrations.
     * @table product_details
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cms')->create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->integer('netsuite_id')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('eccn', 45)->nullable();
            $table->string('ccats', 45)->nullable();
            $table->float('online_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->tinyInteger('taxable')->nullable();
            $table->float('weight')->nullable();
            $table->string('weight_units', 45)->nullable();
            $table->float('auth_dealer_price')->nullable();
            $table->float('buying_group_price')->nullable();
            $table->float('dealer_price')->nullable();
            $table->float('dealer_dist_price')->nullable();
            $table->float('dis_price')->nullable();
            $table->float('dropship_price')->nullable();
            $table->float('gov_price')->nullable();
            $table->float('msrp')->nullable();
            $table->float('specials')->nullable();
            $table->tinyInteger('backordered')->nullable();
            $table->string('product_dimensions')->nullable();
            $table->tinyInteger('active_online')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->string('sku', 22)->nullable();
            $table->string('upc', 45)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->index(["product_id"], 'product_details_product_id_idx');
        });

        Schema::connection('cms')->table($this->tableName, function (Blueprint $table) {
            $table->foreign('product_id', 'product_details_product_id_idx')
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
