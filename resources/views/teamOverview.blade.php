{{-- 
    TODO
    - click a team and get all info
    - be able to change team info:
        - add/remove members
        - replace manager
        - ...
 --}}

<!DOCTYPE html>
<html>
    <head>
        <link href="/css/roles.css" rel="stylesheet" type="text/css"/>
        <title>Team Names</title>
    </head>
    <body>
        <h1>Team Names</h1>
        <table>
            <thead>
                <tr>
                    <th>Team Name</th>
                    <th>Manager</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $team)
                    <tr>
                        <td>{{ $team->teamName }}</td>
                        <td>{{ $team->first_name }} {{ $team->last_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <select>
            @foreach($allTeams as $team)
                <option value="{{ $team->id }}">{{ $team->team_name }}</option>
            @endforeach
        </select>
        <input type="text" id="managerTextbox" disabled>

        {{-- <p>
            <button id="unlockButton">Unlock</button>
            <form id="addTeamForm">
                <input type="text" name="team_name" placeholder="Enter team name" required>
                <button type="submit">Add Team</button>
            </form>
        </p> --}}

        {{-- <script>
            document.getElementById('addTeamForm').addEventListener('submit', function(e) {
                e.preventDefault();
            
                var teamName = this.elements['team_name'].value;
            
                fetch('/add-team',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                    },
                    body: JSON.stringify({ team_name: teamName })
                })
                .then(response => response.json())
                .then(data =>
                {
                    if(data.success)
                    {
                        window.location.reload();
                    }
                    else
                    {
                        alert('Failed to add the team');
                    }
                });
            });
        </script> --}}
            


        {{-- <script>
            var teamManagers = @json($teamManagers);
    
            document.getElementById('teamSelect').addEventListener('change', function() {
                var selectedTeamId = this.value;
                var managerName = teamManagers[selectedTeamId] || 'No manager';
                document.getElementById('managerTextbox').value = managerName;
            });
        </script> --}}
    </body>
</html>