<?php

use App\Models\Item;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no');
            $table->foreignIdFor(Item::class, 'itemID');
            $table->decimal('selling_price', 8, 2);
            $table->integer('quantity');
            $table->timestamps();
            $table->date('date_created')->default(now());
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
