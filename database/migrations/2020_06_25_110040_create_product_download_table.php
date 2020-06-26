<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDownloadTable extends Migration
{
    const CONNECTION = 'cms';

    public function __construct()
    {
        $this->connection = self::CONNECTION;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_download', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('file_mananger_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('product_download', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')->on('product')
                ->onDelete('no action')
                ->onUpdate('no action');
            $table->foreign('file_manager_id')
                ->references('id')->on('file_manager')
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
        Schema::dropIfExists('product_download');
    }
}
