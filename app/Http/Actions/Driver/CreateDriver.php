<?php

namespace App\Http\Actions\Driver;

use App\Models\Driver;

class CreateDriver {

    public function handle(array $data): Driver
    {
        return Driver::create($data);
    }

}