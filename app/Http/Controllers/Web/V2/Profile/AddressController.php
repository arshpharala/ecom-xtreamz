<?php

namespace App\Http\Controllers\Web\Profile;

use App\Models\CMS\Area;
use App\Models\CMS\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AddressRepository;

class AddressController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected AddressRepository $addressRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'province_id' => 'required|exists:provinces,id',
            'city_id'     => 'required|exists:cities,id',
            'area_id'     => 'required|exists:areas,id',
            'address'     => 'required|string|max:1000',
            'landmark'    => 'nullable|string|max:500',
        ]);

        $data['user_id'] = auth()->user()->id;

        $this->addressRepository->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Address saved successfully.',
            'redirect' => redirect()->back()
        ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isDeleted = $this->addressRepository->delete($id);

        if ($isDeleted) {
            return response()->json([
                'success' => true,
                'message' => 'Address Deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "You can't delete default address",
            ], 400);
        }
    }

    public function getCities($provinceId)
    {
        $cities = City::where('province_id', $provinceId)->get();
        return response()->json($cities);
    }

    public function getAreas($cityId)
    {
        $areas = Area::where('city_id', $cityId)->get();
        return response()->json($areas);
    }
}
