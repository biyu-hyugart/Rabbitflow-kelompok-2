<?php

// DIKERJAKAN OLEH: Lena | STATUS: KOSONG

namespace App\Http\Requests;

use App\Models\Rabbit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RabbitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // TODO: nama_kelinci required|string|max:100; jenis_kelamin required|in:Rabbit::JENIS_KELAMIN; tgl_lahir required|date|before_or_equal:today; cage_id required|integer|exists:cages,id; status_kelinci required|in:Rabbit::STATUS.
    public function rules(): array
    {
        return [];
    }

    // TODO: Kembalikan pesan bahasa Indonesia untuk required/before_or_equal/exists pada field di atas.
    public function messages(): array
    {
        return [];
    }
}
