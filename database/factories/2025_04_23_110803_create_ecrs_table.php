<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecrs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            /*
                document_id: Method
                customer_name: test
                part_name: test
                productLine: test
                section: test
                internal_external: External
                part_number: test
                device_name: test
                customer_ec_no: test
                date_of_request: test

                description_of_change[]: 1
                reason_of_change[]: 3

                qad_approved_by_external: 263
                qad_approved_by_internal: 14
                qad_checked_by: 121

                requested_by[]: 149
                technical_evaluation[]: 564
                reviewed_by[]: 121

                prepared_by[]: 833
                checked_by[]: 833
                approved_by[]: 689
            */
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
        Schema::dropIfExists('ecrs');
    }
}
