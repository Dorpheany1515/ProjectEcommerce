<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();                                 
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id'); // សំដៅទៅតារាង product (គ្មាន s)
            $table->integer('quantity');           
            $table->decimal('price', 10, 2);       
            $table->timestamps();
        });

        // 💡 ដំណោះស្រាយផ្ដាច់ព្រ័ត្រ៖ ចង Foreign Key បំបែកដាច់ដោយឡែកនៅខាងក្រោម ការពារ Error 150
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};