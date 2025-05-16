<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            // Link to users table instead of employees
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->decimal('goal_amount', 15, 2);
            $table->string('image_url')->nullable();
            $table->enum('status', ['active', 'closed'])
                  ->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
