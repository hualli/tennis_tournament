<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function playGame(Request $request){

        $playersArray = $request->all();

        $scorePlayerOne = $playersArray['playerOne']['score'] + rand(1,100);
        $scorePlayerTwo = $playersArray['playerTwo']['score'] + rand(1,100);

        if($scorePlayerOne > $scorePlayerTwo){
            $winner = $playersArray['playerOne'];
        }else{
            $winner = $playersArray['playerTwo'];
        }

        return $winner;
    }
}
