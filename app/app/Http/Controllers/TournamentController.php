<?php

namespace App\Http\Controllers;

use App\Http\Requests\FindTournamentRequest;
use App\Http\Requests\InputTournamentRequest;
use App\Http\Resources\TournamentResouce;
use App\Interfaces\TournamentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *      title="API Swagger",
 *      version="1.0",
 *      description="Tennis tournament simulator API"
 * )
 *
 * @OA\Server(url="http://localhost:8080")
 */

class TournamentController extends Controller
{
    protected $game;
    protected $repository;

    public function __construct(TournamentRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->game = app(GameController::class);
    }

    /**
 * @OA\Post(
 *     path="/api/playTournament",
 *     tags={"Tournament"},
 *     summary="Play new tournament",
 *     description="Play and save a new tournament",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"category", "players"},
 *             @OA\Property(property="category", type="string", example="female"),
 *             @OA\Property(
 *                property="players",
 *                type="array",
 *                example={{
 *                  "name": "Laura Perez",
 *                  "skill_level": 60,
 *                  "strength": 50,
 *                  "travel_speed": 10,
 *                  "reaction_time": 2
 *                },{
 *                  "name": "Juana Garcia",
 *                  "skill_level": 50,
 *                  "strength": 40,
 *                  "travel_speed": 9,
 *                  "reaction_time": 2
 *                }},
 *                @OA\Items(
 *                      @OA\Property(property="name", type="string", example="Juana Garcia"),
 *                      @OA\Property(property="skill_level", type="integer", example=50),
 *                      @OA\Property(property="strength", type="integer", example=40),
 *                      @OA\Property(property="travel_speed", type="integer", example=9),
 *                      @OA\Property(property="reaction_time", type="integer", example=2),
 *                ),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Record created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/TournamentResouce")
 *     )
 * )
 */

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

                \Log::info($playersArray[$i]['name'] . ' vs ' . $playersArray[$i + 1]['name']);

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
            'winner' => $winners[0]['name'],
        ];

        $this->store($tournamentData);

        return response()->json([
            'success' => true,
            'message' => 'Winner: ' . $winners[0]['name'],
        ], 200);

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
                'error' => 'Error: ' . $ex,
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/getTournament",
     *     tags={"Tournament"},
     *     summary="Get list of Tournament",
     *     description="Return list of Tournament",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date", "category"},
     *             @OA\Property(property="date", type="date", example="2024-08-24"),
     *             @OA\Property(property="category", type="string", example="male")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succesful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TournamentResouce")
     *         )
     *      )
     * )
     */

    public function getTournament(FindTournamentRequest $request)
    {
        try {
            $data = $this->repository->getTournament($request->all());
            return response()->json([
                'success' => true,
                'message' => '',
                'data' => TournamentResouce::collection($data),
            ], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error: ' . $ex,
            ], 500);
        }
    }
}
