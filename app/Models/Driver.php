<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = ['league_id', 'nickname', 'name'];

    /**
     * League this driver belongs to.
     * 
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(league::class);
    }
}
