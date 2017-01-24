<?php

use Illuminate\Http\Request;

class ClientControllerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function test_all_the_clients_for_the_current_user_can_be_retrieved()
    {
        $clients = Mockery::mock('NeoEloquent\Passport\ClientRepository');
        $clients->shouldReceive('activeForUser')->once()->with(1)->andReturn($client = Mockery::mock());
        $client->shouldReceive('makeVisible')->with('secret')->andReturn($client);

        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('user')->andReturn(new ClientControllerFakeUser);

        $controller = new NeoEloquent\Passport\Http\Controllers\ClientController(
            $clients, Mockery::mock('Illuminate\Contracts\Validation\Factory')
        );

        $this->assertEquals($client, $controller->forUser($request));
    }

    public function test_clients_can_be_stored()
    {
        $clients = Mockery::mock('NeoEloquent\Passport\ClientRepository');

        $request = Request::create('/', 'GET', ['name' => 'client name', 'redirect' => 'http://localhost']);
        $request->setUserResolver(function () {
            return new ClientControllerFakeUser;
        });

        $clients->shouldReceive('create')->once()->with(1, 'client name', 'http://localhost')->andReturn($client = new NeoEloquent\Passport\Client);

        $validator = Mockery::mock('Illuminate\Contracts\Validation\Factory');
        $validator->shouldReceive('make')->once()->with([
            'name' => 'client name',
            'redirect' => 'http://localhost',
        ], [
            'name' => 'required|max:255',
            'redirect' => 'required|url',
        ])->andReturn($validator);
        $validator->shouldReceive('validate')->once();

        $controller = new NeoEloquent\Passport\Http\Controllers\ClientController(
            $clients, $validator
        );

        $this->assertEquals($client, $controller->store($request));
    }

    public function test_clients_can_be_updated()
    {
        $clients = Mockery::mock('NeoEloquent\Passport\ClientRepository');

        $request = Request::create('/', 'GET', ['name' => 'client name', 'redirect' => 'http://localhost']);

        $request->setUserResolver(function () {
            $user = Mockery::mock();
            $user->id = 1;
            $user->clients = Mockery::mock();
            $user->clients->shouldReceive('find')->with(1)->andReturn(
                $client = Mockery::mock('NeoEloquent\Passport\Client')
            );

            return $user;
        });

        $clients->shouldReceive('update')->once()->with(
            Mockery::type('NeoEloquent\Passport\Client'), 'client name', 'http://localhost'
        )->andReturn('response');

        $validator = Mockery::mock('Illuminate\Contracts\Validation\Factory');
        $validator->shouldReceive('make')->once()->with([
            'name' => 'client name',
            'redirect' => 'http://localhost',
        ], [
            'name' => 'required|max:255',
            'redirect' => 'required|url',
        ])->andReturn($validator);
        $validator->shouldReceive('validate')->once();

        $controller = new NeoEloquent\Passport\Http\Controllers\ClientController(
            $clients, $validator
        );

        $this->assertEquals('response', $controller->update($request, 1));
    }

    public function test_404_response_if_client_doesnt_belong_to_user()
    {
        $clients = Mockery::mock('NeoEloquent\Passport\ClientRepository');

        $request = Request::create('/', 'GET', ['name' => 'client name', 'redirect' => 'http://localhost']);

        $request->setUserResolver(function () {
            $user = Mockery::mock();
            $user->id = 1;
            $user->clients = Mockery::mock();
            $user->clients->shouldReceive('find')->with(1)->andReturn(null);

            return $user;
        });

        $clients->shouldReceive('update')->never();

        $validator = Mockery::mock('Illuminate\Contracts\Validation\Factory');

        $controller = new NeoEloquent\Passport\Http\Controllers\ClientController(
            $clients, $validator
        );

        $this->assertEquals(404, $controller->update($request, 1)->status());
    }

    public function test_clients_can_be_deleted()
    {
        $clients = Mockery::mock('NeoEloquent\Passport\ClientRepository');

        $request = Request::create('/', 'GET', ['name' => 'client name', 'redirect' => 'http://localhost']);

        $request->setUserResolver(function () {
            $user = Mockery::mock();
            $user->id = 1;
            $user->clients = Mockery::mock();
            $user->clients->shouldReceive('find')->with(1)->andReturn(
                $client = Mockery::mock('NeoEloquent\Passport\Client')
            );

            return $user;
        });

        $clients->shouldReceive('delete')->once()->with(
            Mockery::type('NeoEloquent\Passport\Client')
        )->andReturn('response');

        $validator = Mockery::mock('Illuminate\Contracts\Validation\Factory');

        $controller = new NeoEloquent\Passport\Http\Controllers\ClientController(
            $clients, $validator
        );

        $controller->destroy($request, 1);
    }

    public function test_404_response_if_client_doesnt_belong_to_user_on_delete()
    {
        $clients = Mockery::mock('NeoEloquent\Passport\ClientRepository');

        $request = Request::create('/', 'GET', ['name' => 'client name', 'redirect' => 'http://localhost']);

        $request->setUserResolver(function () {
            $user = Mockery::mock();
            $user->id = 1;
            $user->clients = Mockery::mock();
            $user->clients->shouldReceive('find')->with(1)->andReturn(null);

            return $user;
        });

        $clients->shouldReceive('delete')->never();

        $validator = Mockery::mock('Illuminate\Contracts\Validation\Factory');

        $controller = new NeoEloquent\Passport\Http\Controllers\ClientController(
            $clients, $validator
        );

        $this->assertEquals(404, $controller->destroy($request, 1)->status());
    }
}

class ClientControllerFakeUser
{
    public $id = 1;
    public function getKey()
    {
        return $this->id;
    }
}
