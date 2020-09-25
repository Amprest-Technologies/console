<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');

            $table->string('name');
            $table->unsignedBigInteger('usage_limit')->nullable();
            $table->unsignedFloat('price', 8, 2)->nullable();
            $table->enum('status', ['private', 'public'])->default('public');

            $table->timestamps();

            $table->foreign('service_id')
                ->references('id')->on('services');

            $table->unique(['service_id', 'usage_limit', 'price'], 'rate');
        });

        DB::statement('
            ALTER TABLE tiers
            ADD FULLTEXT (name)
            WITH PARSER NGRAM
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tiers');
    }
}
