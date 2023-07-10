<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('author_name');
            $table->string('title');
            $table->text('body')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');

            if (config('scout.driver') === 'database' && in_array(DB::connection()->getDriverName(), ['mysql', 'pgsql'])) {
                $table->fullText('title');
                $table->fullText('body');
            }
        });

        Schema::create('message_read_status', function (Blueprint $table): void {
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
