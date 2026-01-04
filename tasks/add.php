<?php
// proteksi halaman (harus login)
require '../includes/session.php';
require '../config/database.php';

// proses tambah tugas
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ambil & sanitasi input
    $title       = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $due_date    = $_POST['due_date'];

    // validasi sederhana
    if (empty($title) || empty($due_date)) {
        $error = "Judul dan tanggal jatuh tempo wajib diisi!";
    } else {
        // simpan ke database
        $stmt = $pdo->prepare(
            "INSERT INTO tasks (user_id, title, description, due_date) 
             VALUES (:user_id, :title, :description, :due_date)"
        );

        $stmt->execute([
            ':user_id'     => $_SESSION['user_id'],
            ':title'       => $title,
            ':description' => $description,
            ':due_date'    => $due_date
        ]);

        // kembali ke halaman daftar tugas
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<h2>Tambah Tugas Baru</h2>

<?php if (isset($error)) : ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Judul Tugas</label><br>
    <input type="text" name="title" required><br><br>

    <label>Deskripsi</label><br>
    <textarea name="description" rows="4"></textarea><br><br>

    <label>Tanggal Jatuh Tempo</label><br>
    <input type="date" name="due_date" required><br><br>

    <button type="submit">Simpan</button>
    <a href="index.php">Batal</a>
</form>

<?php include '../includes/footer.php'; ?>

</body>
</html>
