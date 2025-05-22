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
            /*
            "ecrs_id" => 'required',
            "pd_material" => 'required',
            "msds" => 'required',
            "icp" => 'required',
            "qoutation" => 'required',
            "material_supplier" => 'required',
            "material_color" => 'required',
            "material_sample" => 'required',
            "coc" => 'required',
            // "rohs" => 'required',
            // "remarks" => 'required',
            */
            $table->id();
            $table->foreignId('ecrs_id')->references('id')->on('ecrs')->comment ='Ecr Id';
            $table->string('msds');
            $table->string('icp');
            $table->string('msds');
            $table->string('qoutation');
            $table->string('qoutation');
            $table->string('coc');
            $table->bigInteger('material_color');
            $table->bigInteger('material_sample');
            $table->string('rohs');
            $table->timestamps();
            $table->softDeletes();
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
