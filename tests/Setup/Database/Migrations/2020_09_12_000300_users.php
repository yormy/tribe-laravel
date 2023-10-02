<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_members', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('encryption_key', 1024);
        });
    }
};
