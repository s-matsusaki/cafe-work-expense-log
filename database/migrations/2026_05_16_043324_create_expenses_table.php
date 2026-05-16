<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * nullOnDeleteの意図：作業記録・カフェが消えても支出記録は残す
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            
            $table->foreignId('work_session_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('cafe_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->date('expense_date');

            $table->string('title', 255);
            $table->unsignedInteger('amount');

            $table->string('expense_type', 50);
            $table->string('payment_method', 50)->nullable();

            $table->boolean('accounting_recorded')->default(false);
            $table->dateTime('accounting_recorded_at')->nullable();
            $table->text('accounting_memo')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
