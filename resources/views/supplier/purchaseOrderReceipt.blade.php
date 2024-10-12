<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        h2 {
            text-align: center;
            text-decoration: underline;
        }

        .purchase-details {
            margin-top: 20px;
        }

        .purchase-details p {
            margin: 5px 0;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-table, .order-table th, .order-table td {
            border: 1px solid #000;
        }

        .order-table th, .order-table td {
            padding: 8px;
            text-align: center;
        }

        .prepared-by {
            margin-top: 20px;
        }

        .prepared-by p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Purchase Order (PO)</h2>
    <div class="purchase-details">
        <p><strong>Purchase Order Number:</strong> {{$purchaseOrder->purchase_number}}</p>
        <p><strong>Date:</strong> {{now()->format('F d, Y')}}</p>
        <p><strong>Supplier:</strong> {{$purchaseOrder->supplier->CompanyName}}</p>
        <p><strong>Address:</strong> Gensan Drive, Koronadal City</p>
    </div>

    <table class="order-table">
        <thead>
            <tr>
                <th>Item Number</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>

            @php
                $totalAmount = 0;
            @endphp
            @foreach($purchaseOrder->items as $item)
            <tr>
                <td>{{ $item->item->itemID}}</td>
                <td>{{ $item->item->itemName }}</td>
                <td>{{ $item->quantity }}</td>
                <td>P{{ number_format($item->item->unitPrice, 2) }}</td>
                <td>P{{ number_format($item->quantity * $item->item->unitPrice, 2) }}</td>

                @php
                    $totalAmount += $item->quantity * $item->item->unitPrice;
                @endphp

            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Total Amount</strong></td>
                <td>P{{number_format($totalAmount, 2)}}</td>
            </tr>
        </tfoot>
    </table>

    <div class="prepared-by">

        @php

        $currentDate = now()->format('F d, Y'); // Format the current date as 'Month Day, Year'
    @endphp

    <p><strong>Prepared By:</strong>{{ $employee}}</p>
    <p><strong>Date:</strong> {{ $currentDate }}</p>

    </div>
</div>

</body>
</html>
