<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('new', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'new'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('new', 'processing', 'shipped', 'delivered', 'canceled') DEFAULT 'new'");
    }

};
