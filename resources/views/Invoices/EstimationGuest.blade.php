<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Estimation Form</title>
    <link rel="stylesheet" href="{{ asset('css/EstimationGuest.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Energy Estimation Form</h1>

            <form id="energyEstimationForm" action="{{ route('EstimationGuestForm')}}" method="POST">
                @csrf
                <div class="input-group">
                    <label for="nrOccupants">Number of Occupants:</label>
                    <input type="number" id="nrOccupants" name="nrOccupants" value="1">
                    @error('nrOccupants')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-group">
                    <label for="isHomeAllDay">Is Home All Day:</label>
                    <select id="isHomeAllDay" name="isHomeAllDay">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="heatWithPower">Heat house with electricity:</label>
                    <select id="heatWithPower" name="heatWithPower">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="waterWithPower">Heat water with electricity:</label>
                    <select id="waterWithPower" name="waterWithPower">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="cookWithPower">Cook with electricity:</label>
                    <select id="cookWithPower" name="cookWithPower">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="nrAirCon">Number of air conditioning units:</label>
                    <input type="number" id="nrAirCon" name="nrAirCon" value="0">
                    @error('nrAirCon')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-group">
                    <label for="nrFridges">Number of fridges and freezers:</label>
                    <input type="number" id="nrFridges" name="nrFridges" value="0">
                    @error('nrFridges')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-group">
                    <label for="nrWashers">Number of washers and dryers:</label>
                    <input type="number" id="nrWashers" name="nrWashers" value="0">
                    @error('nrWashers')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-group">
                    <label for="nrComputers">Number of computer devices:</label>
                    <input type="number" id="nrComputers" name="nrComputers" value="0">
                    @error('nrComputers')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-group">
                    <label for="nrEntertainment">Number of entertainment devices:</label>
                    <input type="number" id="nrEntertainment" name="nrEntertainment" value="0">
                    @error('nrEntertainment')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
                <div class="input-group">
                    <label for="nrDishwashers">Number of dishwashers:</label>
                    <input type="number" id="nrDishwashers" name="nrDishwashers" value="0">
                    @error('nrDishwashers')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit">Calculate Energy Estimate</button>
            </form>
        </div>
    </div>

    @isset($totalEstimateYear)
        <script>
            window.scrollTo(0, 1000);
        </script>
        <div class="result-container" id="result-container">
            <h2>Total Energy Estimate for the Year:</h2>
            <p>{{ $totalEstimateYear }} kWh</p>
        </div>
    @endisset
</body>