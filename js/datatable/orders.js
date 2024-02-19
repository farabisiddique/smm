

$(document).ready(function() {
    var t = $('#allOrders').DataTable( {
      "bAutoWidth": false,
      "columnDefs": [ {
          "searchable": false,
          "orderable": true,
          "defaultContent": "-",
          "targets": "_all" 
      } ],
      "language": {
          "infoFiltered": ""
      },
      "responsive": true,
      "order": [[ 1, 'asc' ]],
      "paging": true,
      "responsive": true,
      "lengthChange": true,
      "pageLength": 25,
      "processing": true,
      "serverSide": true,
      "searching": false,
      "ajax": {
          "url": "./all_orders.php",
          "type": "GET",
          "data": function (data) {
              console.log(data);
          },
          "error": function(err, status){
             console.log(err);
          },
  
      }

      
    } );

    t.columns.adjust().draw();

    $(".orderStatusCheck").click(function(){
        console.log("clicked");
        $(".loading-overlay").show();
        var orderId = $(this).data("orderid");
        console.log(orderId);
        $(".loading-overlay").hide();


    });





} );