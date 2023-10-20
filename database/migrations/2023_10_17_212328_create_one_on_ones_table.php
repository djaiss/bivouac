<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('one_on_ones', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('other_user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('other_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('one_on_one_entries', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('one_on_one_id');
            $table->string('body');
            $table->datetime('checked_at')->nullable();
            $table->timestamps();
            $table->foreign('one_on_one_id')->references('id')->on('one_on_ones')->onDelete('cascade');
        });
    }
};
