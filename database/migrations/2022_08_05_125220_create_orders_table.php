<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('num')->nullable();
            $table->integer('order_operation_id')->unsigned()->nullable();
            $table->integer('client_id')->unsigned()->nullable();
            $table->integer('origin_client_address_id')->unsigned()->nullable();
            $table->integer('destination_client_address_id')->unsigned()->nullable();
            $table->integer('order_status_id')->unsigned()->default(1);
            $table->boolean('with_money')->default(0);
            $table->integer('driver_id')->unsigned()->nullable();
            $table->integer('location_id')->unsigned()->nullable();
            $table->integer('order_type_id')->unsigned()->nullable();
            $table->decimal('money_price', 10,2)->nullable();
            $table->decimal('person_price', 10,2)->nullable();
            $table->integer('person_amount')->nullable();
            $table->text('observations')->nullable();
            $table->timestamp('to_execute_at')->nullable();
            $table->timestamp('to_limit_execute_at')->nullable();
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('orders');
    }
}
