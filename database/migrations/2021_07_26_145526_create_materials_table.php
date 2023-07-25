<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('igmp_material_id');
            $table->string('igmp_spec_id', 191);
            $table->string('igmp_part_num', 191);
            $table->string('igmp_name', 191);
            $table->text('igmp_material_description');
            $table->bigInteger('igmp_lead_time');
            $table->string('grade', 191);
            $table->string('storage_requirement');
            $table->string('reference_num', 191)->nullable();
            $table->enum('reference_type', array('cas_no', 'cat_no'))->default('cas_no');
            $table->bigInteger('price')->default('0');
            $table->bigInteger('cost')->default('0');
            $table->bigInteger('pricing_quantity')->default('1');
            $table->smallInteger('pricing_unit');
            $table->smallInteger('display_unit');
            $table->boolean('is_active')->default(1);
            $table->string('nb_part_num', 191);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
