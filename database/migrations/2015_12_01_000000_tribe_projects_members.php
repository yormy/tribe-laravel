<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Yormy\TribeLaravel\Models\Project;

return new class extends Migration
{
    public function up()
    {
        $memberClass = config('tribe.models.member');

        Schema::create('tribe_projects_members', function (Blueprint $table) use ($memberClass){
            $table->id();
            $table->foreignIdFor($memberClass)
                ->constrained((new $memberClass())->getTable())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(Project::class)
                ->constrained((new Project())->getTable())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('project_role')->nullable();

            $table->datetime('expires_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tribe_projects_members');
    }
};
