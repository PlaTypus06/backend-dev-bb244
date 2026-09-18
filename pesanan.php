<?php

require_once "config/database.php";
require_once "helpers/keamanan.php";


// CREATE
if (isset($_POST['simpan'])) {

    $customer_id = (int) $_POST['customer_id'];
    $produk = trim($_POST['produk']);
    $quantitas = (int) $_POST['quantitas'];

    // Validasi
    if ($customer_id == "" || $produk == "" || $quantitas == "") {
        die("Semua data wajib diisi!");
    }

    if ($quantitas <= 0) {
        die("Quantitas harus lebih dari 0!");
    }

    // INSERT
    $sql = "INSERT INTO pesanan (customer_id, produk, quantitas)
            VALUES (:customer_id, :produk, :quantitas)";

    $stmt = $koneksi->prepare($sql);

    $stmt->execute([
        ':customer_id' => $customer_id,
        ':produk' => $produk,
        ':quantitas' => $quantitas
    ]);

    header("Location: pesanan.php");
    exit;
}

// UPDATE
if (isset($_POST['update'])) {

    $id = (int) $_POST['id'];
    $customer_id = (int) $_POST['customer_id'];
    $produk = trim($_POST['produk']);
    $quantitas = (int) $_POST['quantitas'];

    // Validasi sederhana
    if ($customer_id <= 0 || $produk == "" || $quantitas <= 0) {
    die("Data pesanan tidak valid!");
}

    if ($quantitas <= 0) {
        die("Quantitas harus lebih dari 0!");
    }

    // UPDATE database
    $sql = "UPDATE pesanan
            SET customer_id = :customer_id,
                produk = :produk,
                quantitas = :quantitas
            WHERE id = :id";

    $stmt = $koneksi->prepare($sql);

    $stmt->execute([
        ':customer_id' => $customer_id,
        ':produk' => $produk,
        ':quantitas' => $quantitas,
        ':id' => $id
    ]);

    header("Location: pesanan.php");
    exit;
}

// DELETE
if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    $sql = "DELETE FROM pesanan WHERE id = :id";

    $stmt = $koneksi->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    header("Location: pesanan.php");
    exit;
}

// DATA PESANAN YANG MAU DIEDIT
if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $sql = "SELECT * FROM pesanan WHERE id = :id";

    $stmt = $koneksi->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    $pesanan_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}


// ambil data customer
$sql_customer = "SELECT id, nama FROM customers";

$stmt_customer = $koneksi->query($sql_customer);

$customers = $stmt_customer->fetchAll(PDO::FETCH_ASSOC);


// READ
$sql = "SELECT
            pesanan.id,
            customers.nama,
            pesanan.produk,
            pesanan.quantitas
        FROM pesanan
        JOIN customers
            ON pesanan.customer_id = customers.id";

$stmt = $koneksi->query($sql);

$pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html>

<head>
    <title>Data Pesanan</title>
</head>

<body>

    <h1>Data Pesanan</h1>


    <h2>Tambah Pesanan</h2>

    <form method="POST">

        <label>Customer</label>
        <br>

        <select name="customer_id">

            <option value="">-- Pilih Customer --</option>

            <?php foreach ($customers as $customer): ?>

                <option value="<?= aman($customer['id']) ?>">
                    <?= aman($customer['nama']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <br><br>


        <label>Produk</label>
        <br>

        <input type="text" name="produk">

        <br><br>


        <label>Quantitas</label>
        <br>

        <input type="number" name="quantitas">

        <br><br>


        <button type="submit" name="simpan">
            Simpan
        </button>

    </form>


    <hr>

    <?php if (isset($pesanan_edit)): ?>

    <h2>Edit Pesanan</h2>

    <form method="POST">

        <input
            type="hidden"
            name="id"
            value="<?= aman($pesanan_edit['id']) ?>"
        >

        <label>Customer</label>
        <br>

        <select name="customer_id">

            <?php foreach ($customers as $customer): ?>

                <option
                    value="<?= aman($customer['id']) ?>"
                    <?= $customer['id'] == $pesanan_edit['customer_id'] ? 'selected' : '' ?>
                >
                    <?= aman($customer['nama']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

        <label>Produk</label>
        <br>

        <input
            type="text"
            name="produk"
            value="<?= aman($pesanan_edit['produk']) ?>"
        >

        <br><br>

        <label>Quantitas</label>
        <br>

        <input
            type="number"
            name="quantitas"
            value="<?= aman($pesanan_edit['quantitas']) ?>"
        >

        <br><br>

        <button type="submit" name="update">
            Update
        </button>

    </form>

    <hr>

<?php endif; ?>


    <h2>Daftar Pesanan</h2>

    <table border="1" cellpadding="10">

        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Produk</th>
            <th>Quantitas</th>
            <th>Aksi</th>
        </tr>


        <?php foreach ($pesanan as $item): ?>

            <tr>

                <td>
                    <?= aman($item['id']) ?>
                </td>

                <td>
                    <?= aman($item['nama']) ?>
                </td>

                <td>
                    <?= aman($item['produk']) ?>
                </td>

                <td>
                    <?= aman($item['quantitas']) ?>
                </td>

                <td>
                    <a href="pesanan.php?edit=<?= aman($item['id']) ?>">
                        Edit
                    </a>
                    ||
                     <a
                        href="pesanan.php?hapus=<?= $item['id'] ?>"
                        onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                        Hapus
                    </a>
                </td>

            </tr>

        <?php endforeach; ?>

    </table>

</body>

</html>