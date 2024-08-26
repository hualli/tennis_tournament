<?php

namespace App\Repositories;

use App\Interfaces\GameRepositoryInterface;
use App\Models\Game;

class GameRepository implements GameRepositoryInterface
{
    public function getGamesByTournamentId($id){
        return Game::where('',$id);
    }

    public function store(array $data){
        return Game::create($data);
    }
}
