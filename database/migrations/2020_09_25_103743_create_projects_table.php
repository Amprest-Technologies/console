<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');

            $table->string('uuid')->unique();
            $table->string('api_key')->unique();

            $table->string('name');
            $table->string('description', 500)->nullable();

            $table->string('pay_transaction_callback')->nullable();
            $table->string('pay_validation_callback')->nullable();

            $table->string('pay_balance_callback')->nullable();

            $table->enum('status', [
                'inactive', 'suspended', 'active'
            ])->default('active');

            $table->foreign('team_id')
                ->references('id')->on('teams');

            $table->timestamps();
        });

        DB::statement('
            ALTER TABLE projects
            ADD FULLTEXT (uuid, name, description)
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
        Schema::dropIfExists('projects');
    }
}
