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
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('invite_code', 20)->unique();
            $table->boolean('is_private')->default(true);
            $table->integer('max_members')->default(20);
            $table->timestamps();
        });

        Schema::create('league_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('weekly_score')->default(0);
            $table->integer('monthly_score')->default(0);
            $table->integer('total_score')->default(0);
            $table->integer('position')->default(0);
            $table->enum('role', ['member', 'admin'])->default('member');
            $table->timestamp('last_score_update')->nullable();
            $table->timestamps();

            $table->unique(['league_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('league_members');
        Schema::dropIfExists('leagues');
    }
}; 