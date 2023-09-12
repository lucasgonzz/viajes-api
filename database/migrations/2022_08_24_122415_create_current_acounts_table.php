<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentAcountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_acounts', function (Blueprint $table) {
            $table->id();
            $table->text('detalle')->nullable();
            $table->decimal('debe', 15,2)->nullable();
            $table->decimal('haber', 15,2)->nullable();
            $table->decimal('saldo', 15,2)->nullable();
            $table->enum('status', [
                'saldo_inicial',
                'sin_pagar', 
                'pagandose', 
                'pagado', 
                'nota_credito',
                'pago_from_client',
            ]);
            $table->text('description')->nullable();

            $table->integer('num_receipt')->nullable();

            $table->bigInteger('client_id')->unsigned()->nullable();

            $table->bigInteger('order_id')->unsigned()->nullable();

            $table->bigInteger('current_acount_payment_method_id')->unsigned()->nullable();

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
        Schema::dropIfExists('current_acounts');
    }
}
