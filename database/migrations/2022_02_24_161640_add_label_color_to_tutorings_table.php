<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabelColorToTutoringsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('tutorings', function (Blueprint $table) {
            $table->string('label_color')->default("#3c8dbc")->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('tutorings', function (Blueprint $table) {
            $table->dropColumn('label_color');
        });
    }
}
