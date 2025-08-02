<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentGatewayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];

        $configs = config('payment_gateways');

        foreach ($configs as $gatewayKey => $gateway) {

            if ($this->has("is_active.$gatewayKey")) {
                foreach ($gateway['fields'] as $fieldKey => $fieldConfig) {
                    $label = $fieldConfig['label'] ?? ucfirst($fieldKey);
                    $rules["{$fieldKey}.{$gatewayKey}"] = ['required', 'string'];
                }

                if (!empty($gateway['webhook'])) {
                    $rules["webhook_url.$gatewayKey"] = ['nullable', 'url'];
                }
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        $configs = config('payment_gateways');

        foreach ($configs as $gatewayKey => $gateway) {
            foreach ($gateway['fields'] as $fieldKey => $fieldConfig) {
                $label = $fieldConfig['label'] ?? ucfirst($fieldKey);
                $messages["{$fieldKey}.{$gatewayKey}.required"] = __("The :label for :gateway is required.", [
                    'label'     => $label,
                    'gateway'   => $gateway['label'] ?? ucfirst($gatewayKey)
                ]);
            }

            if (!empty($gateway['webhook'])) {
                $messages["webhook_url.$gatewayKey.url"] = __("The Webhook URL for :gateway must be a valid URL.", [
                    'gateway' => $gateway['label'] ?? ucfirst($gatewayKey)
                ]);
            }
        }

        return $messages;
    }
}
