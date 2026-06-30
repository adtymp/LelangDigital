<?php

namespace App\Http\Requests;

use App\Rules\TerimaDomainPortofolio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterUser extends FormRequest
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
            'email' => strtolower(trim($this->email)),
            'name' => trim($this->name),
            'no_telp' => preg_replace('/\s+/', '', $this->no_telp ?? ''),
            'link_url' => $this->link_url ? trim($this->link_url) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\pL\s\'.-]+$/u'],

            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email'),],

            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()],

            'no_telp' => ['required', 'string', 'min:10', 'max:20', 'regex:/^(08|\+628)[0-9]{8,15}$/', Rule::unique('users', 'no_telp')],

            'type' => ['required', 'string', 'in:link,file'],

            'file_path' => ['nullable', 'required_if:type,file', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

            'link_url' => ['nullable', 'required_if:type,link', 'url', 'max:2048', new TerimaDomainPortofolio],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, petik, dan tanda hubung.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email terlalu panjang.',
            'email.unique' => 'Email sudah terdaftar.',

            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',

            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'no_telp.min' => 'Nomor telepon terlalu pendek.',
            'no_telp.max' => 'Nomor telepon terlalu panjang.',
            'no_telp.regex' => 'Format nomor telepon harus dimulai dengan 08 atau +628.',
            'no_telp.unique' => 'Nomor telepon sudah terdaftar.',

            'type.required' => 'Jenis portofolio wajib dipilih.',
            'type.in' => 'Jenis portofolio tidak valid.',

            'file_path.required_if' => 'File portofolio wajib diunggah jika memilih tipe file.',
            'file_path.file' => 'Upload portofolio harus berupa file yang valid.',
            'file_path.mimes' => 'File portofolio harus berupa JPG, JPEG, PNG, atau PDF.',
            'file_path.max' => 'Ukuran file portofolio maksimal 5 MB.',

            'link_url.required_if' => 'Link portofolio wajib diisi jika memilih tipe link.',
            'link_url.url' => 'Format link portofolio tidak valid.',
            'link_url.max' => 'Link portofolio terlalu panjang.',
        ];
    }
}
