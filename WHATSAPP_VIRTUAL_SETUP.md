# Panduan Konfigurasi WhatsApp Virtual

## Daftar Layanan WhatsApp Virtual/Business API

### 1. **Fonnte** (Rekomendasi untuk Indonesia)
- Website: https://fonnte.com
- Harga: Mulai dari Rp 150.000/bulan
- Fitur: WhatsApp Business API, Auto Reply, Webhook
- Cara Daftar:
  1. Daftar di https://fonnte.com/register
  2. Verifikasi nomor WhatsApp Business
  3. Dapatkan API Token
  4. Gunakan nomor yang diberikan

### 2. **Wablas**
- Website: https://wablas.com
- Harga: Mulai dari Rp 100.000/bulan
- Fitur: WhatsApp Gateway, Multi Device, API
- Cara Daftar:
  1. Daftar di https://wablas.com/register
  2. Pilih paket yang sesuai
  3. Connect WhatsApp device
  4. Dapatkan nomor virtual

### 3. **Twilio** (International)
- Website: https://www.twilio.com/whatsapp
- Harga: Pay-as-you-go (mulai dari $0.005/pesan)
- Fitur: WhatsApp Business API, Global Coverage
- Cara Daftar:
  1. Daftar di https://www.twilio.com/try-twilio
  2. Verifikasi akun
  3. Request WhatsApp sender
  4. Dapatkan nomor virtual

### 4. **Qontak**
- Website: https://qontak.com
- Harga: Custom pricing
- Fitur: WhatsApp Business API, CRM Integration
- Cara Daftar:
  1. Request demo di website
  2. Konsultasi dengan sales
  3. Setup WhatsApp Business API
  4. Dapatkan nomor virtual

## Cara Menggunakan Nomor Virtual

### Langkah 1: Pilih Provider
Pilih salah satu provider di atas sesuai kebutuhan dan budget.

### Langkah 2: Dapatkan Nomor Virtual
Setelah mendaftar, Anda akan mendapatkan:
- Nomor WhatsApp Virtual (format: 628xxxxxxxxxx)
- API Token/Key (untuk integrasi)
- Webhook URL (untuk menerima pesan)

### Langkah 3: Update Konfigurasi

#### A. Update File `.env`
Buka file `.env` di root project dan tambahkan/update:

```env
# WhatsApp Configuration
WHATSAPP_NUMBER=628xxxxxxxxxx  # Ganti dengan nomor virtual Anda
WHATSAPP_MESSAGE="Halo Admin BPS Batang Hari. Saya mengakses melalui Website untuk menggunakan Layanan Chatbot AI Statistik. Saya ingin bertanya mengenai data dan informasi BPS."
```

**Contoh dengan Fonnte:**
```env
WHATSAPP_NUMBER=6281234567890
WHATSAPP_MESSAGE="Halo! Saya ingin bertanya tentang data statistik."
```

#### B. Restart Server
Setelah update `.env`, restart Laravel server:
```bash
php artisan config:clear
php artisan cache:clear
php artisan serve
```

### Langkah 4: Test
1. Buka website di browser
2. Klik tombol "Chatbot Statistik" atau floating WhatsApp button
3. Pastikan redirect ke nomor virtual yang baru

## Integrasi Webhook (Opsional)

Jika ingin menerima dan membalas pesan otomatis, Anda perlu setup webhook:

### 1. Update Route API
File sudah ada di `routes/api.php`:
```php
Route::post('/whatsapp/webhook', [WhatsAppController::class, 'handleWebhook']);
```

### 2. Setup Webhook di Provider

**Untuk Fonnte:**
1. Login ke dashboard Fonnte
2. Masuk ke menu "Webhook"
3. Set URL: `https://yourdomain.com/api/whatsapp/webhook`
4. Save

**Untuk Wablas:**
1. Login ke dashboard Wablas
2. Masuk ke "Settings" > "Webhook"
3. Set URL: `https://yourdomain.com/api/whatsapp/webhook`
4. Save

### 3. Update WhatsAppController
File: `app/Http/Controllers/WhatsAppController.php`

Sudah ada template dasar, tinggal customize sesuai kebutuhan:
```php
public function handleWebhook(Request $request)
{
    $sender = $request->input('sender');
    $message = $request->input('message');
    
    // Query AI Knowledge Base
    $response = $this->queryAI($message);
    
    // Send response via provider API
    // ...
}
```

## Troubleshooting

### Nomor tidak berubah setelah update .env
**Solusi:**
```bash
php artisan config:clear
php artisan cache:clear
```

### Link WhatsApp tidak terbuka
**Solusi:**
- Pastikan format nomor benar (628xxx tanpa + atau -)
- Cek apakah nomor sudah aktif di WhatsApp
- Test dengan browser berbeda

### Webhook tidak menerima pesan
**Solusi:**
- Pastikan URL webhook accessible (https)
- Cek log di `storage/logs/laravel.log`
- Verifikasi webhook URL di dashboard provider

## Rekomendasi

Untuk BPS Batang Hari, saya rekomendasikan:

1. **Fonnte** - Jika budget terbatas dan fokus Indonesia
   - ✅ Harga terjangkau
   - ✅ Support Indonesia
   - ✅ Easy setup

2. **Twilio** - Jika butuh reliability tinggi dan global
   - ✅ Infrastruktur robust
   - ✅ Pay-as-you-go
   - ✅ Dokumentasi lengkap

## Contoh Konfigurasi Lengkap

### Menggunakan Fonnte
```env
# .env
WHATSAPP_NUMBER=6281234567890
WHATSAPP_MESSAGE="Halo Admin BPS! Saya ingin bertanya tentang data statistik."

# Optional: Untuk auto-reply
FONNTE_TOKEN=your_fonnte_token_here
FONNTE_DEVICE=your_device_id
```

### Menggunakan Twilio
```env
# .env
WHATSAPP_NUMBER=14155238886  # Twilio Sandbox number
WHATSAPP_MESSAGE="Hello! I need statistical data information."

# Optional: Untuk auto-reply
TWILIO_SID=your_account_sid
TWILIO_TOKEN=your_auth_token
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
```

## Support

Jika ada pertanyaan atau butuh bantuan setup:
1. Hubungi support provider yang dipilih
2. Baca dokumentasi API provider
3. Check Laravel logs untuk debugging

---

**Catatan Penting:**
- Nomor virtual biasanya memerlukan verifikasi WhatsApp Business
- Beberapa provider memerlukan dokumen perusahaan
- Pastikan comply dengan WhatsApp Business Policy
