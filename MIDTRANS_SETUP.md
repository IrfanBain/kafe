# Setup Midtrans Payment Gateway

## Langkah-langkah Konfigurasi Midtrans

### 1. Dapatkan Credentials Midtrans

1. **Daftar di Midtrans Dashboard**:

    - Sandbox: https://dashboard.sandbox.midtrans.com/
    - Production: https://dashboard.midtrans.com/

2. **Dapatkan Server Key dan Client Key**:
    - Login ke dashboard
    - Pilih menu "Settings" > "Access Keys"
    - Copy Server Key dan Client Key

### 2. Konfigurasi Environment Variables

Tambahkan ke file `.env`:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY_HERE
MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY_HERE
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 3. Setup Webhook/Callback URL

Di Midtrans Dashboard:

1. Masuk ke "Settings" > "Configuration"
2. Set **Payment Notification URL** ke: `https://yourdomain.com/payment/callback`
3. Set **Finish Redirect URL** ke: `https://yourdomain.com/order/{order_id}/status`
4. Set **Unfinish Redirect URL** ke: `https://yourdomain.com/payment/{order_id}`
5. Set **Error Redirect URL** ke: `https://yourdomain.com/payment/{order_id}/failed`

### 4. Test Payment

1. Jalankan aplikasi Laravel
2. Buat pesanan melalui QR code
3. Setelah konfirmasi pesanan, akan redirect ke halaman pembayaran
4. Klik "Bayar Sekarang" untuk membuka Midtrans Snap
5. Gunakan test cards untuk testing:

#### Test Credit Cards (Sandbox):

-   **Visa**: 4811 1111 1111 1114
-   **Mastercard**: 5264 2210 3887 4659
-   **CVV**: 123
-   **Exp Date**: 01/25

#### Test E-Wallet Numbers:

-   **GoPay**: 081234567890
-   **OVO**: 081234567891

### 5. Flow Pembayaran

1. **Order Creation**: Customer membuat pesanan → status: `pending`, payment_status: `pending`
2. **Payment Page**: Customer diarahkan ke halaman pembayaran
3. **Midtrans Snap**: Customer melakukan pembayaran melalui Midtrans
4. **Callback**: Midtrans mengirim notification ke `/payment/callback`
5. **Status Update**: Order status dan payment_status diupdate berdasarkan response Midtrans
6. **Completion**: Customer kembali ke halaman status pesanan

### 6. Status Mapping

#### Payment Status:

-   `pending` → Pembayaran belum dilakukan
-   `paid` → Pembayaran berhasil
-   `failed` → Pembayaran gagal
-   `expired` → Pembayaran expired
-   `cancelled` → Pembayaran dibatalkan

#### Order Status:

-   `pending` → Pesanan menunggu pembayaran
-   `confirmed` → Pesanan dikonfirmasi (setelah pembayaran)
-   `preparing` → Pesanan sedang disiapkan
-   `ready` → Pesanan siap
-   `completed` → Pesanan selesai

### 7. Testing dengan Postman

Test callback endpoint:

```
POST /payment/callback
Content-Type: application/json

{
    "order_id": "ORDER-20241215001-1734233456",
    "transaction_status": "settlement",
    "payment_type": "bank_transfer",
    "transaction_time": "2024-12-15 10:30:00",
    "gross_amount": "50000"
}
```

### 8. Production Checklist

-   [ ] Ganti credentials ke production
-   [ ] Set `MIDTRANS_IS_PRODUCTION=true`
-   [ ] Update webhook URL di Midtrans Dashboard
-   [ ] Test payment dengan real payment methods
-   [ ] Monitor logs untuk error handling

### 9. Troubleshooting

#### Common Issues:

1. **Snap Token tidak generate**:

    - Check server key dan client key
    - Check koneksi internet
    - Check format transaction data

2. **Callback tidak berfungsi**:

    - Pastikan webhook URL benar
    - Check firewall settings
    - Verify SSL certificate

3. **Payment tidak update**:
    - Check logs di storage/logs/laravel.log
    - Verify order_id format matching
    - Check database transaction records

#### Debug Commands:

```bash
# Check logs
tail -f storage/logs/laravel.log

# Test Midtrans config
php artisan tinker
>>> config('services.midtrans')

# Check orders
php artisan tinker
>>> App\Models\Order::with('transaction')->latest()->first()
```
