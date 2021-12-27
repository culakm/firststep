<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove table with wrong name
        Schema::dropIfExists('block_posts');

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->string('title')->default('');
            
            // pretoze sqlite DB ktoru pouzivame pre testy vyzaduje default value a to mysql nema
            if (env('DB_CONNECTION') === 'sqlite_testing'){
                $table->text('content')->default('');
            } 
            else {
                $table->text('content');
            }
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
