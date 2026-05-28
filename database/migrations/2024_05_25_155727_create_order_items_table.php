<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();                                          
            $table->string('order_number')->unique(); 
            
            // បង្កើត Column user_id ជាប្រភេទធម្មតាសិន ដើម្បីកុំឱ្យទាក់ទើសពេលរត់ដំបូង
            $table->unsignedBigInteger('user_id')->nullable(); 

            $table->decimal('total_amount', 10, 2); 
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'stripe', 'paypal'])->default('cash');
            $table->string('payment_id')->nullable(); 
            $table->string('name');                
            $table->string('email');               
            $table->string('phone');               
            $table->text('address');               
            $table->timestamps();                  
        });

        // 💡 ដំណោះស្រាយផ្ដាច់ព្រ័ត្រ៖ ចង Foreign Key ទៅកាន់តារាង users នៅបន្ទាត់ខាងក្រោមដាច់ដោយឡែក
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};