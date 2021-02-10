<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationAcceptRequest;
use App\Http\Requests\InvitationRequest;
use App\Mail\InviteCallaboratorEmail;
use App\Models\Invitation;
use App\Profile;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

    // public function show($token)
    // {

    //     $invitation = Invitation::whereToken($token)->first();

    //     if (!$invitation) {
    //         return response(
    //             ['errors' => [
    //             'no_invitation' => [
    //                 "Il n'y a pas d'invitation correspondant à ce token"
    //             ]
    //             ]], 400
    //         );
    //     }

    //     return $invitation;
    // }

    public function store(InvitationRequest $request)
    {
        do {
            $token = Str::random();
        } while (Invitation::where('token', $token)->first());

        $attributes = $request->validated();
        $attributes['token'] = $token;

        $invitation = Invitation::create($attributes);

        // Mail::to(request('email'))
        //     ->send(new InviteCallaboratorEmail($invitation));

        return $invitation;
    }

    // public function accept(InvitationAcceptRequest $request)
    // {

    //     $invitation = Invitation::whereEmail(request('email'))->first();

    //     if (!$invitation) {
    //         return response(
    //             ['errors' => [
    //             'no_invitation' => [
    //                 "Il n'y a pas d'invitation correspondant à cet email"
    //             ]
    //             ]], 400
    //         );
    //     }

    //     $user = User::create(
    //         [
    //         'name' => request("email"),
    //         'email' => request("email"),
    //         'password' => Hash::make(request("password"))
    //         ]
    //     );

    //     $user->syncRoles($invitation->role);

    //     $profile = Profile::create($invitation->toArray());

    //     $user->profile()->save($profile);

    //     $invitation->delete();

    //     return $user;
    // }

    // public function destroy(Invitation $invitation)
    // {
    //     return (string) $invitation->delete();
    // }
}
