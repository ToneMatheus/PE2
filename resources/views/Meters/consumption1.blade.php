<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meter Reading</title>
  <link rel="stylesheet" href="../../css/consumption1.css">
</head>
<body>
  <div class="container">
    <h1>Meter Reading</h1>
    <div id="meterIdInput">
      <input type="text" id="meterId" placeholder="Enter Meter ID">
      <button onclick="showMeterType()">Submit</button>
    </div>
    <div id="meterType" style="display:none;">
      <p id="meterTypeText"></p>
      <input type="date" id="readingDate">
      <input type="number" id="readingValue" placeholder="Enter Reading Value">
      <button onclick="storeReading()">Submit Reading</button>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
