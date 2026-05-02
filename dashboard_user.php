<?php
session_start();
include "config.php";

/* CEK LOGIN */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

/* CEK ROLE */
if($_SESSION['role'] != 'user'){
    header("Location: dashboard_admin.php");
    exit;
}

$user = $_SESSION['user'];

/* AMBIL PRODUK */
/* FILTER KATEGORI */
$kategori = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : 'all';

if($kategori == 'all'){
    $produk = mysqli_query($conn, "SELECT * FROM products");
} else {
    $produk = mysqli_query($conn, "SELECT * FROM products WHERE category='$kategori'");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>ZMart Pro</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

/* ===== GLOBAL ===== */
body{
    margin:0;
    font-family:'Segoe UI', Arial, sans-serif;
    background:#f1f5f9;
    color:#1f2937;
}

/* ===== HEADER ===== */
.header{
    background:white;
    padding:12px 20px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    position:sticky;
    top:0;
    z-index:1000;
    border-bottom:1px solid #e5e7eb;
}

.logo{
    font-size:20px;
    font-weight:bold;
    color:#2563eb;
}

.menu{
    display:flex;
    align-items:center;
    gap:10px;
}

/* SEARCH */
.search input{
    width:100%;
    max-width:400px;
    padding:10px 15px;
    border-radius:30px;
    border:1px solid #e5e7eb;
    outline:none;
    background:#f9fafb;
}

/* CART */
.cart-icon{
    position:relative;
    width:40px;
    height:40px;
    border-radius:50%;
    background:#111;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
}

.cart-badge{
    position:absolute;
    top:-5px;
    right:-5px;
    background:red;
    color:white;
    font-size:10px;
    width:18px;
    height:18px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* BUTTON */
.btn{
    padding:8px 12px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
}

.history-btn{
    background:#2563eb;
    color:white;
}

.logout-btn{
    background:#ef4444;
    color:white;
}

/* CATEGORY */
.category{
    display:flex;
    gap:10px;
    padding:10px 20px;
    overflow-x:auto;
}
.category-wrapper{
    position:relative;
    padding:10px 20px;
}
.category-btn{
    background:white;
    border:1px solid #e5e7eb;
    padding:10px 14px;
    border-radius:10px;
    width:160px;
    cursor:pointer;
    font-weight:600;
    display:flex;
    justify-content:space-between;
    align-items:center;
    transition:.2s;
}
.category-btn:hover{
    background:#f9fafb;
}
/* DROPDOWN MODERN */
.category-dropdown{
    position:absolute;
    top:55px;
    left:20px;
    width:180px;
    background:white;
    border:1px solid #e5e7eb;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    display:none;
    flex-direction:column;
    overflow:hidden;
    z-index:1000;
    animation:fade .2s ease;
}
.category-dropdown a{
    padding:10px 12px;
    text-decoration:none;
    color:#111;
    font-size:14px;
    transition:.2s;
}

.category-dropdown a:hover{
    background:#f1f5f9;
}
.category-dropdown a.active{
    background:#2563eb;
    color:white;
}
@keyframes fade{
    from{opacity:0; transform:translateY(-5px);}
    to{opacity:1; transform:translateY(0);}
}

.category div{
    padding:6px 12px;
    background:white;
    border-radius:20px;
    font-size:13px;
    border:1px solid #e5e7eb;
    cursor:pointer;
}

/* BANNER */
.banner{
    margin:15px 20px;
    padding:15px;
    border-radius:12px;
    background:linear-gradient(135deg,#3b82f6,#60a5fa);
    color:white;
    display:flex;
    justify-content:space-between;
}

/* GRID */
.container{
    padding:20px;
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
    transition:.3s;
}

.card:hover{
    transform:translateY(-6px);
}

.card img{
    width:100%;
    height:150px;
    object-fit:contain;
    background:#f9fafb;
}

.body{
    padding:12px;
}

.title{
    font-size:14px;
    font-weight:600;
    color:#111;
    text-decoration:none;
    display:block;
    height:36px;
    overflow:hidden;
}

.price{
    color:#2563eb;
    font-weight:bold;
    margin:5px 0;
}

/* BUTTON GROUP */
.btn-group{
    display:flex;
    gap:5px;
}

.buy{
    flex:1;
    background:#2563eb;
    color:white;
    text-align:center;
    padding:8px;
    border-radius:8px;
    font-size:12px;
}

.cart-btn{
    width:40px;
    background:#e5e7eb;
    text-align:center;
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
}
.shopee-chat{
    position:fixed;
    bottom:20px;
    right:20px;
    width:55px;
    height:55px;
    background:#ee4d2d;
    color:white;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
    text-decoration:none;
    box-shadow:0 8px 20px rgba(0,0,0,0.2);
    z-index:9999;
    transition:.2s;
}

.shopee-chat:hover{
    transform:scale(1.1);
}
.chat-label{
    position:absolute;
    right:70px;
    background:#111;
    color:white;
    padding:5px 8px;
    border-radius:6px;
    font-size:12px;
    opacity:0;
    transition:.2s;
    white-space:nowrap;
}

.shopee-chat:hover .chat-label{
    opacity:1;
}
/* FLOAT BUTTON */
.chat-float{
    position:fixed;
    bottom:20px;
    right:20px;
    width:55px;
    height:55px;
    background:#2563eb;
    color:white;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    cursor:pointer;
    box-shadow:0 8px 20px rgba(0,0,0,0.2);
    z-index:9999;
}

/* CHAT BOX */
.chat-box{
    position:fixed;
    bottom:90px;
    right:20px;
    width:320px;
    height:420px;
    background:white;
    border-radius:14px;
    box-shadow:0 12px 35px rgba(0,0,0,0.25);
    display:none;
    flex-direction:column;
    overflow:hidden;
    z-index:10000;
    animation:pop .2s ease;
}
@keyframes pop{
    from{transform:scale(.9); opacity:0;}
    to{transform:scale(1); opacity:1;}
}

/* HEADER */
.chat-header{
    background:#2563eb;
    color:white;
    padding:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-weight:bold;
}

.chat-header button{
    background:none;
    border:none;
    color:white;
    font-size:16px;
    cursor:pointer;
}

/* BODY */
.chat-body{
    flex:1;
    padding:10px;
    overflow-y:auto;
    background:#f9fafb;
}

/* message style fix */
.msg{
    max-width:80%;
    padding:8px 10px;
    margin:6px 0;
    border-radius:10px;
    font-size:13px;
    word-wrap:break-word;
}

.user{
    background:#dcfce7;
    margin-left:auto;
    text-align:right;
}

.admin{
    background:#e5e7eb;
    margin-right:auto;
}

/* FOOTER */
.chat-footer{
    display:flex;
    border-top:1px solid #e5e7eb;
}

.chat-footer input{
    flex:1;
    padding:10px;
    border:none;
    outline:none;
    border-top-left-radius:12px;
}

.chat-footer button{
    background:#2563eb;
    color:white;
    border:none;
    padding:0 15px;
    cursor:pointer;
    border-top-right-radius:12px;
}
.chat-footer button:hover{
    background:#1d4ed8;
}
@media (max-width: 600px){
    .chat-box{
        width: 90%;
        right: 5%;
        bottom: 80px;
    }
    .chat-float{
        width:50px;
        height:50px;
        font-size:20px;
    }
}
</style>
</head>

<body>
<?php
$cartCount = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(qty),0) as total 
    FROM cart 
    WHERE user_name='$user'
"));
?>
<!-- HEADER -->
<div class="header">

    <div class="logo">ZMart</div>

    <div class="search">
        <input type="text" id="search" placeholder="Cari produk...">
    </div>

    <div class="menu">
        <a href="cart.php" class="cart-icon">
        🛒
        <span class="cart-badge">
            <?= $cartCount['total'] ?? 0 ?>
        </span>
        </a>

        <a href="orders_user.php" class="btn history-btn">Riwayat</a>
        <a href="logout.php" class="btn logout-btn">Logout</a>
    </div>

</div>

<!-- CATEGORY -->
<div class="category-wrapper">

    <div class="category-btn" onclick="toggleCategory()">
        ☰ Kategori
    </div>

    <div class="category-dropdown" id="categoryDropdown">

        <a href="dashboard_user.php?cat=all"
            class="<?= ($kategori=='all') ? 'active' : '' ?>">
            Semua
        </a>
        <a href="dashboard_user.php?cat=makanan"
            class="<?= ($kategori=='makanan') ? 'active' : '' ?>">
            Makanan
        </a>
        <a href="dashboard_user.php?cat=minuman"
            class="<?= ($kategori=='minuman') ? 'active' : '' ?>">
            Minuman
        </a>
        <a href="dashboard_user.php?cat=atk"
            class="<?= ($kategori=='atk') ? 'active' : '' ?>">
            ATK
        </a>

    </div>

</div>

<!-- BANNER -->
<div class="banner">
    <div>
        🔥 Promo Hari Ini
        <br><small>Diskon sampai 50%</small>
    </div>
    <div>🏷️ SALE</div>
</div>

<!-- PRODUK -->
<div class="container">

<?php while($p = mysqli_fetch_assoc($produk)) { ?>

<div class="card product">

    <img src="uploads/<?= $p['image'] ?>">

    <div class="body">

        <a href="detail_produk.php?id=<?= $p['id'] ?>" class="title">
            <?= htmlspecialchars($p['name']) ?>
        </a>

        <div class="price">
            Rp <?= number_format($p['price']) ?>
        </div>

        <div class="btn-group">
            <a class="buy" href="buy_now.php?id=<?= $p['id'] ?>">
                Beli
            </a>

            <a class="cart-btn" href="add_cart.php?id=<?= $p['id'] ?>">
                +
            </a>
        </div>

    </div>

</div>

<?php } ?>

</div>
<!-- FLOATING CHAT BUTTON -->
<div class="chat-float" onclick="openChat()">
    💬
</div>
<?php
$user = $_SESSION['user'];

$chat = mysqli_query($conn,"
    SELECT * FROM chat_messages 
    WHERE sender='$user' OR receiver='$user'
    ORDER BY id ASC
");
?>

<!-- CHAT POPUP -->
<div id="chatBox" class="chat-box">

    <div class="chat-header">
        <span>💬 Chat Admin</span>
        <button onclick="closeChat()">✖</button>
    </div>

    <div class="chat-body" id="chatBody">

    <?php while($c = mysqli_fetch_assoc($chat)) { ?>

        <div class="msg <?= $c['sender'] == $user ? 'user' : 'admin' ?>">
            <?= htmlspecialchars($c['message']) ?>
        </div>

    <?php } ?>

</div>

    <div class="chat-footer">
        <input type="text" id="chatInput" placeholder="Tulis pesan...">
        <button onclick="sendMessage()">Kirim</button>
    </div>

</div>
<script>
/* SEARCH */
document.getElementById("search").addEventListener("keyup",function(){
    let val=this.value.toLowerCase();
    document.querySelectorAll(".product").forEach(p=>{
        let name=p.querySelector(".title").innerText.toLowerCase();
        p.style.display=name.includes(val)?"block":"none";
    });
});
</script>
<script>
function openChat(){
    document.getElementById("chatBox").style.display = "flex";
}

function closeChat(){
    document.getElementById("chatBox").style.display = "none";
}

function sendMessage(){
    let input = document.getElementById("chatInput");
    let text = input.value.trim();
    if(text === "") return;
    fetch("send_chat.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "message=" + encodeURIComponent(text)
    })
    .then(res => res.text())
    .then(data => {
        let chatBody = document.getElementById("chatBody");

        let msg = document.createElement("div");
        msg.className = "msg user";
        msg.innerText = text;
        chatBody.appendChild(msg);

        input.value = "";
        chatBody.scrollTop = chatBody.scrollHeight;
        });
}

/* ENTER TO SEND */
document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("chatInput").addEventListener("keypress", function(e){
        if(e.key === "Enter"){
            sendMessage();
        }
    });
});
</script>
<script>
function toggleCategory(){
    let drop = document.getElementById("categoryDropdown");
    drop.style.display = (drop.style.display === "flex") ? "none" : "flex";
}

/* klik luar untuk close */
document.addEventListener("click", function(e){
    let btn = document.querySelector(".category-btn");
    let drop = document.getElementById("categoryDropdown");

    if(!btn.contains(e.target) && !drop.contains(e.target)){
        drop.style.display = "none";
    }
});
</script>

</body>
</html>  