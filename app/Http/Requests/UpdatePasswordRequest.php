<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password_r' => 'sometimes|required|string|min:6|max:12',
            'password' => 'sometimes|required|same:password_r|string|min:6|max:12'
        ];
    }

    
    public function attributes()
    {
        return [
            'password_r' => 'password confirmation',
        ];
    }
}
