<?php

use App\Models\League;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

it('All league api endpoints unavailable to guest users.', function () {
  $this->getJson('/api/leagues')->assertUnauthorized();
  $this->postJson('/api/leagues')->assertUnauthorized();
  $this->getJson('/api/leagues/1')->assertUnauthorized();
  $this->putJson('/api/leagues/1')->assertUnauthorized();
  $this->deleteJson('/api/leagues/1')->assertUnauthorized();
});

test('Authenticated user can read all leagues.', function () {
  $user = User::factory()->make();

  $response = $this->actingAs($user)
    ->getJson('/api/leagues');

  $response
    ->assertOk()
    ->assertJson(fn (AssertableJson $json) => 
      $json
        ->hasAll(['data', 'links', 'meta'])
        ->missing('message')
    );
});

test('Authenticated user can create a league.', function () {
  $user = User::factory()->create();

  $response = $this->actingAs($user)
    ->postJson('/api/leagues', [
      'name' => 'Portugal Racing League',
    ]);

  $response
    ->assertStatus(201)
    ->assertJson([
      'data' => [
        'name' => 'Portugal Racing League',
        'manager' => $user->name,
      ]
    ]);
});

test('Authenticated user can not create two leagues with the same name.', function () {
  $user = User::factory()->create();
  $league = League::factory()->create([
    'user_id' => $user->id,
  ]);

  // try to create another league with same name
  $response = $this->actingAs($user)
    ->postJson('/api/leagues', [
      'name' => $league->name
    ]);

    $response
      ->assertUnprocessable()
      ->assertJson(fn (AssertableJson $json) => 
        $json->where('message', 'The name has already been taken.')
          ->etc()
      )
      ->assertJsonValidationErrorFor('name', 'errors');
});

test('Authenticated user can delete his league', function () {
  $user = User::factory()->create();

  $league = League::factory()->create([
    'user_id' => $user->id,
  ]);

  $response = $this->actingAs($user)
    ->deleteJson('/api/leagues/' . $league->id);

  $response
    ->assertOk();
});

test('Authenticated user can not delete leagues of other manager.', function () {
  $otherManager = User::factory()->create();
  $otherManagerLeague = League::factory()->create([
    'user_id' => $otherManager->id
  ]);

  $user = User::factory()->create();

  $response = $this->actingAs($user)
    ->deleteJson('/api/leagues/' . $otherManagerLeague->id);

  $response
    ->assertForbidden()
    ->assertJson([
      'message' => 'This action is unauthorized.'
    ]);
});