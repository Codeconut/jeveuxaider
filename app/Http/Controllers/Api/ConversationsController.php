<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersConversationSearch;

class ConversationsController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Conversation::role($request->header('Context-Role'))->with(['messages', 'latestMessage', 'users', 'conversable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                        Participation::class => ['mission.structure:id,name', 'mission.domaine'],
                    ]);
        }]))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersConversationSearch),
            ])
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function messages(Request $request, Conversation $conversation)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $currentUser->markConversationAsRead($conversation);

        return QueryBuilder::for(Message::where('conversation_id', $conversation->id)->with(['from']))
            ->defaultSort('-updated_at')
            ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
    }
}