<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $projectClass = config('tribe.models.project');
        $roleClass = config('tribe.models.role');

        Schema::create((new $roleClass())->getTable(), function (Blueprint $table) use ($projectClass) {
            $table->id();
            $table->foreignIdFor($projectClass)
                ->constrained((new $projectClass())->getTable())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('code')->nullable();
            $table->string('name')->nullable();

        });
    }

    public function down()
    {
        $roleClass = config('tribe.models.role');
        Schema::dropIfExists((new $roleClass())->getTable());
    }
};
