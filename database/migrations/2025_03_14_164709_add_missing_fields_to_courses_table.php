<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('state')->default('active'); // Campo "state"
            $table->time('course_start')->nullable();   // Campo "course_start"
            $table->time('course_end')->nullable();     // Campo "course_end"
            $table->string('level')->nullable();        // Campo "level"
            $table->string('days')->nullable();         // Campo "days" (es: "Lunedì,Martedì")
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['state', 'course_start', 'course_end', 'level', 'days']);
        });
    }
};
