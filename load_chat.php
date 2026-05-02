<?php
include "config.php";

$room_id = $_GET['room_id'];

$q = mysqli_query($conn, "
    SELECT * FROM chat_messages 
    WHERE room_id=$room_id 
    ORDER BY id ASC
");

while($row = mysqli_fetch_assoc($q)){

    $class = ($row['sender']=='user') ? 'user' : 'admin';

    echo "<div class='msg $class'>
            ".$row['message']."
          </div>";
}
?>