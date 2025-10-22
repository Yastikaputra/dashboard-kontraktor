<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar semua user dengan role 'user'
    public function index()
    {
        $users = User::where('role', 'user')->latest()->paginate(10);
        // [DISESUAIKAN] Path view diubah agar cocok dengan struktur Anda
        return view('admin.index', compact('users'));
    }

    // Menampilkan form untuk membuat user baru
    public function create()
    {
        $proyeks = Proyek::all();
        // [DISESUAIKAN] Path view diubah agar cocok dengan struktur Anda
        return view('admin.create', compact('proyeks'));
    }

    // Menyimpan user baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'proyek_ids' => 'nullable|array',
            'proyek_ids.*' => 'exists:proyeks,id_proyek',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        if ($request->has('proyeks_ids')) {
            $user->proyeks()->attach($request->proyeks_ids);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    // Menampilkan form untuk mengedit user
    public function edit(User $user)
    {
        $proyeks = Proyek::all();
        $assignedProyekIds = $user->proyeks->pluck('id_proyek')->toArray();
        // [DISESUAIKAN] Path view diubah agar cocok dengan struktur Anda
        return view('admin.edit', compact('user', 'proyeks', 'assignedProyekIds'));
    }

    // Mengupdate data user di database
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'proyek_ids' => 'nullable|array',
            'proyek_ids.*' => 'exists:proyeks,id_proyek',
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->proyeks()->sync($request->proyek_ids ?? []);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    // Menghapus user dari database
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}