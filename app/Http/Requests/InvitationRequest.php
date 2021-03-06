<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // ONLY ADMIN
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'user_id' => 'required',
            'email' => 'email|required|unique:invitations,email',
            'role' => 'required',
            'invitable_id' => '',
            'invitable_type' => '',
            'properties' => ''
        ];

        return $rules;
    }

    /**
    * Get the validation messages that apply to the request.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'user_id.required' => 'Un utilisateur est requis',
            'email.required' => 'Un email est requis',
            'email.unique' => 'Une invitation a déjà été envoyée à cet email',
            'email.email' => 'Cet email est mal formaté',
            'role' => 'Un rôle est requis'
        ];
    }
}
