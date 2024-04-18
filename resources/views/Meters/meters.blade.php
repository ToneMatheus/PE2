<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
    @livewireStyles
</head>
<body>

    <livewire:meters-table />

    @livewireScripts


    <h1>EAN Number Test:</h1>
<?php
//https://stackoverflow.com/a/19890444
function generateEAN($number)
{
  $code = '54' . str_pad($number, 15, '0', STR_PAD_LEFT);
  $weightflag = true;
  $sum = 0;
  for ($i = strlen($code) - 1; $i >= 0; $i--)
  {
    $sum += (int)$code[$i] * ($weightflag?3:1);
    $weightflag = !$weightflag;
  }
  $code .= (10 - ($sum % 10)) % 10;
  return $code;
}
?>
<h3>{{generateEAN(10)}}</h3>
</body>
</html>