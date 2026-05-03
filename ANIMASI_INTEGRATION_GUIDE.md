# Panduan Integrasi Animasi Responsive

## 📁 File yang Telah Dibuat

### CSS Files

1. **css/animations.css** - CSS animasi untuk semua halaman
2. **css/login-animations.css** - CSS khusus animasi login page dengan background bergerak

### JavaScript Files

1. **js/animations.js** - JavaScript untuk scroll-triggered animations dan hover effects
2. **js/login-animations.js** - JavaScript untuk animasi particles dan interaksi login

### PHP Files (Updated)

1. **login.php** - Sudah terintegrasi dengan animasi background dan form animations

---

## 🚀 Cara Mengintegrasikan ke Halaman Lain

### 1. HOME PAGE (home.php)

Tambahkan ke dalam `<head>`:

```html
<link rel="stylesheet" href="css/animations.css" />
```

Tambahkan di akhir `<body>` sebelum `</body>`:

```html
<script src="js/animations.js"></script>
```

### 2. ADMIN DASHBOARD (dashboard_admin.php)

Tambahkan ke dalam `<head>`:

```html
<link rel="stylesheet" href="css/animations.css" />
```

Tambahkan di akhir `<body>` sebelum `</body>`:

```html
<script src="js/animations.js"></script>
```

### 3. USER DASHBOARD (dashboard_user.php)

Tambahkan ke dalam `<head>`:

```html
<link rel="stylesheet" href="css/animations.css" />
```

Tambahkan di akhir `<body>` sebelum `</body>`:

```html
<script src="js/animations.js"></script>
```

### 4. HALAMAN LAINNYA

Untuk halaman lain (payment.php, orders_user.php, detail_produk.php, dll):
Tambahkan ke dalam `<head>`:

```html
<link rel="stylesheet" href="css/animations.css" />
```

Tambahkan di akhir `<body>`:

```html
<script src="js/animations.js"></script>
```

---

## 📊 Fitur Animasi yang Tersedia

### Fade In Effects

- **fadeIn** - Elemen muncul dengan efek fade in
- **slideInLeft** - Elemen masuk dari kiri
- **slideInRight** - Elemen masuk dari kanan
- **slideInTop** - Elemen masuk dari atas
- **slideDown** - Menu dropdown dengan animasi smooth

### Interactive Effects

- **bounce** - Efek bouncing saat hover
- **pulse** - Efek denyut nadi
- **glow** - Efek cahaya bersinar
- **scaleUp** - Elemen membesar saat hover
- **wave** - Efek gelombang

### Scroll-Triggered Animations

- Product cards muncul saat di-scroll ke view
- Stat cards dengan delay animation
- Table rows dengan staggered animation
- Content sections dengan smooth entry

### Hover Effects

- Product card elevation dan shadow
- Button scale effect
- Navigation link styling
- Form input focus glow

---

## 🎨 Cara Menambahkan Class Animasi ke Elemen

### Product Cards

```html
<div class="product-card">
  <!-- Content -->
</div>
```

### Statistics Cards

```html
<div class="stat-card">
  <!-- Content -->
</div>
```

### Content Sections

```html
<div class="content-section">
  <!-- Content -->
</div>
```

### Buttons

```html
<button class="btn-view">View</button>
<button class="btn-cart">Add to Cart</button>
<button class="btn-primary">Primary Action</button>
<button class="btn-secondary">Secondary Action</button>
```

### Form Groups

```html
<div class="form-group">
  <input type="text" placeholder="Your input" />
</div>
```

### Table Rows

```html
<table>
  <tbody>
    <tr>
      <td>Data 1</td>
      <td>Data 2</td>
    </tr>
  </tbody>
</table>
```

---

## 🛠️ Fungsi JavaScript yang Tersedia

### 1. Show Loading Spinner

```javascript
// Show spinner
window.showLoadingSpinner(true);

// Hide spinner
window.showLoadingSpinner(false);
```

### 2. Show Notification

```javascript
window.showNotification("Success message", "success");
// Types: success, error, warning, info
```

### 3. Show Login Notification

```javascript
window.showLoginNotification("Message", "type");
```

---

## ⚙️ Customization

### Mengubah Durasi Animasi

Di file `css/animations.css`, ubah nilai `animation-duration`:

