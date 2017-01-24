<?php

namespace NeoEloquent\Passport\Http\Controllers;

use NeoEloquent\Passport\Passport;

class ScopeController
{
    /**
     * Get all of the available scopes for the application.
     *
     * @return Response
     */
    public function all()
    {
        return Passport::scopes();
    }
}
