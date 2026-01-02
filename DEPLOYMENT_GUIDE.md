# 🚀 Panduan Deploy Kaslyn ke Azure (Tanpa Ngrok)

## 📋 Prerequisites
- Azure Account dengan subscription aktif
- Domain custom (opsional, bisa pakai Azure domain)
- Midtrans Production Account

---

## 1. 🔧 Setup Midtrans Production

### A. Konfigurasi Webhook URL
1. Login ke [Midtrans Dashboard](https://dashboard.midtrans.com/)
2. Pergi ke **Settings** → **Configuration**
3. Pada bagian **Payment Notification URL**, isi:
   ```
   Production: https://your-azure-app-name.azurewebsites.net/midtrans/callback
   Sandbox: https://your-azure-app-name.azurewebsites.net/midtrans/callback
   ```
4. **PENTING**: Pastikan URL dapat diakses dari internet (tidak localhost/ngrok)

### B. Dapatkan Production Keys
1. Di Midtrans Dashboard → **Settings** → **Access Keys**
2. Copy credentials untuk Production environment:
   - **Merchant ID**
   - **Client Key**
   - **Server Key**

---

## 2. ☁️ Deploy ke Azure App Service

### A. Buat Azure App Service
```bash
# Via Azure CLI
az group create --name kaslyn-rg --location southeastasia
az appservice plan create --name kaslyn-plan --resource-group kaslyn-rg --sku B1
az webapp create --name your-app-name --resource-group kaslyn-rg --plan kaslyn-plan --runtime "PHP|8.1"
```

### B. Setup Database
```bash
# Buat Azure Database for MySQL
az mysql server create --name kaslyn-db --resource-group kaslyn-rg \
  --admin-user kaslynadmin --admin-password YourStrongPassword123! \
  --sku-name B_Gen5_1 --version 8.0

# Buat database
az mysql db create --name kaslyn_prod --server-name kaslyn-db --resource-group kaslyn-rg
```

### C. Konfigurasi Environment Variables
Di Azure Portal → App Service → **Configuration** → **Application Settings**:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.azurewebsites.net

DB_CONNECTION=mysql
DB_HOST=kaslyn-db.mysql.database.azure.com
DB_DATABASE=kaslyn_prod
DB_USERNAME=kaslynadmin@kaslyn-db
DB_PASSWORD=YourStrongPassword123!

# Midtrans Production
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_MERCHANT_ID=G627616238
MIDTRANS_CLIENT_KEY=your-production-client-key
MIDTRANS_SERVER_KEY=your-production-server-key

# Google OAuth (jika digunakan)
GOOGLE_CLIENT_ID=your-production-client-id
GOOGLE_CLIENT_SECRET=your-production-secret
```

---

## 3. 🔄 Deploy Aplikasi

### A. Via Git Deployment
```bash
# Setup deployment source
az webapp deployment source config --name your-app-name --resource-group kaslyn-rg \
  --repo-url https://github.com/Naufall152/kaslynproject.git \
  --branch main --manual-integration
```

### B. Via FTP/Local Git
```bash
# Dapatkan FTP credentials
az webapp deployment list-publishing-profiles --name your-app-name --resource-group kaslyn-rg --xml
```

### C. Post-Deployment Steps
```bash
# SSH ke App Service
az webapp ssh --name your-app-name --resource-group kaslyn-rg

# Jalankan migrations
php artisan migrate

# Jalankan seeders jika perlu
php artisan db:seed

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 4. 🧪 Testing Integrasi

### A. Test Webhook
```bash
# Test endpoint dapat diakses
curl -X POST https://your-app-name.azurewebsites.net/midtrans/callback \
  -H "Content-Type: application/json" \
  -d '{"test": "webhook"}'
```

### B. Test Pembayaran
1. Akses aplikasi di Azure URL
2. Lakukan pembayaran test
3. Cek log di Azure App Service → **Log Stream**
4. Verifikasi subscription teraktivasi

---

## 5. 🔒 Security Best Practices

### A. Environment Variables
- ✅ Gunakan Azure Key Vault untuk sensitive data
- ✅ Jangan commit `.env` ke Git
- ✅ Rotate keys secara berkala

### B. Webhook Security
- ✅ Signature validation sudah aktif
- ✅ Rate limiting untuk webhook endpoint
- ✅ Logging untuk monitoring

### C. Database Security
- ✅ Gunakan SSL connection
- ✅ Minimal privileges untuk DB user
- ✅ Regular backup

---

## 6. 📊 Monitoring & Troubleshooting

### A. Enable Application Insights
```bash
az monitor app-insights component create --app your-app-name \
  --location southeastasia --resource-group kaslyn-rg
```

### B. Log Monitoring
- Azure Portal → App Service → **Log Stream**
- Cek Laravel logs: `storage/logs/laravel.log`
- Monitor webhook callbacks di Midtrans dashboard

### C. Common Issues
1. **Webhook tidak sampai**: Cek URL di Midtrans dashboard
2. **Database connection failed**: Verifikasi connection string
3. **File upload failed**: Setup Azure Storage jika perlu

---

## 7. 🔄 Update Deployment

### A. Via Git
```bash
# Push ke GitHub
git add .
git commit -m "Update for production"
git push origin main

# Azure akan auto-deploy dari GitHub
```

### B. Manual Update
```bash
# Via FTP atau Azure CLI
az webapp up --name your-app-name --resource-group kaslyn-rg --src .
```

---

## 📞 Support
Jika ada masalah, cek:
1. Azure App Service logs
2. Laravel application logs
3. Midtrans dashboard notifications
4. Network connectivity ke database

**🎉 Selamat! Aplikasi Kaslyn sudah production-ready di Azure tanpa ngrok!**
