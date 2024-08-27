<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('kabupaten', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('short_code');
            $table->string('long_code')->unique();
            $table->string('name');
        });
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('short_code');
            $table->string('long_code')->unique();
            $table->string('name');
            $table->foreignId('kab_id')->constrained('kabupaten');
        });
        Schema::create('desa', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('short_code');
            $table->string('long_code')->unique();
            $table->string('name');
            $table->integer('klas');
            $table->foreignId('kec_id')->constrained('kecamatan');
        });
        Schema::create('bs', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('short_code');
            $table->string('long_code')->unique();
            $table->string('name');
            $table->foreignId('des_id')->constrained('desa');
        });

        Schema::create('subsectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('id_sbr')->nullable();
            $table->string('name');
            $table->string('kab');
            $table->string('kec')->nullable();
            $table->string('des')->nullable();
            $table->string('bs')->nullable();
            $table->string('address')->nullable();
            $table->string('xcoordinate')->nullable();
            $table->string('ycoordinate')->nullable();
        });

        Schema::create('commodities', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('code');
            $table->string('name');
            $table->foreignId('company_id')->constrained('companies');
        });

        Schema::create('company_subsector', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('subsector_id')->constrained('subsectors')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('company_survey', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
