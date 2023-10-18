<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyJobPostingTableAddEmploymentTypeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('employment_type')->default('full_time')->index()->after('job_location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('employment_type');
        });
    }
}
