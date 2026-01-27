<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use App\Models\CMS\PaymentGateway;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gateways  = PaymentGateway::get();
        $settings  = Setting::pluck('value', 'key')->toArray();

        $categories = Category::query()
            ->with(['translations' => function ($q) {
                $q->where('locale', app()->getLocale());
            }])
            ->orderBy('id')
            ->get();

        return view('theme.adminlte.settings.index', [
            'categories'   => $categories,
            'gateways'     => $gateways,
            'settings'     => $settings,
            'gatwayConfig' => config('payment_gateways') ?? [],
        ]);
    }

    /**
     * Store settings (used by ajax-form in tabs)
     * NOTE: No global validation to avoid affecting other forms.
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {

            // ✅ File handling
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                Setting::updateOrCreate(['key' => $key], ['value' => $path]);
                continue;
            }

            // ✅ Normalize arrays (multi-select etc.) into JSON
            if (is_array($value)) {

                // ✅ UUID-safe: store excluded category IDs as strings
                if ($key === 'jasani_discount_excluded_category_ids') {
                    $value = array_values(array_unique(array_map('strval', $value)));
                }

                $value = json_encode($value);
            }

            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        cache()->forget('app_settings');

        return response()->json([
            'message' => 'Settings updated successfully.',
            'redirect' => url()->current(),
        ]);
    }

    /**
     * Update (kept for compatibility)
     */
    public function update(Request $request, string $id)
    {
        foreach ($request->except('_token') as $key => $value) {

            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                Setting::set($key, $path);
                continue;
            }

            if (is_array($value)) {

                // ✅ UUID-safe: store excluded category IDs as strings
                if ($key === 'jasani_discount_excluded_category_ids') {
                    $value = array_values(array_unique(array_map('strval', $value)));
                }

                $value = json_encode($value);
            }

            Setting::set($key, $value);
        }

        cache()->forget('app_settings');

        return back()->with('success', 'Settings updated successfully.');
    }
}
