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
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('kab', 'kab_id');
            $table->renameColumn('kec', 'kec_id');
            $table->renameColumn('des', 'des_id');
            $table->renameColumn('bs', 'bs_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('kab_id', 'kab');
            $table->renameColumn('kec_id', 'kec');
            $table->renameColumn('des_id', 'des');
            $table->renameColumn('bs_id', 'bs');
        });
    }
};
