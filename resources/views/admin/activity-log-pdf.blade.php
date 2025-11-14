<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora del Sistema</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #333;
        }
        
        .header {
            background-color: #881F34;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10px;
            opacity: 0.9;
        }
        
        .filters {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 3px solid #881F34;
        }
        
        .filters h3 {
            font-size: 11px;
            margin-bottom: 5px;
            color: #881F34;
        }
        
        .filters p {
            font-size: 9px;
            margin: 2px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead {
            background-color: #881F34;
            color: white;
        }
        
        th {
            padding: 8px 4px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        
        td {
            padding: 6px 4px;
            border-bottom: 1px solid #ddd;
            font-size: 8px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        
        .badge-login { background-color: #d1fae5; color: #065f46; }
        .badge-logout { background-color: #e5e7eb; color: #374151; }
        .badge-create { background-color: #dbeafe; color: #1e40af; }
        .badge-update { background-color: #fef3c7; color: #92400e; }
        .badge-delete { background-color: #fee2e2; color: #991b1b; }
        .badge-view { background-color: #e9d5ff; color: #6b21a8; }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 BITÁCORA DEL SISTEMA - FICCT SGA</h1>
        <p>Sistema de Gestión Académica</p>
        <p>Generado el: {{ $generated_at }}</p>
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Filtros Aplicados:</h3>
        @if(!empty($filters['user']))
            <p><strong>Usuario:</strong> {{ $filters['user'] }}</p>
        @endif
        @if(!empty($filters['action']))
            <p><strong>Acción:</strong> {{ ucfirst($filters['action']) }}</p>
        @endif
        @if(!empty($filters['module']))
            <p><strong>Módulo:</strong> {{ ucfirst($filters['module']) }}</p>
        @endif
        @if(!empty($filters['date_from']))
            <p><strong>Desde:</strong> {{ $filters['date_from'] }}</p>
        @endif
        @if(!empty($filters['date_to']))
            <p><strong>Hasta:</strong> {{ $filters['date_to'] }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Fecha</th>
                <th style="width: 6%;">Hora</th>
                <th style="width: 15%;">Usuario</th>
                <th style="width: 8%;">Rol</th>
                <th style="width: 10%;">IP</th>
                <th style="width: 8%;">Acción</th>
                <th style="width: 10%;">Módulo</th>
                <th style="width: 35%;">Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('d/m/Y') }}</td>
                <td>{{ $log->created_at->format('H:i:s') }}</td>
                <td>{{ $log->user_name }}</td>
                <td>{{ $log->user_role }}</td>
                <td style="font-family: monospace;">{{ $log->ip_address }}</td>
                <td>
                    <span class="badge badge-{{ $log->action }}">
                        {{ strtoupper($log->action) }}
                    </span>
                </td>
                <td>{{ ucfirst($log->module) }}</td>
                <td>{{ $log->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>FICCT - Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones</p>
        <p>Sistema de Gestión Académica - Bitácora de Auditoría</p>
        <p>Total de registros: {{ $logs->count() }}</p>
    </div>
</body>
</html>
