<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Reseña — {{ siteName() }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Anton', sans-serif; }
    </style>
</head>
<body class="bg-[#1c1c1c] min-h-screen text-white">
    {{ $slot }}
</body>
</html>
