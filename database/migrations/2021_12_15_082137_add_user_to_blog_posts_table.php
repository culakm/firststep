<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            //  pri vytvoreni tohto noveho stlpca bude vsade hodnota NULL
            // to je pri pridavani v produkcii
            // $table->unsignedBigInteger('user_id')->nullable();
           
            // pretoze sqlite DB ktoru pouzivame pre testy vyzaduje default value a to mysql nema
            if (env('DB_CONNECTION') === 'sqlite_testing') {
                $table->unsignedBigInteger('user_id')->default(0);
            } 
            else {
                $table->unsignedBigInteger('user_id');
            }

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // FK definovany v DB ma ine meno ako samotny stlpec pre FK v TB, toto najde spravne meno
            $table->dropColumn('user_id');
        });
    }
}
