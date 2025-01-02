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
        Schema::create('program__b_o_p__data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users');
            $table->foreignId('member_id')->constrained('members');
            $table->enum('status', ['acc', 'belum_acc'])->default('belum_acc');
            $table->date('transaction_date');
            $table->enum('transaction_type', ['pinjam','setor']);
            $table->decimal('transaction_value',11,2);
            $table->string('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program__b_o_p__data');
    }
};
