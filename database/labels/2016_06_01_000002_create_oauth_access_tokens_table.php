<?php

use Vinelab\NeoEloquent\Schema\Blueprint;
use Vinelab\NeoEloquent\Migrations\Migration;

class CreateOauthAccessTokensTable extends Migration
{
    /**
     * Run the labels.
     *
     * @return void
     */
    public function up()
    {
        Neo4jSchema::label('oauth_access_tokens', function (Blueprint $table) {
            $table->unique('uuid', 100);
        });
    }

    /**
     * Reverse the labels.
     *
     * @return void
     */
    public function down()
    {
        Neo4jSchema::drop('oauth_access_tokens');
    }
}
