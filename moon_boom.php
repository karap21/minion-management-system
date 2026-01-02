<!DOCTYPE html>
<html lang="id">
<head>
    <title>SISTEM PENGHANCURAN BULAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: black; color: red; font-family: 'Courier New', monospace; text-align: center; display: flex; flex-direction: column; justify-content: center; height: 100vh; }
        .countdown { font-size: 8rem; font-weight: bold; }
        .warning { font-size: 2rem; animation: blink 0.5s infinite; }
        @keyframes blink { 50% { opacity: 0; } }
    </style>
</head>
<body>
    <div class="warning">⚠️ PERINGATAN: PROTOKOL KESELAMATAN DIAKTIFKAN ⚠️</div>
    <h1 class="mt-4">MELUNCURKAN ROKET DALAM:</h1>
    <div id="timer" class="countdown">10</div>
    <p>Target: THE MOON 🌑</p>
    <a href="dashboard.php" class="btn btn-outline-light mt-5">BATALKAN MISI</a>

    <script>
        let timeLeft = 10;
        const timer = document.getElementById('timer');
        const interval = setInterval(() => {
            timeLeft--;
            timer.innerText = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(interval);
                document.body.innerHTML = "<h1 style='color:white; font-size:5rem; margin-top:20%'>💥 BOOOOM!!! 💥</h1><p style='color:white'>Bulan berhasil diledakkan.</p><a href='dashboard.php' style='color:yellow'>Kembali ke Markas</a>";
            }
        }, 1000);
    </script>
</body>
</html>