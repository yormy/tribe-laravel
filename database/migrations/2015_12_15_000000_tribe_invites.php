<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Yormy\TribeLaravel\Models\Project;

return new class extends Migration
{
    //
    //    public function up()
    //    {
    //        $memberClass = config('tribe.models.member');
    //        $projectClass = config('tribe.models.project');
    //        Schema::create('tribe_invites', function (Blueprint $table) use ($memberClass, $projectClass) {
    //            $table->id();
    //            $table->foreignIdFor($memberClass)
    //                ->constrained((new $memberClass())->getTable())
    //                ->onUpdate('cascade')
    //                ->onDelete('cascade');
    //
    //            $table->foreignIdFor($projectClass)
    //                ->constrained((new $projectClass())->getTable())
    //                ->onUpdate('cascade')
    //                ->onDelete('cascade');
    //
    //            $table->enum('type', ['invite', 'request']);
    //            $table->string('email');
    //            $table->string('accept_token');
    //            $table->string('deny_token');
    //
    //            $table->foreignIdFor($memberClass, 'invited_by')
    //                ->constrained((new $memberClass())->getTable())
    //                ->onUpdate('cascade')
    //                ->onDelete('cascade');
    //
    //            $table->timestamps();
    //        });
    //    }
    //
    //    public function down()
    //    {
    //        Schema::dropIfExists('tribe_projects_invites');
    //    }
};
