<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: auto; padding: 20px; }
        .header { background-color: #f4f4f4; padding: 10px; text-align: center; }
        .order-details { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .footer { text-align: center; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Order Received</h1>
        </div>
        <p>Hello Admin,</p>
        <p>A new order has been placed. Below are the details of the order:</p>

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
                <tr>
                    <th>Payment Type</th>
                    <td>{{ $order->payment_type }}</td>
                </tr>
                <tr>
                    <th>Order Status</th>
                    <td>{{ $order->order_status }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p><a href="{{ url('/admin/orders/' . $order->id) }}">View Order in Admin Panel</a></p>
        </div>
    </div>
</body>
</html>
