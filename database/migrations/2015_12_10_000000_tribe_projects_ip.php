<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Yormy\TribeLaravel\Models\Project;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tribe_projects_whitelisted_ips', function (Blueprint $table) {
            $table->id();
            $table->string('xid')->unique();
            $table->string('comment')->nullable();

            $table->foreignIdFor(Project::class)
                ->constrained((new Project())->getTable())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('ip_address', 511);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tribe_projects_whitelisted_ips');
    }
};
