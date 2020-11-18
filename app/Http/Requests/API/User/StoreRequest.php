<?php

namespace App\Http\Requests\API\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'name'          => 'required|string|max:50|min:3',
            'image'         => 'required',
            'email'         => 'required|string|email|unique:users,email',
            'password'      => 'required|string',
        ];
    }


    public function persist()
    {
       
        $data = User::create($this->request->all());
        return $data;
    }
}
