<x-app-layout>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Meter History</title>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            .pagination {
                margin-top: 20px;
            }
            .pagination button {
                padding: 5px 10px;
                margin-right: 5px;
                cursor: pointer;
            }
            .error {
                color: red;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <h1>Meter History</h1>

        <table id="meterTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Meter Reading</th>
                </tr>
            </thead>
            <tbody id="meterTableBody">
                <!-- Meter readings will be dynamically added here -->
            </tbody>
        </table>

        <div class="pagination" id="pagination"></div>
        <div id="errorBox" class="error"></div>

        <form action="./Meter_History_Conn.php" method="post">
            <div>
                <label for="meter_reading">Meter Reading:</label>
                <input type="text" name="meter_reading" id="meter_reading" pattern="\d{5}\.\d" maxlength="7" title="Please enter 5 digits followed by a decimal point and then another digit" required>
            </div>
            <div>
                <label for="reading_date">Reading Date:</label>
                <input type="date" name="reading_date" id="reading_date" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <button type="button" onclick="addMeterReading()">Add Reading</button>
        </form>

        <script>
            var currentPage = 1;
            var entriesPerPage = 10;

            function addMeterReading() {
                var meterReadingInput = document.getElementById('meter_reading').value;
                var dateInput = document.getElementById('reading_date').value;

                // Convert date to dd-mm-yyyy format
                var dateParts = dateInput.split("-");
                var formattedDate = dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0];

                var table = document.getElementById('meterTable');
                var tbody = document.getElementById('meterTableBody');

                // Perform validations
                var errorBox = document.getElementById('errorBox');
                errorBox.innerHTML = '';
                if (tbody.rows.length > 0) {
                    var lastRow = tbody.rows[tbody.rows.length - 1];
                    var lastReading = parseFloat(lastRow.cells[1].innerHTML);
                    var currentReading = parseFloat(meterReadingInput);

                    if (currentReading <= lastReading) {
                        errorBox.innerHTML = 'Error: Please contact support.';
                        return;
                    }

                    if (currentReading > lastReading + 500) {
                        errorBox.innerHTML = 'Error: Meter reading increase exceeds 500. Please check again.';
                        return;
                    }
                }

                // Add the new entry to the table
                var row = tbody.insertRow();
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);

                cell1.innerHTML = formattedDate;
                cell2.innerHTML = meterReadingInput;

                // Update pagination
                updatePagination();

                // Clear the input fields
                document.getElementById('meter_reading').value = '';
                document.getElementById('reading_date').value = '';

                // Show success message
                var successMessage = document.createElement('div');
                successMessage.textContent = 'Entry added successfully!';
                successMessage.style.backgroundColor = 'lightgreen';
                successMessage.style.padding = '10px';
                successMessage.style.marginTop = '10px';
                document.body.appendChild(successMessage);

                // Hide success message after 3 seconds
                setTimeout(function() {
                    document.body.removeChild(successMessage);
                }, 3000);
            }

            function updatePagination() {
                // Your existing updatePagination function code...
            }

            // Other functions...
        </script>
    </body>
    </html>


</x-app-layout>

