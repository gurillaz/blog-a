<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCommentRequest extends FormRequest
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





            'body'=>'required|max:5000|',


        ];
    }


    public function attributes()
    {
        return [
            'body' => 'Comment body',
        ];
    }
}
