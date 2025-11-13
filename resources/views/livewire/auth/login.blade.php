<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FICCT SIS - Iniciar Sesión</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            primary: '#881F34', // El color guindo exacto de la imagen
                            hover: '#6d1829',
                        }
                    },
                    boxShadow: {
                        'aesthetic': '0 2px 40px -12px rgba(0,0,0,0.1)', // Sombra muy suave y difuminada
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Pequeños ajustes finos que Tailwind a veces necesita ayuda */
        body { background-color: #FAFAF9; } /* Un gris/beige apenas perceptible como en la foto */
        .input-aesthetic {
            width: 100%;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid #E5E5E5;
            color: #171717;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s ease;
            background-color: #FFFFFF;
        }
        .input-aesthetic:focus {
            outline: none;
            border-color: #881F34;
            box-shadow: 0 0 0 4px rgba(136, 31, 52, 0.05); /* Anillo de enfoque muy sutil */
        }
        .input-aesthetic::placeholder {
            color: #A3A3A3;
            font-weight: 400;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans antialiased text-[#171717]">
    <div class="w-full max-w-[440px] space-y-10">
        
        <div class="text-center space-y-2">
            <h1 class="text-4xl font-bold tracking-tight text-brand-primary">
                FICCT SIS
            </h1>
            <p class="text-gray-500 font-medium">
                Sistema de Gestión Académica
            </p>
        </div>

        <div class="bg-white py-10 px-8 shadow-aesthetic rounded-2xl border border-gray-100/80">
            <div class="space-y-8">
                
                <div>
                    <h2 class="text-2xl font-semibold text-[#171717]">
                        Iniciar Sesión
                    </h2>
                    </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-[#171717]">
                            Correo Institucional
                        </label>
                        <input 
                            id="email"
                            type="email" 
                            name="email" 
                            class="input-aesthetic"
                            placeholder="estudiante@correo.edu"
                            required 
                            autofocus
                        >
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-[#171717]">
                            Contraseña
                        </label>
                        <div class="relative">
                            <input 
                                id="password"
                                type="password" 
                                name="password" 
                                class="input-aesthetic pr-20" 
                                placeholder="••••••"
                                required
                            >
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm font-medium text-gray-500 hover:text-brand-primary transition-colors">
                                <span id="toggleText">Mostrar</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center">
                            <input 
                                id="remember" 
                                name="remember" 
                                type="checkbox" 
                                class="h-4 w-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary"
                            >
                            <label for="remember" class="ml-2 block text-sm text-gray-600">
                                Recordarme
                            </label>
                        </div>
                        
                        <div class="text-sm">
                            <a href="#" class="font-medium text-brand-primary hover:text-brand-hover">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl text-base font-semibold text-white bg-brand-primary hover:bg-brand-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all duration-200 shadow-sm">
                        Iniciar Sesión
                    </button>
                </form>
            </div>
        </div>
            
        <p class="text-center text-sm text-gray-500">
            © {{ date('Y') }} FICCT UAGRM. Todos los derechos reservados.
        </p>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleText = document.getElementById('toggleText');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleText.textContent = 'Ocultar';
            } else {
                passwordInput.type = 'password';
                toggleText.textContent = 'Mostrar';
            }
        }
    </script>
</body>
</html>