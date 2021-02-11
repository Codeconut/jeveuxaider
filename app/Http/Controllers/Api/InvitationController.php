<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationRequest;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index()
    {
        return QueryBuilder::for(Invitation::class)
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

    public function accept(String $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (!$invitation) {
            abort(402, "L'invitation n'est plus disponible");
        }

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
