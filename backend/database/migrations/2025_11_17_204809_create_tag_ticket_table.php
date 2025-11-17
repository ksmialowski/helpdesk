<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tag_ticket', function (Blueprint $table) {
            $table->foreignId('ticket_id')->constrained();
            $table->foreignId('tag_id')->constrained();
            $table->primary(['ticket_id', 'tag_id']);
        });
    }
};
