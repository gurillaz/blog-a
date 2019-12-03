<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateCategoryRequest extends FormRequest
{
    //named errors multiple forms one page
    //    protected $errorBag = 'createNote';



    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {

        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [





            'name' => 'required|string|min:3|max:20|unique:categories,name',
            'description' => 'max:5000',


        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Category name',
            'description' => 'Category description',
        ];
    }
}
