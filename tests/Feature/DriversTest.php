<?php

/**
 * Drivers
 *  1. create
 *  2. read all
 *  3. read
 *  4. update
 *  5. delete
 * 
 */

use App\Models\Driver;
use App\Models\League;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

 it('All drivers api endpoints unavailable to guest users.', function () {
  $this->getJson('/api/drivers')->assertUnauthorized();
  $this->postJson('/api/drivers')->assertUnauthorized();
  $this->getJson('/api/drivers/1')->assertUnauthorized();
  $this->putJson('/api/drivers/1')->assertUnauthorized();
  $this->deleteJson('/api/drivers/1')->assertUnauthorized();
});

test('Authenticated user can create drivers to his own league.', function () {
  $user = User::factory()->create();
  $league = League::factory()->create(['user_id' => $user->id]);

  $response = $this->actingAs($user)
    ->postJson('/api/drivers', [
      'league_id' => $league->id,
      'nickname' => 'fabioroque92',
      'name' => 'Fábio Roque',
    ]);

  $response
    ->assertCreated()
    ->assertJson([
      'data' => [
        'league_id' => $league->id,
        'nickname' => 'fabioroque92',
        'name' => 'Fábio Roque',
      ]
    ]);
});

test('Authenticated user can not create drivers to other leagues.', function () {
  $externalUser = User::factory()->create();
  $externalUserLeague = League::factory()->create(['user_id' => $externalUser->id]);

  $user = User::factory()->create();

  $response = $this->actingAs($user)
    ->postJson('/api/drivers', [
      'league_id' => $externalUserLeague->id,
      'nickname' => 'fabioroque92',
      'name' => 'Fábio Roque',
    ]);

  $response
    ->assertForbidden();
});

it('possible create two drivers with the same name but different nicknames.', function () {
  $user = User::factory()->create();
  $league = League::factory()->create(['user_id' => $user->id]);
  $driver = Driver::factory()
    ->for($league)
    ->create();

  $response = $this->actingAs($user)
    ->postJson('/api/drivers', [
      'league_id' => $league->id,
      'nickname' => 'othernickname',
      'name' => $driver->name,
    ]);

  $response
    ->assertCreated();
});

it('not possible create two drivers with the same nickname.', function () {
  $user = User::factory()->create();
  $league = League::factory()->create(['user_id' => $user->id]);
  $driver = Driver::factory()
    ->for($league)
    ->create();

  $response = $this->actingAs($user)
    ->postJson('/api/drivers', [
      'league_id' => $league->id,
      'nickname' => $driver->nickname,
      'name' => $driver->name,
    ]);

  $response
    ->assertJson(fn (AssertableJson $json) => 
      $json->where('message', 'The nickname has already been taken.')
        ->etc()
    )
    ->assertJsonValidationErrorFor('nickname', 'errors')
    ->assertUnprocessable();
});

it('not possible create driver without be assigned to a league.', function () {
  $user = User::factory()->create();

  $response = $this->actingAs($user)
    ->postJson('/api/drivers', [
      'nickname' => 'fabioroque92',
      'name' => 'Fábio Roque',
    ]);

  $response
    ->assertJson(fn (AssertableJson $json) => 
        $json->where('message', 'The league id field is required.')
          ->etc()
    )
    ->assertJsonValidationErrorFor('league_id', 'errors')
    ->assertUnprocessable();
});

test('Authenticated user can read all drivers from any league.', function () {
  $user = User::factory()->make();

  $response = $this->actingAs($user)
    ->getJson('/api/drivers');

  $response
    ->assertOk()
    ->assertJson(fn (AssertableJson $json) => 
      $json
        ->hasAll(['data', 'links', 'meta'])
        ->missing('message')
    );
});

test('Authenticated user can read one driver from any league.', function () {})->todo();

test('Authenticated user can update drivers from his own league.', function () {})->todo();

test('Authenticated user can not update drivers from other leagues.', function () {})->todo();

test('Authenticated user can delete drivers from his own league.', function () {})->todo();

test('Authenticated user can not delete drivers from his own league.', function () {})->todo();


