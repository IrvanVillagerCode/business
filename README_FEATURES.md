# 📱 ZMart E-Commerce Platform - Dokumentasi Lengkap

## 🎯 Fitur yang Telah Ditambahkan

### 1. **Halaman Home Page Dinamis** (`home.php`)

- Desain modern terinspirasi dari Walmart.com
- Header dengan search bar dan navigasi user
- Navbar kategori produk yang dinamis
- Banner promosi menarik
- Promo cards (pengiriman, harga, keamanan)
- Grid produk dinamis dengan filter kategori
- Responsive design untuk mobile dan desktop
- Integrasi dengan database untuk menampilkan produk real-time
- Footer informatif

**Fitur JavaScript:**

- Search produk secara real-time
- Filter kategori produk
- Tambah ke keranjang tanpa refresh
- Update cart count dinamis

### 2. **Dashboard Admin Baru** (`dashboard_admin.php`)

- Desain modern dengan sidebar navigasi
- Statistik dashboard:
  - Total pesanan
  - Total pengguna
  - Total produk
  - Total pendapatan
- Tabel pesanan terbaru
- Modal untuk edit profil admin
- Upload foto profil
- Edit data pribadi (email, telepon, alamat, password)

**Fitur:**

- Sidebar navigasi lengkap
- Statistik real-time dari database
- Avatar pengguna dinamis
- Edit profil dengan upload foto

### 3. **Dashboard User Baru** (`dashboard_user.php`)

- Desain konsisten dengan admin dashboard
- Sidebar navigasi user
- Statistik pengguna:
  - Total pesanan
  - Total pengeluaran
  - Jumlah keranjang
- Daftar pesanan terbaru
- Informasi profil lengkap
- Modal edit profil
- Upload foto profil

**Fitur:**

- Dashboard personal untuk pengguna
- Riwayat pesanan
- Statistik pengeluaran
- Edit profil dengan foto

### 4. **Sistem Edit Profil & Upload Foto** (`update_profile.php`)

- Upload foto profil dengan validasi
- Update email, telepon, alamat
- Update password
- Validasi file gambar (jpg, png, gif, jpeg)
- Response JSON untuk kemudahan handling

**Format Upload:**

- Tipe file: JPG, PNG, GIF, JPEG
- Disimpan di folder: `/uploads/`
- Naming: `timestamp_namafile.ext`

### 5. **Integrasi Database** (`setup_profile.php`)

Script untuk menambahkan kolom profil ke database:

- `email` (VARCHAR 100)
- `no_hp` (VARCHAR 15)
- `alamat` (TEXT)
- `foto_profil` (VARCHAR 255)

### 6. **Helper Functions** (`get_cart_count.php`)

API endpoint untuk mendapatkan jumlah item di keranjang

## 🚀 Cara Menggunakan

### Setup Database

1. Jalankan script setup profil:

```
http://localhost/business/setup_profile.php
```

2. Atau jalankan manual di phpMyAdmin:

```sql
ALTER TABLE `users`
ADD COLUMN `email` VARCHAR(100) DEFAULT NULL,
ADD COLUMN `no_hp` VARCHAR(15) DEFAULT NULL,
ADD COLUMN `alamat` TEXT DEFAULT NULL,
ADD COLUMN `foto_profil` VARCHAR(255) DEFAULT NULL;
```

### Login & Navigasi

1. **Login** di `login.php` dengan:
   - Admin: `admin` / `12345`
   - User: `user1` / `12345`

2. **Dari Home Page:**
   - Klik logo ZMart untuk ke halaman home
   - Cari produk dengan search bar
   - Filter kategori produk
   - Tambah ke keranjang (perlu login)
   - View produk detail

3. **Dari Admin:**
   - Akses ke `/dashboard_admin.php`
   - Lihat statistik & pesanan
   - Edit profil admin (klik "Edit Profil")
   - Upload foto profil

4. **Dari User:**
   - Akses ke `/dashboard_user.php`
   - Lihat riwayat pesanan
   - Lihat statistik pengeluaran
   - Edit profil & upload foto

## 📁 File Struktur

```
business/
├── home.php                    # Halaman home page
├── dashboard_admin.php         # Dashboard admin baru
├── dashboard_user.php          # Dashboard user baru
├── update_profile.php          # API untuk update profil
├── get_cart_count.php          # API untuk hitung keranjang
├── setup_profile.php           # Script setup database
├── add_profile_columns.sql     # SQL untuk tambah kolom
├── login.php                   # Halaman login (sudah ada)
├── logout.php                  # Halaman logout (sudah ada)
├── cart.php                    # Halaman keranjang (sudah ada)
├── orders_user.php             # Riwayat pesanan user (sudah ada)
├── orders_admin.php            # Pesanan admin (sudah ada)
├── order_detail.php            # Detail pesanan user (sudah ada)
├── order_detail_admin.php      # Detail pesanan admin (sudah ada)
├── admin_chat.php              # Chat admin (sudah ada)
├── chat_room.php               # Chat room (sudah ada)
├── detail_produk.php           # Detail produk (sudah ada)
├── tambah_produk.php           # Tambah produk (sudah ada)
├── edit.php                    # Edit produk (sudah ada)
├── add_cart.php                # Tambah keranjang (sudah ada)
├── config.php                  # Konfigurasi DB (sudah ada)
└── uploads/                    # Folder untuk upload gambar

```

## 🎨 Desain & UX

### Warna Tema

- **Primary Blue**: `#0071ce`
- **Secondary Yellow**: `#ffc220`
- **Neutral Gray**: `#f5f5f5`
- **Text Dark**: `#333`

### Responsive Design

- Desktop: Full layout dengan sidebar tetap
- Tablet: Sidebar 200px, grid 2 kolom
- Mobile: Sidebar responsive, grid 1 kolom

## 📱 Login & Test

### Test Akun Admin

```
Username: admin
Password: 12345
```

Akses: `/dashboard_admin.php`

### Test Akun User

```
Username: user1
Password: 12345
```

Akses: `/dashboard_user.php`

## ⚙️ Fitur JavaScript

### Home Page

```javascript
// Search real-time
searchProducts();

// Filter kategori
filterCategory(category);

// Lihat produk
viewProduct(id);

// Tambah keranjang
addToCart(productId);

// Update cart count
updateCartCount();
```

### Dashboard

```javascript
// Modal profil
openEditProfileModal();
closeEditProfileModal();

// Preview gambar
previewImage(input);

// Update profil
updateProfile();
```

## 🔒 Security Notes

1. Password di database masih plaintext (untuk demo)
   - Gunakan bcrypt/password_hash untuk production
2. SQL Injection: Gunakan prepared statements
3. File upload: Validasi file type & size
4. Session: Gunakan secure session settings

## 📝 Next Steps / Improvement

1. **Password Security**

   ```php
   $password = password_hash($password, PASSWORD_BCRYPT);
   ```

2. **Image Optimization**
   - Compress images sebelum upload
   - Resize ke ukuran standar

3. **Error Handling**
   - Tambah try-catch
   - Log error ke file

4. **Performance**
   - Pagination untuk tabel
   - Caching produk populer
   - Image lazy loading

## 📞 Support

Untuk troubleshooting:

1. Jalankan `setup_profile.php` untuk setup database
2. Pastikan folder `uploads` writable
3. Check database connection di `config.php`

---

**Version:** 1.0
**Last Updated:** May 3, 2026
**Created with:** ❤️ by AI Assistant
