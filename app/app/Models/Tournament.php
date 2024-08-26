<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = ['date','category','winner'];

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
