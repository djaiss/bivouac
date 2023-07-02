<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('permissions');
            $table->string('name_for_avatar');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('locale')->default('en');
            $table->string('timezone')->default('UTC');
            $table->datetime('born_at')->nullable();
            $table->string('age_preferences')->default(User::AGE_HIDDEN);
            $table->string('password')->nullable();
            $table->string('invitation_code')->nullable();
            $table->datetime('last_active_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            if (config('scout.driver') === 'database' && in_array(DB::connection()->getDriverName(), ['mysql', 'pgsql'])) {
                $table->fullText('first_name');
                $table->fullText('last_name');
                $table->fullText('email');
            }
        });
    }
};
