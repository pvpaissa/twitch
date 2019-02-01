<?php

namespace Cleanse\Twitch\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('cleanse_twitch_requests', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('cleanse_twitch_requests');
    }
}
