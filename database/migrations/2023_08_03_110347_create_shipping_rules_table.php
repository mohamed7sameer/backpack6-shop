<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_region_id')->unsigned()->nullable();
            $table->foreign('shipping_region_id')->references('id')->on('shipping_regions')->onDelete('cascade');
            $table->integer('shipping_size_id')->unsigned()->nullable();
            $table->foreign('shipping_size_id')->references('id')->on('shipping_sizes')->onDelete('cascade');
            $table->integer('max_weight')->unsigned()->nullable();
            $table->decimal('price')->unsigned()->nullable();
            $table->integer('vat_class_id')->unsigned()->nullable();
            $table->foreign('vat_class_id')->references('id')->on('vat_classes')->onDelete('set null');
            $table->integer('shipping_vat_class_id')->unsigned()->nullable();
            $table->foreign('shipping_vat_class_id')->references('id')->on('vat_classes')->onDelete('set null');
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
        Schema::dropIfExists('shipping_rules');
    }
}
