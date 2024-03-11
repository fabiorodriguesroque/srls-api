<?php 

namespace App\Http\Actions\League;

use App\Models\League;

class CreateLeague {

  public function handle(array $data): League
  {
      return League::create($data);
  }
}