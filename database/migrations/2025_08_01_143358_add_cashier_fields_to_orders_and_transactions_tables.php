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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('created_by_cashier')->default(false);
            $table->enum('order_type', ['dine_in', 'take_away'])->default('dine_in');
            $table->string('customer_phone', 20)->nullable();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('amount_paid', 15, 2)->nullable();
            $table->decimal('change_amount', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['created_by_cashier', 'order_type', 'customer_phone']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['amount_paid', 'change_amount']);
        });
    }
};
