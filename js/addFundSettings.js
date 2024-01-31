// script.js

$(document).ready(function() {
    $('#fndamnt').keyup(function() {
        var amountInUSD = $('#fndamnt').val();
        console.log(amountInUSD);
        $.ajax({
            url: './add_fund_settings.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var dollaRate = data.dollarRate;
                var tnxFee = amountInUSD * 15;
                var amountInBDT = (amountInUSD * dollaRate) + tnxFee;
                $('#fndfee').val(tnxFee);
                $('#fndamntBDT').val(amountInBDT);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error:', textStatus, errorThrown);
            }
        });
    });
});
