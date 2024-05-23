<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR code</title>
</head>

<body>
    <!--Page for testing purposes only-->
    <?php 
    $domain = config('app.host_domain');
    $invoiceID = "209";                   //edit to invoice you want
    $QRService = app()->make('App\Services\QRCodeService');
    $hash = $QRService->getHash($invoiceID);
    echo DNS2D::getBarcodeHTML($domain . "/pay/" . $invoiceID . "/" . $hash, 'QRCODE',5,5);
    // browse to /code
    ?>
</body>