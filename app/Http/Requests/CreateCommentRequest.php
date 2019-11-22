<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateCommentRequest extends FormRequest
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





            'body'=>'required|max:5000',
            'user_id'=>'required|exists:users,id',
            'commentable_type'=>'required',
            'commentable_id'=>'required',

        ];
    }


    public function attributes()
    {
        return [
            'user_id' => 'User',
            'body' => 'Comment body',
        ];
    }
}
