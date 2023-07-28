<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('author_name')->nullable();
            $table->string('name');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');

            if (config('scout.driver') === 'database' && in_array(DB::connection()->getDriverName(), ['mysql', 'pgsql'])) {
                $table->fullText('name');
                $table->fullText('description');
            }
        });

        Schema::create('project_user', function (Blueprint $table): void {
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
