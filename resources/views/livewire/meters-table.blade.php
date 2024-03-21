<div>
    <div>
        <select wire:model.live="type">
            <option value="">All</option>
            <option value="Electricity">Electricity</option>
            <option value="Gas">Gas</option>
        </select>
        <select wire:model.live="status">
            <option value="">All</option>
            <option value="Installed">Installed</option>
            <option value="In Storage">In Storage</option>
        </select>
    </div>
    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search" style="border: 1px solid black">
    <table class="border-collapse border border-slate-500">
        <tr>
            <td class="border border-slate-600 p-2">ID</td>
            <td class="border border-slate-600 p-2">EAN</td>
            <td class="border border-slate-600 p-2">Type</td>
            <td class="border border-slate-600 p-2">Installation Date</td>
            <td class="border border-slate-600 p-2">Status</td>
            <td class="border border-slate-600 p-2">Assign</td>
            <td class="border border-slate-600 p-2">Edit</td>
            <td class="border border-slate-600 p-2">Delete</td>
        </tr>
        @foreach ($meters as $meter)
            <tr class="">
                <td class="border border-slate-700 p-2">{{$meter->ID}}</td>
                <td class="border border-slate-700 p-2">{{$meter->EAN}}</td>
                <td class="border border-slate-700 p-2">{{$meter->type}}</td>
                <td class="border border-slate-700 p-2">{{$meter->installationDate}}</td>
                <td class="border border-slate-700 p-2">{{$meter->status}}</td>
                <td class="border border-slate-700 p-2"><button class="bg-green-300">Assign</button></td>
                <td class="border border-slate-700 p-2"><button class="bg-orange-300">Edit</button></td>
                <td class="border border-slate-700 p-2"><button class="bg-red-300">Delete</button></td>
            </tr>    
        @endforeach
    </table>
    <div>
        <select wire:model.live="perPage">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    {{$meters->links()}}

    
</div>
