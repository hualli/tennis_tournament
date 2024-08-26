<?php

namespace App\Interfaces;

interface GameRepositoryInterface
{
    public function getGamesByTournamentId($id);
    public function store(array $data);
}
