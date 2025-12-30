<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $user_id = $_POST['user_id'];

    // Cari user dan rolenya
    $sql = "SELECT u.id, u.full_name, r.role_name 
            FROM users u
            JOIN user_roles ur ON u.id = ur.user_id
            JOIN roles r ON ur.role_id = r.id
            WHERE u.id = :id";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Simpan data user ke sesi (kuncinya di sini!)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['full_name'];
        $_SESSION['role'] = $user['role_name'];
        
        header("Location: dashboard.php"); // Lempar ke dashboard
        exit;
    } else {
        $error = "ID Minion tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Markas Gru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-warning d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card p-4" style="width: 350px;">
        <h3 class="text-center">🍌 Login Minion</h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label>Masukkan ID Pegawai:</label>
                <input type="number" name="user_id" class="form-control" placeholder="Contoh: 1 atau 500" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Masuk Kerja</button>
        </form>
        
        <div class="mt-3 text-muted small">
            <small>Cheat Sheet:<br>ID 1 = Gru (Admin)<br>ID 2 = Dr. Nefario (Scientist)<br>ID 100 = Minion Random</small>
        </div>
    </div>
</body>
</html>