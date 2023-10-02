<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $memberClass = config('tribe.models.member');
        Schema::create('tribe_projects', function (Blueprint $table) use ($memberClass) {
            $table->id();
            $table->foreignIdFor($memberClass, 'owner_id')
                ->constrained((new $memberClass())->getTable())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('xid')->unique();
            $table->string('name');
            $table->string('encryption_key')->nullable();
            $table->text('api_submit_key')->nullable();
            $table->dateTime('disabled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tribe_projects');
    }
};
