<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $projectClass = config('tribe.models.project');
        Schema::create((new $projectClass())->getTable(), function (Blueprint $table){
            $table->id();
            $table->string('xid')->unique();
            $table->string('name');
            $table->string('encryption_key')->nullable();
            $table->text('api_submit_key')->nullable();
            $table->dateTime('disabled_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        $projectClass = config('tribe.models.project');
        Schema::dropIfExists((new $projectClass())->getTable());
    }
};
