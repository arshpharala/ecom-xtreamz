<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment Gateway...</title>
</head>
<body>
    <div style="text-align: center; margin-top: 20%; font-family: sans-serif;">
        <p>Redirecting to Payment Gateway...</p>
        <p>Please do not close this window.</p>
    </div>

    <form id="touras_form" action="{{ $actionUrl }}" method="POST">
        <input type="hidden" name="me_id" value="{{ $formData['me_id'] }}">
        <input type="hidden" name="merchant_request" value="{{ $formData['merchant_request'] }}">
        <input type="hidden" name="hash" value="{{ $formData['hash'] }}">
    </form>

    <script>
        document.getElementById('touras_form').submit();
    </script>
</body>
</html>
