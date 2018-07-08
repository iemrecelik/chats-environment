<?php

namespace App\Http\Requests\Chats;

use App\Rules\ValidationPassword;
use Auth;
use Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfilePost extends FormRequest
{
    protected $user;
    protected $redirectRoute = 'profile.profile';

    public function __construct(){
        $this->user = Auth::user();
    }

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
            'name' => 'required|max:255|string',
            'surname' => 'required|max:255|string',
            'nickname' => 'required|max:255',
            'brief' => 'required',
            'email' => [
                'required',
                'max:255',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'mobile' => 'required|digits:10',
            'password' => [
                'required',
                'min:3',
                'max:255',
                new ValidationPassword($this->user)
            ],
            'hide_account' => 'nullable',
        ];
    }

    public function withValidator($validator)
    {
        $this->session()->flash('componentName', 'infoFormComp');
    }
}
