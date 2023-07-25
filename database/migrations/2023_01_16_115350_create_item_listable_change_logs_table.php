<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemListableChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_listable_change_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->enum('change_type', ['order_status', 'payment_status', 'payment_type', 'manual', 'lead_time']);
            $table->string('status_name', 50);
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('item_list_id')
                ->constrained('item_lists')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->text('description');
            $table->boolean('is_visible_to_customer');
            $table->boolean('is_email_triggered');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_listable_change_logs');
    }
}
