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
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('sellingPrice', 8, 2)->after('unitPrice')->nullable(false);
        });
    }

    /**cls
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
