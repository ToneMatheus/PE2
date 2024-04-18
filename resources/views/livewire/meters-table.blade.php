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
            <td class="border border-slate-600">ID</td>
            <td class="border border-slate-600">EAN</td>
            <td class="border border-slate-600">Type</td>
            <td class="border border-slate-600">Installation Date</td>
            <td class="border border-slate-600">Status</td>
        </tr>
        @foreach ($meters as $meter)
            <tr>
                <td class="border border-slate-700">{{$meter->ID}}</td>
                <td class="border border-slate-700">{{$meter->EAN}}</td>
                <td class="border border-slate-700">{{$meter->type}}</td>
                <td class="border border-slate-700">{{$meter->installationDate}}</td>
                <td class="border border-slate-700">{{$meter->status}}</td>
            </tr>    
        @endforeach
    </table>
    <div>
        <select wire:model.live="perPage">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="5">5</option>
        </select>
    </div>
    {{$meters->links()}}
</div>
