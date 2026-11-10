    $(function () {
        initDataTable('.table-product_categories', admin_url+'stock_management/manage_settings/categories', [], [], '', ['0', 'DESC']);
    });

    $('body').on('click','.category_edit',function () {
        var categorydescription = $(this).attr('data-categorydescription');
        var categoryname = $(this).attr('data-categoryname');
        var categoryid = $(this).attr('data-categoryid');
        $('.category_name_v').val(categoryname);
        $('.description_v').val(categorydescription);
        $('#categoryid').val(categoryid);

    });
    $('body').on('click','.warehouse_edit',function () {
        var warehousedescription = $(this).attr('data-warehousedescription');
        var warehousename = $(this).attr('data-warehousename');
        var warehouseaddress = $(this).attr('data-warehouseaddress');
        var dtid = $(this).attr('data-warehouseid');
        $('.warehouse_name').val(warehousename);
        $('.warehouse_description').val(warehousedescription);
        $('.warehouse_address').val(warehouseaddress);
        $('#warehouseid').val(dtid);

    });

    $('body').on('click','.categories_tab',function () {
        $("#categories_tab").show();
        $("#warehouse_tab").hide();
    });

        $('body').on('click','.warehouse_tab',function () {
        $("#warehouse_tab").show();
        $("#categories_tab").hide();

    });
        initDataTable('.table-warehouse', admin_url+'stock_management/manage_settings/warehouse', [], [], '', ['0', 'DESC']);
