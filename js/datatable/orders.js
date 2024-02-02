

$(document).ready(function() {
    var t = $('#allOrders').DataTable( {
        "bAutoWidth": false,
        "columnDefs": [ {

            // "sWidth": "30%",
            // "searchable": true,
            // "orderable": true,
            // "defaultContent": "-",
            // "targets": "_all"

        } ],
        "language": {
            "infoFiltered": ""
        },

        "order": [[ 1, 'asc' ]],
        // "paging": true,
        // "responsive": true,
        // "lengthChange": true,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        "ajax": "datatable/all_orders.php"
    } );

    t.columns.adjust().draw();





} );