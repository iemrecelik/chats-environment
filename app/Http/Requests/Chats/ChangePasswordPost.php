<?php

namespace App\Http\Requests\Chats;

use App\Rules\ValidationPassword;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\redirect;

class ChangePasswordPost extends FormRequest
{
    protected $user;
    protected $redirect = '/profile?tab=change-password';

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
            'password' => [
                'required',
                'min:3',
                'max:255',
                new ValidationPassword($this->user)
            ],
            'new_password' => 'required|min:3|max:255|confirmed',
        ];
    }

    public function withValidator($validator)
    {
        $this->session()->flash('componentName', 'changePassFormComp');
    }
}
