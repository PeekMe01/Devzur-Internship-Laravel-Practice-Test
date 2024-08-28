<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Delivered</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: auto; padding: 20px; }
        .header { background-color: #f4f4f4; padding: 10px; text-align: center; }
        .footer { text-align: center; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Delivered</h1>
        </div>
        <p>Hello {{ $order->first_name }},</p>
        <p>We are pleased to inform you that your order has been delivered. Below are the details of your order:</p>

        <ul>
            <li><strong>Order ID:</strong> {{ $order_id }}</li>
            <li><strong>Invoice:</strong> {{ $order_invoice }}</li>
        </ul>

        <p>Thank you for shopping with us!</p>

        <div class="footer">
            <p><a href="{{ url('/myorders/' . $order_id) }}">View Your Order</a></p>
        </div>
    </div>
</body>
</html>
