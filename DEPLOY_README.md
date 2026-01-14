# 🚀 Quick Deployment Guide

## Cara Deploy ke bps-batanghari.com

### Metode 1: Otomatis (Recommended)

1. **Upload project ke server**
   ```bash
   # Via SSH
   cd ~
   git clone https://github.com/pandu2406/bps1504-LP_ChatbotWA.git landingpage
   ```

2. **Jalankan script deployment**
   ```bash
   cd ~/landingpage
   chmod +x deploy.sh
   ./deploy.sh
   ```

3. **Edit .env**
   ```bash
   nano ~/landingpage/.env
   ```
   Update database credentials:
   ```env
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

4. **Done!** Akses https://bps-batanghari.com/

---

### Metode 2: Manual

Lihat panduan lengkap di: [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

---

## 📝 Checklist Cepat

- [ ] Upload folder `landingpage` ke `~/landingpage/`
- [ ] Jalankan `./deploy.sh`
- [ ] Edit `.env` dengan database credentials
- [ ] Test website: https://bps-batanghari.com/
- [ ] Login admin: https://bps-batanghari.com/admin/login

---

## 🔑 Default Login

**Super Admin:**
- Email: `superadmin@bps.com`
- Password: `password`

**Admin:**
- Email: `admin@bps.com`
- Password: `password`

⚠️ **PENTING:** Ganti password setelah login pertama!

---

## 🆘 Troubleshooting

### Error 500
```bash
chmod -R 775 ~/landingpage/storage
chmod -R 775 ~/landingpage/bootstrap/cache
php artisan config:clear
```

### Assets tidak muncul
Edit `.env`:
```env
APP_URL=https://bps-batanghari.com
```

### Database error
Cek credentials di `.env` dan pastikan database sudah dibuat di cPanel.

---

## 📞 Need Help?

Lihat dokumentasi lengkap di `DEPLOYMENT_GUIDE.md`
