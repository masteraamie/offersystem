<!DOCTYPE html>
<html>

<head>
    <title>Offer PDF</title>
</head>

<body>
    <p>Hi {{ $offer->customer->name }} {{ $offer->customer->surname }},</p>
    <p>Offer Id - {{ $offer->id }}</p>
    <p>Email: {{ $offer->customer->email }} </p>

    <p>Vehicle Details</p>
    <ul>
        @php
            $total = 0;
        @endphp
        @foreach ($offer->vehicles as $ov)
            <li>{{ $ov->vehicle->model }} - ${{ $ov->vehicle->price }} x {{ $ov->quantity }} =
                ${{ $ov->vehicle->price * $ov->quantity }}</li>
            @php
                $total += $ov->vehicle->price * $ov->quantity;
            @endphp
        @endforeach
    </ul>

    <p><b>Total Amount: ${{ $total }}</b></p>
</body>

</html>
