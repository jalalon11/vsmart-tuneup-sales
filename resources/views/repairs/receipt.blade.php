<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Repair Receipt</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #f9f9f9;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 0;
            background-color: white;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }
        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #000;
            background-color: white;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000000;
            letter-spacing: 1px;
        }
        .smart-text {
            color: #e10000;
        }
        .company-tagline {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
            font-style: italic;
        }
        .receipt-title {
            background-color: white;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
        }
        .receipt-number {
            font-size: 16px;
            color: #e10000;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .receipt-date, .payment-method {
            color: #555;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .info-left {
            background-color: white;
            padding: 20px;
        }
        .info-right {
            background-color: #f1f1f1;
            padding: 20px;
        }
        .info-item {
            margin-bottom: 15px;
        }
        .label {
            font-weight: 600;
            color: #000;
            font-size: 12px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .value {
            color: #333;
            font-size: 14px;
        }
        .items-section {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .items-header {
            background-color: #000;
            color: white;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 600;
        }
        .items-header th {
            padding: 10px;
            text-align: left;
        }
        .items-header th:nth-child(1) {
            width: 30%;
        }
        .items-header th:nth-child(2) {
            width: 30%;
        }
        .items-header th:nth-child(3) {
            width: 25%;
        }
        .items-header th:nth-child(4) {
            width: 15%;
        }
        .items-row td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .items-row:nth-child(odd) {
            background-color: #f1f1f1;
        }
        .items-row:nth-child(even) {
            background-color: white;
        }
        .cost-column {
            text-align: right;
        }
        .total-section {
            background-color: #f1f1f1;
            padding: 15px 20px;
            text-align: right;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
            color: #e10000;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #666;
            background-color: white;
        }
        .thanks-message {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #e10000;
        }
        .contact-message {
            font-size: 12px;
            color: #666;
        }
        @media print {
            body {
                padding: 0;
                background-color: white;
            }
            .receipt {
                border: none;
                box-shadow: none;
                max-width: 100%;
            }
            .no-print, .no-print * {
                display: none !important;
            }
            .items-header {
                background-color: #000 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .info-right, .total-section {
                background-color: #f1f1f1 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .items-row:nth-child(odd) {
                background-color: #f1f1f1 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="company-name">V<span class="smart-text">SMART</span> TUNE UP</div>
            <div class="company-tagline">Reliable Service, Lasting Results</div>
            <div>Contact: 09956277648</div>
        </div>

        <div class="receipt-title">
            <div class="receipt-number">Receipt #{{ str_pad($repair->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="receipt-date">Date: {{ $repair->completed_at ? $repair->completed_at->timezone('Asia/Manila')->format('F j, Y g:i A') : $repair->created_at->timezone('Asia/Manila')->format('F j, Y g:i A') }} PHT</div>
            <div class="payment-method">Payment Method: {{ ucfirst($repair->payment_method) }}</div>
        </div>

        <div class="info-section">
            <div class="info-left">
                <div class="info-item">
                    <div class="label">Customer</div>
                    <div class="value">{{ $repair->items->first()->device->customer->name }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Contact</div>
                    <div class="value">{{ $repair->items->first()->device->customer->phone }}</div>
                </div>
            </div>
            <div class="info-right">
                <div class="info-item">
                    <div class="label">Status</div>
                    <div class="value" style="color: 
                        @if($repair->status == 'completed') #006400
                        @elseif($repair->status == 'in_progress') #0066cc
                        @elseif($repair->status == 'pending') #cc9900
                        @else #e10000
                        @endif
                    ">{{ ucfirst($repair->status) }}</div>
                </div>
                @if($repair->started_at)
                <div class="info-item">
                    <div class="label">Service Date</div>
                    <div class="value">{{ $repair->started_at->timezone('Asia/Manila')->format('F j, Y g:i A') }}</div>
                </div>
                @endif
                @if($repair->completed_at)
                <div class="info-item">
                    <div class="label">Completion Date</div>
                    <div class="value">{{ $repair->completed_at->timezone('Asia/Manila')->format('F j, Y g:i A') }}</div>
                </div>
                @endif
            </div>
        </div>

        <table class="items-section">
            <thead>
                <tr class="items-header">
                    <th>Device</th>
                    <th>Service</th>
                    <th>Notes</th>
                    <th class="cost-column">Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($repair->items as $item)
                <tr class="items-row">
                    <td>
                        @if($item->device->deviceModel)
                            {{ $item->device->deviceModel->full_name }}
                        @else
                            {{ $item->device->brand }} {{ $item->device->model }}
                        @endif
                    </td>
                    <td>{{ $item->service->name }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                    <td class="cost-column">₱{{ number_format($item->cost, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($repair->notes)
        <div class="info-left">
            <div class="info-item">
                <div class="label">Overall Notes</div>
                <div class="value">{{ $repair->notes }}</div>
            </div>
        </div>
        @endif

        <div class="total-section">
            <div class="total">
                Total Amount: ₱{{ number_format($repair->total_cost, 2) }}
            </div>
        </div>

        <div class="footer">
            <p class="thanks-message">Thank you for choosing VSMART TUNE UP!</p>
            <p class="contact-message">For questions or concerns, please contact us at 09956277648</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px; display: flex; justify-content: center; gap: 15px; position: relative;">
        <button onclick="window.print()" style="padding: 12px 24px; background: #000; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s ease; border-bottom: 3px solid #e10000; display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print Receipt
        </button>
        <button onclick="saveAsImage()" style="padding: 12px 24px; background: #e10000; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s ease; border-bottom: 3px solid #000; display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Save as Image
        </button>
    </div>

    <script>
        function saveAsImage() {
            // Create a clone of the receipt for capturing
            const receiptOriginal = document.querySelector('.receipt');
            const receiptClone = receiptOriginal.cloneNode(true);
            
            // Apply styles to the clone to make sure it looks the same
            const tempContainer = document.createElement('div');
            tempContainer.appendChild(receiptClone);
            tempContainer.style.position = 'absolute';
            tempContainer.style.left = '-9999px';
            tempContainer.style.top = '-9999px';
            document.body.appendChild(tempContainer);
            
            // Capture the clone
            html2canvas(receiptClone).then(canvas => {
                // Create an image
                const image = canvas.toDataURL('image/png');
                
                // Create a temporary link
                const link = document.createElement('a');
                link.href = image;
                link.download = 'VSMART_Receipt_{{ str_pad($repair->id, 6, '0', STR_PAD_LEFT) }}.png';
                
                // Trigger download
                link.click();
                
                // Clean up
                document.body.removeChild(tempContainer);
            });
        }
    </script>
</body>
</html>