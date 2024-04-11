<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Yormy\TribeLaravel\Exceptions\InvalidValueException;

return new class extends Migration
{
    public function up()
    {
        $memberClass = config('tribe.models.member');
        if (! $memberClass) {
            throw new InvalidValueException('Missing config for tribe.models.member class');
        }
        $projectClass = config('tribe.models.project');
        if (! $projectClass) {
            throw new InvalidValueException('Missing config for tribe.models.project class');
        }

        Schema::create('tribe_memberships', function (Blueprint $table) use ($memberClass, $projectClass) {
            $table->id();
            $table->foreignIdFor($memberClass)
                ->constrained((new $memberClass())->getTable())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor($projectClass)
                ->constrained((new $projectClass())->getTable())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('role_id')->nullable();

            $table->datetime('expires_at')->nullable();

            $table->unsignedBigInteger('invited_by');

            $table->datetime('joined_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tribe_memberships');
    }
};
