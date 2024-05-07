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
        Schema::create('payements', function (Blueprint $table) {
            $table->id();
            $table->decimal('total',8,2)->nullable();
            $table->string('order_id')->nullable();
            $table->string('st_cus_id',1024)->nullable();
            $table->string('st_sub_id',1024)->nullable();
            $table->string('st_payement_intent_id',1024)->nullable();
            $table->string('st_payement_method',1024)->nullable();
            $table->string('st_payement_status',1024)->nullable();
            $table->bigInteger('date',0)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payements');
    }
};
