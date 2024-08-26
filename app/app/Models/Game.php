<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['date','round','winner','loser','tournament_id'];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }
}
