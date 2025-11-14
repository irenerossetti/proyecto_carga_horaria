<!DOCTYPE html>
<html lang="es" class="h-full bg-[#F5F5F5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - FICCT SGA</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Instrument Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            primary: '#881F34',
                            hover: '#6d1829'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans antialiased flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-brand-primary tracking-tight">FICCT SGA</h1>
            <p class="text-gray-500 mt-2 text-lg">Sistema de Gestión Académica</p>
        </div>

        <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Bienvenido de nuevo</h2>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-700">
                    <p class="font-medium">Ups, algo salió mal:</p>
                    <ul class="mt-1.5 list-disc list-inside ml-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-700 font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if (request()->query('reset') === 'success')
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 text-sm text-green-700">
                    <p class="font-medium">✓ Contraseña restablecida exitosamente</p>
                    <p class="mt-1">Ahora puedes iniciar sesión con tu nueva contraseña.</p>
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Correo Institucional
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border-gray-200 focus:bg-white focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition-all outline-none"
                        placeholder="ejemplo@ficct.uagrm.edu.bo">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Contraseña
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-brand-primary hover:text-brand-hover transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border-gray-200 focus:bg-white focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition-all outline-none"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" 
                        class="w-4 h-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary/30">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                        Recordar mi sesión
                    </label>
                </div>

                <button type="submit" 
                    class="w-full flex justify-center items-center py-3.5 px-4 bg-brand-primary hover:bg-brand-hover text-white font-semibold rounded-xl transition-all active:scale-[0.98] shadow-md hover:shadow-lg">
                    Iniciar Sesión
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
        </div>
        
        <p class="text-center text-sm text-gray-400 mt-8">
            © {{ date('Y') }} FICCT UAGRM. Todos los derechos reservados.
        </p>
    </div>

</body>
</html>