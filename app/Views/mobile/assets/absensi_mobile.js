// Script untuk menangani tampilan mobile absensi
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk toggle tampilan antara desktop dan mobile
    function toggleMobileView() {
        const isMobile = window.innerWidth < 768;
        const tables = document.querySelectorAll('.table');
        
        tables.forEach(table => {
            if (isMobile) {
                convertTableToMobile(table);
            } else {
                convertTableToDesktop(table);
            }
        });
    }

    // Konversi tabel ke tampilan mobile
    function convertTableToMobile(table) {
        // Cek jika sudah dikonversi
        if (table.classList.contains('table-mobile-responsive')) return;

        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent);
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                cell.setAttribute('data-label', headers[index]);
            });
        });

        table.classList.add('table-mobile-responsive');
    }

    // Konversi tabel ke tampilan desktop (original)
    function convertTableToDesktop(table) {
        table.classList.remove('table-mobile-responsive');
        
        const cells = table.querySelectorAll('td[data-label]');
        cells.forEach(cell => {
            cell.removeAttribute('data-label');
        });
    }

    // Panggil fungsi saat load dan resize
    toggleMobileView();
    window.addEventListener('resize', toggleMobileView);
});