<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: auto; padding: 20px; }
        .header { background-color: #f4f4f4; padding: 10px; text-align: center; }
        .order-details { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; }
        .footer { text-align: center; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thank You for Your Order!</h1>
        </div>
        <p>Hello {{ $order->first_name }},</p>
        <p>Thank you for shopping with us. Your order has been placed successfully. Below are the details of your order:</p>

        <div class="order-details">
            <h2>Order Details</h2>
            <table>
                <tr>
                    <th>Order ID</th>
                    <td>{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Invoice</th>
                    <td>{{ $order->invoice }}</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>If you have any questions, feel free to <a href="mailto:support@example.com">contact us</a>.</p>
            <p><a href="{{ url('/myorders/' . $order->id) }}">View your order</a></p>
        </div>
    </div>
</body>
</html>