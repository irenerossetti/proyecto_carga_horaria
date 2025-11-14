<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Asistencia con QR - FICCT SGA</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Instrument Sans', 'sans-serif'] },
                    colors: { brand: { primary: '#881F34', hover: '#6d1829' } }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50">
<div class="flex min-h-screen">
    @include('layouts.admin-sidebar')

    <main class="flex-1 ml-64 p-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Registro de Asistencia con QR</h1>
                <p class="text-gray-500 mt-1">Escanea el código QR para registrar tu asistencia</p>
            </div>
            <a href="{{ route('attendance.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                ← Volver a Asistencia
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- QR Scanner -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Escanear Código QR</h2>
                
                <!-- Scanner Container -->
                <div id="qr-reader" class="w-full rounded-lg overflow-hidden border-2 border-gray-300 mb-4"></div>
                
                <!-- Scanner Controls -->
                <div class="flex gap-3 mb-4">
                    <button id="startScanBtn" onclick="startScanner()" class="flex-1 px-4 py-3 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                        📷 Iniciar Escáner
                    </button>
                    <button id="stopScanBtn" onclick="stopScanner()" class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors hidden">
                        ⏹️ Detener Escáner
                    </button>
                </div>
                
                <!-- Camera Selection -->
                <div id="cameraSelection" class="hidden mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Cámara</label>
                    <select id="cameraSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Cargando cámaras...</option>
                    </select>
                </div>
                
                <!-- Scanner Status -->
                <div id="scannerStatus" class="p-4 bg-gray-50 rounded-lg text-center text-gray-600">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <p class="text-sm">Presiona "Iniciar Escáner" para comenzar</p>
                </div>
                
                <!-- Instructions -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-medium text-blue-900 mb-2">Instrucciones:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Permite el acceso a la cámara cuando se solicite</li>
                        <li>• Coloca el código QR frente a la cámara</li>
                        <li>• Mantén el código dentro del marco</li>
                        <li>• El registro se hará automáticamente</li>
                    </ul>
                </div>
            </div>

            <!-- Scan Results & History -->
            <div class="space-y-6">
                <!-- Last Scan Result -->
                <div id="scanResult" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Último Escaneo</h2>
                    <div id="scanResultContent"></div>
                </div>

                <!-- Recent Scans -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Escaneos Recientes</h2>
                    <div id="recentScans" class="space-y-3">
                        <p class="text-center text-gray-500 py-8">No hay escaneos recientes</p>
                    </div>
                </div>

                <!-- QR Code Generator -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Generar Código QR</h2>
                    <p class="text-sm text-gray-600 mb-4">Genera un código QR para una clase específica</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Horario</label>
                            <select id="scheduleSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                <option value="">Seleccionar horario...</option>
                            </select>
                        </div>
                        
                        <button onclick="generateQR()" class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                            🔲 Generar Código QR
                        </button>
                        
                        <div id="qrCodeDisplay" class="hidden text-center p-4 bg-gray-50 rounded-lg">
                            <div id="qrCodeImage" class="mb-4"></div>
                            <button onclick="downloadQR()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                📥 Descargar QR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
let html5QrCode = null;
let recentScans = [];

