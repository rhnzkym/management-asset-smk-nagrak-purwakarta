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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cat_id');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('item_name', 255);
            $table->integer('qty');
            $table->integer('good_qty')->default(0);
            $table->integer('broken_qty')->default(0);
            $table->integer('lost_qty')->default(0);
            $table->string('photo')->nullable();
            $table->boolean('is_borrowable')->default(false);
            $table->timestamps();

            $table->foreign('cat_id')->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
