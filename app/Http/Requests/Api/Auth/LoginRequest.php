<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateEmail($validator);
            $this->validatePassword($validator);
        });
    }

    private function validateEmail($validator)
    {
        if ($this->emailIsInvalid()) {
            $validator->errors()->add('email', trans('auth.failed'));
        }
    }

    private function emailIsInvalid()
    {
        $this->user = User::whereEmail($this->email)->first();

        return is_null($this->user);
    }

    private function validatePassword($validator)
    {
        if (!$this->user->isValidPassword($this->password)) {
            $validator->errors()->add('password', trans('auth.failed'));
        }
    }
}
