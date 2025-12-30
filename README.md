Panduan Instalasi Proyek Markas Gru (Minion Management System)


---

1. Persiapan Awal (Wajib)

Pastikan laptop kalian sudah terinstall:

* Laragon(untuk Server PHP & MySQL).
* Git (untuk download kodingan).
* JMeter (untuk tugas Stress Test nanti).

---

2. Cara Download Codingan (Clone)

1. Buka Terminal (Cmder/Laragon Terminal).
2. Masuk ke folder `www` Laragon:
```bash

cd C:\laragon\www

```


3. Download proyek ini:
```bash

git clone https://github.com/USERNAME_ANDA/minion-management-system.git

```


(Ganti `USERNAME_ANDA` dengan username GitHub karap21)*
4. Masuk ke folder proyek:
```bash

cd minion-management-system

```


(Jika nama foldernya panjang, boleh direname jadi `gru-base` biar gampang).*

---

3. Cara Setup Database (PENTING!)

Kodingan tidak akan jalan kalau databasenya kosong. Pilih salah satu cara:

Cara A: Generate Sendiri (Disarankan)

1. Buka **HeidiSQL**.
2. Buat database baru bernama `despicable_db`.
3. Buka file `database_setup.sql` yang ada di folder proyek ini.
4. Copy semua isinya -> Paste di tab Query HeidiSQL -> Klik **Run (Play)**.
5. Tunggu 2-3 menit. Script akan otomatis membuat tabel & 500.000 data minion.

Cara B: Import Data Jadi (cara ringan)

1. Download file `full_data_minion.zip` (kalau ada) atau minta file SQL ke ketua.
2. Di HeidiSQL, klik menu **File** > **Load SQL file**.
3. Pilih filenya, lalu Run.

---

4. Cara Menjalankan Website

1. Pastikan tombol **Start All** di Laragon sudah diklik.
2. Buka browser, akses:
* Jika folder bernama `gru-base`: `http://gru-base.test/dashboard.php`
* Jika folder bernama `minion-management-system`: `http://localhost/minion-management-system/dashboard.php`



---

5. Akun Login (Cheat Sheet)

Gunakan ID ini untuk mengetes fitur RBAC (Hak Akses):

| Role | ID Login | Fitur yang Bisa Dilihat |
| --- | --- | --- |
| **Gru (Supreme Admin)** | `1` | Tombol Ledakkan Bulan, Edit, Hapus, Lihat Gaji. |
| **Dr. Nefario (Scientist)** | `2` | Lihat Gaji, tapi tidak bisa Hapus. |
| **Minion (Worker)** | `500` | Cuma bisa lihat daftar nama (Read Only). |

---

6. Cara Stress Test (Tugas JMeter)

Untuk mendapatkan grafik laporan:

1. Buka **JMeter**.
2. Buat *HTTP Request* baru.
3. Set Path ke:
* Mode Ringan: `/gru-base/dashboard.php`
* Mode Siksa: `/gru-base/dashboard.php?mode=siksa`


4. Jalankan dengan 100 User.

---



---

### **Cara Upload File Ini ke GitHub**

Setelah Anda membuat file `README.md` di folder laptop Anda:

1. Buka Terminal Laragon.
2. Ketik:
```bash
git add README.md
git commit -m "Menambahkan panduan instalasi untuk kelompok"
git push

```
