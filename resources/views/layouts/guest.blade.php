<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Monitoring Personnel | PLN UID Lampung</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandLight: '#E9F1FA',
                        brandBright: '#00ABE4',
                        brandDeep: '#075985',
                    },
                    fontFamily: { 
                        sans: ['Plus Jakarta Sans', 'sans-serif'] 
                    },
                }
            }
        }
    </script>

    <style>
        /* Perbaikan input agar transisi focus lebih halus */
        input { transition: all 0.3s ease; }
        
        /* Hilangkan ring default browser */
        input:focus { outline: none; }
    </style>
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-screen w-full flex flex-col">
        {{ $slot }}
    </div>
</body>

</html>