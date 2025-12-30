<?php
session_start();

$limit_data = 50; 
$status_text = "Mode Aman (Pagination)";
$class_alert = "alert-success"; // Warna Hijau

if (isset($_GET['mode']) && $_GET['mode'] == 'siksa') {
    $limit_data = 500000; // PAKSA LOAD 500.000
    $status_text = "⚠️ MODE SIKSA (No Pagination)";
    $class_alert = "alert-danger"; // Warna Merah
}

if (!isset($_SESSION['user_id'])) {
    
    $_SESSION['user_id'] = 1;
    $_SESSION['nama'] = "Tester";
    $_SESSION['role'] = "Supreme Admin";
}

$start_time = microtime(true);
include 'db.php';

$sql = "SELECT u.id, u.full_name, u.department, u.status, u.salary, r.role_name 
        FROM users u
        JOIN user_roles ur ON u.id = ur.user_id
        JOIN roles r ON ur.role_id = r.id
        LIMIT $limit_data"; // <--- INI KUNCINYA

$stmt = $conn->prepare($sql);
$stmt->execute();
$minions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$end_time = microtime(true);
$duration = round(($end_time - $start_time), 4);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Minion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<div class="container mt-4">
    <div class="alert <?php echo $class_alert; ?> text-center">
        <h3><?php echo $status_text; ?></h3>
        <p>
            Loading Time: <strong><?php echo $duration; ?> detik</strong> | 
            Jumlah Data Tampil: <strong><?php echo count($minions); ?> Baris</strong>
        </p>
    </div>

    <div class="mb-3 text-center">
        <a href="dashboard.php" class="btn btn-success">🟢 Masuk Mode Ringan</a>
        <a href="dashboard.php?mode=siksa" class="btn btn-danger">🔴 Masuk Mode Siksa</a>
    </div>

    <div class="card text-dark">
        <div class="card-body">
            <div class="table-responsive" style="height: 600px; overflow: auto;">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($minions as $m): ?>
                        <tr>
                            <td><?php echo $m['id']; ?></td>
                            <td><?php echo $m['full_name']; ?></td>
                            <td><?php echo $m['department']; ?></td>
                            <td><?php echo $m['role_name']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>