<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id('id_artikel')->autoIncrement(); // Primary key
            $table->string('title'); // Judul artikel
            $table->text('content'); // Isi artikel
            $table->enum('role', ['berita', 'jurnal', 'majalah']); // Role artikel
            $table->string('author'); // Penulis artikel
            $table->string('image')->nullable(); // Gambar artikel
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};