function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border ${alertClass} z-50 shadow-lg`;
    notification.innerHTML = `<span class="font-medium">${message}</span>`;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

async function startScanner() {
    try {
        html5QrCode = new Html5Qrcode("qr-reader");
        
        // Get cameras
        const cameras = await Html5Qrcode.getCameras();
        
        if (cameras && cameras.length > 0) {
            // Populate camera select
            const cameraSelect = document.getElementById('cameraSelect');
            cameraSelect.innerHTML = cameras.map((camera, index) => 
                `<option value="${camera.id}">${camera.label || `Cámara ${index + 1}`}</option>`
            ).join('');
            
            document.getElementById('cameraSelection').classList.remove('hidden');
            
            // Start with first camera
            const cameraId = cameras[0].id;
            
            await html5QrCode.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                onScanSuccess,
                onScanFailure
            );
            
            document.getElementById('startScanBtn').classList.add('hidden');
            document.getElementById('stopScanBtn').classList.remove('hidden');
            document.getElementById('scannerStatus').innerHTML = `
                <div class="animate-pulse">
                    <svg class="w-12 h-12 mx-auto mb-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-green-600 font-medium">Escáner activo - Esperando código QR...</p>
                </div>
            `;
        } else {
            throw new Error('No se encontraron cámaras');
        }
    } catch (error) {
        console.error('Error starting scanner:', error);
        showNotification('❌ Error al iniciar el escáner: ' + error.message, 'error');
    }
}

async function stopScanner() {
    if (html5QrCode) {
        try {
            await html5QrCode.stop();
            html5QrCode = null;
            
            document.getElementById('startScanBtn').classList.remove('hidden');
            document.getElementById('stopScanBtn').classList.add('hidden');
            document.getElementById('cameraSelection').classList.add('hidden');
            document.getElementById('scannerStatus').innerHTML = `
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <p class="text-sm">Escáner detenido</p>
            `;
        } catch (error) {
            console.error('Error stopping scanner:', error);
        }
    }
}

async function onScanSuccess(decodedText, decodedResult) {
    console.log(`QR Code detected: ${decodedText}`);
    
    // Stop scanner temporarily
    await stopScanner();
    
    // Process QR code
    await processQRCode(decodedText);
}

function onScanFailure(error) {
    // Silent - scanning continuously
}

async function processQRCode(qrData) {
    try {
        // Parse QR data (assuming format: schedule_id:timestamp)
        const [scheduleId, timestamp] = qrData.split(':');
        
        // Register attendance via API
        const response = await fetch('/api/attendances/qr', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                qr_code: qrData,
                schedule_id: scheduleId
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showNotification('✅ Asistencia registrada exitosamente');
            displayScanResult(result);
            addToRecentScans(result);
        } else {
            throw new Error(result.message || 'Error al registrar asistencia');
        }
    } catch (error) {
        console.error('Error processing QR:', error);
        showNotification('❌ ' + error.message, 'error');
    }
    
    // Restart scanner after 2 seconds
    setTimeout(() => {
        startScanner();
    }, 2000);
}

function displayScanResult(data) {
    const resultDiv = document.getElementById('scanResult');
    const contentDiv = document.getElementById('scanResultContent');
    
    const statusColor = data.status === 'present' ? 'green' : data.status === 'late' ? 'yellow' : 'blue';
    const statusText = data.status === 'present' ? 'Presente' : data.status === 'late' ? 'Tardanza' : 'Registrado';
    
    contentDiv.innerHTML = `
        <div class="space-y-3">
            <div class="flex items-center justify-between p-4 bg-${statusColor}-50 border border-${statusColor}-200 rounded-lg">
                <div>
                    <p class="font-medium text-${statusColor}-900">${statusText}</p>
                    <p class="text-sm text-${statusColor}-700">${new Date().toLocaleString('es-ES')}</p>
                </div>
                <svg class="w-8 h-8 text-${statusColor}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-sm space-y-2">
                <p><strong>Docente:</strong> ${data.teacher_name || 'N/A'}</p>
                <p><strong>Materia:</strong> ${data.subject_name || 'N/A'}</p>
                <p><strong>Grupo:</strong> ${data.group_name || 'N/A'}</p>
                <p><strong>Horario:</strong> ${data.start_time || 'N/A'} - ${data.end_time || 'N/A'}</p>
            </div>
        </div>
    `;
    
    resultDiv.classList.remove('hidden');
}

function addToRecentScans(data) {
    recentScans.unshift({
        ...data,
        timestamp: new Date().toLocaleString('es-ES')
    });
    
    if (recentScans.length > 5) {
        recentScans = recentScans.slice(0, 5);
    }
    
    renderRecentScans();
}

function renderRecentScans() {
    const container = document.getElementById('recentScans');
    
    if (recentScans.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-500 py-8">No hay escaneos recientes</p>';
        return;
    }
    
    container.innerHTML = recentScans.map(scan => {
        const statusColor = scan.status === 'present' ? 'green' : scan.status === 'late' ? 'yellow' : 'blue';
        return `
            <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2 py-1 bg-${statusColor}-100 text-${statusColor}-800 rounded text-xs font-medium">
                        ${scan.status === 'present' ? 'Presente' : scan.status === 'late' ? 'Tardanza' : 'Registrado'}
                    </span>
                    <span class="text-xs text-gray-500">${scan.timestamp}</span>
                </div>
                <p class="text-sm font-medium text-gray-900">${scan.subject_name || 'N/A'}</p>
                <p class="text-xs text-gray-600">${scan.teacher_name || 'N/A'}</p>
            </div>
        `;
    }).join('');
}

// QR Code Generation
async function loadSchedules() {
    try {
        const response = await fetch('/api/schedules', {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            const schedules = await response.json();
            const select = document.getElementById('scheduleSelect');
            
            select.innerHTML = '<option value="">Seleccionar horario...</option>' + 
                schedules.map(s => `
                    <option value="${s.id}">
                        ${s.subject_name} - ${s.group_name} (${s.day} ${s.start_time}-${s.end_time})
                    </option>
                `).join('');
        }
    } catch (error) {
        console.error('Error loading schedules:', error);
    }
}

function generateQR() {
    const scheduleId = document.getElementById('scheduleSelect').value;
    
    if (!scheduleId) {
        showNotification('❌ Selecciona un horario', 'error');
        return;
    }
    
    const qrData = `${scheduleId}:${Date.now()}`;
    const qrDisplay = document.getElementById('qrCodeDisplay');
    const qrImage = document.getElementById('qrCodeImage');
    
    // Clear previous QR
    qrImage.innerHTML = '';
    
    // Generate new QR
    new QRCode(qrImage, {
        text: qrData,
        width: 256,
        height: 256,
        colorDark: "#881F34",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
    
    qrDisplay.classList.remove('hidden');
    showNotification('✅ Código QR generado');
}

function downloadQR() {
    const canvas = document.querySelector('#qrCodeImage canvas');
    if (canvas) {
        const url = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = `qr-asistencia-${Date.now()}.png`;
        link.href = url;
        link.click();
        showNotification('✅ Código QR descargado');
    }
}

// Change camera
document.getElementById('cameraSelect')?.addEventListener('change', async (e) => {
    if (html5QrCode) {
        await stopScanner();
        await startScanner();
    }
});

// Load schedules on page load
loadSchedules();
</script>

</body>
</html>
