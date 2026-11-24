<?php

// DIKERJAKAN OLEH: Abelina | STATUS: KOSONG

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LogisticsItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // TODO: category_id required|integer|exists:logistics_categories,id; nama_item required|string|max:150; stok required|integer|min:0|max:1000000; status_aktif required|in:0,1,true,false.
    public function rules(): array
    {
        return [];
    }

    // TODO: Kembalikan pesan bahasa Indonesia untuk required/min pada field di atas.
    public function messages(): array
    {
        return [];
    }
}
