<?php

namespace App\Http\Requests;

use App\Models\Rabbit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GrowthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rabbit_id' => ['required', 'integer', 'exists:rabbits,id'],
            'tgl_pencatatan' => ['required', 'date', 'before_or_equal:today'],
            'berat_badan' => ['required', 'numeric', 'min:0.1', 'max:99.99'],
            'panjang_badan' => ['required', 'numeric', 'min:1', 'max:200'],
            'status_kelinci' => ['required', Rule::in(Rabbit::STATUS)],
            'kondisi_kesehatan' => ['required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'rabbit_id.required' => 'Kelinci wajib dipilih.',
            'tgl_pencatatan.required' => 'Tanggal pencatatan wajib diisi.',
            'tgl_pencatatan.before_or_equal' => 'Tanggal pencatatan tidak boleh melebihi hari ini.',
            'berat_badan.required' => 'Berat badan wajib diisi.',
            'panjang_badan.required' => 'Panjang badan wajib diisi.',
            'status_kelinci.required' => 'Status kelinci wajib dipilih.',
            'kondisi_kesehatan.required' => 'Kondisi kesehatan wajib diisi.',
        ];
    }
}
