<?php

use App\Enums\OTPActions;
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
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('otp', 6);
            $table->string('token');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('action', [OTPActions::CHANGE_EMAIL->value, OTPActions::VERIFY_EMAIL->value, OTPActions::RESET_PASSWORD->value]);
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_verifications');
    }
};
