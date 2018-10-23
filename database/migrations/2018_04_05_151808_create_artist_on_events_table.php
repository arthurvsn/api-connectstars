<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtistOnEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artist_on_events', function (Blueprint $table) {
            
            $table->uuid('event_id')->unsigned();
            $table->uuid('artist_id')->unsigned();
            
            $table->primary(['artist_id', 'event_id']);

            $table->double('amount_artist_receive');
            $table->enum('artist_confirmed', ['yes', 'no', 'pending'])->default('pending');

            $table->foreign('artist_id')->references('id')->on('users');

            $table->foreign('event_id')->references('id')->on('events');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artist_on_events');
    }
}
