Berikut rangkuman langkah kerja Git lokal dari awal sampai merge.

1. Inisialisasi repo.
   git init

2. Tambah semua file.
   git add .

3. Buat commit pertama.
   git commit -m "commit awal"

4. Buat branch fitur.
   git branch fitur-Qrcode

5. Pindah ke branch fitur.
   git switch fitur-Qrcode

6. Kerjakan fitur.
   Edit file
   git add .
   git commit -m "tambah fitur qrcode"

7. Pindah ke branch utama.
   git switch master

8. Gabungkan hasil kerja.
   git merge fitur-Qrcode

9. Hapus branch fitur jika sudah selesai.
   git branch -d fitur-Qrcode
