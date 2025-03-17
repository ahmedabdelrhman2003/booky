<?php

use App\Enums\BookLangEnum;
use App\Enums\BookStatusEnum;
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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 7, 2)->nullable();
            $table->decimal('price_before_commission', 7, 2);
            $table->foreignId('author_id')->constrained('authors');
            $table->boolean('activation')->default(false);
            $table->integer('pages')->nullable();
            $table->enum('status', [BookStatusEnum::PENDING->value,BookStatusEnum::APPROVED->value,BookStatusEnum::REJECTED->value])->default(BookStatusEnum::PENDING->value);
            $table->enum('language',[BookLangEnum::ARABIC->value,BookLangEnum::ENGLISH->value]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
