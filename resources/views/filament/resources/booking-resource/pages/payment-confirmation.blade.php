<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
</head>
<body>
    <h1>Payment Successful!</h1>
    <p>Your payment has been processed successfully for the booking.</p>

    <a href="{{ route('bookings.index') }}" class="btn btn-primary">Return to Bookings</a>
</body>
</html>
