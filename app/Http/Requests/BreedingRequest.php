<?php

namespace App\Http\Requests;

use App\Models\BreedingRecord;
use App\Models\Rabbit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BreedingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'male_rabbit_id' => ['required', 'integer', 'exists:rabbits,id'],
            'female_rabbit_id' => ['required', 'integer', 'exists:rabbits,id', 'different:male_rabbit_id'],
            'tgl_rencana_kawin' => ['required', 'date'],
            'tgl_realisasi_kawin' => ['nullable', 'date'],
            'status_perkawinan' => ['required', Rule::in(BreedingRecord::STATUS)],
            'jumlah_anakan' => ['nullable', 'integer', 'min:0', 'max:20'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'male_rabbit_id.required' => 'Kelinci jantan wajib dipilih.',
            'female_rabbit_id.required' => 'Kelinci betina wajib dipilih.',
            'female_rabbit_id.different' => 'Kelinci jantan dan betina tidak boleh sama.',
            'tgl_rencana_kawin.required' => 'Tanggal rencana kawin wajib diisi.',
            'status_perkawinan.required' => 'Status perkawinan wajib dipilih.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $male = Rabbit::find($this->input('male_rabbit_id'));
            $female = Rabbit::find($this->input('female_rabbit_id'));

            if ($male && $male->jenis_kelamin !== 'Jantan') {
                $validator->errors()->add('male_rabbit_id', 'Kelinci jantan harus berjenis kelamin Jantan.');
            }

            if ($female && $female->jenis_kelamin !== 'Betina') {
                $validator->errors()->add('female_rabbit_id', 'Kelinci betina harus berjenis kelamin Betina.');
            }
        });
    }
}
