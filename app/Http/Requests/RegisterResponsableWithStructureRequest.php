<?php

namespace App\Http\Requests;

use App\Rules\Lowercase;
use Illuminate\Foundation\Http\FormRequest;

class RegisterResponsableWithStructureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required','email', 'unique:users', new Lowercase],
            'password' => 'required|min:8',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:2',
            'structure_name' => 'required|min:3'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'Un prénom est requis',
            'first_name.min' => 'Votre prénom doit contenir au moins :min lettres',
            'last_name.required' => 'Un nom est requis',
            'last_name.min' => 'Votre nom doit contenir au moins :min lettres',
            'email.required' => 'Un email est requis',
            'email.unique' => 'Cet email est déjà pris',
            'email.email' => 'Cet email est mal formaté',
            'password.required' => 'Un mot de passe est requis',
            'password.min' => 'Votre mot de passe doit contenir au moins :min caractères',
            'structure_name.required' => 'Le nom de votre organisation est requis',
            'structure_name.min' => 'Votre organisation doit contenir au moins :min caractères'
        ];
    }
}