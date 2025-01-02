<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_number'); 
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 0);
            $table->string('unit');
            $table->decimal('amount', 10, 0);
            $table->foreignId('user_id')->constrained();           
            $table->timestamps();

            $table->foreign('po_number')->references('po_number')->on('purchase_orders');    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropForeign(['po_number', 'user_id']);
        $table->dropIndex(['po_number','user_id']);
        Schema::dropIfExists('purchase_order_details');
    }
};
