<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura POS #{{ str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace, 'Segoe UI', sans-serif;
        }

        body {
            background-color: #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            color: #000;
        }

        /* Barra de acciones superior */
        .actions-bar {
            background: #fff;
            padding: 12px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-print {
            background: #2563eb;
            color: #fff;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        .btn-back {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-back:hover {
            background: #e2e8f0;
        }

        /* Contenedor del Ticket POS (80mm) */
        .ticket {
            width: 320px; /* Ancho estándar de papel térmico 80mm */
            background: #fff;
            padding: 20px 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            font-size: 12px;
            line-height: 1.3;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        .header {
            margin-bottom: 12px;
            text-align: center;
        }

        .header h1 {
            font-size: 16px;
            font-weight: 900;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 11px;
            color: #333;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .info-section {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        /* Tabla de Productos */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 11px;
        }

        .items-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
            font-size: 11px;
        }

        .items-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .totals-section {
            margin-top: 6px;
            font-size: 11px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }

        .totals-row.grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 6px 0;
            margin: 6px 0;
        }

        .footer {
            margin-top: 16px;
            text-align: center;
            font-size: 10px;
        }

        /* Estilos de Impresión */
        @media print {
            body {
                background: none;
                padding: 0;
            }

            .actions-bar {
                display: none !important;
            }

            .ticket {
                box-shadow: none;
                width: 100%;
                max-width: 80mm;
                padding: 0;
                margin: 0;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    {{-- BARRA DE ACCIONES --}}
    <div class="actions-bar">
        <button class="btn-action btn-print" onclick="window.print()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 000-4H9a2 2 0 000 4zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Imprimir / Descargar PDF
        </button>

        <a href="{{ route('ventas.index') }}" class="btn-action btn-back">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al POS
        </a>
    </div>

    {{-- TICKET POS FISICO --}}
    <div class="ticket">

        {{-- MEMBRETE / ENCABEZADO --}}
        <div class="header">
            <h1>SIGI POS</h1>
            <p class="bold">NIT: 900.123.456-1</p>
            <p>CRA. 9 # 7-30 GARZÓN</p>
            <p>TEL: (+57) 3224971380</p>
            <div class="divider"></div>
            <p class="bold uppercase">FACTURA DE VENTA</p>
            <p>RÉGIMEN SIMPLIFICADO</p>
            <p>{{ $venta->fecha_venta ? \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y h:i a') : now()->format('d/m/Y h:i a') }}</p>
        </div>

        {{-- INFORMACIÓN DE VENTA Y CLIENTE --}}
        <div class="info-section">
            <div class="info-row">
                <span>Cliente:</span>
                <span class="bold">Cliente General</span>
            </div>
            <div class="info-row">
                <span>Factura Nro.:</span>
                <span class="bold">#{{ str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span>Vendedor:</span>
                <span class="bold">{{ $venta->usuario->nombre ?? ($venta->usuario->name ?? 'Admin POS') }}</span>
            </div>
        </div>

        <div class="divider"></div>

        {{-- LISTA DE PRODUCTOS --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Artículo</th>
                    <th style="width: 25%; text-align: center;">Precio</th>
                    <th style="width: 10%; text-align: center;">Cant.</th>
                    <th style="width: 15%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $det)
                <tr>
                    <td class="bold">{{ $det->producto->nombre ?? 'Producto #' . $det->id_producto }}</td>
                    <td style="text-align: center;">${{ number_format($det->precioUnitario, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $det->cantidad }}</td>
                    <td style="text-align: right;">${{ number_format($det->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        {{-- TOTALES --}}
        <div class="totals-section">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span>${{ number_format($venta->total, 0, ',', '.') }}</span>
            </div>
            <div class="totals-row">
                <span>IVA (0%):</span>
                <span>$0</span>
            </div>
            <div class="totals-row grand-total">
                <span>TOTAL:</span>
                <span>${{ number_format($venta->total, 0, ',', '.') }}</span>
            </div>
            <div class="totals-row" style="margin-top: 6px;">
                <span>Tipo de Pago:</span>
                <span class="bold uppercase">{{ $venta->metodo->nombre ?? 'Efectivo' }}</span>
            </div>
        </div>

        <div class="divider"></div>

        {{-- PIE DE PÁGINA --}}
        <div class="footer">
            <p class="bold">¡GRACIAS POR SU COMPRA!</p>
            <p style="margin-top: 4px;">Desarrollado por SIGI POS</p>
            <p>www.sigipos.co</p>
        </div>

    </div>

</body>
</html>
