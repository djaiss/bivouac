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
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->string('created_by_user_name')->nullable();
            $table->string('title');
            $table->text('body')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');

            if (config('scout.driver') === 'database' && in_array(DB::connection()->getDriverName(), ['mysql', 'pgsql'])) {
                $table->fullText('title');
                $table->fullText('body');
            }
        });
    }
};
