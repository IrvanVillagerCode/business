# 🔧 PERBAIKAN LOGIN - GUIDE TESTING

## ⚡ Apa yang Sudah Diperbaiki

### 1. **login.php** - Simplified & Fixed

```
✅ Hapus onsubmit handler JavaScript yang mungkin blocking form
✅ Gunakan $_SERVER['REQUEST_METHOD'] === 'POST' untuk lebih reliable
✅ Simplified password checking (plain text OR hashed)
✅ Clean header redirect tanpa HTTP status codes
✅ Error handling yang lebih jelas
```

### 2. **Form Submission Logic**

```
✅ Hapus name="login" dari button (gunakan $_SERVER['REQUEST_METHOD'] saja)
✅ Hapus custom JavaScript validation yang mungkin preventing submit
✅ Direct form submission ke login.php dengan method="POST"
```

### 3. **Session Management**

```
✅ Simple $_SESSION['user'], $_SESSION['role'], $_SESSION['user_id']
✅ No session_regenerate_id (bisa jadi masalahnya)
✅ Direct header("Location: dashboard_xxx.php")
```

---

## 🚀 TESTING SEKARANG - IKUTI STEP INI

### **STEP 1: Cek Koneksi & Data**

Buka di browser:

```
http://localhost/business/login_diagnostic.php
```

**Apa yang harus terlihat:**

- ✅ Database connected successfully
- ✅ User found (admin)
- ✅ Password matches
- ✅ Session variables set
- ✅ Role check passed

Jika ada ERROR di salah satu step, report error message itu!

---

### **STEP 2: Langsung Login dari Sini**

Di halaman diagnostic di atas, tekan tombol:

```
Try Dashboard Admin
```

atau

```
Try Dashboard User
```

**Expected:**

- Harus masuk ke dashboard tanpa error
- Jika redirect tidak terjadi, buka browser console (F12) dan lihat tab Network

---

### **STEP 3: Testing Manual Login**

Buka:

```
http://localhost/business/login_tester.php
```

Tekan button:

```
Test Admin Login
```

atau isi form manual dengan:

```
Username: admin
Password: 12345
```

Tekan "Login Now"

**Expected:**

- Redirect ke dashboard_admin.php
- Session harus tersimpan
- Halaman admin harus muncul

---

### **STEP 4: Verify Session Persist**

Setelah login, buka:

```
http://localhost/business/debug_session.php
```

**Harus menunjukkan:**

```
Session User: admin
Session Role: admin
Session ID: (angka)
```

Jika kosong = session tidak persist = ada masalah

---

## 🔍 TROUBLESHOOTING - Jika Masih Error

### **Error: "Username tidak ditemukan"**

```
→ Pastikan username benar: admin atau user1
→ Cek di diagnostic page apakah user ada di database
→ Username CASE SENSITIVE
```

### **Error: "Password salah"**

```
→ Pastikan password = 12345 (bukan yang lain)
→ Di diagnostic, lihat apakah password check berhasil
```

### **Redirect tidak terjadi (stuck di login)**

```
1. Buka F12 → Console tab
2. Lihat ada error JavaScript tidak?
3. Buka F12 → Network tab
4. Lakukan login
5. Lihat ada request ke login.php?
6. Lihat response status (300-399 = redirect)
```

### **Login OK tapi dashboard error**

```
1. Buka debug_session.php
2. Lihat session variables ada tidak
3. Buka developer console (F12) → Console
4. Cek error apa yang muncul
```

### **Session hilang setiap refresh**

```
1. Pastikan cookies tidak di-block
2. Buka settings browser → Cookies
3. Pastikan localhost cookies allowed
4. Try incognito/private mode
```

---

## 📋 TESTING CHECKLIST

### Saat Login:

- [ ] Form submit without error
- [ ] No JavaScript console errors (F12)
- [ ] Browser berpindah ke dashboard (bukan stuck)
- [ ] URL berubah ke dashboard_admin.php atau dashboard_user.php

### Setelah Login:

- [ ] Dashboard page muncul (bukan error page)
- [ ] Session variables exist (debug_session.php)
- [ ] Refresh page (F5) masih tetap login
- [ ] Logout button berfungsi

### Untuk Admin:

- [ ] Login dengan admin/12345
- [ ] Redirect ke dashboard_admin.php
- [ ] Bisa lihat product management
- [ ] Session role = "admin"

### Untuk User:

- [ ] Login dengan user1/12345
- [ ] Redirect ke dashboard_user.php
- [ ] Bisa lihat orders & cart
- [ ] Session role = "user"

---

## 🔗 QUICK LINKS

| URL                    | Purpose                           |
| ---------------------- | --------------------------------- |
| `login_diagnostic.php` | Test login system step-by-step    |
| `login_tester.php`     | Form untuk manual testing         |
| `debug_session.php`    | Check session variables           |
| `login.php`            | Actual login page (clean version) |
| `test_login.php`       | Testing interface (old)           |

---

## 📊 CREDENTIALS

### Admin:

```
Username: admin
Password: 12345
Expected Redirect: dashboard_admin.php
```

### User:

```
Username: user1
Password: 12345
Expected Redirect: dashboard_user.php
```

---

## 🎯 EXPECTED FLOW

```
1. User opens login.php
   ↓
2. Enter username (admin) & password (12345)
   ↓
3. Click "Login Sekarang"
   ↓
4. Form submit POST ke login.php
   ↓
5. PHP process:
   - Find user in database
   - Check password
   - Set $_SESSION variables
   - header("Location: dashboard_admin.php")
   ↓
6. Browser redirect ke dashboard_admin.php
   ↓
7. Dashboard_admin.php:
   - Check if $_SESSION['user'] exist
   - Check if $_SESSION['role'] == 'admin'
   - If OK → Show dashboard
   - If NOT → Redirect to login.php
   ↓
8. Dashboard muncul dengan welcome message
```

---

## ⚠️ PENTING

1. **Clear Cache**: Sebelum test, buka Developer Tools (F12) → Settings → check "Disable cache"
2. **Refresh**: Tekan Ctrl+Shift+R (hard refresh) bukan hanya F5
3. **New Tab**: Test di tab baru (tidak ada cookie lama)
4. **Database**: Pastikan MySQL running (Laragon Start All Services)
5. **Server**: Pastikan Apache running (Laragon)

---

## 📞 IF STILL NOT WORKING

Silakan:

1. Open `login_diagnostic.php`
2. Screenshot hasil (setiap step)
3. Open Developer Console (F12 → Console tab)
4. Report error messages yang muncul
5. Report URL yang muncul setelah submit login

---

**Status: READY FOR TESTING** ✅
