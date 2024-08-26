<?php

namespace App\Repositories;

use App\Interfaces\TournamentRepositoryInterface;
use App\Models\Tournament;

class TournamentRepository implements TournamentRepositoryInterface
{
    public function getTournament(array $data){
        return Tournament::whereDate('date',$data['date'])
        ->where('category',$data['category'])->get();
    }

    public function store(array $data){
        return Tournament::create($data);
    }
}
