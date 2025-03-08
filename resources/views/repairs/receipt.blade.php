<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Repair Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .receipt-title {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th,
        .items-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #666;
        }
        .total-section {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: right;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        @media print {
            body {
                padding: 0;
            }
            .receipt {
                border: none;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="company-name">VSMART TUNE UP</div>
            <div>Reliable Service, Lasting Results</div>
            <div>Contact: 09956277648</div>
        </div>

        <div class="receipt-title">
            <strong>Receipt #{{ str_pad($repair->id, 6, '0', STR_PAD_LEFT) }}</strong>
            <div>Date: {{ $repair->completed_at ? $repair->completed_at->format('F j, Y') : $repair->created_at->format('F j, Y') }}</div>
        </div>

        <div class="info-section">
            <div class="info-grid">
                <div>
                    <div class="info-item">
                        <div class="label">Customer</div>
                        <div class="value">{{ $repair->items->first()->device->customer->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Contact</div>
                        <div class="value">{{ $repair->items->first()->device->customer->phone }}</div>
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <div class="label">Status</div>
                        <div class="value">{{ ucfirst($repair->status) }}</div>
                    </div>
                    @if($repair->started_at)
                    <div class="info-item">
                        <div class="label">Started</div>
                        <div class="value">{{ $repair->started_at->format('F j, Y') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Service</th>
                    <th>Notes</th>
                    <th style="text-align: right;">Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($repair->items as $item)
                <tr>
                    <td>
                        @if($item->device->deviceModel)
                            {{ $item->device->deviceModel->full_name }}
                        @else
                            {{ $item->device->brand }} {{ $item->device->model }}
                        @endif
                    </td>
                    <td>{{ $item->service->name }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                    <td style="text-align: right;">₱{{ number_format($item->cost, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($repair->notes)
        <div class="info-section">
            <div class="label">Overall Notes</div>
            <div class="value">{{ $repair->notes }}</div>
        </div>
        @endif

        <div class="total-section">
            <div class="total">
                Total Amount: ₱{{ number_format($repair->total_cost, 2) }}
            </div>
        </div>

        <div class="footer">
            <p>Thank you for choosing VSmart Tune Up!</p>
            <p>For questions or concerns, please contact us at 09956277648</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4F46E5; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Print Receipt
        </button>
    </div>
</body>
</html> 