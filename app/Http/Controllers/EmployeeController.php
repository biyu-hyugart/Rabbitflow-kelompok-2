<?php

// DIKERJAKAN OLEH: Lena | STATUS: KOSONG

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    // TODO: Tampilkan daftar karyawan dengan filter q/status/role, statistik total/aktif/nonaktif, dan pagination 10.
    public function index(Request $request): View
    {
        return view('employees.index');
    }

    // TODO: Validasi EmployeeRequest lalu buat User ber-role Karyawan dengan nama, username, password, dan status_aktif.
    public function store(EmployeeRequest $request): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Cegah perubahan akun Owner, validasi data, perbarui nama/username/status, dan password hanya jika diisi.
    public function update(EmployeeRequest $request, User $employee): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Cegah akun Owner dan akun sendiri dinonaktifkan, lalu set status_aktif menjadi false.
    public function deactivate(Request $request, User $employee): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Cegah perubahan akun Owner, lalu set status_aktif menjadi true.
    public function activate(User $employee): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }
}
