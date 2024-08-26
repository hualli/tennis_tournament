<?php

namespace App\Http\Controllers;

use App\Http\Requests\InputTournamentRequest;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    protected $game;

    public function __construct()
    {
        $this->game = app(GameController::class);
    }

    public function playTournament(InputTournamentRequest $request)
    {
        $requestArray = $request->input('players');
        $playerCount = count($requestArray);
        \Log::info('CANTIDAD DE JUGADORES');
        \Log::info($playerCount);

        $playersArray = [];

        switch ($request->category) {
            case "male":
                for ($i = 0; $i < $playerCount; $i++) {
                    $scorePlayer = $requestArray[$i]['skill_level'] + $requestArray[$i]['strength'] + $requestArray[$i]['travel_speed'];
                    $player = [
                        "name" => $requestArray[$i]['name'],
                        "score" => $scorePlayer,
                    ];
                    array_push($playersArray, $player);
                }
                break;
            case "female":
                for ($i = 0; $i < $playerCount; $i++) {
                    $scorePlayer = $requestArray[$i]['skill_level'] + $requestArray[$i]['reaction_time'];
                    $player = [
                        "name" => $requestArray[$i]['name'],
                        "score" => $scorePlayer,
                    ];
                    array_push($playersArray, $player);
                }
                break;
        }

        \Log::info('JUGADORES');
        \Log::info($playersArray);

        while ($playerCount != 1) {
            $winners = [];

            for ($i = 0; $i < $playerCount; $i += 2) {

                $data = new Request([
                    "playerOne" => $playersArray[$i],
                    "playerTwo" => $playersArray[$i + 1],
                ]);

                \Log::info($playersArray[$i]['name'].' vs '.$playersArray[$i+1]['name']);

                $winner = $this->game->playGame($data);

                \Log::info('WINNER');
                \Log::info($winner);

                array_push($winners, $winner);
            }

            $playersArray = $winners;

            $playerCount = $playerCount / 2;
        }

        return response()->json([
            'mensaje' => 'Winner: '.$winners[0]['name'],
        ]);

    }

    public function getTournament()
    {

        return response()->json([
            'mensaje' => 'entraste a getTournament',
        ]);

    }
}
