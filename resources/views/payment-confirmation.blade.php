<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Payment Confirmation</title>  
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">  
</head>  
<body class="bg-gray-900 text-gray-100 flex flex-col justify-center items-center min-h-screen">  
    <h1 class="text-3xl font-bold mb-4">Payment Successful!</h1>  
    <p class="text-gray-300 mb-6">Your payment has been processed successfully for the booking.</p>  

    <a href="{{ \App\Filament\Resources\BookingResource::getUrl('create') }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition duration-300">Return to Bookings</a>  
</body>  
</html>