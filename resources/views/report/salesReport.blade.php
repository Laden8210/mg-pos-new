<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales Report</title>
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
            <div class="report-title">SALES REPORT</div>

            <div>
                <p class="report-date">Date:
                    {{ \Carbon\Carbon::parse($start_date)->format('F j, Y') . ' - ' . \Carbon\Carbon::parse($end_date)->format('F j, Y') }}
                </p>

            </div>
        </div>

        <!-- Table with applicants data -->
        <table class="table-applicants">
            <thead>
                <tr>
                    <th>INVOICE No.</th>
                    <th>Date</th>
                    <th>Item Sold</th>
                    <th>Selling Price</th>

                    <th>No. of Items Sold</th>

                    <th>Total Sales</th>


                </tr>
            </thead>
            <tbody>
                @php
                    $totalItemsSold = 0;
                    $totalRevenue = 0;
                    $topSellingProduct = null;
                    $topSellingProductCount = 0;
                    $productSales = [];

                    // Calculate total items sold, total revenue, and track product sales
                    foreach ($saleTransaction as $sale) {
                        foreach ($sale->transactions as $transaction) {
                            $totalItemsSold += $transaction->quantity;

                            // Track sales by product
                            if (!isset($productSales[$transaction->item->itemName])) {
                                $productSales[$transaction->item->itemName] = 0;
                            }
                            $productSales[$transaction->item->itemName] += $transaction->quantity;

                            // Check if this product is the top-selling product
                            if ($productSales[$transaction->item->itemName] > $topSellingProductCount) {
                                $topSellingProductCount = $productSales[$transaction->item->itemName];
                                $topSellingProduct = $transaction->item->itemName;
                            }
                        }
                    }
                @endphp


                @foreach ($saleTransaction as $sale)
                    <tr>
                        <td>{{ $sale->transaction_number }}</td>
                        <td>{{ $sale->transactions->first()->created_at->timezone('Asia/Manila')->format('F d, Y') }}
                        </td>

                        <td>
                            @foreach ($sale->transactions as $transaction)
                                {{ $transaction->item->itemName }}<br>
                            @endforeach
                        </td>

                        <td>
                            @foreach ($sale->transactions as $transaction)
                                P {{ number_format($transaction->total_sell, 2) }}<br>
                            @endforeach
                        </td>

                        <td>
                            @foreach ($sale->transactions as $transaction)
                                {{ $transaction->quantity }}<br>
                            @endforeach
                        </td>

                        <td>

                                P {{ number_format($sale->total_amount, 2) }}<br>

                        </td>
                    </tr>

                    @php
                        $totalRevenue += $sale->transactions->sum(function ($transaction) {
                            return $transaction->item->sellingPrice * $transaction->quantity;
                        });
                    @endphp
                @endforeach


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right font-weight-bold">Total Sales:</td>

                    <td> <span style="font-family: DejaVu Sans;">&#x20B1;</span>{{ number_format($totalRevenue, 2) }}
                    </td>
                </tr>

                <tr>

                    <td colspan="5" class="text-right font-weight-bold">Total Transaction:</td>
                    <td>{{ $saleTransaction->count() }}</td>

                </tr>

                <tr>
                    <td colspan="5" class="text-right font-weight-bold">Top Selling Product:</td>


                    <td>{{ $topSellingProduct }}</td>
                </tr>
            </tfoot>

        </table>
        <div class="report-info">
            <p class="prepared-by">Prepared By:</p>
            <h5>{{ Auth::user()->firstname . ' ' . Auth::user()->middle . ' ' . Auth::user()->lastname }}</h5>
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
