<?php

use App\Models\Employee;
use App\Models\Salestransaction;
use App\Models\Transactions;
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
        Schema::create('sale_transaction', function (Blueprint $table) {
            $table->id('sales_transaction_id');
            $table->string('transaction_number');
            $table->decimal('total_amount', 8, 2);
            $table->decimal('amount_tendered', 8, 2);
            $table->decimal('change', 8, 2);
            $table->decimal('vat', 8, 2);
            $table->decimal('subtotal', 8, 2);
            $table->integer('total_items');
            $table->foreignIdFor(Employee::class, 'employee_id');


            $table->timestamps();

        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignIdFor(Salestransaction::class, 'sales_transaction_id');
            $table->dropColumn('transaction_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
