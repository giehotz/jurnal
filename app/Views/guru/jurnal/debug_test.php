<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Jurnal Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="p-5">
    <div class="container">
        <h1>Debug Tool: Cek Koneksi Absensi</h1>
        <hr>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">Form Simulasi</div>
                    <div class="card-body">
                        <form id="debugForm">
                            <div class="form-group">
                                <label>Rombel ID (Contoh: 61)</label>
                                <input type="number" id="rombel_id" class="form-control" value="61">
                            </div>
                            <div class="form-group">
                                <label>Tanggal (Contoh: 2025-11-26)</label>
                                <input type="date" id="tanggal" class="form-control" value="2025-11-26">
                            </div>
                            <button type="button" class="btn btn-success" onclick="testConnection()">Test Request AJAX</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">Log Output</div>
                    <div class="card-body bg-light" style="height: 300px; overflow-y: auto;">
                        <pre id="logOutput">Menunggu test...</pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h3>Analisis Masalah:</h3>
            <ul>
                <li>Jika status <b>200 OK</b> tapi data <b>exists: false</b> -> Berarti data belum masuk ke tabel <code>rekap_absensi_harian</code>.</li>
                <li>Jika status <b>404 Not Found</b> -> Berarti Route URL salah.</li>
                <li>Jika status <b>500 Server Error</b> -> Ada error di Controller/Model.</li>
            </ul>
        </div>
    </div>

    <script>
        function log(msg) {
            var timestamp = new Date().toLocaleTimeString();
            $('#logOutput').prepend(`[${timestamp}] ${msg}\n`);
        }

        function testConnection() {
            var rombelId = $('#rombel_id').val();
            var tanggal = $('#tanggal').val();
            var url = '<?= base_url('guru/jurnal/check-daily-attendance') ?>'; // Adjust base URL manually if needed for raw HTML
            
            // Hardcode base URL for this standalone file test if needed, or assume relative
            // Since this is a view file served by CI, base_url() works.
            
            log(`Mengirim request ke: /guru/jurnal/check-daily-attendance`);
            log(`Data: rombel_id=${rombelId}, tanggal=${tanggal}`);

            $.ajax({
                url: '/guru/jurnal/check-daily-attendance', // Relative path
                type: 'POST',
                data: {
                    rombel_id: rombelId,
                    tanggal: tanggal,
                    // CSRF might be needed if enabled globally
                    'csrf_test_name': '<?= csrf_hash() ?>' 
                },
                success: function(response) {
                    log('✅ RESPONSE SUKSES:');
                    log(JSON.stringify(response, null, 2));
                    
                    if(response.exists) {
                        log('KESIMPULAN: Data DITEMUKAN! Fitur harusnya jalan.');
                    } else {
                        log('KESIMPULAN: Data TIDAK DITEMUKAN di database.');
                    }
                },
                error: function(xhr, status, error) {
                    log('❌ ERROR:');
                    log(`Status: ${xhr.status} ${xhr.statusText}`);
                    log(`Response: ${xhr.responseText}`);
                }
            });
        }
    </script>
</body>
</html>
