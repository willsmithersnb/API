<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('uuid');
            $table->string('model_no');
            $table->string('model_name');
            $table->timestamp('manufacture_date');
            $table->text('ssh_public_key');
            $table->text('password');
            $table->string('hardware_version');
            $table->jsonb('command')->default('{}');
            $table->enum('status', ['active', 'inactive']);
            $table->ipAddress('last_known_ip');
            $table->timestamp('last_seen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
