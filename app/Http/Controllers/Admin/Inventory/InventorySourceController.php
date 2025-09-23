<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Models\CMS\City;
use App\Models\CMS\Country;
use App\Models\CMS\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory\InventorySource;
use App\Http\Requests\StoreInventorySourceRequest;
use App\Http\Requests\UpdateInventorySourceRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InventorySourceController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', InventorySource::class);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', InventorySource::class);

        $source = new InventorySource();

        $countries = Country::orderBy('name')->pluck('name', 'id');

        return view('theme.adminlte.inventories.sources.create', compact('source', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventorySourceRequest $request)
    {
        $this->authorize('create', InventorySource::class);

        InventorySource::create($request->validated());
        return redirect()->route('admin.inventory.sources.index')
            ->with('success', 'Inventory source created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $source = InventorySource::findOrFail($id);

        $this->authorize('update', $source);

        $countries = Country::orderBy('name')->pluck('name', 'id');

        // Prefill dependent lists based on saved values (if any)
        $provinces = $source->country_id
            ? Province::where('country_id', $source->country_id)->orderBy('name')->pluck('name', 'id')
            : collect();

        $cities = $source->province_id
            ? City::where('province_id', $source->province_id)->orderBy('name')->pluck('name', 'id')
            : collect();

        return view('theme.adminlte.inventories.sources.edit', compact('source', 'countries', 'provinces', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventorySourceRequest $request, string $id)
    {
        $source = InventorySource::findOrFail($id);

        $this->authorize('update', $source);

        $validated = $request->validated();
        $validated['is_active'] = (bool) $request->boolean('is_active');

        $source->update($validated);

        return redirect()
            ->route('admin.inventory.sources.index')
            ->with('success', 'Inventory source updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $source = InventorySource::findOrFail($id);

        $this->authorize('delete', $source);

        $source->delete();

        return back()->with('success', 'Inventory source deleted.');
    }
}
