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
            $table->id('order_id');
            $table->date('date');
            $table->text('status');
            $table->date('del_date');
            $table->decimal('price', 8, 2);
            $table->text('first_name');
            $table->text('address');
            $table->string('last_name', 50);
            $table->integer('phone');
            $table->integer('zip');
            $table->string('email', 50);
            // $table->timestamps();
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
