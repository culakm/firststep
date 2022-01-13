<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // pretoze sqlite DB ktoru pouzivame pre testy vyzaduje default value a to mysql nema
            // opoznamkovane veci su stary sposob tvorenia FK
            if (env('DB_CONNECTION') === 'sqlite_testing') {
                // $table->unsignedBigInteger('user_id')->default(0);
                $table->foreignId('user_id')->constrained()->onDelete('cascade')->default(0);
            } 
            else {
                // $table->unsignedBigInteger('user_id');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }

            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // FK definovany v DB ma ine meno ako samotny stlpec pre FK v TB, toto najde spravne meno
            $table->dropColumn('user_id');
        });
    }
}
