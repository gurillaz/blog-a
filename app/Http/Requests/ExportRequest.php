<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ExportRequest extends FormRequest
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

            'model' => 'sometimes|required|string|alpha',
            'date_start' => 'sometimes|required|string|max:10',
            'date_end' => 'sometimes|required|string|max:10',


        ];
    }


    public function attributes()
    {
        return [
            'modle' => 'resource',
            'date_start' => 'Date from',
            'date_end' => 'Date to',
        ];
    }
}
