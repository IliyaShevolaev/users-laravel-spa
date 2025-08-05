<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->default('male');
            $table->string('status')->default('active');
            $table->foreignId('department_id')->nullable()->constrained('departments', 'id')->onDelete('set null');
            $table->foreignId('position_id')->nullable()->constrained('positions', 'id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'gender', 'department_id', 'position_id']);
        });
    }
};
