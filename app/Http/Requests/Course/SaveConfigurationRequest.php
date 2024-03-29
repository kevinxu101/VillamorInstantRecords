<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class SaveConfigurationRequest extends FormRequest
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

            'quiz_fullmark' => 'required|numeric|min:0|max:100',
            'a_fullmark' => 'required|numeric|min:0|max:100',
            'ct_fullmark' => 'required|numeric|min:0|max:100',
          
        ];
    }
}
