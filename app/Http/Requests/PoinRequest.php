<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PoinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'aspek' => trim($this->aspek ?? '')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $poin = $this->route('poin');

        $rules = [
            'aspek' => [
                'required',
                'string',
                'max:255',
                'regex:/.*\S.*/',
                Rule::unique('poins', 'aspek')->ignore($poin)
            ],

            'bobot' => [
                'required',
                'numeric',
                'between:0.01,1',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['status'] = [
                'required',
                Rule::in(['aktif', 'nonaktif'])
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'aspek.required' => 'Nama aspek wajib diisi.',
            'aspek.string' => 'Nama aspek harus berupa teks.',
            'aspek.max' => 'Nama aspek maksimal 255 karakter.',
            'aspek.unique' => 'Nama aspek sudah digunakan.',
            'aspek.regex' => 'Nama aspek tidak boleh kosong.',

            'bobot.required' => 'Bobot wajib diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka.',
            'bobot.between' => 'Bobot harus antara 0.01 sampai 1.00.',
            'bobot.regex' => 'Bobot maksimal 2 angka di belakang koma.',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => collect($validator->errors()->all())->first(),
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
