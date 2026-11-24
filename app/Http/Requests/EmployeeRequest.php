<?php

// DIKERJAKAN OLEH: Lena | STATUS: KOSONG

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // TODO: nama_lengkap required|string|max:100; username required|string|max:60|alpha_dash|lowercase|unique:users,username (ignore saat update); password required saat create / nullable saat update|string|min:8|max:100; status_aktif required|boolean.
    public function rules(): array
    {
        return [];
    }

    // TODO: Kembalikan pesan bahasa Indonesia untuk required/alpha_dash/lowercase/unique/min pada field di atas.
    public function messages(): array
    {
        return [];
    }
}
