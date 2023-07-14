<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('task_list_id');
            $table->string('title')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
            $table->foreign('task_list_id')->references('id')->on('task_lists')->onDelete('cascade');
        });
    }
};
