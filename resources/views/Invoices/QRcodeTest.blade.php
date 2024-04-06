<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR code</title>
</head>

<body>
    <!--Page for testing purposes only-->
    <?php 
        echo DNS2D::getBarcodeHTML('http://127.0.0.1:8000/pay/1', 'QRCODE');
    ?>
</body>