<?php
session_start();
include "config.php";

// Ambil data produk
$query = mysqli_query($conn, "SELECT * FROM products LIMIT 12");
$products = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Ambil kategori unik
$kategori_query = mysqli_query($conn, "SELECT DISTINCT category FROM products");
$categories = [];
while ($row = mysqli_fetch_assoc($kategori_query)) {
    $categories[] = $row['category'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZMart - E-Commerce Store</title>
    <link rel="stylesheet" href="css/animations.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        /* HEADER */
        header {
            background-color: #0071ce;
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #ffc220;
        }

        .search-bar {
            flex: 1;
            margin: 0 30px;
            display: flex;
            gap: 10px;
        }

        .search-bar input {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-bar button {
            padding: 12px 25px;
            background-color: #ffc220;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-weight: bold;
            color: #0071ce;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #ffb81c;
        }

        .header-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .header-actions a,
        .header-actions button {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .header-actions a:hover,
        .header-actions button:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* NAVBAR */
        .navbar {
            background-color: #f5f5f5;
            padding: 15px 0;
            border-bottom: 1px solid #ddd;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            gap: 20px;
            overflow-x: auto;
        }

        .navbar-item {
            white-space: nowrap;
            padding: 8px 12px;
            background-color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #ddd;
        }

        .navbar-item:hover {
            background-color: #0071ce;
            color: white;
            border-color: #0071ce;
        }

        /* BANNER */
        .banner {
            background: linear-gradient(135deg, #0071ce 0%, #00a8e8 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 30px;
        }

        .banner h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .banner p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .banner-btn {
            padding: 12px 30px;
            background-color: #ffc220;
            color: #0071ce;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .banner-btn:hover {
            transform: scale(1.05);
        }

        /* PROMO SECTION */
        .promo-section {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .promo-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .promo-card:hover {
            transform: translateY(-5px);
        }

        .promo-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }

        /* PRODUCTS SECTION */
        .section-title {
            max-width: 1200px;
            margin: 40px auto 20px;
            padding: 0 20px;
            font-size: 24px;
            font-weight: bold;
            color: #0071ce;
        }

        .products-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
        }

        .product-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
            height: 32px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-price {
            color: #0071ce;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .product-stock {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }

        .product-actions {
            display: flex;
            gap: 8px;
        }

        .btn-view {
            flex: 1;
            padding: 8px;
            background-color: #0071ce;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.3s;
        }

        .btn-view:hover {
            background-color: #00539b;
        }

        .btn-cart {
            flex: 1;
            padding: 8px;
            background-color: #ffc220;
            color: #0071ce;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-cart:hover {
            background-color: #ffb81c;
        }

        /* FOOTER */
        footer {
            background-color: #0071ce;
            color: white;
            padding: 40px 20px;
            margin-top: 60px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .footer-section h3 {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .footer-section a {
            display: block;
            color: white;
            text-decoration: none;
            margin-bottom: 8px;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #ffc220;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-container {
                flex-wrap: wrap;
            }

            .search-bar {
                margin: 10px 0;
                width: 100%;
                flex: none;
                order: 3;
            }

            .banner h1 {
                font-size: 32px;
            }

            .products-container {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .navbar-container {
                justify-content: space-between;
            }

            .products-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .cart-count {
            background-color: #ffc220;
            color: #0071ce;
            border-radius: 50%;
            padding: 2px 8px;
            font-weight: bold;
            font-size: 12px;
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header>
        <div class="header-container">
            <div class="logo">🛒 ZMart</div>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Cari produk...">
                <button onclick="searchProducts()">Cari</button>
            </div>
            <div class="header-actions">
                <?php if (isset($_SESSION['user'])): ?>
                    <span>Halo, <?php echo $_SESSION['user']; ?></span>
                    <a href="cart.php">Keranjang<span class="cart-count" id="cartCount">0</span></a>
                    <a href="<?php echo $_SESSION['role'] == 'admin' ? 'dashboard_admin.php' : 'dashboard_user.php'; ?>">Dashboard</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- NAVBAR KATEGORI -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-item" onclick="filterCategory('all')">Semua Produk</div>
            <?php foreach ($categories as $cat): ?>
                <div class="navbar-item" onclick="filterCategory('<?php echo $cat; ?>')"><?php echo ucfirst($cat); ?></div>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- BANNER -->
    <section class="banner">
        <h1>Belanja Sekarang!</h1>
        <p>Temukan ribuan produk dengan harga terbaik</p>
        <button class="banner-btn" onclick="<?php echo isset($_SESSION['user']) ? 'scrollToProducts()' : "window.location='login.php'"; ?>">
            Mulai Belanja
        </button>
    </section>

    <!-- PROMO CARDS -->
    <section class="promo-section">
        <div class="promo-card">
            <div class="promo-icon">🚚</div>
            <h3>Pengiriman Cepat</h3>
            <p>Gratis ongkos kirim untuk pembelian di atas Rp 50.000</p>
        </div>
        <div class="promo-card">
            <div class="promo-icon">💰</div>
            <h3>Harga Terjangkau</h3>
            <p>Harga paling kompetitif di kelasnya</p>
        </div>
        <div class="promo-card">
            <div class="promo-icon">🔒</div>
            <h3>Belanja Aman</h3>
            <p>Pembayaran aman dan terjamin</p>
        </div>
    </section>

    <!-- PRODUK POPULER -->
    <h2 class="section-title">Produk Populer</h2>
    <section class="products-container" id="productsContainer">
        <?php foreach ($products as $product): ?>
            <div class="product-card" data-category="<?php echo $product['category']; ?>">
                <div class="product-image">
                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                </div>
                <div class="product-info">
                    <div class="product-name"><?php echo $product['name']; ?></div>
                    <div class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                    <div class="product-stock">Stok: <?php echo $product['stock']; ?></div>
                    <div class="product-actions">
                        <button class="btn-view" onclick="viewProduct(<?php echo $product['id']; ?>)">Lihat</button>
                        <button class="btn-cart" onclick="addToCart(<?php echo $product['id']; ?>)">+ Keranjang</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Tentang ZMart</h3>
                <p>ZMart adalah platform e-commerce terpercaya dengan jutaan produk berkualitas untuk kebutuhan Anda.</p>
            </div>
            <div class="footer-section">
                <h3>Layanan</h3>
                <a href="#">Bantuan Pelanggan</a>
                <a href="#">Pengiriman</a>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat & Ketentuan</a>
            </div>
            <div class="footer-section">
                <h3>Hubungi Kami</h3>
                <a href="mailto:info@zmart.id">Email: info@zmart.id</a>
                <a href="tel:+62812345678">Telp: +62 812 3456 78</a>
                <a href="#">Ikuti Media Sosial Kami</a>
            </div>
        </div>
    </footer>

    <script>
        // Update cart count
        function updateCartCount() {
            fetch('get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cartCount').textContent = data.count;
                });
        }

        // Filter kategori
        function filterCategory(category) {
            const products = document.querySelectorAll('.product-card');
            products.forEach(product => {
                if (category === 'all' || product.dataset.category === category) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        // Lihat detail produk
        function viewProduct(id) {
            window.location.href = 'detail_produk.php?id=' + id;
        }

        // Tambah ke keranjang
        function addToCart(productId) {
            <?php if (!isset($_SESSION['user'])): ?>
                alert('Silakan login terlebih dahulu');
                window.location.href = 'login.php';
                return;
            <?php endif; ?>

            fetch('add_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'product_id=' + productId + '&qty=1'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produk berhasil ditambahkan ke keranjang');
                        updateCartCount();
                    } else {
                        alert('Gagal menambahkan ke keranjang');
                    }
                });
        }

        // Search produk
        function searchProducts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            window.location.href = 'home.php?search=' + encodeURIComponent(searchTerm);
        }

        // Scroll ke produk
        function scrollToProducts() {
            document.getElementById('productsContainer').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Update cart count on page load
        window.addEventListener('load', updateCartCount);
    </script>
    <script src="js/animations.js"></script>
</body>

</html>