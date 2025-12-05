Dokumentasi Fitur Proyek Jurnal Guru & Absensi
Dokumen ini menjelaskan fitur-fitur yang tersedia untuk setiap peran pengguna (User Role) dalam sistem Jurnal Guru dan Absensi.

1. Admin
Admin memiliki akses penuh untuk mengelola data master, pengguna, dan pengaturan sistem.

Fitur Utama:
Dashboard: Halaman utama yang menampilkan ringkasan statistik sistem.
Profil: Melihat dan mengubah informasi profil admin.
Manajemen Absensi:
Melihat, membuat, mengedit, dan menghapus data absensi siswa.
Melihat detail absensi per siswa.
Ekspor data absensi.
Debug data rombel dan siswa.
Pindah Kelas:
Memindahkan siswa antar kelas (Rombel) secara massal.
Manajemen Data Master:
Kelas: Mengelola data tingkat kelas (Create, Read, Update, Delete).
Rombel (Rombongan Belajar): Mengelola data rombel, menetapkan siswa ke rombel, serta upload/download template data rombel.
Ruangan: Mengelola data ruangan kelas.
Mata Pelajaran (Mapel): Mengelola daftar mata pelajaran, termasuk import data dan download template.
Siswa: Mengelola data siswa lengkap, termasuk fitur import data siswa dari Excel.
Manajemen Pengguna (User Management):
Mengelola akun pengguna (Admin, Guru, Kepala Sekolah, dll).
Reset password pengguna.
Import data pengguna dari Excel.
Monitoring Jurnal:
Memantau jurnal mengajar yang diisi oleh guru.
Melihat detail jurnal.
Ekspor data monitoring ke PDF dan Excel.
Laporan:
Akses berbagai laporan: Laporan Guru, Laporan Jurnal, dan Statistik.
Ekspor laporan ke format PDF dan Excel.
Pengaturan (Settings):
Aplikasi: Pengaturan umum aplikasi.
Maintenance: Fitur untuk mengaktifkan mode pemeliharaan.
QR Code: Pengaturan global untuk fitur QR Code.
Auto-Route: Mengelola izin rute dinamis.
Ekspor/Impor: Fitur umum untuk generate file PDF dan Excel.
2. Guru
Peran Guru difokuskan pada kegiatan belajar mengajar sehari-hari, seperti mengisi jurnal dan absensi kelas.

Fitur Utama:
Dashboard: Ringkasan aktivitas guru.
Manajemen Jurnal Mengajar:
Membuat, melihat, mengedit, dan menghapus jurnal harian.
Cek absensi harian saat mengisi jurnal.
Ekspor jurnal sendiri ke PDF dan Excel.
Manajemen Absensi:
Input absensi siswa di kelas yang diajar.
Melihat rekap dan detail absensi.
Ekspor laporan absensi.
Profil:
Mengelola profil pribadi.
Mengubah password akun.
QR Code:
Membuat dan mengelola QR Code (misalnya untuk keperluan absensi atau materi).
Download dan render QR Code.
3. Kepala Sekolah
Kepala Sekolah memiliki akses untuk memantau kegiatan belajar mengajar dan melihat laporan.

Fitur Utama:
Dashboard: Ringkasan statistik sekolah.
Monitoring Jurnal:
Memantau jurnal mengajar seluruh guru.
Melihat detail isi jurnal.
Laporan:
Mengakses laporan kinerja guru.
Melihat rekapitulasi jurnal.
Melihat statistik pembelajaran.
Profil:
Melihat dan mengubah informasi profil.
Catatan Tambahan
Autentikasi: Semua peran memerlukan login untuk mengakses fitur-fitur di atas.
Hak Akses: Fitur dibatasi berdasarkan peran pengguna yang login (Middleware/Filter auth).
4. Rekomendasi Fitur Tambahan
Berikut adalah beberapa rekomendasi fitur yang dapat dikembangkan untuk meningkatkan fungsionalitas sistem:

A. Notifikasi & Komunikasi
Notifikasi WhatsApp/Email: Mengirim notifikasi otomatis kepada orang tua jika siswa tidak hadir (Alpha/Bolos) atau kepada guru jika belum mengisi jurnal harian.
Pengumuman Sistem: Fitur bagi Admin/Kepala Sekolah untuk membuat pengumuman yang muncul di dashboard Guru.
B. Integrasi & Aksesibilitas
Portal Orang Tua (Wali Murid): Akun khusus bagi orang tua untuk memantau kehadiran dan jurnal pembelajaran anak mereka secara real-time.
Aplikasi Mobile / PWA: Mengembangkan Progressive Web App (PWA) agar aplikasi lebih mudah diakses melalui smartphone dengan fitur notifikasi push.
Integrasi Kalender Akademik: Menampilkan kalender akademik di dashboard yang berisi jadwal libur, ujian, dan kegiatan sekolah.
C. Manajemen Absensi & Jurnal Lanjutan
Upload Surat Izin/Sakit: Fitur bagi siswa/orang tua untuk mengunggah foto surat dokter atau surat izin, yang kemudian divalidasi oleh Wali Kelas/Admin.
Rekapitulasi Keterlambatan: Menambahkan status "Terlambat" pada absensi dengan pencatatan jam kedatangan.
Jurnal Mengajar dengan Bukti Foto: Mewajibkan atau memfasilitasi guru untuk mengunggah foto kegiatan belajar mengajar (KBM) sebagai bukti fisik dalam jurnal.
D. Analitik & Gamifikasi
Analitik Lanjutan: Grafik yang lebih mendalam mengenai tren kehadiran siswa per bulan, perbandingan kehadiran antar kelas, dan kinerja guru.
Gamifikasi Kehadiran: Memberikan "Badges" atau penghargaan digital bagi siswa dengan kehadiran 100% dalam satu semester untuk memotivasi siswa.
