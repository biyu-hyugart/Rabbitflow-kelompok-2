<?php

// DIKERJAKAN OLEH: Lena | STATUS: KOSONG

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // TODO: lokasi_blok required|string|max:120; kapasitas required|integer|min:1|max:100.
    public function rules(): array
    {
        return [];
    }

    // TODO: Kembalikan pesan bahasa Indonesia untuk required/min/max pada field di atas.
    public function messages(): array
    {
        return [];
    }
}
