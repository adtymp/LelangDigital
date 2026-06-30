<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class TambahProyek extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    
    public function rules()
    {
        return [
            'nama_proyek' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',

            'sub_proyek' => 'required|array|min:1',

            'sub_proyek.*.nama' => 'required|string|max:255',

            'sub_proyek.*.sub_sub' => 'nullable|array',

            'sub_proyek.*.sub_sub.*.nama_halaman' => 'required|string|max:255',
            'sub_proyek.*.sub_sub.*.harga' => 'required|numeric|min:0',
            'sub_proyek.*.sub_sub.*.kualitas' => 'required|string|max:100',

            'sub_proyek.*.sub_sub.*.file_pdf' => 'nullable|file|mimes:pdf|max:512000',
            'sub_proyek.*.sub_sub.*.temp_pdf' => 'nullable|string',
            'sub_proyek.*.sub_sub.*.pdf_source' => 'nullable|string|in:local,google_drive',
            'sub_proyek.*.sub_sub.*.file_xls' => 'required',File::types(['xls', 'xlsx', 'csv'])->max(10240),
        ];
    }

    public function messages()
    {
        return [
            'nama_proyek.required' => 'Nama proyek wajib diisi',

            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai',

            'sub_proyek.required' => 'Minimal 1 sub proyek',

            'sub_proyek.*.nama.required' => 'Nama sub proyek wajib diisi',

            'sub_proyek.*.sub_sub.*.nama_halaman.required' => 'Nama halaman wajib diisi',
            'sub_proyek.*.sub_sub.*.harga.required' => 'Harga wajib diisi',
            'sub_proyek.*.sub_sub.*.kualitas.required' => 'Kualitas wajib diisi',

            'sub_proyek.*.sub_sub.*.file_xls.required' => 'File XLS/XLSX masih kosong',
            'sub_proyek.*.sub_sub.*.file_pdf.mimes' => 'File harus PDF',
            'sub_proyek.*.sub_sub.*.file_xls.mimes' => 'File harus XLS/XLSX/CSV',
        ];
    }
}
