<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reorder List Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header h1 {
            text-align: left;
            font-size: 20px;
            margin: 0;
        }

        .header p {
            text-align: left;
            font-size: 12px;
            margin: 0;
            color: gray;
        }

        .title-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .report-title {
            font-weight: bold;
            font-size: 14px;
        }

        .report-date {
            font-size: 12px;
            color: gray;
        }

        .table-applicants {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
            /* Decrease table font size to 11 */
        }

        .table-applicants th,
        .table-applicants td {
            text-align: left;
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-applicants th {
            background-color: #f9f9f9;
        }

        .report-info {
            margin-top: 40px;
            font-weight: bold;
        }

        .prepared-by {
            margin: 0;
            font-size: 12px;
            /* Adjust the font size */
            font-weight: bold;
            /* Make it bold */
            color: black;
            /* Optional: you can adjust the color */
            margin-bottom: 10px;
            /* Space below this text */
        }

        .report-info h5 {
            margin: 0;
            font-size: 14px;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .report-info p {
            margin: 5px 0 0 0;
            font-size: 12px;
            font-weight: normal;
            color: gray;
        }

        .total-row {
            font-weight: bold;
            /* Make total row bold */
            background-color: #f9f9f9;
            /* Optional: add background color */
        }

        .currency-sign {
            font-family: DejaVu Sans !important;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <div>
                <h1>MG MINI MART</h1>
                <p>Brgy. Tinongcop, Tantangan, South Cot.</p>
            </div>
        </div>
        <div class="title-container">
            <div class="report-title">REORDER LIST REPORT</div>

        </div>

        <!-- Table with applicants data -->
        <table class="table-applicants">
            <thead>
                <th>ITEM NUMBER</th>
                <th>ITEM NAME</th>
                <th>SUPPLIER NAME</th>
                <th>QTY ON HAND</th>
                <th>REORDER POINT</th>
                <th>ORIGINAL QUANTITY</th>
                <th>LAST ORDER</th>
                <th>UNIT PRICE</th>
                <th>Status</th>
            </thead>
            <tbody>
                @foreach($reorderItems as $item)
                <tr>
                    <td>{{ $item->batch }}</td>

                    <td>{{ $item->item->itemName ?? 'N/A' }}</td> <!-- Access item name -->
                    <td>{{ $item->supplier->CompanyName ?? 'N/A' }}</td> <!-- Access supplier name -->

                    <td>{{ $item->qtyonhand }}</td> <!-- Quantity on hand -->
                    <td>{{ $item->reorder_point }}</td><!-- Reorder point -->
                    <td>{{$item->original_quantity}}</td><!--ORIGINAL QUANTITY HERE-->
                    <td>{{$item->date_received}}</td><!--UNIT PRICE HERE-->
                    <td>{{$item->item->unitPrice}}</td>
                    <td>
                        @php

                            $threshold = $item->reorder_point;
                            $qty = $item->qtyonhand;

                            if($qty <= $threshold){
                                echo "Reorder";
                            }else{
                                echo "Sufficient";
                            }
                        @endphp

                    </td><!--TOTAL COST HERE-->
                </tr>
            @endforeach
            </tbody>

        </table>
        <div class="report-info">
            <p class="prepared-by">Prepared By:</p>
            <h5>{{ Auth::user()->firstname . " " . Auth::user()->middle . " " . Auth::user()->lastname}}</h5>
            <p>{{ Auth::user()->role }}</p>
        </div>
        <div class="printed-info">
            <p style="font-size: 12px; color: gray; margin: 5px 0 0 0;">Printed Date: {{ date('F j, Y') }}</p>
            <p style="font-size: 12px; color: gray; margin: 0;">Printed Time:
                {{ \Carbon\Carbon::now('Asia/Manila')->format('h:i A') }}
            </p>
        </div>

    </div>

</body>

</html>
