h# Laporan Helper

Helper ini menyediakan fungsi untuk menghasilkan tabel tanda tangan dalam laporan PDF.

## Fungsi yang Tersedia

### `generateSignatureTable($headmasterData, $teacherData)`

Fungsi ini menghasilkan HTML untuk tabel tanda tangan dengan informasi kepala sekolah dan guru.

#### Parameter

- `$headmasterData` (array|null): Data kepala sekolah dengan key setidaknya 'nama' dan 'nip'
- `$teacherData` (array|null): Data guru dengan key setidaknya 'nama' dan 'nip'

#### Return

- `string`: HTML dalam bentuk string yang dapat digunakan dalam view atau generator PDF

#### Contoh Penggunaan

```php
// Di controller
$headmaster = [
    'nama' => 'Drs. Kepala Sekolah, M.Pd.',
    'nip' => '19700101 199702 1 001'
];

$teacher = [
    'nama' => 'Dra. Guru Mata Pelajaran',
    'nip' => '19751231 200012 2 002'
];

$htmlSignature = generateSignatureTable($headmaster, $teacher);

// Kemudian gunakan $htmlSignature dalam view atau PDF
```

#### Output

Fungsi ini akan menghasilkan HTML seperti berikut:

```html
<table style="width: 100%; border-collapse: collapse; margin-top: 40px;">
    <tr>
        <td style="width: 45%; vertical-align: top;">
            Mengetahui,<br>
            Kepala Sekolah<br>
            <div style="margin-top: 60px;">
                <strong>Drs. Kepala Sekolah, M.Pd.</strong><br>
                NIP: 19700101 199702 1 001
            </div>
        </td>
        <td style="width: 10%;"></td>
        <td style="width: 45%; vertical-align: top;">
            Guru Mata Pelajaran,<br>
            <div style="margin-top: 60px;">
                <strong>Dra. Guru Mata Pelajaran</strong><br>
                NIP: 19751231 200012 2 002
            </div>
        </td>
    </tr>
</table>
```

## Cara Menggunakan Helper

Untuk menggunakan helper ini, cukup panggil fungsi `generateSignatureTable()` dengan data yang sesuai.

Jika menggunakan dalam controller, pastikan helper diload terlebih dahulu:

```php
helper('laporan');

// Atau jika menggunakan beberapa helper sekaligus
helper(['laporan', 'tanggal']);
```

Helper ini juga bisa digunakan langsung dalam view jika helper sudah diload sebelumnya.