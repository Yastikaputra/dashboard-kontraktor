<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan.
     */
    public function index()
    {
        $path = storage_path('app/settings.json');
        $settings = [];

        if (File::exists($path)) {
            $settings = json_decode(File::get($path), true);
        }

        return view('setting.index', compact('settings'));
    }

    /**
     * Mengupdate dan menyimpan pengaturan.
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024', // max 1MB
        ]);

        $path = storage_path('app/settings.json');
        
        // Ambil pengaturan lama jika file sudah ada
        $settings = File::exists($path) ? json_decode(File::get($path), true) : [];

        // Update data dari form
        $settings['company_name'] = $request->input('company_name');
        $settings['company_address'] = $request->input('company_address');
        $settings['company_phone'] = $request->input('company_phone');

        // Proses upload logo jika ada
        if ($request->hasFile('company_logo')) {
            // Hapus logo lama jika ada
            if (isset($settings['company_logo'])) {
                Storage::disk('public')->delete($settings['company_logo']);
            }
            
            // Simpan logo baru di storage/app/public/logos
            $logoPath = $request->file('company_logo')->store('logos', 'public');
            $settings['company_logo'] = $logoPath;
        }

        // Simpan kembali ke file JSON
        File::put($path, json_encode($settings, JSON_PRETTY_PRINT));

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}