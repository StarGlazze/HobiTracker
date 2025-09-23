<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use App\Models\KategoriHobi;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class WebSettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        // Get current web settings (assuming single row configuration)
        $webSettings = WebSetting::first();
        
        // Get all hobby categories with hobi count
        $kategoriHobis = KategoriHobi::withCount('hobis')->get();
        
        return view('admin.setting', compact('webSettings', 'kategoriHobis'));
    }
    
    /**
     * Save website settings
     */
    public function saveSettings(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'nama_website' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string|max:1000',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'favicon' => 'nullable|image|mimes:ico,png,jpg,gif,svg|max:1024',
                'email' => 'nullable|email|max:255',
                'telepon' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:500',
                'whatsapp' => 'nullable|string|max:20',
                'telegram' => 'nullable|string|max:100',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'youtube' => 'nullable|url|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get existing settings or create new
            $webSettings = WebSetting::first();
            if (!$webSettings) {
                $webSettings = new WebSetting();
            }

            // Handle file uploads
            $logoPath = $webSettings->logo ?? null;
            $faviconPath = $webSettings->favicon ?? null;

            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                    Storage::disk('public')->delete($logoPath);
                }
                
                $logoFile = $request->file('logo');
                $logoPath = $logoFile->store('uploads/logos', 'public');
            }

            if ($request->hasFile('favicon')) {
                // Delete old favicon if exists
                if ($faviconPath && Storage::disk('public')->exists($faviconPath)) {
                    Storage::disk('public')->delete($faviconPath);
                }
                
                $faviconFile = $request->file('favicon');
                $faviconPath = $faviconFile->store('uploads/favicons', 'public');
            }

            // Update settings
            $webSettings->fill([
                'nama_website' => $request->input('nama_website', $webSettings->nama_website),
                'deskripsi' => $request->input('deskripsi', $webSettings->deskripsi),
                'logo' => $logoPath,
                'favicon' => $faviconPath,
                'email' => $request->input('email', $webSettings->email),
                'telepon' => $request->input('telepon', $webSettings->telepon),
                'alamat' => $request->input('alamat', $webSettings->alamat),
                'whatsapp' => $request->input('whatsapp', $webSettings->whatsapp),
                'telegram' => $request->input('telegram', $webSettings->telegram),
                'facebook' => $request->input('facebook', $webSettings->facebook),
                'instagram' => $request->input('instagram', $webSettings->instagram),
                'twitter' => $request->input('twitter', $webSettings->twitter),
                'linkedin' => $request->input('linkedin', $webSettings->linkedin),
                'youtube' => $request->input('youtube', $webSettings->youtube),
            ]);

            $webSettings->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil disimpan!',
                'data' => $webSettings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add new hobby category
     */
    public function addCategory(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'nama_kategori' => 'required|string|max:100|unique:kategori_hobis,nama_kategori',
                'icon' => 'required|string|max:50',
                'background_color' => 'required|string|max:50',
            ], [
                'nama_kategori.required' => 'Nama kategori harus diisi',
                'nama_kategori.unique' => 'Kategori sudah ada',
                'nama_kategori.max' => 'Nama kategori maksimal 100 karakter',
                'icon.required' => 'Icon harus dipilih',
                'background_color.required' => 'Warna harus dipilih',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create new category
            $kategori = KategoriHobi::create([
                'nama_kategori' => $request->nama_kategori,
                'icon' => $request->icon,
                'background_color' => $request->background_color,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan!',
                'category' => $kategori
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove hobby category
     */
    public function removeCategory($categoryId)
    {
        try {
            $kategori = KategoriHobi::findOrFail($categoryId);
            
            // Check if category has hobbies
            $hobiCount = $kategori->hobis()->count();
            
            if ($hobiCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat menghapus kategori karena masih memiliki {$hobiCount} hobi. Hapus atau pindahkan hobi terlebih dahulu."
                ], 400);
            }

            // Delete category
            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus!'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset settings to default (optional)
     */
    public function resetSettings()
    {
        try {
            $webSettings = WebSetting::first();
            
            if ($webSettings) {
                // Delete uploaded files if exist
                if ($webSettings->logo && Storage::disk('public')->exists($webSettings->logo)) {
                    Storage::disk('public')->delete($webSettings->logo);
                }
                if ($webSettings->favicon && Storage::disk('public')->exists($webSettings->favicon)) {
                    Storage::disk('public')->delete($webSettings->favicon);
                }
                
                // Reset to default values
                $webSettings->update([
                    'nama_website' => 'HobiTracker',
                    'deskripsi' => '',
                    'logo' => null,
                    'favicon' => null,
                    'email' => '',
                    'telepon' => '',
                    'alamat' => '',
                    'whatsapp' => '',
                    'telegram' => '',
                    'facebook' => '',
                    'instagram' => '',
                    'twitter' => '',
                    'linkedin' => '',
                    'youtube' => '',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil direset ke default!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mereset pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current settings (for API)
     */
    public function getSettings()
    {
        try {
            $webSettings = WebSetting::first();
            
            if (!$webSettings) {
                $webSettings = WebSetting::create([
                    'nama_website' => 'HobiTracker',
                    'deskripsi' => '',
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $webSettings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }
}