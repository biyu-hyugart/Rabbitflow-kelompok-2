<?php

// DIKERJAKAN OLEH: Abelina | STATUS: KOSONG

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogisticsCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // TODO: nama_kategori required|string|max:80|unique:logistics_categories,nama_kategori.
    public function rules(): array
    {
        return [];
    }

    // TODO: Kembalikan pesan bahasa Indonesia untuk required/unique pada nama_kategori.
    public function messages(): array
    {
        return [];
    }
}
