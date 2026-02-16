<?php

namespace App\Services\Touras;

use App\Models\CMS\PaymentGateway;

class TourasService
{
    protected string $merchantId;

    protected string $merchantKey;

    protected bool $isProduction;

    protected string $aggregatorId = 'touras';

    public function __construct()
    {
        $gatewayConfig = PaymentGateway::where('gateway', 'touras')->first();

        if (! $gatewayConfig) {
            throw new \RuntimeException('Touras gateway is not configured in DB.');
        }

        // Your PaymentGateway model decrypts key/secret via accessors
        $this->merchantId = (string) $gatewayConfig->key;
        $this->merchantKey = (string) $gatewayConfig->secret;

        // test_mode true => UAT
        $this->isProduction = ! ($gatewayConfig->additional['test_mode'] ?? true);
    }

    /**
     * Touras JS Checkout payload (International)
     * Returns: merchantId, encryptedData, hash, orderDetails
     */
    public function prepareJsPayload(array $data): array
    {
        // Normalize amount to 2 decimals (Touras gateways often require this)
        $amount = number_format((float) ($data['amount'] ?? 0), 2, '.', '');

        // Validate minimum required fields
        $required = ['order_no', 'country', 'currency', 'txn_type', 'channel', 'cust_name', 'email_id', 'mobile_no'];
        foreach ($required as $k) {
            if (! isset($data[$k]) || $data[$k] === '') {
                throw new \InvalidArgumentException("Touras missing required field: {$k}");
            }
        }

        $txnDetails = new \stdClass;
        $txnDetails->meId = $this->merchantId;
        $txnDetails->merchantOrderNo = (string) $data['order_no'];
        $txnDetails->amount = (string) $amount;
        $txnDetails->countryCode = (string) $data['country'];   // e.g. ARE
        $txnDetails->currencyCode = (string) $data['currency'];  // e.g. AED
        $txnDetails->txnType = (string) $data['txn_type'];  // SALE
        $txnDetails->channel = (string) $data['channel'];   // WEB
        $txnDetails->userId = (string) ($data['email_id'] ?? '');
        $txnDetails->planId = (string) ($data['planId'] ?? '');
        // $txnDetails->SuccessUrl = route('touras.return');
        // $txnDetails->FailureUrl = route('touras.return');

        $customerDetails = new \stdClass;
        $customerDetails->name = (string) ($data['cust_name'] ?? '');
        $customerDetails->email = (string) ($data['email_id'] ?? '');
        $customerDetails->phone = (string) ($data['mobile_no'] ?? '');
        $customerDetails->uniqueId = (string) ($data['uniqueId'] ?? '');

        $billingDetails = new \stdClass;
        $billingDetails->bill_address = (string) ($data['bill_address'] ?? '');
        $billingDetails->bill_city = (string) ($data['bill_city'] ?? '');
        $billingDetails->bill_state = (string) ($data['bill_state'] ?? '');
        $billingDetails->bill_country = (string) ($data['bill_country'] ?? ''); // country name is ok as per doc sample
        $billingDetails->bill_zip = (string) ($data['bill_zip'] ?? '');

        $shipDetails = new \stdClass;
        $shipDetails->ship_address = (string) ($data['ship_address'] ?? '');
        $shipDetails->ship_city = (string) ($data['ship_city'] ?? '');
        $shipDetails->ship_state = (string) ($data['ship_state'] ?? '');
        $shipDetails->ship_country = (string) ($data['ship_country'] ?? '');
        $shipDetails->ship_zip = (string) ($data['ship_zip'] ?? '');
        $shipDetails->ship_days = (string) ($data['ship_days'] ?? '');
        $shipDetails->address_count = (string) ($data['address_count'] ?? 1);

        $itemDetails = new \stdClass;
        $itemDetails->item_count = (string) ($data['item_count'] ?? '');
        $itemDetails->item_value = (string) ($data['item_value'] ?? '');
        $itemDetails->item_category = (string) ($data['item_category'] ?? '');

        $otherDetails = new \stdClass;
        $otherDetails->udf_1 = (string) ($data['udf_1'] ?? '');
        $otherDetails->udf_2 = (string) ($data['udf_2'] ?? '');
        $otherDetails->udf_3 = (string) ($data['udf_3'] ?? '');
        $otherDetails->udf_4 = (string) ($data['udf_4'] ?? '');
        $otherDetails->udf_5 = (string) ($data['udf_5'] ?? '');
        $otherDetails->planId = (string) ($data['planId'] ?? '');

        $orderDetails = new \stdClass;
        $orderDetails->txnDetails = $txnDetails;
        $orderDetails->customerDetails = $customerDetails;
        $orderDetails->billingDetails = $billingDetails;
        $orderDetails->shipDetails = $shipDetails;
        $orderDetails->itemDetails = $itemDetails;
        $orderDetails->otherDetails = $otherDetails;

        $compute = $this->merchantId.'|'.$txnDetails->merchantOrderNo.'|'.$txnDetails->amount.'|'.$txnDetails->countryCode.'|'.$txnDetails->currencyCode;

        $encryptedData = $this->encrypt(json_encode($orderDetails));
        $hash = $this->checksum($compute);

        return [
            'merchantId' => $this->merchantId,
            'encryptedData' => $encryptedData,
            'hash' => $hash,
            'orderDetails' => $orderDetails,
            'payMode' => '',
        ];

    }

    public function encrypt($text)
    {
        $iv = '0123456789abcdef';
        $size = 16;
        $pad = $size - (strlen($text) % $size);
        $padtext = $text.str_repeat(chr($pad), $pad);
        $crypt = openssl_encrypt($padtext, 'AES-256-CBC', base64_decode($this->merchantKey), OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);

        return base64_encode($crypt);
    }

    public function decrypt($crypt)
    {
        $iv = '0123456789abcdef';
        $crypt = base64_decode($crypt);
        $padtext = openssl_decrypt($crypt, 'AES-256-CBC', base64_decode($this->merchantKey), OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        $pad = ord($padtext[
                       strlen($padtext) - 1]);
        if ($pad > strlen($padtext)) {
            return false;
        }
        if (strspn($padtext, $padtext[
                       strlen($padtext) - 1], strlen($padtext) - $pad) != $pad) {
            $text = 'Error';
        }

        $text = substr($padtext, 0, -1 * $pad);

        return $text;
    }

    public function checksum($compute)
    {

        $hash = hash('sha512', $compute);

        return $hash;
    }
}
