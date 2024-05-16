<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR code</title>
</head>

<body>
    <!--Page for testing purposes only-->
    <?php 
    $domain = "http://127.0.0.1:8000";  //change to webserver ip *10.64.0.100*
    $invoiceID = "1";                   //edit to invoice you want
    $QRService = app()->make('App\Services\QRCodeService');
    $hash = $QRService->getHash($invoiceID);
    echo DNS2D::getBarcodeHTML($domain . "/pay/" . $invoiceID . "/" . $hash, 'QRCODE',5,5);
    ?>
</body>