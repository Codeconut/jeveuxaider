<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterVolontaireRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:2',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'birthday' => 'required|date|before:-16 years',
            'zip' => 'required|postal_code:FR',
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
            'birthday.before' => 'La Réserve Civique est ouverte aux personnes de plus de 16 ans',
            'postal_code' => 'Le code postal n\'est pas valide',
            'zip.required' => 'Le code postal est requis',
            'mobile.required' => 'Le numéro de téléphone est requis',
            'mobile' => 'Le numéro de téléphone n\'est pas valide'
        ];
    }
}