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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middle')->nullable(true);
            $table->string('suffix')->nullable(true);
            $table->integer('age');
            $table->string('address');
            $table->string('contact_number');
            $table->string('gender');
            $table->string('role');
            $table->string('username');
            $table->string('password');
            $table->string('status');
            $table->timestamps();
            $table->longText('avatar')->charset('binary')->nullable(true);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