```css
/* Default: 0.8s */
.product-card {
  animation: fadeIn 0.8s ease-out;
}

/* Ubah menjadi 1.2s */
.product-card {
  animation: fadeIn 1.2s ease-out;
}
```

### Mengubah Delay Animasi

Di file `css/animations.css`, ubah nilai `animation-delay`:

```css
/* Stagger effect */
.stat-card:nth-child(1) {
  animation-delay: 0.1s;
}
.stat-card:nth-child(2) {
  animation-delay: 0.2s;
}
.stat-card:nth-child(3) {
  animation-delay: 0.3s;
}
```

### Mengubah Warna Gradient Login

Di file `css/login-animations.css`, ubah `.login-container`:

```css
.login-container {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

---

## 📱 Responsive Behavior

### Animasi di Mobile

- Semua animasi dioptimasi untuk performa mobile
- Duration dikurangi menjadi 0.4s pada layar ≤ 768px
- Stagger delays dihilangkan untuk performa lebih baik

### Media Query untuk Customization

```css
@media (max-width: 768px) {
  /* Mobile customizations */
  .product-card {
    animation-delay: 0 !important;
  }
}
```

---

## ♿ Accessibility

### Dukungan Reduced Motion

Untuk user dengan preferensi "prefers-reduced-motion", semua animasi akan otomatis dinonaktifkan.

Ini dihandle otomatis di kedua file JavaScript.

---

## 🔍 Debugging

### Cek Console untuk Errors

Buka Developer Tools (F12) → Console untuk melihat error messages.

### Common Issues

1. **Animasi tidak berjalan**
   - Pastikan CSS dan JS files sudah di-link dengan benar
   - Check file path (sesuaikan dengan struktur folder Anda)

2. **Animasi lambat**
   - Kurangi jumlah elemen yang di-animate
   - Gunakan `will-change` CSS property pada elemen penting

3. **Performance Issues**
   - Disable pada mobile dengan media query
   - Gunakan `transform` dan `opacity` untuk animasi yang smooth

---

## 📋 Checklist Integrasi

- [ ] Tambahkan `css/animations.css` ke semua halaman
- [ ] Tambahkan `js/animations.js` ke semua halaman
- [ ] Tambahkan class yang sesuai ke elemen yang ingin di-animate
- [ ] Test di browser desktop
- [ ] Test di mobile device
- [ ] Test di Safari browser
- [ ] Check accessibility (prefers-reduced-motion)

---

## 🎬 Contoh Implementasi Lengkap

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Halaman Contoh</title>
    <!-- Animasi CSS -->
    <link rel="stylesheet" href="css/animations.css" />
    <style>
      body {
        font-family: Arial, sans-serif;
        padding: 20px;
      }
    </style>
  </head>
  <body>
    <h1>Selamat Datang</h1>

    <!-- Product Grid dengan Animasi -->
    <div
      style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;"
    >
      <div class="product-card">
        <h3>Product 1</h3>
        <p>Description...</p>
      </div>
      <div class="product-card">
        <h3>Product 2</h3>
        <p>Description...</p>
      </div>
    </div>

    <!-- Button dengan Animasi -->
    <button class="btn-primary" onclick="handleClick()">Click Me</button>

    <!-- Form dengan Animasi -->
    <form style="margin-top: 30px;">
      <div class="form-group">
        <input type="text" placeholder="Nama..." />
      </div>
      <div class="form-group">
        <input type="email" placeholder="Email..." />
      </div>
      <button type="submit" class="btn-primary">Submit</button>
    </form>

    <!-- Scripts -->
    <script src="js/animations.js"></script>
    <script>
      function handleClick() {
        window.showNotification("Button clicked!", "success");
      }
    </script>
  </body>
</html>
```

---

## 📞 Support

Jika ada pertanyaan atau issue dengan animasi:

1. Buka Developer Tools (F12)
2. Cek Console untuk error messages
3. Pastikan file paths sudah benar
4. Pastikan browser support CSS animations (semua browser modern support)

---

## 🎯 Next Steps

1. **Update semua halaman** dengan link ke CSS dan JS animations
2. **Test responsiveness** di berbagai ukuran layar
3. **Optimize performance** jika diperlukan
4. **Integrate dengan Node.js/Next.js** di tahap berikutnya

---

Dibuat: 2024
Versi: 1.0
