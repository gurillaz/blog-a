<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SearchRequest extends FormRequest
{
    //named errors multiple forms one page
    //    protected $errorBag = 'createNote';



    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [





            'search_term' => 'sometimes|required|regex:/^[a-zA-Z0-9\s]+$/',
            'user_id' => 'sometimes|required|alpha_dash|exists:users,id',
            'category_id' => 'sometimes|required|alpha_dash|exists:categories,id',
            'date_start' => 'sometimes|required|string|max:10',
            'date_end' => 'sometimes|required|string|max:10',
            'tags' => 'sometimes|required|array',
            'sort_by' => 'sometimes|required|alpha_dash|string',

        ];
    }



    public function attributes()
    {
        return [
            'search_term' => 'search term',
            'user_id' => 'User',
            'category_id' => 'Category',
            'date_start' => 'Date from',
            'date_end' => 'Date to',
            'tags' => 'tags',
            'sort_by' => 'Sort',
        ];
    }
}
