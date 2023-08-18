<?php

namespace App\Http\Controllers;

use App\Models\GameResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function getGameHistory()
    {
        $user = Auth::user();

        $history = GameResult::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return response()->json($history);
    }

    public function saveGameResult(Request $request)
    {
        $user = Auth::user();

        $result = $request->input('result');
        $rolledNumber = $request->input('rolled_number');
        $winAmount = $request->input('winAmount');

        $gameResult = new GameResult([
            'user_id' => $user->id,
            'result' => $result,
            'rolled_number' => $rolledNumber,
            'winAmount' => $winAmount
        ]);

        $gameResult->save();

        return response()->json(['message' => 'Game result saved successfully']);
    }
}
