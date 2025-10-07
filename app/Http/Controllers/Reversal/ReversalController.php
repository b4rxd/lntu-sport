<?php

namespace App\Http\Controllers\Reversal;
use App\Http\Controllers\Controller;
use App\Models\Reversal;
use Auth;
use Illuminate\Http\Request;

class ReversalController extends Controller
{
    public function index(){
        $reversals = Reversal::with('card.price.product', 'createdBy')->get();
        return view('reversal.reversals', compact('reversals'));
    }

    public function create(){
        return view('reversal.create');
    }

    public function store(Request $request){
        $credentials = $request->validate([
            'cardId' => 'required|exists:cards,id',
            'amount_in_uah' => 'required|numeric|min:1'
        ]);

        Reversal::create([
            'card_id' => $credentials['cardId'],
            'amount_in_uah' => $credentials['amount_in_uah'],
            'created_by_id' => Auth::user()->id
        ]);

        return redirect()->route('reversal.index')->with('success', 'Reversal created');
    }
}