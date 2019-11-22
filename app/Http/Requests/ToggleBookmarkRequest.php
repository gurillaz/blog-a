<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ToggleBookmarkRequest extends FormRequest
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





            'article_id'=>'required|exists:articles,id',

        ];
    }



    public function attributes()
    {
        return [
            'article_id' => 'Article',
        ];
    }
}
