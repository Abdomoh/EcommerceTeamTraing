<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:50',
            'body' => 'required|string',
            //'image' => 'required|image|mimes:jpeg,jpg,gif,png|
            //max:10240|dimensions:min_width=280,min_height=280',
            'user_id' => 'required'

        ];
    }
}
