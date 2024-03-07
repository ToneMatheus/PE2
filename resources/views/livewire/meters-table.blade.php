<div>
    <table border="1px">
        <tr>
            <td>ID</td>
            <td>EAN</td>
            <td>Type</td>
            <td>Installation Date</td>
            <td>Status</td>
        </tr>
        @foreach ($meters as $meter)
            <tr>
                <td>{{$meter->ID}}</td>
                <td>{{$meter->EAN}}</td>
                <td>{{$meter->type}}</td>
                <td>{{$meter->installationDate}}</td>
                <td>{{$meter->status}}</td>
            </tr>    
        @endforeach
    </table>
</div>
