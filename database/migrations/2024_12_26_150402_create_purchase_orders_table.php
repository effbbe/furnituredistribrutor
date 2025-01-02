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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_number')->unique(); 
            $table->foreignId('supplier_id')->constrained();           
            $table->decimal('total_amount');
            $table->foreignId('user_id')->constrained();         
            $table->timestamps();
            
            $table->index('po_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropForeign(['supplier_id', 'user_id']);
        $table->dropIndex(['po_number','agent_id', 'category_id']);
        Schema::dropIfExists('purchase_orders');
    }
};
