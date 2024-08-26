<?php

namespace App\Http\Controllers;

use App\Http\Requests\InputTournamentRequest;
use App\Http\Requests\FindTournamentRequest;
use Illuminate\Http\Request;
use App\Interfaces\TournamentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TournamentController extends Controller
{
    protected $game;
    protected $repository;

    public function __construct(TournamentRepositoryInterface $repository)
    {
        $this->repository = $repository;
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

        $tournamentData = [
            'date' => Carbon::now(),
            'category' => $request->category,
            'winner' => $winners[0]['name']
        ];

        $this->store($tournamentData);

        return response()->json([
            'success' => true,
            'message' => 'Winner: '.$winners[0]['name'],
        ],200);
        
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $tournament = $this->repository->store($data);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error: '.$ex
            ],500);
        }
    }

    public function getTournament(FindTournamentRequest $request)
    {
        try {
            $data = $this->repository->getTournament($request->all());
            return response()->json([
                'success' => true,
                'message' => '',
                'data' => json_encode($data)
            ],200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error: '.$ex
            ],500);
        }
    }
}
