<x-app-layout>
    @if (session()->has('success'))
        <div class="flex justify-center align-center">
            {{ session('success') }}
        </div>
    @endif
    <div class="flex justify-center align-center flex-col w-1/2 p-4">
        <a href="/credit-notes/create"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded self-start">Create Credit Note</a>

        <h1 class="font-bold text-lg mt-2">Credit Notes Overview</h1>

        <table class="mt-2">
            <thead>
                <tr>
                    <th class="border border-black-2">Type</th>
                    <th class="border border-black-2">Status</th>
                    <th class="border border-black-2">Description</th>
                    <th class="border border-black-2">Created At</th>
                    <th class="border border-black-2">User</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($creditNotes as $creditNote)
                    <tr>
                        <td class="border border-black-2 text-center">{{ $creditNote->type }}</td>
                        <td class="border border-black-2 text-center">
                        @if ($creditNote->status==1)
                            Not used
                        @elseif ($creditNote->status==0)
                            Used
                        @endif</td>
                        <td class="border border-black-2 text-center">{{ $creditNote->description }}</td>
                        <td class="border border-black-2 text-center">{{ $creditNote->created_at }}</td>
                        <td class="border border-black-2 text-center">{{ $creditNote->user->username }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-app-layout>