<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('Chat/ChatboxPage');
    }

    public function fetchMessages()
    {
        $messages = Chat::orderBy('created_at', 'asc')->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id() ?? 1,
            'receiver_id' => null,
            'message' => $request->message,
        ]);

        return response()->json($chat);
    }
}
