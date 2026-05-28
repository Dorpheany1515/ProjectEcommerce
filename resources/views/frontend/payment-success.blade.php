<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Urbanist', sans-serif; }
        .success-card { max-width: 600px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <div class="success-card text-center">
        <div class="text-success mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
        </div>
        
        <h2>Order Placed Successfully!</h2>
        <p class="text-muted">Thank you for your purchase and for supporting our shop.</p>
        <hr>

        <div class="text-start mb-4">
            <h5>Invoice Information:</h5>
            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
            <p><strong>Customer Name:</strong> {{ $order->name }}</p>
            <p><strong>Phone Number:</strong> {{ $order->phone }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
            <p><strong>Payment Method:</strong> <span class="badge bg-primary">{{ strtoupper($order->payment_method) }}</span></p>
        </div>

        <a href="{{ url('/') }}" class="btn btn-success w-100">Back to Homepage</a>
    </div>
</div>

</body>
</html>