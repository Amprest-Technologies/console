<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('tier_id');

            $table->unsignedBigInteger('usage_limit')->nullable();
            $table->unsignedFloat('amount', 8, 2)->nullable();

            $table->dateTimeTz('expires_at');
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')->on('projects');
            $table->foreign('tier_id')
                ->references('id')->on('tiers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
