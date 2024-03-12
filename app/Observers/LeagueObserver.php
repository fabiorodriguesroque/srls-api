<?php

namespace App\Observers;

use App\Models\League;

class LeagueObserver
{
    /**
     * Assigned the authenticated user as manager when the user_id is not defined.
     * 
     */
    public function creating(League $league): void
    {
        $league->user_id = $league->user_id ?? auth()->user()->id;
    }
}
