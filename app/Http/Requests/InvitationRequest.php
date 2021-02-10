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
            'properties' => ''
        ];

        return $rules;
    }
}
