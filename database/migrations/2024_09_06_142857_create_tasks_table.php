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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->dateTime('due_date')->nullable();
            $table->enum('status', ['in_progress', 'completed', 'canceled']);
            $table->unsignedBigInteger('assigned_to')->constrained('users');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_on');
            $table->timestamp('updated_on');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
