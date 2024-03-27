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
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Meter History</h1>

    <div>
        <label for="meter_reading">Meter Reading:</label>
        <input type="text" name="meter_reading" id="meter_reading" pattern="\d{1,6}(\.\d)?" maxlength="8" title="Please enter up to 6 digits optionally followed by a decimal point and then another digit" required>
    </div>
    <div>
        <label for="reading_date">Reading Date:</label>
        <input type="date" name="reading_date" id="reading_date" max="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <button onclick="addMeterReading()">Add Reading</button>
    <div id="error-message" class="error-message" style="display: none;"></div>

    <table id="meterTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Meter Reading</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="meterTableBody">
            <!-- Meter readings will be dynamically added here -->
        </tbody>
    </table>

    <div class="pagination" id="pagination"></div>

    <script>
        var currentPage = 1;
        var entriesPerPage = 10;

        function addMeterReading() {
            var meterReadingInput = parseFloat(document.getElementById('meter_reading').value);
            var dateInput = document.getElementById('reading_date').value;

            // Validate meter reading pattern
            var meterPattern = /^\d{1,6}(\.\d)?$/;
            if (!meterPattern.test(meterReadingInput)) {
                alert("Please enter meter reading in the correct format (e.g., 123456.7)");
                return;
            }

            var table = document.getElementById('meterTable');
            var tbody = document.getElementById('meterTableBody');

            // Check if the current date already has an entry
            var rows = tbody.getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
                var rowDate = rows[i].getElementsByTagName('td')[0].innerText;
                if (rowDate === dateInput) {
                    alert("An entry for this date already exists. You can edit or delete the existing entry.");
                    return;
                }
            }

            // Convert date to dd-mm-yyyy format
            var dateParts = dateInput.split("-");
            var formattedDate = dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0];

            var row = tbody.insertRow();
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);

            cell1.innerHTML = formattedDate;
            cell2.innerHTML = meterReadingInput;
            cell3.innerHTML = '<button onclick="editEntry(this)">Edit</button> <button onclick="deleteEntry(this)">Delete</button>';

            // Show notification
            var notification = document.createElement('div');
            notification.textContent = 'Entry added successfully!';
            notification.style.backgroundColor = 'lightgreen';
            notification.style.padding = '10px';
            notification.style.marginTop = '10px';
            document.body.appendChild(notification);

            // Hide notification after 3 seconds
            setTimeout(function() {
                document.body.removeChild(notification);
            }, 3000);

            // Hide error message if any
            document.getElementById('error-message').style.display = 'none';

            // After adding a new entry, update pagination
            updatePagination();
        }

        function editEntry(button) {
            var row = button.parentNode.parentNode;
            var date = row.getElementsByTagName('td')[0].innerText;
            var reading = row.getElementsByTagName('td')[1].innerText;

            // Populate the form fields for editing
            document.getElementById('reading_date').value = date;
            document.getElementById('meter_reading').value = reading;

            // Remove the current row
            row.parentNode.removeChild(row);
        }

        function deleteEntry(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);

            // After deleting an entry, update pagination
            updatePagination();
        }

        function updatePagination() {
            // Your pagination update code...
        }
    </script>
</body>
</html>
