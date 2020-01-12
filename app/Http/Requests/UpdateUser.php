<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
        $id = $this->route('user')->id;
        return [
            'name' => 'required|string|min:2|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$id,
            'password' => 'required|string|min:6|max:191',
        ];
    }
}
