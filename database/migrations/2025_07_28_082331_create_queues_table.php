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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['priority', 'regular']);
            $table->string('queue_number')->nullable();
             $table->enum('status', ['waiting', 'called', 'served', 'skipped'])
                ->default('waiting');
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('service_options')
                ->onDelete('set null');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
