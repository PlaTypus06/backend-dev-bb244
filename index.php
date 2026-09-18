<?php

require_once "config/database.php";
require_once "helpers/keamanan.php";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>

    <h1>Selamat Datang</h1>
    <p>Silakan pilih menu:</p>

    <a href="customers.php">
        <button>Data Customer</button>
    </a>
    <a href="pesanan.php">
        <button>Data Pesanan</button>
    </a>

</body>
</html>