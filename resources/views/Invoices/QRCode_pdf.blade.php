<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice payment QR code</title>
    <style>
        h1 {
            color: #60A5FA;
            font-size: 1.875rem;
            line-height: 2.25rem;
        }

        body {
            font-family: Figtree, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            text-align: center;
        }

        .content {
            max-width: 600px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
    <div class="content">
    <h1>Your personalized QR code</h1>
    
    <p>
    <?php 
    echo DNS2D::getBarcodeHTML($domain . "/pay/" . $invoiceID, 'QRCODE',10,10);
    ?>
    </p>

    <p>Scanning this QR code will bring you directly to a page where you can handle the payment of your invoice.</p>
    </div>
    </div>
</body>