<?php

namespace App\Http\Requests\User\Plan;

use App\Http\Requests\JsonFormRequest;
use Project\Domain\UserManagement\Registration\Enums\RegistrationSteps;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserPlanRequest extends JsonFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'discountType' => [
                'string',
                'nullable',
                Rule::in(RegistrationSteps::getValues())
            ],
            'costPlan' => [
                'string',
                'nullable',
                Rule::in(RegistrationSteps::getValues())
            ],
            'contractTerm' => [
                'numeric',
                'nullable'
            ],
        ];
    }
}
