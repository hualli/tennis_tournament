<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function playTournament(){

        return response()->json([
            'mensaje' => 'entraste a playTournament'
        ]);

    }

    public function getTournament(){

        return response()->json([
            'mensaje' => 'entraste a getTournament'
        ]);
        
    }
}
