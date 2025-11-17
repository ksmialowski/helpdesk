<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_status_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('from_status');
            $table->string('to_status');
            $table->timestamps();

            $table->index(['ticket_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }
};
