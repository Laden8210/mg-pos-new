<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            /* Center the entire receipt */
        }

        .receipt-container {
            max-width: 58mm;
            /* Adjust for thermal printer width (58mm) */
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        th,
        td {
            padding: 4px;
            text-align: left;
            border: none;
        }

        th {
            background-color: #f0f0f0;
        }

        td {
            text-align: right;
        }

        td:first-child {
            text-align: left;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            /* Center the logo and company name */
            align-items: center;
            margin-bottom: 10px;
        }

        .logo-container img {
            width: 50px;
            /* Adjust logo size */
            margin-right: 10px;
        }

        .total-container {
            display: flex;
            justify-content: space-between;
            padding: 0 5px;
        }

        .total-container h3,
        .total-container h4 {
            margin: 0;
        }

        h1,
        h3,
        h4 {
            margin: 5px 0;
        }

        h2 {
            margin: 0;
        }

        hr {
            border: 1px solid black;
        }
    </style>
</head>

<body>

    <div class="receipt-container">
        <div>
            <p style="font-size: 14px; font-weight: bold; margin: 0;">MG MINI MART</p>
            <p style="font-size: 12px; margin: 0;">Barangay Tiningcop, Tantangan</p>
            <p style="font-size: 12px; margin: 0;">South Cotabato</p>
            <p style="font-size: 12px; margin: 0;">
                {{ Auth::user()->role }}:
                {{ Auth::user()->firstname }}
                {{ Auth::user()->middle ? Auth::user()->middle . ' ' : '' }}
                {{ Auth::user()->lastname }}
            </p>
        </div>

        <h3 style="text-align: center;">Official Receipt</h3>
        <p style="text-align: center;">{{ now()->format('d/m/Y h:i A') }}</p>
        <hr>

        <!-- Table for items -->
        <table>
            <thead>
                <tr>
                    <th style="text-align: left;">Item Name</th>
                    <th style="text-align: left;">Price</th>
                    <th style="text-align: left;">Quantity</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($items as $item)
                    <tr>
                        <td>{{ str_replace('+', ' ', $item['name']) }}</td>
                        <td>P {{ number_format($item['price'], 2) }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>P {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>

        <div class="total-container" style="display: flex; justify-content: space-between;">
            <h4 style="margin: 0;">SubTotal</h4>
            <h2 style="margin: 0; text-align: right;">P {{ number_format($subtotal, 2) }}</h2>
        </div>
        <hr>

        <div class="total-container" style="display: flex; justify-content: space-between;">
            <h3 style="margin: 0;">Total</h3>
            <h2 style="margin: 0; text-align: right;">P {{ number_format($total, 2) }}</h2>
        </div>
        <hr>

        <div class="total-container" style="display: flex; justify-content: space-between;">
            <h4 style="margin: 0;">Discount</h4>
            <h4 style="margin: 0; text-align: right;">P {{ number_format($discount ?? 0, 2) }}</h4>
        </div>

        <div class="total-container" style="display: flex; justify-content: space-between;">
            <h4 style="margin: 0;">Cash</h4>
            <h4 style="margin: 0; text-align: right;">P {{ number_format($amountTendered, 2) }}</h4>
        </div>

        
        <div class="total-container" style="display: flex; justify-content: space-between;">
            <h4 style="margin: 0;">Change</h4>
            <h4 style="margin: 0; text-align: right;">P {{ number_format($change, 2) }}</h4>
        </div>
        <hr>

        <!-- Thank you message -->
        <h1 style="text-align: center;">Thank You</h1>
        <hr>
    </div>

</body>

</html>