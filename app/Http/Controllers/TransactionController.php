<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Showroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request){

    $validate = $request->validate([
        'user_id' => 'required|exists:users,id',
        'showroom_id' => 'required|exists:showrooms,id',
        'quantity' => 'required|integer|min:1'
    ]);

    return DB::transaction(function () use ($validate){
        $car = Showroom::lockForUpdate()->find ($validate['showroom_id']);

        if($car->stock < $validate['quantity']){
            return response()->json([
                'message'=>'Stock mobil tidak mencukupi',
            ]);
        }

        $car->decrement('stock',$validate['quantity']);

        $transaction = Transaction::create([
            'user_id' => $validate['user_id'],
            'showroom_id' => $car->id,
            'quantity' => $validate['quantity'],
            'total_price' => $car->price * $validate['quantity'],
            'status' => 'paid',
        ]);

        return response()->json([
            'message'=> 'Pembelian mobil berhasil di lakukan',
            'data'=> $transaction->load(['buyer','car'])
        ],201);
    });
    }

}
