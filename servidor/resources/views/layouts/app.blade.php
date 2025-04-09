<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Pendências</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold text-green-700">Painel de Pendências</h1>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>
