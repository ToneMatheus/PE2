<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/contractOverview.css" rel="stylesheet" />
    <title>Document</title>
</head>

<body>


<h2>Flexbox Layout:</h2>
<div class="flex-container">
  <div class="rowing">
    <div>1</div>
    <div>2</div>
    <div>3</div>

  </div>
  <div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>
  <div class="flex-item">Item 2</div>
  <div class="flex-item">Item 3</div>
  <div class="flex-item">Item 4</div>
</div>



<div class="container">
    <div class="row">
        <div class="col_1">nr</div>
        <div class="col_2">contract name</div>
        <div class="column">description</div>
        <div class="column"></div>
        <div class="column"></div>

    </div>
    <div class="row">
        <div class="col_1">testing</div>
        <div class="col_2">testing</div>
        <div class="column">trenbelone acetate</div>
    </div>
    <div class="row">
        <div class="col_1">2</div>
        <div class="col_2">product_name</div>
        <div class="column">description</div>
        <div class="column">trenbelone acetate</div>

        
    </div>
    @foreach($contracts as $contract)
    <div class="row">
        <div>{{ $contract->id }}</div>
        <div>{{ $contract->customer_contract_id }}</div>
        
        <div>{{ $contract->product->product_name}}</div>
        <div>{{ $contract->product->type}}</div>
        <div>{{ $contract->product->description}}</div>
    </div>
    @endforeach

    <div class="row">test</div>
    <div class="row">tes</div>
    

</div>

</body>

</html>




@foreach($contracts as $contract)
        <tr>
            <td>{{ $contract->id }}</td>
            <td>{{ $contract->customer_contract_id }}</td>
            
            <td>{{ $contract->product->product_name}}</td>
            <td>{{ $contract->product->type}}</td>
            <td>{{ $contract->product->description}}</td>
        </tr>
        @endforeach