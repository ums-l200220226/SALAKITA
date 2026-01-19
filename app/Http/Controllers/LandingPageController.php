<?php

namespace App\Http\Controllers;

use App\Models\LandingPageSetting;
use App\Models\LandingPageFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    /**
     * Display the landing page settings form
     */
    public function index()
    {
        $settings = LandingPageSetting::getSettings();
        return view('superAdmin.kelolaLandingPage', compact('settings'));
    }

    /**
     * Update landing page settings
     */
    public function update(Request $request)
    {

        try {
            // Log untuk debugging
            Log::info('Landing page update request', $request->all());

            $request->validate([
                // Hero Section
                'hero_title' => 'required|string|max:255',
                'hero_description' => 'required|string',
                'hero_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
                'logo_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',

                // Features Section
                'features_title' => 'required|string|max:255',

                // History Section
                'history_title' => 'required|string|max:255',
                'history_content' => 'required|string',

                // Products Section
                'products_title' => 'required|string|max:255',
                'products_description' => 'required|string',
                // PENTING: Hapus validasi boolean untuk checkbox

                // Stats
                'stats_transactions' => 'required|integer|min:0',
                'stats_products' => 'required|integer|min:0',
                'stats_rating' => 'required|numeric|min:0|max:5',

                // Location Section
                'location_title' => 'required|string|max:255',
                'location_address' => 'required|string',
                'location_phone' => 'required|string|max:50',
                'location_email' => 'required|email|max:255',
                'location_hours' => 'required|string',
                'location_map_url' => 'nullable|url',

                // CTA Section
                'cta_title' => 'required|string|max:255',
                'cta_description' => 'required|string',

                // Footer
                'footer_copyright' => 'required|string|max:255',
            ]);

            $settings = LandingPageSetting::getSettings();

            // Prepare data untuk update
            $data = [
                'hero_title' => $request->hero_title,
                'hero_description' => $request->hero_description,
                'features_title' => $request->features_title,
                'history_title' => $request->history_title,
                'history_content' => $request->history_content,
                'products_title' => $request->products_title,
                'products_description' => $request->products_description,
                // PENTING: Gunakan has() untuk checkbox
                'show_products_section' => $request->has('show_products_section'),
                'stats_transactions' => $request->stats_transactions,
                'stats_products' => $request->stats_products,
                'stats_rating' => $request->stats_rating,
                'location_title' => $request->location_title,
                'location_address' => $request->location_address,
                'location_phone' => $request->location_phone,
                'location_email' => $request->location_email,
                'location_hours' => $request->location_hours,
                'location_map_url' => $request->location_map_url,
                'cta_title' => $request->cta_title,
                'cta_description' => $request->cta_description,
                'footer_copyright' => $request->footer_copyright,
            ];

            // Handle hero image upload
            if ($request->hasFile('hero_image')) {
                try {
                    // Delete old image jika ada
                    if ($settings->hero_image && Storage::disk('public')->exists($settings->hero_image)) {
                        Storage::disk('public')->delete($settings->hero_image);
                    }

                    // Upload image baru
                    $heroPath = $request->file('hero_image')->store('landing-page', 'public');
                    $data['hero_image'] = $heroPath;

                    Log::info('Hero image uploaded', ['path' => $heroPath]);
                } catch (\Exception $e) {
                    Log::error('Error uploading hero image: ' . $e->getMessage());
                }
            }

            // Handle logo upload
            if ($request->hasFile('logo_image')) {
                try {
                    // Delete old logo jika ada
                    if ($settings->logo_image && Storage::disk('public')->exists($settings->logo_image)) {
                        Storage::disk('public')->delete($settings->logo_image);
                    }

                    // Upload logo baru
                    $logoPath = $request->file('logo_image')->store('landing-page', 'public');
                    $data['logo_image'] = $logoPath;

                    Log::info('Logo uploaded', ['path' => $logoPath]);
                } catch (\Exception $e) {
                    Log::error('Error uploading logo: ' . $e->getMessage());
                }
            }

            // Update ke database dengan save() untuk memastikan tersimpan
            $settings->fill($data);
            $saved = $settings->save();

            if (!$saved) {
                throw new \Exception('Gagal menyimpan data ke database');
            }

            Log::info('Landing page settings updated successfully', [
                'id' => $settings->id,
                'data' => $data
            ]);

            // Hapus cache jika ada
            //cache()->forget('landing_settings');

            return redirect()->route('superAdmin.kelolaLandingPage')
               ->with('success', 'Perubahan berhasil disimpan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);

            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();

        } catch (\Exception $e) {
            Log::error('Error updating landing page: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Update a single feature
     */
    public function updateFeature(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'icon_svg' => 'nullable|string',
            ]);

            $feature = LandingPageFeature::findOrFail($id);
            $feature->update([
                'title' => $request->title,
                'description' => $request->description,
                'icon_svg' => $request->icon_svg,
            ]);

            return redirect()->back()->with('success', 'Fitur berhasil diupdate!');

        } catch (\Exception $e) {
            Log::error('Error updating feature: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Gagal update fitur: ' . $e->getMessage())
                           ->withInput();
        }
    }
}
