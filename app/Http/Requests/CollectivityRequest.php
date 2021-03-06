<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectivityRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:collectivities',
                'min:2',
                'max:255',
            ],
            'title' => '',
            'type' => 'required',
            'description' => '',
            'department' => '',
            'zips' => '',
            'structure_id' => '',
            'published' => 'boolean',
        ];
    }
}
