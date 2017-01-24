<?php

use Vinelab\NeoEloquent\Schema\Blueprint;
use Vinelab\NeoEloquent\Migrations\Migration;

class CreateOauthPersonalAccessClientsTable extends Migration
{
    /**
     * Run the labels.
     *
     * @return void
     */
    public function up()
    {
        Neo4jSchema::create('oauth_personal_access_clients', function (Blueprint $table) {

        });
    }

    /**
     * Reverse the labels.
     *
     * @return void
     */
    public function down()
    {
        Neo4jSchema::drop('oauth_personal_access_clients');
    }
}
