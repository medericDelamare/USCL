$('http://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', function() {
    $('.tableau-buteur').DataTable({
        "order": [[1, "desc"]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/French.json"
        },
        "autoWidth": false,
        "paging": false,
        "bInfo": false,
        "searching": false
    });
})