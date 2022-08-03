<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCardRequest;
use App\Models\Card;
use App\Models\Column;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(StoreCardRequest $request)
    {
        $card = Card::query()->create($request->only(['column_id', 'title', 'description']));
        return response()->json([
            'card' => $card
        ]);
    }

    public function update(Card $card, StoreCardRequest $request)
    {
        $card->update($request->only(['title', 'description']));
        return response()->json([
            'card' => $card
        ]);
    }

    public function deleteRestore($card_id, Request $request)
    {
        $card = Card::query()->withTrashed()->findOrFail($card_id);
        if ($request->status == '0') {
            $card->restore();
        } else {
            $card->delete();
        }
        return response()->json([]);
    }
}
