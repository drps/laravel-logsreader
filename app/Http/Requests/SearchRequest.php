<?php

namespace App\Http\Requests;

use App\Logs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sort' => ['string', 'nullable', Rule::in(['asc', 'desc'])],
            'type' => ['string', 'nullable', Rule::in(array_keys(Logs::types()))],
            'page' => ['string', 'nullable'],
        ];
    }
}
