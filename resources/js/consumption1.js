function showMeterType() {
    var meterId = document.getElementById('meterId').value;
  
    // Simulate fetching meter type from database
    // This should be replaced with actual AJAX call to your server
    // Assuming meterType is retrieved as 'gas' or 'electricity'
    var meterType = 'gas'; // Example, replace this with actual fetched value
    
    document.getElementById('meterTypeText').innerHTML = "Meter Type: " + meterType;
    document.getElementById('meterType').style.display = 'block';
  }
  
  function storeReading() {
    var meterId = document.getElementById('meterId').value;
    var readingDate = document.getElementById('readingDate').value;
    var readingValue = document.getElementById('readingValue').value;
  
    // Simulate storing reading in database
    // This should be replaced with actual AJAX call to your server
    // For Laravel, you'll handle this in your controller
    console.log("Meter ID: " + meterId + ", Date: " + readingDate + ", Reading Value: " + readingValue);
    // Here you'll make an AJAX call to your Laravel backend to store the reading
  }
  