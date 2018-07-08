<?php

namespace App\Http\Requests\Chats;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileImgPost extends FormRequest
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
            'profileImg' => 'required|file|max:1000|mimes:jpeg,jpg,png,gif',
        ];
    }

    public function withValidator($validator)
    {
        $this->session()->flash('componentName', 'userInfoComp');
    }
}
