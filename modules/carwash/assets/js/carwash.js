$(function () {
        var customer_id = $('input[name="userid"]').val();
        initDataTable('.table-carwash', admin_url + 'carwash/table/?clientid=' + customer_id, [], [], '', [0, 'DESC']);

    
        $('body').on('click','.customer_profile #carwash_add_vehicle',function(){
                $('#vehicle').val('');
                $('#vin').val('');
                $('#vehicle_id').val('');
                $('#vehicles_modal').modal('show');
        });

        $('body').on('click','.customer_profile .carwash_edit_vehicle',function(){
                $('#vehicle').val($(this).attr('data-vehicle'));
                $('#vin').val($(this).attr('data-vin'));
                $('#plate_no').val($(this).attr('data-plate_no'));
                $('#vehicle_id').val($(this).attr('data-id'));
                $('#vehicles_modal').modal('show');
        });

        $('body').on('click','.customer_profile #carwash_save_vehicle',function(){
                var vehicle = $('#vehicle').val();
                var vin = $('#vin').val();
                var plate_no = $('#plate_no').val();
                var id = $('#vehicle_id').val();
                var clientid = customer_id;
                var action =  admin_url + 'carwash/create';
                $.ajax(action, {
                        type: 'POST',
                        data: {vehicle : vehicle, vin : vin, plate_no : plate_no, vehicle_id : id, clientid : clientid},
         
                }).done(function(data){
                        data = JSON.parse(data);
                        alert_float(data.status,data.message);
                        $('.table-carwash').DataTable().ajax.reload();
                        $('#vehicles_modal').modal('hide');
                });
        });

        $('body').on('click','.customer_profile .delete_vehicle',function(){
                if(confirm_delete()){
                        var id = $(this).attr('data-id');
                        var action =  admin_url + 'carwash/delete';
                        $.ajax(action, {
                                type: 'POST',
                                data: {id : id}
                        }).done(function(data){
                                data = JSON.parse(data);
                                $('.table-carwash').DataTable().ajax.reload();
                                alert_float(data.status,data.message);
                        });
                }
        })


        
        /* search nags tables */
        $("body").on('change', 'select[name="cw_nags"]', function () {
            var selected_nag = $(this).val();
             $('.main textarea[name="description"]').val(selected_nag);
        });
        
});


/* invoices cashout modal*/
$('#carwash-cashout-btn').on('click',function () {
    initDataTable('.table-carwash_invoices_payments', admin_url + 'carwash/tables/payments/', [], [], '', ['0','DESC']);
    $('#carwash_cashout_modal').modal('show');

$("#carwash_cashout_modal .table-carwash_invoices_payments").append(`<tfoot>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total"></td>
            <td></td>
         </tfoot>`)

var paymentReceivedReportsTable = $('#carwash_cashout_modal .table-carwash_invoices_payments').DataTable();
 
paymentReceivedReportsTable.on( 'draw', function () {
     var sums = paymentReceivedReportsTable.ajax.json().sums;
    $('#carwash_cashout_modal .table-carwash_invoices_payments').find('tfoot').addClass('bold');
     $('#carwash_cashout_modal .table-carwash_invoices_payments').find('tfoot td').eq(0).html("Total (Per Page)");
     $('#carwash_cashout_modal .table-carwash_invoices_payments').find('tfoot td.total').html(sums.total_amount);
} );


    return false;
});
/* invoices cashout modal*/


$('#clientid').on('change', function (e) {
    // Get the current value of the select element
    var clientid = $(this).val();
    if(clientid)  
    {
        var action =  admin_url + 'carwash/search_client_for_taxes';
        $.ajax(action, {
            type: 'POST',
            data: {clientid},
        }).done(function(data){
            let client = JSON.parse(data);
            if(client.client_taxes)
                $('.main .main-tax').val(JSON.parse(client.client_taxes)).selectpicker('refresh');
            else
                $('.main .main-tax').val([]).selectpicker('refresh');
        });
    }
});