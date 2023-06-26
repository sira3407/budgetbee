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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->datetime('date');
            $table->unsignedBigInteger('from_account_id');
            $table->unsignedBigInteger('to_account_id')->nullable();
            $table->string('type');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->float('amount');
            $table->string('bank_code')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('link_record_id')->nullable();
            $table->foreign('from_account_id')->references('id')->on('accounts');
            $table->foreign('to_account_id')->references('id')->on('accounts');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};