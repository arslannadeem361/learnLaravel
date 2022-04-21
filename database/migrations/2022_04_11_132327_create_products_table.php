<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->integer('category_id')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->decimal('regular_price', 5,3)->nullable();
            $table->decimal('discounted_price', 5,3)->nullable();
            $table->string('quantity')->nullable();
            $table->string('sku')->nullable();
            $table->string('publish_date');
            $table->longText('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->string('visible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
