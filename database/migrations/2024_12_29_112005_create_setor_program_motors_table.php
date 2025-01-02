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
        Schema::create('setor_program_motors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users');
            $table->foreignId('member_id')->constrained('members');
            $table->foreignId('program_motor_id')->constrained('program_motors');
            $table->enum('status', ['acc', 'belum_acc'])->default('acc');
            $table->enum('transaction_type', ['pinjam','setor'])->default('setor');
            $table->string('note');
            $table->date('transaction_date');
            $table->decimal('transaction_value',11,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setor_program_motors');
    }
};
