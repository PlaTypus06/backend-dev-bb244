<?php

require_once "config/database.php";
require_once "helpers/keamanan.php";


//CREATE
if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);
    $no_hp_terenkripsi = enkripsiData($no_hp);

    // Validasi sederhana
    if ($nama == "" || $email == "" || $no_hp == "") {
        die("Semua data wajib diisi!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Format email tidak valid!");
    }

    // INSERT
    $sql = "INSERT INTO customers (nama, email, no_hp)
            VALUES (:nama, :email, :no_hp)";

    $stmt = $koneksi->prepare($sql);

    $stmt->execute([
        ':nama' => $nama,
        ':email' => $email,
        ':no_hp' => $no_hp_terenkripsi
    ]);

    header("Location: customers.php");
    exit;
}

// UPDATE
if (isset($_POST['update'])) {

    $id = (int) $_POST['id'];
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);
    $no_hp_terenkripsi = enkripsiData($no_hp);

    // Validasi sederhana
    if ($nama == "" || $email == "" || $no_hp == "") {
        die("Semua data wajib diisi!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Format email tidak valid!");
    }
    

    // UPDATE database
    $sql = "UPDATE customers
            SET nama = :nama,
                email = :email,
                no_hp = :no_hp
            WHERE id = :id";

    $stmt = $koneksi->prepare($sql);

    $stmt->execute([
        ':nama' => $nama,
        ':email' => $email,
        ':no_hp' => $no_hp_terenkripsi,
        ':id' => $id
    ]);

    header("Location: customers.php");
    exit;
}

//data yang mau diedit
if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $sql = "SELECT * FROM customers WHERE id = :id";

    $stmt = $koneksi->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    $customer_edit = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer_edit) {
    $customer_edit['no_hp'] = dekripsiData($customer_edit['no_hp']);
}
}

// DELETE
if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    $sql = "DELETE FROM customers WHERE id = :id";

    $stmt = $koneksi->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    header("Location: customers.php");
    exit;
}

//READ
$sql = "SELECT * FROM customers";

$stmt = $koneksi->query($sql);

$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($customers as &$customer) {
    $customer['no_hp'] = dekripsiData($customer['no_hp']);
}

unset($customer);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Customer</title>
</head>

<body>

    <h1>Data Customer</h1>

    <h2>Tambah Customer</h2>

        <form method="POST">

            <label>Nama</label>
            <br>
            <input type="text" name="nama">

            <br><br>

            <label>Email</label>
            <br>
            <input type="email" name="email">

            <br><br>

            <label>No HP</label>
            <br>
            <input type="text" name="no_hp">

            <br><br>

            <button type="submit" name="simpan">
                Simpan
            </button>

        </form>


    <?php if (isset($customer_edit)): ?>

    <h2>Edit Customer</h2>

    <form method="POST">

        <input type="hidden" name="id" value="<?= aman($customer_edit['id']) ?>">

        <label>Nama</label>
        <br>
        <input 
            type="text" 
            name="nama"
            value="<?= aman($customer_edit['nama']) ?>"
        >

        <br><br>

        <label>Email</label>
        <br>
        <input 
            type="email" 
            name="email"
            value="<?= aman($customer_edit['email']) ?>"
        >

        <br><br>

        <label>No HP</label>
        <br>
        <input 
            type="text" 
            name="no_hp"
            value="<?= aman($customer_edit['no_hp']) ?>"
        >

        <br><br>

        <button type="submit" name="update">
            Update
        </button>

    </form>

    <hr>

<?php endif; ?>
        <hr>

    <table border="1" cellpadding="10">

        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($customers as $customer): ?>

            <tr>
                <td>
                    <?= aman($customer['id']) ?>
                </td>

                <td>
                    <?= aman($customer['nama']) ?>
                </td>

                <td>
                    <?= aman($customer['email']) ?>
                </td>

                <td>
                    <?= aman($customer['no_hp']) ?>
                </td>
                <td>
                    <a href="customers.php?edit=<?= $customer['id'] ?>">Edit</a>
                    ||                  
                    <a href="customers.php?hapus=<?= $customer['id'] ?>"
                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                        Hapus
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

</body>
</html>