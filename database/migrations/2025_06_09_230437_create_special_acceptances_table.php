<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_acceptances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecrs_id')->references('id')->on('ecrs')->comment ='Ecr Id';
            $table->longText('product_detail');
            $table->int('lot_qty');
            $table->int('samples');
            $table->longText('mod')->nullable()->default('N/A');
            $table->int('mod_qty')->nullable()->default(0);
            $table->string('judgement');
            $table->date('inspection_date');
            $table->bigInteger('inspector_rapidx_user_id')->comment('Rapidx User Id');
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('special_acceptances');
    }
}
