<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR code</title>
</head>

<body>
    <!--Page for testing purposes only-->
    <?php 
    $domain = "http://127.0.0.1:8000";
    $invoiceID = "1";
    echo DNS2D::getBarcodeHTML($domain . "/pay/" . $invoiceID, 'QRCODE',3,3);
    echo DNS2D::getBarcodeSVG($domain . "/pay/" . $invoiceID, 'QRCODE',10,10);
    ?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#ffffff">
    <tr>
        <td align="center" style="padding: 20px;">
            <?php 
            echo DNS2D::getBarcodeHTML($domain . "/pay/" . $invoiceID, 'QRCODE',3,3);
            ?>
        </td>
    </tr>
</table>
</body>