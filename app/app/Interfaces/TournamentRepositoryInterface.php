<?php

namespace App\Interfaces;

interface TournamentRepositoryInterface
{
    public function getTournament(array $data);
    public function store(array $data);
}
