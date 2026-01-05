<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $sale->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            color: #333;
            background: #f9f9f9;
            padding: 40px 20px;
        }
        .invoice {
            max-width: 850px;
            margin: 0 auto;
            background: white;
            padding: 50px 60px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        
        /* HEADER */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        .logo-section {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: #5a6c7d;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
        }
        .business-info p {
            font-size: 12px;
            line-height: 1.6;
            color: #666;
        }
        .business-info .name {
            font-weight: 700;
            color: #333;
            font-size: 13px;
            margin-bottom: 4px;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info p {
            font-size: 12px;
            line-height: 1.6;
            color: #666;
        }
        .invoice-info .label {
            font-weight: 700;
            color: #333;
        }
        
        /* SEPARATOR LINE */
        .separator {
            height: 3px;
            background: #5a6c7d;
            margin: 25px 0 35px 0;
        }
        
        /* TITLE */
        .main-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 13px;
            color: #666;
            margin-bottom: 30px;
        }
        
        /* TWO COLUMNS - MÁS COMPACTO */
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 35px;
        }
        .column {
            padding: 15px 18px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #5a6c7d;
        }
        .column h3 {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #5a6c7d;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .column p {
            font-size: 11.5px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 2px;
        }
        .column p strong {
            color: #333;
        }
        
        /* TABLE - MEJORADA */
        .items-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        .items-table thead {
            background: #5a6c7d;
        }
        .items-table th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: left;
            padding: 15px 18px;
            color: white;
            letter-spacing: 0.5px;
        }
        .items-table th.text-center {
            text-align: center;
        }
        .items-table th.text-right {
            text-align: right;
        }
        .items-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.2s;
        }
        .items-table tbody tr:last-child {
            border-bottom: none;
        }
        .items-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .items-table td {
            padding: 18px;
            font-size: 12px;
            color: #333;
            vertical-align: top;
        }
        .items-table td.text-center {
            text-align: center;
        }
        .items-table td.text-right {
            text-align: right;
        }
        .item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }
        .item-desc {
            font-size: 11px;
            color: #999;
            font-style: italic;
        }
        
        /* TOTALS - MEJORADO */
        .totals {
            margin-left: auto;
            width: 320px;
            margin-top: 20px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 13px;
        }
        .total-row.border-top {
            border-top: 1px solid #ddd;
            margin-top: 10px;
            padding-top: 15px;
        }
        .total-row.final {
            font-weight: 700;
            font-size: 18px;
            color: white;
            background: #5a6c7d;
            border-radius: 6px;
            padding: 15px 20px;
            margin: 15px -20px -20px -20px;
        }
        .total-label {
            color: #666;
        }
        .total-row.final .total-label {
            color: white;
        }
        .total-amount {
            color: #333;
            font-weight: 600;
        }
        .total-row.final .total-amount {
            color: white;
        }
        
        /* FOOTER */
        .footer {
            margin-top: 60px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }
        .footer p {
            font-size: 11px;
            color: #999;
            line-height: 1.6;
        }
        .footer .page-number {
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <!-- HEADER -->
        <div class="top-header">
            <div class="logo-section">
                <div class="logo">
                    {{ strtoupper(substr($businessName, 0, 1)) }}
                </div>
                <div class="business-info">
                    <p class="name">{{ $businessName }}</p>
                    <p>{{ $businessAddress }}</p>
                    <p>{{ $businessPhone }}</p>
                    <p>{{ $businessEmail }}</p>
                </div>
            </div>
            <div class="invoice-info">
                <p><span class="label">Factura# </span>{{ $sale->invoice_number }}</p>
                <p><span class="label">Fecha de emisión</span></p>
                <p>{{ $sale->sale_date->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- SEPARATOR -->
        <div class="separator"></div>

        <!-- TITLE -->
        <h1 class="main-title">{{ $businessName }}</h1>
        <p class="subtitle">Gracias por su compra. A continuación el detalle de su factura.</p>

        <!-- TWO COLUMNS -->
        <div class="two-columns">
            <div class="column">
                <h3>Facturar a</h3>
                <p><strong>{{ $sale->customer_name }}</strong></p>
                @if($sale->customer_email)
                    <p>{{ $sale->customer_email }}</p>
                @endif
                @if(isset($sale->customer_phone))
                    <p>{{ $sale->customer_phone }}</p>
                @endif
                @if(isset($sale->customer_address))
                    <p>{{ $sale->customer_address }}</p>
                @endif
            </div>
            <div class="column">
                <h3>Detalles de venta</h3>
                <p>Fecha: {{ $sale->sale_date->format('d/m/Y') }}</p>
                <p>Emitida: {{ $sale->created_at->format('d/m/Y') }}</p>
                @if(isset($sale->payment_method))
                    <p>Método de pago: {{ $sale->payment_method }}</p>
                @endif
                @if(isset($sale->due_date))
                    <p>Vencimiento: {{ $sale->due_date->format('d/m/Y') }}</p>
                @endif
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Artículos</th>
                    <th style="width: 15%;" class="text-center">Cantidad</th>
                    <th style="width: 20%;" class="text-right">Precio Unitario</th>
                    <th style="width: 25%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product->name ?? 'Producto sin nombre' }}</div>
                            @if(isset($item->description))
                                <div class="item-desc">{{ $item->description }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->unit_price, 2, '.', ',') }}</td>
                        <td class="text-right"><strong>${{ number_format($item->quantity * $item->unit_price, 2, '.', ',') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTALS -->
        <div class="totals">
            <div class="total-row">
                <span class="total-label">Subtotal</span>
                <span class="total-amount">${{ number_format($sale->subtotal, 2, '.', ',') }}</span>
            </div>
            
            @if($sale->discount > 0)
                <div class="total-row">
                    <span class="total-label">Descuento</span>
                    <span class="total-amount" style="color: #dc3545;">-${{ number_format($sale->discount, 2, '.', ',') }}</span>
                </div>
            @endif
            
            @if(isset($sale->tax) && $sale->tax > 0)
                <div class="total-row">
                    <span class="total-label">Impuesto</span>
                    <span class="total-amount">${{ number_format($sale->tax, 2, '.', ',') }}</span>
                </div>
            @endif
            
            <div class="total-row final">
                <span class="total-label">Total a pagar</span>
                <span class="total-amount">${{ number_format($sale->total, 2, '.', ',') }}</span>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            @if(isset($sale->notes) && $sale->notes)
                <p><strong>Nota:</strong> {{ $sale->notes }}</p>
            @endif
            <p>Esta factura es válida como comprobante de pago.</p>
            <p>Para consultas, contacte a: {{ $businessEmail }} | {{ $businessPhone }}</p>
            <p class="page-number">Página 1</p>
        </div>
    </div>
</body>
</html>
