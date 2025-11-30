
> Bantu saya mengimplementasikan **AdminLTE v4.0.0-rc4** ke dalam proyek **CodeIgniter 4** saya.
> Saat ini template lama masih dibuat secara manual di folder `app/Views`. Saya ingin **menghapus template lama tersebut** dan menggantinya dengan **template AdminLTE**.
>
> Detail proyek:
>
> * Struktur proyek mengikuti standar **CodeIgniter 4**.
> * Template **AdminLTE** telah saya letakkan di: `public/AdminLTE/`
> * Saya ingin memanfaatkan layout AdminLTE sepenuhnya (dashboard, sidebar, navbar, dll) dengan sistem **extend** dan **section** milik CodeIgniter (`extend('layout')`, `section('content')`, dll).
>
> Tugas Anda:
>
> 1. Pelajari terlebih dahulu **alur kerja CodeIgniter 4** dan **struktur AdminLTE v4**.
> 2. Setelah memahami alurnya, **langsung implementasikan secara penuh** AdminLTE pada proyek CodeIgniter ini.
> 3. Buatkan **langkah-langkah terperinci** untuk menghapus template lama yang dibuat manual.
> 4. Buatkan **struktur folder `app/Views`** yang baru sesuai standar CodeIgniter dan AdminLTE.
> 5. Tulis contoh implementasi:
>
>    * File `app/Views/layouts/main.php` sebagai layout utama.
>    * File `app/Views/dashboard.php` yang mewarisi layout tersebut.
> 6. Pastikan semua file **CSS**, **JS**, dan **asset AdminLTE** dipanggil dengan benar dari `public/AdminLTE/`.
> 7. Jika perlu, jelaskan pengaturan **BaseURL** di `app/Config/App.php` agar path asset berjalan benar.
> 8. Tambahkan tips untuk menjaga kompatibilitas dengan **update AdminLTE berikutnya**.
>
> Hasil akhir yang saya inginkan:
>
> * Template manual lama sudah dihapus.
> * Tampilan dashboard CodeIgniter menggunakan AdminLTE sepenuhnya.
> * Struktur layout sudah modular dan siap dikembangkan untuk modul-modul lain (guru, siswa, keuangan, dll).
> * Implementasi dilakukan langsung setelah Anda memahami alur proyek.