<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $data = [
            'user_id' => Auth::user()->id,
            'message' => $request['message'],
            'room' => $request['room'],
        ];

        event(new MessageSent($data));

        return response()->json(['success' => true]);
    }
}
