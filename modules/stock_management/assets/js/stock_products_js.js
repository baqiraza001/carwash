$(document).ready(function(){
	$('body').on('click' ,'.cancel-me', function(){
		window.location = admin_url + 'stock_management/stock_products';
	});
});

$('body').on('change','#tax',function(){
	var taxRate = $('#tax option:selected').attr('data-rate');
	$('#tax_rate').val(taxRate);
})

// Generate random product code
function generateCode(field) {
    var length = 12;
    var numbers = "1234567890";
    var retVal = "";
    for (var i = 0, n = numbers.length; i < length; ++i) {
        retVal += numbers.charAt(Math.floor(Math.random() * n));
    }
    $(field).closest('.row').find('input[name="product_code"]').val(retVal)
}


// $('body').on('change','#item_select',function(){
//     let item_id = $(this).val();
//      setTimeout(get_remaining_quantity(item_id),1000)
// });

function get_remaining_quantity(item_id = 0){
    var action= admin_url + 'stock_management/stock_products/quantity_available';
    $.ajax(action, {
        type: 'POST',
        data: {item_id},
    }).done(function(data){
        data = JSON.parse(data);
        let max_qty = data.count;
        max_qty = parseInt(max_qty);
        if(max_qty == 0)
            $('.main input[name="quantity"]').val(max_qty);
        
        $('.main input[name="quantity"]').attr('max', max_qty);
    });
}

/*$('body').on('change blur','.main input[name="quantity"]',function(){
    let input_qty = $(this).val();
    let max_qty = $('.main input[name="quantity"]').attr('max');
    max_qty = parseInt(max_qty);
    if(input_qty > max_qty)
        $('.main input[name="quantity"]').val(max_qty);
});*/
