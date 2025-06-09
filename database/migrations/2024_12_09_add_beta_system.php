<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table pour les codes d'invitation beta
        Schema::create('beta_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // Code unique
            $table->string('email')->nullable(); // Email du beta testeur
            $table->boolean('used')->default(false); // Code utilisé ou pas
            $table->timestamp('used_at')->nullable(); // Quand utilisé
            $table->unsignedBigInteger('user_id')->nullable(); // Qui l'a utilisé
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['code', 'used']);
        });

        // Ajouter une colonne beta aux utilisateurs
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_beta_tester')->default(false);
            $table->string('beta_invitation_code', 10)->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_beta_tester', 'beta_invitation_code']);
        });
        
        Schema::dropIfExists('beta_invitations');
    }
}; 