<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Transactions extends Migration{
    
    public function up(){
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('order_code'); //code of the product transaction (TR08332)
            $table->string('status'); //pending(0), success(1), failed(2)
            $table->double('amount');
            $table->double('price');
            $table->double('total_price');
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('transactions');
    }
}