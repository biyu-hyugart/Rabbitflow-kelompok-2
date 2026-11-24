<?php

// DIKERJAKAN OLEH: Lena | STATUS: KOSONG

namespace App\Http\Requests;

use App\Models\Rabbit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RabbitStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // TODO: status_kelinci required|in:Rabbit::STATUS.
    public function rules(): array
    {
        return [];
    }

    // TODO: Kembalikan pesan bahasa Indonesia untuk required pada status_kelinci.
    public function messages(): array
    {
        return [];
    }
}
