<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserCreatePost extends FormRequest
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

            'name' => 'not_regex:/[!@#$%^&*()""\';:<>\/\[\]]/|required|max:255',
            'lastname' => 'not_regex:/[!@#$%^&*()""\';:<>\/\[\]]/|required|max:255',
            'email' => 'email|required|max:255|unique:App\User,email',
            'password' => [
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
                'required',
                'confirmed',
                'min:8'
            ],
            'type_aw' => 'boolean|required',
            'type_l' => 'boolean|required',
            'phone' => "string|required_if:type_l,1|max:255",
            'education' => [
                Rule::in(config('test.education_degrees')),
                "required_if:type_l,1"
            ],
            'address' => "array|required_if:type_aw,1",
            "correspondal_address" => "array|required_if:type_aw,1",
            "address.region" => "string|max:255|required_if:type_aw,1",
            "address.city" => "string|max:255|required_if:type_aw,1",
            "address.country" => "string|max:255|required_if:type_aw,1",
            "address.code" => "string|max:255|required_if:type_aw,1",
            "address.street" => "string|max:255",
            "address.number" => "string|max:255|required_if:type_aw,1",
            "correspondal_address.region" => "string|max:255|required_if:type_aw,1",
            "correspondal_address.city" => "string|max:255|required_if:type_aw,1",
            "correspondal_address.country" => "string|max:255|required_if:type_aw,1",
            "correspondal_address.code" => "string|max:255|required_if:type_aw,1",
            "correspondal_address.street" => "string|max:255",
            "correspondal_address.number" => "string|max:255|required_if:type_aw,1"
        ];
    }


    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if ($validator->passes()) {
            $validator->after(function ($validator) {
                $data = $validator->valid();
                $message = 'Choose at least one type.';
                if (!$data['type_aw'] && !$data['type_l']) {
                    $validator->errors()->add('type_aw', $message);
                    $validator->errors()->add('type_l', $message);
                }
            });
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
