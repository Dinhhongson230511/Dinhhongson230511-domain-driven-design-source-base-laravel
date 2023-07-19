<?php

namespace App\Http\Requests;

use Project\UserInterface\ResponseCodes\ApiCodes;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class JsonFormRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        (new JsonResponse([
            'message' => $validator->getMessageBag(),
            'data' => []
        ], ApiCodes::SOMETHING_WENT_WRONG))->throwResponse();
    }

    /**
     * @return void
     */
    protected function failedAuthorization()
    {
        (new JsonResponse([
            'message' => (new AuthorizationException)->getMessage(),
            'data' => []
        ], ApiCodes::SOMETHING_WENT_WRONG))->throwResponse();
    }
}
