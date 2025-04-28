<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->double('price');
            $table->integer('tQty');
            $table->string('created_user');

            $table->timestamps();
        });

        Schema::create('equipments_has_product', function (Blueprint $table) {
            $table->id();
            $table->integer('qty');
            $table->double('cost');
            $table->double('sub_total');
            $table->integer('product_id');
            $table->integer('equipments_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_equipments');
    }
};
