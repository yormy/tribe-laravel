<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Yormy\TribeLaravel\Models\Project;

return new class extends Migration
{
    public function up()
    {
        $roleClass = config('tribe.models.role');

        Schema::create('tribe_permissions', function (Blueprint $table) use ($roleClass){
            $table->id();
            $table->foreignIdFor($roleClass, 'role_id')
                ->constrained((new $roleClass())->getTable())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('name')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tribe_permissions');
    }
};
