<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMPesaCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesa_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');

            $table->string('short_code');
            $table->string('operating_short_code');
            $table->enum('short_code_type', [
                'pay_bill', 'buy_goods'
            ])->default('pay_bill');

            $table->string('consumer_key')->nullable();
            $table->string('consumer_secret')->nullable();
            $table->string('pass_key')->nullable();

            $table->string('app_user_name')->nullable();
            $table->string('app_user_password')->nullable();

            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')->on('projects');

            $table->unique(['project_id', 'short_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpesa_credentials');
    }
}
