<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateArticleRequest extends FormRequest
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





            'body'=>'required|max:10000',
            'summary'=>'required|max:1000',
            'title'=>'required|max:100',
            'category_id'=>'required|exists:categories,id',
            'tags'=>'required|array',
            // 'image'=>'required|mimetypes:image/jpeg,image/png,image/jpg',
            'image'=>'sometimes|required|image|mimes:jpeg,png|max:20000',

        ];
    }



    public function attributes()
    {
        return [
            'category_id' => 'Category',
            'title' => 'Title',
            'body' => 'Article body',
            'tags' => 'Tags',
            'cover_photo' => 'Cover photo',
            'summary' => 'Summary',
        ];
    }
}
