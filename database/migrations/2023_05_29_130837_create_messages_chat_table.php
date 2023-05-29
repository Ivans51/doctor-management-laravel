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
        Schema::create('messages_chat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('chat_id');
            $table->text('message');
            $table->string('file')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('is_read')->nullable();
            $table->string('is_deleted')->nullable();
            $table->string('is_archived')->nullable();
            $table->string('is_pinned')->nullable();
            $table->string('is_sent')->nullable();
            $table->string('is_edited')->nullable();
            $table->timestamps();
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages_chat');
    }
};
