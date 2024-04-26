{{-- 
    TODO
    - click a team and get all info
    - be able to change team info:
        - remove members
        - move members
        - replace manager?
        - ...
 --}}

<!DOCTYPE html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="/css/roles.css" rel="stylesheet" type="text/css"/>
        <title>Team Names</title>
        <style>
            .hidden {
                display: none;
            }
            .manager {
                font-weight: bold;
            }
        </style>
        
    </head>
    <body>
        <h1>Team Names</h1>
        <table>
            <thead>
                <tr>
                    <th>Team Name</th>
                    <th>Manager</th>
                    <th>View Members</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teamsWithManagers as $team)
                    <tr>
                        <td>{{ $team->teamName }}</td>
                        <td>{{ $team->first_name }} {{ $team->last_name }}</td>
                        <td>
                            <button class="view-members-btn" data-team-id="{{ $team->teamId }}" data-team-name="{{ $team->teamName }}">View Members</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table id="teamMembers" class="hidden">
            <thead>
                <tr>
                    <th colspan="2">Team: <span id="teamName"></span></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr id="addMemberRow">
                    <td colspan="2">
                        <button id="addMemberBtn">Add Member</button>
                    </td>
                </tr>
            </tfoot>
        </table>

        <script>
                function showTeamMembers(teamId, teamName) {
                    const url = `/teams/members/${teamId}`
                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(members => {
                            const table = document.querySelector('#teamMembers');
                            const teamNameElement = document.querySelector('#teamName');
                            const tableBody = table.querySelector('tbody');

                            teamNameElement.textContent = teamName;

                            tableBody.innerHTML = '';
                            members.forEach((member, index) => {
                                const row = tableBody.insertRow();
                                const cell = row.insertCell(0);
                                cell.textContent = member.first_name + ' ' + member.last_name;
                                //row.insertCell(0).textContent = member.first_name + ' ' + member.last_name;
                                if (index === 0) {
                                    cell.classList.add('manager');
                                }
                            });
                            document.getElementById('teamMembers').classList.remove('hidden');
                        }).catch(error => {
                            console.error('Error: ', error);
                        });
                }

                document.querySelectorAll('.view-members-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        document.querySelectorAll('.view-members-btn').forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');

                        const teamId = this.dataset.teamId;
                        const teamName = this.dataset.teamName;
                        document.getElementById('teamMembers').setAttribute('data-team-id', teamId);
                        document.getElementById('teamMembers').setAttribute('data-team-name', teamName);

                        showTeamMembers(teamId, teamName);

                        const addMemberRow = document.getElementById('addMemberRow');
                        //addMemberRow.classList.add('hidden');
                        addMemberRow.cells[0].innerHTML = '<button id="addMemberBtn">Add Member</button>';
                        addEventListenersForAddMember();
                    });
                });

                function addEventListenersForAddMember() {
                    const addMemberBtn = document.getElementById('addMemberBtn');

                    addMemberBtn.addEventListener('click', function() {
                    const teamId = document.getElementById('teamMembers').getAttribute('data-team-id');
                    const teamName = document.getElementById('teamMembers').getAttribute('data-team-name');

                        const existingSelectBox = document.querySelector('#addMemberRow select');
                        const existingConfirmBtn = document.querySelector('#addMemberRow button:not(#addMemberBtn)');
                        if (existingSelectBox) existingSelectBox.remove();
                        if (existingConfirmBtn) existingConfirmBtn.remove();

                        fetch('/users/not-in-team')
                            .then(response => response.json())
                            .then(users => {
                                const selectBox = document.createElement('select');
                                selectBox.id = 'userSelectBox';
                                selectBox.options.add(new Option('Select user to add', ''));

                                users.forEach(user => {
                                    const option = new Option(user.first_name + ' ' + user.last_name, user.id);
                                    selectBox.options.add(option);
                                });

                                const confirmBtn = document.createElement('button');
                                confirmBtn.textContent = 'Confirm';
                                confirmBtn.id = 'confirmAddMemberBtn';
                                confirmBtn.disabled = true;

                                const addMemberCell = document.getElementById('addMemberRow').cells[0];
                                addMemberCell.appendChild(selectBox);
                                addMemberCell.appendChild(confirmBtn);

                                selectBox.addEventListener('change', function() {
                                    confirmBtn.disabled = this.value === '';
                                });

                                confirmBtn.addEventListener('click', function() {
                                    const userId = selectBox.value;
                                    const teamId = document.getElementById('teamMembers').getAttribute('data-team-id');
                                    const teamName = document.getElementById('teamMembers').getAttribute('data-team-name');

                                    console.log('Adding user:', userId, 'to team', teamId, 'with name', teamName);

                                    fetch('/teams/add-member', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({ userId: userId, teamId: teamId })
                                    })
                                    .then(response => {
                                        console.log(response);
                                        return response.json();
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            showTeamMembers(teamId, teamName);
                                            const addMemberCell = document.getElementById('addMemberRow').cells[0];
                                            addMemberCell.innerHTML  = '<button id="addMemberBtn">Add Member</button>';
                                            addEventListenersForAddMember();
                                        } else {
                                            console.error('Error:', data.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error', error);
                                    });
                                });

                                document.getElementById('addMemberRow').classList.remove('hidden');
                            });
                    });
            }

            document.querySelectorAll('.view-members-btn').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.view-members-btn').forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    const teamId = this.dataset.teamId;
                    const teamName = this.dataset.teamName;
                    showTeamMembers(teamId, teamName);

                    const addMemberRow = document.getElementById("addMemberRow");
                    //addMemberRow.classList.add('hidden');
                    addMemberRow.cells[0].innerHTML = '<button id="addMemberBtn">Add Member</button>';
                    addEventListenersForAddMember(teamId);
                });
            });
            </script>
    </body>
</html>