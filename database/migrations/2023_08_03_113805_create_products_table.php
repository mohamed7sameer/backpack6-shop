<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->index();
            $table->string('sku')->nullable();
            $table->integer('product_category_id')->unsigned()->nullable();
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('set null');
            $table->integer('product_status_id')->unsigned()->nullable();
            $table->foreign('product_status_id')->references('id')->on('product_statuses')->onDelete('set null');
            $table->text('description')->nullable();
            $table->longText('extras')->nullable();
            $table->longText('properties')->nullable();
            $table->string('cover')->nullable();
            $table->text('photos')->nullable();
            $table->decimal('price', 10, 2)->unsigned()->nullable();
            $table->decimal('sale_price', 10, 2)->unsigned()->nullable();
            $table->integer('vat_class_id')->unsigned()->nullable();
            $table->foreign('vat_class_id')->references('id')->on('vat_classes');
            $table->text('shipping_sizes')->nullable();
            $table->integer('shipping_weight')->unsigned()->nullable();
            $table->longText('variations')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('lft')->unsigned()->nullable()->index();
            $table->integer('rgt')->unsigned()->nullable();
            $table->integer('depth')->unsigned()->nullable();
            $table->timestamp('deleted_at')->nullable()->index();
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
