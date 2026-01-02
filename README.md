<<<<<<< HEAD
# 🚀 Kaslyn - Aplikasi Keuangan Pribadi

Aplikasi manajemen keuangan pribadi berbasis Laravel dengan integrasi Midtrans untuk pembayaran subscription.

## ✨ Fitur Utama

- 📊 **Dashboard Keuangan** - Pantau pemasukan, pengeluaran, dan laporan keuangan
- 💳 **Manajemen Transaksi** - Catat pemasukan dan pengeluaran harian
- 📈 **Laporan Lanjutan** - Laporan harian, bulanan, dan tahunan (PRO)
- 📄 **Export PDF/CSV** - Export laporan dalam format PDF dan CSV (PRO)
- 🔐 **Authentication** - Login/register dengan Google OAuth
- 👑 **Subscription System** - Sistem langganan dengan Midtrans
- 👨‍💼 **Admin Panel** - Panel admin untuk manajemen user dan transaksi
- 📱 **Responsive Design** - UI modern dengan Tailwind CSS

## 🛠️ Tech Stack

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **Payment**: Midtrans Payment Gateway
- **Authentication**: Laravel Sanctum + Google OAuth
- **File Storage**: Local + Azure Storage (optional)

## 🚀 Quick Start

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+
- Node.js 16+ (untuk frontend assets)

### Installation

1. **Clone Repository**
   ```bash
   git clone https://github.com/Naufall152/kaslynproject.git
   cd kaslynproject
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.development .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   # Buat database MySQL
   # Update .env dengan database credentials

   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

Aplikasi akan berjalan di `http://localhost:8000`

## 🔧 Configuration

### Midtrans Setup
1. Daftar akun [Midtrans](https://midtrans.com/)
2. Dapatkan credentials (Merchant ID, Client Key, Server Key)
3. Update `.env` file:
   ```env
   MIDTRANS_IS_PRODUCTION=false
   MIDTRANS_MERCHANT_ID=your-merchant-id
   MIDTRANS_CLIENT_KEY=your-client-key
   MIDTRANS_SERVER_KEY=your-server-key
   ```

### Google OAuth Setup
1. Buat project di [Google Cloud Console](https://console.cloud.google.com/)
2. Enable Google+ API
3. Buat OAuth 2.0 credentials
4. Update `.env`:
   ```env
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   ```

## 🌐 Production Deployment

### Azure App Service Deployment
Lihat panduan lengkap di [`DEPLOYMENT_GUIDE.md`](./DEPLOYMENT_GUIDE.md)

### Quick Azure Deploy
```bash
# Setup Azure resources
az group create --name kaslyn-rg --location southeastasia
az appservice plan create --name kaslyn-plan --resource-group kaslyn-rg --sku B1
az webapp create --name your-app-name --resource-group kaslyn-rg --plan kaslyn-plan --runtime "PHP|8.1"

# Deploy
az webapp up --name your-app-name --resource-group kaslyn-rg --src .
```

## 📊 API Endpoints

### Authentication
- `POST /login` - Login user
- `POST /register` - Register user
- `POST /logout` - Logout user

### Transactions
- `GET /transactions` - List transactions
- `POST /transactions` - Create transaction
- `PUT /transactions/{id}` - Update transaction
- `DELETE /transactions/{id}` - Delete transaction

### Reports (PRO Only)
- `GET /reports/daily` - Daily report
- `GET /reports/profit-loss` - Profit/Loss report
- `GET /reports/yearly` - Yearly report
- `GET /reports/export/csv` - Export CSV
- `GET /reports/export/pdf` - Export PDF

### Payments
- `POST /midtrans/pay` - Create payment
- `POST /midtrans/check` - Check payment status
- `POST /midtrans/callback` - Webhook callback

### Admin (Admin Only)
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - Manage users
- `GET /admin/transactions` - View all transactions

## 🔒 Security Features

- **Webhook Signature Validation** - Validasi signature untuk Midtrans webhook
- **Rate Limiting** - Rate limiting untuk API endpoints
- **CSRF Protection** - Laravel CSRF protection
- **SQL Injection Prevention** - Eloquent ORM protection
- **XSS Prevention** - Blade template escaping

## 📈 Performance

- **Database Indexing** - Optimized database queries
- **Caching** - Laravel caching untuk performance
- **Asset Optimization** - Vite untuk asset bundling
- **Lazy Loading** - Eager loading untuk relationships

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter TestName
```

## 📝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

- **Ahmad Naufallian To** - *Initial work* - [Naufall152](https://github.com/Naufall152)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The PHP Framework
- [Midtrans](https://midtrans.com/) - Payment Gateway
- [Tailwind CSS](https://tailwindcss.com/) - CSS Framework
- [Alpine.js](https://alpinejs.dev/) - JavaScript Framework

---

**🎉 Happy Coding with Kaslyn!**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# Kaslyn_Project-kelompok-KA
>>>>>>> 8964ce47a3a151b2431eaa17ceddb5eae5fb2ca6
