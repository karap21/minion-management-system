<?php
session_start();
include 'db.php';

// ============================================================
// 1. SIMULASI LOGIN (Ganti ID untuk Tes Role)
// ============================================================
// 1 = Gru (Admin Dewa)
// 2 = Dr. Nefario (Scientist)
// 3 = AVL Agent
// 5 = Minion Biasa
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; 
    $_SESSION['nama'] = "User Test";
}
$user_id = $_SESSION['user_id'];


// ============================================================
// 2. LOGIKA EKSEKUTOR (Pecat, Libur, Kembali)
// ============================================================

// A. LOGIKA PECAT (Hapus Permanen)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'pecat' && isset($_GET['id'])) {
    if ($user_id == 1) { // Hanya Gru
        $id = $_GET['id'];
        
        // Hapus di tabel relasi dulu (foreign key constraint)
        $stmt = $conn->prepare("DELETE FROM user_roles WHERE user_id = :id");
        $stmt->execute([':id' => $id]);

        // Hapus di tabel users
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        echo "<script>alert('SUKSES! Minion telah dipecat selamanya.'); window.location='dashboard.php';</script>";
        exit; 
    }
}

// B. LOGIKA LIBURKAN (Ubah Status -> Sedang Liburan)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'libur' && isset($_GET['id'])) {
    if ($user_id == 1) { 
        $id = $_GET['id'];
        // Ubah dept jadi Holiday agar baris jadi kuning & status update
        // Pastikan kolom status di database cukup panjang (VARCHAR 100)
        $sql = "UPDATE users SET status = 'Sedang Liburan', department = 'Holiday' WHERE id = :id";
        $conn->prepare($sql)->execute([':id' => $id]);
        
        echo "<script>alert('ASYIK! Minion dikirim liburan ke Bahama.'); window.location='dashboard.php';</script>";
        exit;
    }
}

// C. LOGIKA KEMBALI KERJA (Ubah Status -> Bekerja)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'kembali' && isset($_GET['id'])) {
    if ($user_id == 1) { 
        $id = $_GET['id'];
        // Kembalikan dept jadi Worker dan status Bekerja
        $sql = "UPDATE users SET status = 'Bekerja', department = 'Worker' WHERE id = :id";
        $conn->prepare($sql)->execute([':id' => $id]);
        
        echo "<script>alert('LIBURAN SELESAI! Kembali bekerja!'); window.location='dashboard.php';</script>";
        exit;
    }
}


// ============================================================
// 3. LOGIKA FILTER & MODE TAMPILAN
// ============================================================

// Default Mode Aman (50 Data)
$limit = 50; 

// Cek jika user minta Mode Siksa (500.000 Data)
if (isset($_GET['mode']) && $_GET['mode'] == 'siksa') { 
    $limit = 500000; 
}

// Persiapan Filter SQL
$where = "WHERE 1=1";
$params = [];

// Filter Nama (Search Bar)
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $where .= " AND u.full_name LIKE :search";
    $params[':search'] = "%" . $_GET['search'] . "%";
}

// Filter Departemen (Dropdown - Khusus Gru)
if (isset($_GET['dept']) && !empty($_GET['dept']) && $user_id == 1) {
    $where .= " AND u.department = :dept";
    $params[':dept'] = $_GET['dept'];
}

// ============================================================
// 4. EKSEKUSI QUERY DATABASE
// ============================================================
$sql = "SELECT u.id, u.full_name, u.department, u.status, u.salary, r.role_name 
        FROM users u
        JOIN user_roles ur ON u.id = ur.user_id
        JOIN roles r ON ur.role_id = r.id
        $where
        ORDER BY u.id DESC -- Data baru/edit muncul di atas
        LIMIT $limit";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$minions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil List Departemen untuk isi Dropdown (Hanya Gru)
$dept_list = [];
if ($user_id == 1) {
    $dept_list = $conn->query("SELECT DISTINCT department FROM users ORDER BY department")->fetchAll(PDO::FETCH_COLUMN);
}

