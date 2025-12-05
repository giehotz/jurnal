/**
 * Guru Jurnal Scripts
 * Handles DataTables initialization.
 */

$(function () {
    if ($("#jurnal-table").length) {
        $("#jurnal-table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "order": [[1, "desc"]]
        });
    }
});
