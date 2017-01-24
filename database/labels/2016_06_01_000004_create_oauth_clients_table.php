<?php

use Vinelab\NeoEloquent\Schema\Blueprint;
use Vinelab\NeoEloquent\Migrations\Migration;

class CreateOauthClientsTable extends Migration
{
    /**
     * Run the labels.
     *
     * @return void
     */
    public function up()
    {
        Neo4jSchema::label('oauth_clients', function (Blueprint $table) {

        });
    }

    /**
     * Reverse the labels.
     *
     * @return void
     */
    public function down()
    {
        Neo4jSchema::drop('oauth_clients');
    }
}
