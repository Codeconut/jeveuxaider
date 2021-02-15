<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersInvitationSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationRequest;
use App\Models\Invitation;
use App\Notifications\InvitationSent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;

class InvitationController extends Controller
{
    public function index()
    {
        return QueryBuilder::for(Invitation::class)
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersInvitationSearch),
            )
            ->defaultSort('-created_at')
            ->paginate(10);
    }

    public function show(Request $request, String $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (!$invitation) {
            abort(402, "L'invitation n'est plus disponible");
        }

        return $invitation;
    }

    public function store(InvitationRequest $request)
    {
        do {
            $token = Str::random(32);
        } while (Invitation::where('token', $token)->first());

        $attributes = $request->validated();
        $attributes['token'] = $token;

        $invitation = Invitation::create($attributes);

        return $invitation;
    }

    public function resend(String $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (!$invitation) {
            abort(402, "L'invitation n'est plus disponible");
        }

        $invitation->update(['last_sent_at' => Carbon::now()]);
        $invitation->notify(new InvitationSent($invitation));

        return $invitation;
    }

    public function accept(String $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (!$invitation) {
            abort(402, "L'invitation n'est plus disponible");
        }

        // @TODO: CHECK si le  role responsable et qu'il nest pas déjà dans lorga;

        $invitation->accept();
        $invitation->delete();

        return $invitation;
    }

    public function delete(String $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (!$invitation) {
            abort(402, "L'invitation n'est plus disponible");
        }

        return (string) $invitation->delete();
    }
}
