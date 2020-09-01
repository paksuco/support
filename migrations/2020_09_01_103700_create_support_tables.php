<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title");
            $table->string("slug");
            $table->foreignId("parent_id")->nullable();
            $table->integer("order");
            $table->boolean("is_general")->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("parent_id")->references("id")
                ->on("faq_categories")->cascadeOnDelete();
        });

        Schema::create('faq_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('category_id')->nullable();
            $table->string("question", 100);
            $table->string("slug", 100);
            $table->text("answer");
            $table->boolean("visible_on_front")->default(false);
            $table->integer("order");
            $table->integer("likes");
            $table->integer("dislikes");
            $table->integer("visits");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("category_id")->references("id")
                ->on("faq_categories")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('faq_items');
        Schema::dropIfExists('faq_categories');
        Schema::enableForeignKeyConstraints();
    }
}
