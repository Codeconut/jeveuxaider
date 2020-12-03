<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function store(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $message = $currentUser->sendMessage(request('conversation_id'), request('content'));
        $message->from; // HACK

        $currentUser->markConversationAsRead($message->conversation);
        // Trigger updated_at refresh.
        $message->conversation->touch();

        return $message;
    }
}
