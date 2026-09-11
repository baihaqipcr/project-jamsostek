<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePotensiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_input' => ['required', 'date'],
            'nama_usaha' => ['required', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:20'],
            'segmen' => ['required', 'in:PU,BPU,Jakon'],
            'uraian' => ['required', 'string'],
            'alamat' => ['required', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'estimasi_tk' => ['required', 'integer', 'min:1'],
            'estimasi_upah' => ['required', 'numeric', 'min:0'],
            'estimasi_iuran' => ['required', 'numeric', 'min:0'],
            'programs' => ['nullable', 'array'],
            'programs.*' => ['string', 'in:JKK,JKM,JHT,JP,JKP'],
            'status_tindak_lanjut' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal_input' => 'tanggal input',
            'nama_usaha' => 'nama usaha',
            'npwp' => 'NPWP',
            'segmen' => 'segmen',
            'uraian' => 'uraian',
            'alamat' => 'alamat',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'estimasi_tk' => 'estimasi tenaga kerja',
            'estimasi_upah' => 'estimasi upah',
            'estimasi_iuran' => 'estimasi iuran',
            'status_tindak_lanjut' => 'status tindak lanjut',
            'catatan' => 'catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'in' => ':attribute tidak valid.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'min' => ':attribute tidak boleh lebih kecil dari :min.',
            'between' => ':attribute di luar rentang yang diizinkan.',
        ];
    }
}