// Fungsi Random Senjata (Hiasan Visual)
function getWeapon() {
    $list = ['Fart Gun', 'Freeze Ray', 'Cookie Robot', 'Squid Launcher', 'Banana'];
    return $list[array_rand($list)];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Markas Besar Gru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .blink { animation: blinker 1s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }
    </style>
</head>
<body class="bg-dark text-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary mb-3 shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
            <?php 
                if($user_id == 1) echo "👑 PANEL GRU";
                elseif($user_id == 2) echo "🧪 LAB NEFARIO";
                else echo "🍌 MINION DASHBOARD";
            ?>
        </a>
        <div class="d-flex gap-2">
            <?php if(!isset($_GET['mode'])): ?>
                <a href="dashboard.php?mode=siksa" class="btn btn-outline-warning btn-sm">🔴 Mode Siksa (500k)</a>
            <?php else: ?>
                <a href="dashboard.php" class="btn btn-outline-success btn-sm">🟢 Mode Aman (50)</a>
            <?php endif; ?>

            <?php if($user_id == 2): ?>
                <button class="btn btn-info fw-bold" onclick="alert('Akses Lab Diberikan!')">
                    <i class="bi bi-radioactive"></i> AKSES LAB
                </button>
            <?php endif; ?>

            <?php if($user_id == 1): ?>
                <a href="moon_boom.php" class="btn btn-danger fw-bold border-light blink">
                    🚀 HANCURKAN BULAN
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    
    <div class="card bg-secondary mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                
                <div class="col-md-4">
                    <?php if(isset($_GET['mode'])): ?><input type="hidden" name="mode" value="siksa"><?php endif; ?>
                    
                    <input type="text" name="search" class="form-control" placeholder="Cari Minion..." 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>

                <?php if($user_id == 1): ?>
                <div class="col-md-3">
                    <select name="dept" class="form-select">
                        <option value="">-- Semua Departemen --</option>
                        <?php foreach($dept_list as $dept): ?>
                            <option value="<?php echo $dept; ?>" 
                                <?php if(isset($_GET['dept']) && $_GET['dept'] == $dept) echo 'selected'; ?>>
                                <?php echo $dept; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
                <div class="col-md-1"><a href="dashboard.php" class="btn btn-warning w-100">Reset</a></div>
            </form>
        </div>
    </div>

    <div class="card text-dark shadow">
        <div class="card-body p-0">
            <div class="table-responsive" style="height: 650px; overflow: auto;">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Status (Dept)</th>
                            
                            <?php if($user_id == 1 || $user_id == 2): ?> 
                                <th>Gaji</th> 
                                <th>Persenjataan</th> 
                            <?php endif; ?>
                            
                            <th class="text-center">Aksi / Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($minions as $m): ?>
                        
                        <tr class="<?php echo ($m['department'] == 'Holiday') ? 'table-warning' : ''; ?>">
                            
                            <td><?php echo $m['id']; ?></td>
                            <td class="fw-bold"><?php echo $m['full_name']; ?></td>
                            <td><span class="badge bg-secondary"><?php echo $m['role_name']; ?></span></td>
                            
                            <td>
                                <?php if($m['department'] == 'Holiday'): ?>
                                    <span class="badge bg-success">🏖️ LIBURAN</span>
                                <?php else: ?>
                                    <?php echo $m['status']; ?> <br>
                                    <small class="text-muted">(<?php echo $m['department']; ?>)</small>
                                <?php endif; ?>
                            </td>

                            <?php if($user_id == 1 || $user_id == 2): ?>
                                <td class="text-success fw-bold">Rp <?php echo number_format($m['salary']); ?></td>
                                <td class="text-danger"><i class="bi bi-crosshair"></i> <?php echo getWeapon(); ?></td>
                            <?php endif; ?>

                            <td class="text-center">
                                <?php if($user_id == 1): // === MENU GRU === ?>
                                    
                                    <a href="dashboard.php?aksi=pecat&id=<?php echo $m['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin MEMECAT <?php echo $m['full_name']; ?>?')">
                                       🔥 Pecat
                                    </a>

                                    <?php if($m['department'] != 'Holiday'): ?>
                                        <a href="dashboard.php?aksi=libur&id=<?php echo $m['id']; ?>" class="btn btn-sm btn-success">
                                           🏖️ Liburkan
                                        </a>
                                    <?php else: ?>
                                        <a href="dashboard.php?aksi=kembali&id=<?php echo $m['id']; ?>" class="btn btn-sm btn-primary">
                                           💼 Kembali Kerja
                                        </a>
                                    <?php endif; ?>

                                <?php elseif($user_id == 2): // === MENU NEFARIO === ?>
                                    <button class="btn btn-sm btn-warning">🛠️ Upgrade</button>

                                <?php else: // === MENU MINION === ?>
                                    <span class="text-muted small">Tugas Harian</span>
                                <?php endif; ?>
                            </td>
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