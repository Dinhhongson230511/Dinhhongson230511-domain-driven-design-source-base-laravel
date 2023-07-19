<?php

namespace App\Http\Requests;

class MarkAsReadRequest extends JsonFormRequest
{
    public function rules()
    {
        return [
            'code' => 'required|string'
        ];
    }
}
