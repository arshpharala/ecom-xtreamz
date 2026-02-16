<?php
include('aes.php');
$aes = new AES();

//echo $_POST["key"];
$key = $_POST['key']; //<<<<<<<<<<jsCybsOn
//$key="tf3N02w4iZ/q8aw5HkSqkE25k8pN0LB18heLz/1dEag=";

$txnDetails = new \stdClass();
echo $_POST["meid"];
//$txnDetails->meId=$_POST['meid']; //<<<<<<<<<<jsCybsOn

$txnDetails->meId = $_POST['meid'];
//$txnDetails->meId="202401180001";

$txnDetails->merchantOrderNo = rand(1000, 100000);   /** Transaction Unique Order No **/
$txnDetails->amount = $_POST['amount'];     /** Transaction amount **/
$txnDetails->countryCode = "ARE";
$txnDetails->currencyCode = "AED";
$txnDetails->txnType = "SALE";
$txnDetails->channel = "WEB";
$txnDetails->userId = "sani@antino.io";
$txnDetails->planId = "";
$txnDetails->SuccessUrl = "/Response.jsp";
$txnDetails->FailUrl = "/Response.jsp";

$customerDetails = new \stdClass();
$customerDetails->name = "jsCybsOn";
$customerDetails->email = "successful.payment@tabby.ai";
$customerDetails->phone = "500000001";
$customerDetails->uniqueId = "";

$billingDetails = new \stdClass();
$billingDetails->bill_address = "123";
$billingDetails->bill_city = "Gurgaon";
$billingDetails->bill_state = "Hariyana";
$billingDetails->bill_country = "India";
$billingDetails->bill_zip = "110038";

$shipDetails = new \stdClass();
$shipDetails->ship_address = "";
$shipDetails->ship_city = "";
$shipDetails->ship_state = "";
$shipDetails->ship_country = "";
$shipDetails->ship_zip = "";
$shipDetails->ship_days = "";
$shipDetails->address_count = "";

$itemDetails = new \stdClass();
$itemDetails->item_count = "";
$itemDetails->item_value = "";
$itemDetails->item_category = "";

$otherDetails = new \stdClass();
$otherDetails->udf_1 = "";
$otherDetails->udf_2 = "";
$otherDetails->udf_3 = "";
$otherDetails->udf_4 = "";
$otherDetails->udf_5 = "";
$otherDetails->udf_6 = "";

$orderDetails = new \stdClass();
$orderDetails->txnDetails = $txnDetails;
$orderDetails->customerDetails = $customerDetails;
$orderDetails->billingDetails = $billingDetails;
$orderDetails->shipDetails = $shipDetails;
$orderDetails->itemDetails = $itemDetails;
$orderDetails->otherDetails = $otherDetails;

$compute = $txnDetails->meId . '|' . $txnDetails->merchantOrderNo . '|' . $txnDetails->amount . '|' . $txnDetails->countryCode . '|' . $txnDetails->currencyCode;

$test = json_encode($orderDetails);

$encryptedData = $aes->encrypt(json_encode($orderDetails), $key, 256);

$hash = $aes->checksum($compute);

$paymode = '';

echo "<pre>";
print_r($txnDetails);
echo "</pre>";
?>
<!DOCTYPE html>
<html>

<head>

    <title>JS Checkout - FAB Example</title>
    <script src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/js-checkoutNewCheck.js"></script>
    <script type="text/javascript"
        src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/js/scripts/gPayScript.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
    <script type="text/javascript"
        src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/resources/js/jquery.min.js"></script>
    <link href="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/resourcesJS/css/checkout.css"
        rel="stylesheet" type="text/css" />
    <link
        href="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/resourcesJS/css/swiper-bundle.min.css"
        rel="stylesheet" type="text/css" />
    <script type="text/javascript"
        src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/resourcesJS/js/bootstrap.min.js"></script>
    <script type="text/javascript"
        src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/resourcesJS/js/swiper-bundle.min.js"></script>
    <script type="text/javascript"
        src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/resourcesJS/js/script.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://applepay.cdn-apple.com/jsapi/v1.1.0/apple-pay-sdk.js"></script>
    <script type="text/javascript"
        src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/resourcesJS/js/lang.json"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript"
        src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/js/scripts/min/ua-parser.min.js"></script>
    <script>
        /**********Below script to be included in checkout page to trigger the payment module popup**********************/
        function TestResponse(dta) {
            alert(dta);
        }
        let jsCheckoutOptions = {
            merchantId: <?php echo $txnDetails->meId; ?>,//fabpg
            internalKey: 'ek/hLPPSCLxBQ4bx0Zlp6RR+6U9/uUM0DKF/fBPXTkY='
        }

        let spObj = new JsCheckout(); // Creating object of JS Checkout Library.

        spObj.Init(jsCheckoutOptions); // Initializing the required options.
        Buy = function () {
            //alert("hello");
            spObj.Pay('<?php echo $paymode ?>', '<?php echo $encryptedData ?>', '<?php echo $hash ?>', '<?php echo json_encode($orderDetails) ?>', CallbackForResponse); // Pay function will accept 4 arguments and all are mandatory, First will be order details and second will be callback function. Callback function will be used to handle the response on the merchant side.
        }

        /**** Store Transaction response calback function***********/
        function CallbackForResponse(response) {
            console.log("CallbackResponse : " + response);
        }
        const callbackForResponse = (response) => {
            console.log("callbackResponse : " + JSON.stringify(JSON.parse(response)));
        };

        function hello() {
            //alert("hello nnn");
            spObj.Pay('<?php echo $paymode ?>', '<?php echo $encryptedData ?>', '<?php echo $hash ?>', '<?php echo json_encode($orderDetails) ?>', CallbackForResponse); // Pay function will accept 4 arguments and all are mandatory, First will be order details and second will be callback function. Callback function will be used to handle the response on the merchant side.
        }

        document.addEventListener('transactionEvent', function (e) {
            console.log("Received transaction event:", e?.detail);
            const response = e?.detail;
            alert("Transaction Successful! Response: " + response);
        });
    </script>

    <style>
        .buy {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .buy:hover {
            background-color: #45a049;
        }

        .containerIn {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h3>JS Checkout Process</h3>
    <div class="containerIn">
        <label for="buy">Click button to process Order No <?php echo $txnDetails->merchantOrderNo; ?></label>
        <!-- Changed orderNo to merchantOrderNo -->
        <button onclick="Buy();" id="buy" class="buy">Pay Now11111</button>
    </div>
    <div id="response">
    </div>

    <div id="containerDiv" class="containerDiv">
        <iframe id="checkout-iframe" allowtransparency="true" frameborder="0" width="100%" height="100%"
            allowpaymentrequest="true"
            src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/paymentRedirection/jsCheckoutLoader/202406190001"></iframe>
    </div>
</body>

</html>