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
        Schema::create('program_mobils', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_polisi')->unique();
            $table->foreignId('admin_id')->constrained('users');
            $table->foreignId('member_id')->constrained('members');
            $table->enum('status', ['acc', 'belum_acc'])->default('belum_acc');
            $table->date('transaction_date');
            $table->decimal('cicilan',11,2);
            $table->decimal('total_installment',11,2);
            $table->decimal('sisa_bayar',11,2);
            $table->boolean('paid_off')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_mobils');
    }
};
