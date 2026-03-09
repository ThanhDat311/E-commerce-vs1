<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('orders', function (Blueprint $table) use ($driver) {
            if (! $this->indexExists('orders', 'orders_user_id_status_index', $driver)) {
                $table->index(['user_id', 'order_status'], 'orders_user_id_status_index');
            }

            if (! $this->indexExists('orders', 'orders_created_at_index', $driver)) {
                $table->index('created_at', 'orders_created_at_index');
            }
        });

        Schema::table('order_items', function (Blueprint $table) use ($driver) {
            if (! $this->indexExists('order_items', 'order_items_product_id_index', $driver)) {
                $table->index('product_id', 'order_items_product_id_index');
            }
        });

        Schema::table('reviews', function (Blueprint $table) use ($driver) {
            if (! $this->indexExists('reviews', 'reviews_product_user_index', $driver)) {
                $table->index(['product_id', 'user_id'], 'reviews_product_user_index');
            }
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('orders', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('orders', 'orders_user_id_status_index', $driver)) {
                $table->dropIndex('orders_user_id_status_index');
            }

            if ($this->indexExists('orders', 'orders_created_at_index', $driver)) {
                $table->dropIndex('orders_created_at_index');
            }
        });

        Schema::table('order_items', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('order_items', 'order_items_product_id_index', $driver)) {
                $table->dropIndex('order_items_product_id_index');
            }
        });

        Schema::table('reviews', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('reviews', 'reviews_product_user_index', $driver)) {
                $table->dropIndex('reviews_product_user_index');
            }
        });
    }

    private function indexExists(string $table, string $indexName, string $driver): bool
    {
        if ($driver === 'sqlite') {
            $result = DB::select("SELECT COUNT(*) as cnt FROM sqlite_master WHERE type = 'index' AND name = ?", [$indexName]);

            return $result[0]->cnt > 0;
        }

        // MySQL / MariaDB
        $database = config('database.connections.mysql.database');
        $result = DB::select(
            'SELECT COUNT(*) as cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $indexName]
        );

        return $result[0]->cnt > 0;
    }
};
