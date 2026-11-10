$("body").on('change', 'select[name="sm_product"]', function () {
    var itemid = $(this).selectpicker('val');
    if (itemid != '') {
        stock_management_add_item_to_preview(itemid);
    }
});

// Add item to preview
function stock_management_add_item_to_preview(id) {
    requestGetJSON('stock_management/stock_products/get_item_by_id/' + id).done(function (response) {
        clear_item_preview_values();
        $('.main textarea[name="description"]').val(response.description);

        $('.main input[name="quantity"]').val(1);

        var taxSelectedArray = [];
        if (response.taxname && response.taxrate) {
            taxSelectedArray.push(response.taxname + '|' + response.taxrate);
        }

        $('.main select.tax').selectpicker('val', taxSelectedArray);
        $('.main input[name="unit"]').val(response.unit);

        var $currency = $("body").find('.accounting-template select[name="currency"]');
        var baseCurency = $currency.attr('data-base');
        var selectedCurrency = $currency.find('option:selected').val();
        var $rateInputPreview = $('.main input[name="rate"]');

        if (baseCurency == selectedCurrency) {
            $rateInputPreview.val(response.rate);
        } else {
            var itemCurrencyRate = response['rate_currency_' + selectedCurrency];
            if (!itemCurrencyRate || parseFloat(itemCurrencyRate) === 0) {
                $rateInputPreview.val(response.rate);
            } else {
                $rateInputPreview.val(itemCurrencyRate);
            }
        }

        $(document).trigger({
            type: "item-added-to-preview",
            item: response,
            item_type: 'item',
        });
    });
}