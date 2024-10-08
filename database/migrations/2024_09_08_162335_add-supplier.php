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
        Schema::create('suppliers', function (Blueprint $table) {
             $table->id('SupplierId');
             $table->string('CompanyName');
             $table->string('ContactPerson');
             $table->string('ContactNumber');
             $table->string('Address');
             $table->boolean('Status')->default(1);
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
