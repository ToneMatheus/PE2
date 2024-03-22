<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/contractOverview.css" rel="stylesheet" />
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>


<div class="container">
    <div class="row heading">
        <div class="col_1">nr</div>
        <div class="col_2">contract name</div>
        <div class="col_3">description</div>
        <div class="col_4">start date</div>
        <div class="col_5">tarrif</div>
        <div class="col_6">price</div>
        <div class="col_7">type</div>
        <div class="col_8">contract type</div>
        <div class="col_9"></div>
    </div>
    
    <!-- 
    //TODO contract => contract_products;
     -->
    @foreach($contracts as $contract)
    <div class="row">
        <!-- <div class="col_1">{{ $contract->id }}</div> -->
        <div class="col_1">{{ $loop->index + 1 }}</div>
        
        <div class="col_2">{{ $contract->product->product_name}}</div>
        <div class="col_3">{{ $contract->product->description}}</div>
        
        <div class="col_4">{{ $contract->start_date }}</div>
        <div class="col_5">{{ $contract->tarrif_id }}</div>
        <div class="col_6">{{ $contract->customer_contract->price}}</div>

        <div class="col_7">{{ $contract->product->type}}</div>
        
        <div class="col_8">{{ $contract->customer_contract->type }}</div>
        <div class="col_9"><button class="btn" style="width:100%"><i class="fa fa-download"></i> Download</button></div>
    </div>
    @endforeach

</div>
</body>
</html>


<!--

@foreach($contracts as $contract)
    <div class="row">
        <div class="col_1">{{ $contract->id }}</div>
        <div class="col_2">{{ $contract->customer_contract_id }}</div>
        
        <div class="col_3">{{ $contract->product->product_name}}</div>
        <div class="col_4">{{ $contract->product->type}}</div>
        <div class="col_5">{{ $contract->product->description}}</div>
        <div class="col_6">{{ $contract->customer_contract->type}}</div>
    </div>
@endforeach


@foreach($contracts as $contract)
    <div class="row">
        <div class="col_1">{{ $contract->id }}</div>
        <div class="col_2">{{ $contract->customer_contract_id }}</div>
        
        <div>{{ $contract->product->product_name}}</div>
        <div>{{ $contract->product->type}}</div>
        <div>{{ $contract->product->description}}</div>
    </div>
@endforeach


@foreach($contracts as $contract)
        <tr>
            <td>{{ $contract->id }}</td>
            <td>{{ $contract->customer_contract_id }}</td>
            
            <td>{{ $contract->product->product_name}}</td>
            <td>{{ $contract->product->type}}</td>
            <td>{{ $contract->product->description}}</td>
        </tr>
        @endforeach

-->