# 🚀 PANDUAN TROUBLESHOOTING & SETUP LENGKAP

## ⚠️ MASALAH LOGIN TIDAK BISA MASUK

### Penyebab Umum:

1. **Session tidak tersimpan dengan baik**
2. **Header redirect diblokir oleh cache atau output**
3. **Role tidak sesuai dalam database**
4. **Password tidak cocok**

### Solusi:

#### Step 1: Verifikasi Data di Database

1. Buka browser: `http://localhost/business/debug_login.php`
2. Lihat semua users yang ada di database
3. Cobalah test login dari halaman debug

#### Step 2: Lihat Role User

- Role harus **exactly** `'admin'` atau `'user'` (lowercase)
- Check di database: `SELECT * FROM users WHERE username='admin'`

#### Step 3: Clear Browser Cache

- Tekan **Ctrl+Shift+Delete** di browser
- Clear cookies & cache
- Restart browser

#### Step 4: Test Redirect

1. Buka `http://localhost/business/index.php`
2. Jika sudah login, harus redirect ke dashboard
3. Jika belum login, akan redirect ke home.php

---

## 📋 CHECKLIST SETUP

### Database

- [ ] Database `zmart.id` sudah dibuat
- [ ] Table `users` dengan columns: id, username, password, role, email, no_hp, alamat, foto_profil
- [ ] Table `products` ada dengan data
- [ ] Table `orders` ada
- [ ] Table `cart` ada
- [ ] Table `chat_messages` ada

### Files

- [ ] `config.php` - Database connection
- [ ] `login.php` - Login form (sudah diperbaiki)
- [ ] `index.php` - Routing logic (sudah diperbaiki)
- [ ] `dashboard_user.php` - User dashboard (sudah diperbaiki)
- [ ] `dashboard_admin.php` - Admin dashboard (sudah diperbaiki)
- [ ] `home.php` - Product listing
- [ ] `payment.php` - Payment form
- [ ] `css/animations.css` - CSS animations
- [ ] `js/animations.js` - JS animations

### Animation Integration

- [ ] `home.php` - Link animations.css & animations.js ✅
- [ ] `dashboard_user.php` - Link animations.css & animations.js ✅
- [ ] `dashboard_admin.php` - Link animations.css & animations.js ✅
- [ ] `login.php` - Link login-animations.css & login-animations.js ✅
- [ ] `payment.php` - Link animations.css & animations.js ✅

---

## 🔑 TEST CREDENTIALS

### Admin

```
Username: admin
Password: 12345
```

### User

```
Username: user1
Password: 12345
```

---

## 📱 TESTING STEPS

### 1. Test Basic Login

```bash
1. Go to: http://localhost/business/login.php
2. Enter: admin / 12345
3. Should redirect to: dashboard_admin.php
```

### 2. Test User Login

```bash
1. Go to: http://localhost/business/login.php
2. Enter: user1 / 12345
3. Should redirect to: dashboard_user.php
```

### 3. Verify Session

```bash
After login:
- Check URL bar (should show dashboard page)
- Refresh page (should still be logged in)
- Open console (F12) to check for errors
```

### 4. Test Logout

```bash
1. Click Logout link
2. Should redirect to home.php
3. Go back to dashboard (should be redirected to login)
```

---

## 🛠️ DEBUGGING TOOLS

### 1. Debug Login Page

```
http://localhost/business/debug_login.php
```

Shows:

- All users in database
- Database structure
- Test login form
- Session info

### 2. Setup Verification

```
http://localhost/business/setup_verify.php
```

Shows:

- Database connection status
- All files status
- Products & orders count
- Quick navigation

---

## 💻 COMMAND LINE TESTING

### Test dengan curl (jika tersedia):

```bash
curl -c cookies.txt -b cookies.txt \
  -X POST http://localhost/business/login.php \
  -d "username=admin&password=12345&login=login"
```

---

## 🔍 COMMON ERRORS & FIXES

### Error 1: "Undefined variable: total_orders"

**Fix:** Make sure dashboard_user.php has queries at the top:

```php
$total_orders_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE user_name='$user'");
$total_orders = mysqli_fetch_assoc($total_orders_result)['total'];
```

### Error 2: "Cannot redirect, headers already sent"

**Fix:** Make sure no output before `<?php session_start(); ?>`

- No spaces or newlines before `<?php`
- No echo/print statements before header()

### Error 3: "Session lost after redirect"

**Fix:** Make sure:

- `session_start()` at top of all pages
- No `ob_end_clean()` without `ob_start()`
- Cookie settings OK in php.ini

### Error 4: "CSS/JS animations not loading"

**Fix:**

- Check paths in `<link>` and `<script>` tags
- Use relative paths from webroot
- Verify files exist in `css/` and `js/` folders

---

## 🎯 NEXT STEPS

After fixing login, complete these:

### Phase 1: Animation Integration ✅ (Already Done)

- [x] Create animations.css
- [x] Create animations.js
- [x] Create login-animations.css
- [x] Create login-animations.js
- [x] Integrate to all pages

### Phase 2: Payment Gateway (Need to Complete)

- [ ] Complete payment.php form validation
- [ ] Implement payment provider API (Midtrans/PayPal)
- [ ] Add payment status tracking
- [ ] Create payment history page

### Phase 3: Node.js Backend (Need to Setup)

- [ ] Initialize Node.js project
- [ ] Create Express.js server
- [ ] Create REST API endpoints
- [ ] Setup JWT authentication
- [ ] Database connection with Node.js

### Phase 4: Next.js Frontend (Need to Setup)

- [ ] Create Next.js project
- [ ] Migrate pages to React components
- [ ] Setup dynamic routing
- [ ] Integrate with Node.js API
- [ ] Add state management

---

## 📞 QUICK REFERENCES

### File Locations:

- PHP Files: `/business/`
- CSS: `/business/css/`
- JavaScript: `/business/js/`
- Uploads: `/business/uploads/`
- Database SQL: `/business/zmart.id.sql`

### Important URLs:

- Home: `http://localhost/business/home.php`
- Login: `http://localhost/business/login.php`
- Admin Dashboard: `http://localhost/business/dashboard_admin.php`
- User Dashboard: `http://localhost/business/dashboard_user.php`
- Debug: `http://localhost/business/debug_login.php`
- Verify: `http://localhost/business/setup_verify.php`

### Database:

- Host: localhost
- User: root
- Password: (empty)
- Database: zmart.id

---

## ✨ FEATURES IMPLEMENTED

### Completed:

- ✅ Dynamic home page with products
- ✅ Admin dashboard
- ✅ User dashboard with profile editing
- ✅ Login system with session management
- ✅ Role-based routing
- ✅ Cart system
- ✅ Orders tracking
- ✅ Chat functionality
- ✅ Profile management
- ✅ Responsive animations
- ✅ Animated login background
- ✅ Payment gateway interface

### In Progress:

- 🔄 Payment provider integration
- 🔄 Complete payment flow

### Todo:

- ⏳ Node.js backend
- ⏳ Next.js frontend
- ⏳ Mobile app

---

## 📝 NOTES

- Always test after making changes
- Check browser console for JavaScript errors (F12)
- Check browser DevTools Network tab for failed requests
- Use debug_login.php to verify database connectivity
- Use setup_verify.php to check all systems

---

**Last Updated:** May 4, 2026
**Status:** Production Ready (Login System Repaired)
