<?php

namespace App\Http\Controllers;

use App\Models\WebSetting;
use App\Models\KategoriHobi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WebSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriHobis = KategoriHobi::all();
        return view('admin.setting', ['kategoriHobis' => $kategoriHobis]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Add new hobby category
     */
    public function addCategory(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'background_color' => 'required|string|max:255'
        ]);

        $kategori = new KategoriHobi();
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->icon = $request->icon;
        $kategori->background_color = $request->background_color;
        $kategori->save();

        return redirect()->back()->with('success', 'Kategori hobi berhasil ditambahkan');
    }

    /**
     * Remove hobby category
     */
    public function removeCategory($id)
    {
        $kategori = KategoriHobi::find($id);

        if (!$kategori) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan');
        }

        // Check if category is being used by any hobbies (only current user)
        if ($kategori->hobis()->where('user_id', Auth::id())->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh hobi Anda');
        }

        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori hobi berhasil dihapus');
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = KategoriHobi::all();
        return response()->json($categories);
    }

    /**
     * Save general website settings
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'whatsapp' => 'nullable|string|max:20',
            'telegram' => 'nullable|string|max:100',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
        ]);

        // Check if settings already exist
        $settings = WebSetting::first();

        if ($settings) {
            // Update existing settings
            $settings->site_name = $request->site_name;
            $settings->site_description = $request->site_description;
            $settings->contact_email = $request->contact_email;
            $settings->contact_phone = $request->contact_phone;
            $settings->address = $request->address;
            $settings->whatsapp = $request->whatsapp;
            $settings->telegram = $request->telegram;
            $settings->facebook = $request->facebook;
            $settings->instagram = $request->instagram;
            $settings->twitter = $request->twitter;
            $settings->linkedin = $request->linkedin;
            $settings->youtube = $request->youtube;
            $settings->save();
        } else {
            // Create new settings
            $newSettings = new WebSetting();
            $newSettings->site_name = $request->site_name;
            $newSettings->site_description = $request->site_description;
            $newSettings->contact_email = $request->contact_email;
            $newSettings->contact_phone = $request->contact_phone;
            $newSettings->address = $request->address;
            $newSettings->whatsapp = $request->whatsapp;
            $newSettings->telegram = $request->telegram;
            $newSettings->facebook = $request->facebook;
            $newSettings->instagram = $request->instagram;
            $newSettings->twitter = $request->twitter;
            $newSettings->linkedin = $request->linkedin;
            $newSettings->youtube = $request->youtube;
            $newSettings->save();
        }

        return redirect()->back()->with('success', 'Pengaturan website berhasil disimpan');
    }
}
