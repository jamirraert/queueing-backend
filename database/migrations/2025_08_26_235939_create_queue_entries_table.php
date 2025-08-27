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
        Schema::create('queue_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id');
            $table->enum('type', ['priority', 'regular']);
            $table->string('queue_number')->nullable;
            $table->enum('status', ['waiting', 'called', 'served', 'skipped'])
                ->default('waiting');
            $table->foreignId('teller_id')
                ->nullable()
                ->constrained('tellers')
                ->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_entries');
    }
};
