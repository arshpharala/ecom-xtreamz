<?php

namespace App\Http\Controllers\Admin\CMS;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CMS\PaymentGateway;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentGatewayRequest;

class PaymentGatewayController extends Controller
{
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
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentGatewayRequest $request)
    {
        $configs = config('payment_gateways');
        $gateways = PaymentGateway::get()->keyBy('gateway');


        foreach ($configs as $gatewayKey => $gatewayConfig) {

            $pg = $gateways->get($gatewayKey);
            $fields = [];
            $additional = [];

            if (!isset($request->is_active[$gatewayKey])) {
                $fields['is_active'] = false;
            } else {
                $fields['is_active'] = true;
            }


            foreach ($gatewayConfig['fields'] as $fieldKey => $fieldConfig) {
                $rawInput = $request->input("$fieldKey.$gatewayKey");

                $isMasked = Str::contains($rawInput, '****');
                $valueToStore = $isMasked && $pg ? $pg->$fieldKey ?? $pg->additional[$fieldKey] ?? null : $rawInput;


                if (in_array($fieldKey, ['key', 'secret'])) {
                    $fields[$fieldKey] = $valueToStore ?? null;
                } else {
                    $additional[$fieldKey] = $valueToStore;
                }
            }

            PaymentGateway::updateOrCreate(
                ['gateway' => $gatewayKey],
                array_merge($fields, [
                    'additional' => $additional,
                ])
            );
        }

        return response()->json([
            'message' => __('crud.updated', ['name' => 'Payment Gateway']),
            'redirect' => route('admin.cms.settings.index'),
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
        //
    }
}
