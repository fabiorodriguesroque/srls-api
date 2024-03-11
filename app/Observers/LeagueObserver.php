<?php

namespace App\Observers;

use App\Models\League;

class LeagueObserver
{
    /**
     * Handle the creating event.
     */
    public function creating(League $league): void
    {
        $league->user_id = auth()->user()->id;
    }
}
