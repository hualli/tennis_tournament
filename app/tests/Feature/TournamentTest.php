<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TournamentTest extends TestCase
{
    public function test_play_tournament_successful(): void
    {
        Artisan::call('migrate');
        $tournamentData = [
            'category' => 'female',
            'players' => [
                [
                    'name' => 'Gabriela Sabatini',
                    'skill_level' => 60,
                    'strength' => 50,
                    'travel_speed' => 10,
                    'reaction_time' => 1,
                ],
                [
                    'name' => 'Serena Williams',
                    'skill_level' => 70,
                    'strength' => 60,
                    'travel_speed' => 9,
                    'reaction_time' => 2,
                ],
            ],
        ];

        $response = $this->post('/api/playTournament',$tournamentData);
        $response->assertStatus(200);
    }

    public function test_get_tournament_successful(): void
    {
        $filterData = [
            'date' => '2024-08-26',
            'category' => 'female'
        ];

        $response = $this->get('/api/getTournament',$filterData);
        $response->assertStatus(200);
    }
}
