<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Xóa các cột thừa/gây lỗi
            // Kiểm tra tồn tại trước khi xóa để tránh lỗi
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }

            // 2. Thêm cột address mới
            // Đặt sau cột phone_number (hoặc email nếu chưa có phone_number)
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Khôi phục lại (nếu rollback)
            $table->string('role')->default('customer');
            $table->string('phone')->nullable();
            $table->dropColumn('address');
        });
    }
};