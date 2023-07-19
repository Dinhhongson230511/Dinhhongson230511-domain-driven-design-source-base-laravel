<?php

namespace App\Http\Requests;

use App\Http\Traits\Validations\CustomMessages;
use App\Http\Traits\Validations\ValidationRules;
use Project\Domain\UserManagement\Registration\Enums\RegistrationSteps;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegistrationFormRequest extends FormRequest
{
    use ValidationRules, CustomMessages;

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
        $step = (float)$this->route('step');
        $validation = 'rules' . RegistrationSteps::getKey($step);
        return self::$validation();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        $step = (float)$this->route('step');
        $messages = 'messages' . RegistrationSteps::getKey($step);
        return self::$messages();
    }
}
