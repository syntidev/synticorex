<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error del servidor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        <div class="mx-auto mb-6 inline-flex h-20 w-20 items-center justify-center rounded-full bg-red-100 text-3xl font-extrabold text-red-700">500</div>
        
        <h1 class="text-4xl font-bold text-gray-900 mb-4">500</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Error del servidor</h2>
        
        <p class="text-gray-600 mb-8">
            Algo salió mal al procesar tu solicitud. Por favor intenta más tarde.
        </p>
        
        <div class="space-y-4">
            <a 
                href="/" 
                class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors"
            >
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
