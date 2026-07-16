<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; }
        .amount { font-size: 24px; font-weight: bold; color: #4F46E5; }
        .footer { text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0">💰 InvoicePing</h2>
    </div>
    <div class="content">
        <p>Halo <strong>{{ $invoice->client_name }}</strong>,</p>
        <p>Saya ingin mengingatkan tentang invoice berikut yang telah dikirim 7 hari lalu:</p>
        
        <div style="background:white; padding:15px; border-radius:8px; margin:15px 0;">
            <p style="margin:5px 0"><strong>Deskripsi:</strong> {{ $invoice->description ?: '-' }}</p>
            <p style="margin:5px 0"><strong>Jatuh Tempo:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>
            <p class="amount">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
        </div>
        
        <p>Jika invoice ini sudah dibayar, abaikan email ini. Terima kasih!</p>
    </div>
    <div class="footer">
        Dikirim oleh InvoicePing &mdash; {{ config('app.name') }}
    </div>
</body>
</html>
