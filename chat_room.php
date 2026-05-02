<?php
session_start();
include "config.php";

/* CEK ADMIN */
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

/* USER YANG DIBUKA */
$user = isset($_GET['user']) ? mysqli_real_escape_string($conn, $_GET['user']) : '';

if($user == ''){
    echo "User tidak ditemukan";
    exit;
}

/* 🔔 MARK AS READ (CHAT SUDAH DIBUKA) */
mysqli_query($conn,"
    UPDATE chat_messages 
    SET is_read = 1 
    WHERE sender = '$user' 
    AND receiver = 'admin'
    AND is_read = 0
");

/* KIRIM PESAN */
if(isset($_POST['msg'])){
    $msg = mysqli_real_escape_string($conn, $_POST['msg']);

    mysqli_query($conn,"
        INSERT INTO chat_messages (sender, receiver, message, is_read)
        VALUES ('admin', '$user', '$msg', 0)
    ");
}

/* AMBIL CHAT */
$chat = mysqli_query($conn,"
    SELECT * FROM chat_messages 
    WHERE (sender='$user' AND receiver='admin')
       OR (sender='admin' AND receiver='$user')
    ORDER BY id ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat Room Admin</title>

<style>
body{
    font-family:Arial;
    background:#f1f5f9;
    margin:0;
}

.box{
    width:420px;
    margin:30px auto;
    background:white;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(0,0,0,0.15);
}

.header{
    background:#2563eb;
    color:white;
    padding:12px;
    font-weight:bold;
}

.chat{
    height:400px;
    overflow-y:auto;
    padding:10px;
    display:flex;
    flex-direction:column;
}

.msg{
    padding:10px;
    margin:5px 0;
    border-radius:10px;
    max-width:75%;
    font-size:14px;
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

form{
    display:flex;
    border-top:1px solid #ddd;
}

input{
    flex:1;
    padding:12px;
    border:none;
    outline:none;
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:0 18px;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}
</style>
</head>

<body>

<div class="box">

    <div class="header" style="display:flex;justify-content:space-between;align-items:center;position:relative;">

    <!-- TITLE CENTER -->
    <div style="flex:1;text-align:center;">
        💬 Chat dengan <?= htmlspecialchars($user) ?>
    </div>

    <!-- BUTTON X -->
    <a href="admin_chat.php" 
       style="
        position:absolute;
        left:10px;
        top:50%;
        transform:translateY(-50%);
        background:rgba(255,255,255,0.2);
        color:white;
        width:28px;
        height:28px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:8px;
        text-decoration:none;
        font-size:18px;
        font-weight:bold;
        transition:.2s;
       "
       onmouseover="this.style.background='rgba(255,255,255,0.35)'"
       onmouseout="this.style.background='rgba(255,255,255,0.2)'"
    >
        ✖
    </a>

</div>

    <!-- CHAT BOX -->
    <div class="chat" id="chatBox">

        <?php while($c = mysqli_fetch_assoc($chat)) { ?>

            <div class="msg <?= $c['sender']=='admin' ? 'admin' : 'user' ?>">
                <?= htmlspecialchars($c['message']) ?>

                <?php if(!empty($c['created_at'])){ ?>
                    <div style="font-size:10px;opacity:0.6;margin-top:4px;">
                        <?= $c['created_at'] ?>
                    </div>
                <?php } ?>
            </div>

        <?php } ?>

    </div>

    <!-- FORM -->
    <form method="POST">
        <input type="text" name="msg" placeholder="Tulis balasan..." required>
        <button>Kirim</button>
    </form>

</div>

<!-- AUTO SCROLL -->
<script>
let chat = document.getElementById("chatBox");
chat.scrollTop = chat.scrollHeight;
</script>

<!-- ENTER TO SEND -->
<script>
document.querySelector("input[name='msg']").addEventListener("keypress", function(e){
    if(e.key === "Enter"){
        e.preventDefault();
        this.form.submit();
    }
});
</script>

</body>
</html>