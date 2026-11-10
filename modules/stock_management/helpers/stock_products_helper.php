<?php

function stock_get_countries() {
	$CI = &get_instance();

    return $CI->db->get('tblcountries')->result_array();
}

function stock_get_currencies() {
	$CI = &get_instance();

    return $CI->db->get('tblcurrencies')->result_array();
}

function sm_generate_random_string($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function sm_item_qty_find($product , $warehouse){
    $CI = &get_instance();
    return $CI->db->where('product_id' , $product)->where('warehouse_id' , $warehouse)->get('tbl_sm_warehouse_products')->row('quantity');
}

function stock_managment_default_warehouse(){
    $CI = &get_instance();
    return $CI->db->where('isdefault' , 1)->get('tbl_sm_warehouses')->row('id');
}

function render_stock_management_datatable($headings = [], $class = '', $additional_classes = [''], $table_attributes = [])
{
    $_additional_classes = '';
    $_table_attributes   = ' ';
    if (count($additional_classes) > 0) {
        $_additional_classes = ' ' . implode(' ', $additional_classes);
    }
    $CI      = & get_instance();
    $browser = $CI->agent->browser();
    $IEfix   = '';
    if ($browser == 'Internet Explorer') {
        $IEfix = 'ie-dt-fix';
    }

    foreach ($table_attributes as $key => $val) {
        $_table_attributes .= $key . '=' . '"' . $val . '" ';
    }

    $table = '<div class="' . $IEfix . '"><table' . $_table_attributes . 'class="dt-table-loading table table-' . $class . '' . $_additional_classes . '">';
    $table .= '<thead>';
    $table .= '<tr>';
    foreach ($headings as $heading) {
        if (!is_array($heading)) {
            $table .= '<th>' . $heading . '</th>';
        } else {
            $th_attrs = '';
            if (isset($heading['th_attrs'])) {
                foreach ($heading['th_attrs'] as $key => $val) {
                    $th_attrs .= $key . '=' . '"' . $val . '" ';
                }
            }
            $th_attrs = ($th_attrs != '' ? ' ' . $th_attrs : $th_attrs);
            $table .= '<th' . $th_attrs . '>' . $heading['name'] . '</th>';
        }
    }
    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody></tbody>';
    $table .= '<tfoot>
      <tr>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td class="total_qty"></td>
         <td class="total_inventory_value"></td>
         <td class="total_remaining_qty"></td>
    </tr>
    </tfoot>';
    $table .= '</table></div>';
    echo $table;
}


function sm_adjust_inventory_stock($data = ''){
    $CI = &get_instance();
    $warehouse_id = empty($data['warehouse_id']) ? stock_managment_default_warehouse() : $data['warehouse_id'];
	
    $checkout_data = array(
        "customer" => 1,
        "date" => date('Y-m-d'),
        "warehouse" => $warehouse_id,
        "reference" => 'Stock Returned Manually',
        "note" => '',
        "shelf" => '',
        "item" => $data['item_id'],
        "units" => -($data['qty']),
        "created_by" => get_staff_user_id(),
        "created_on" => date('Y-m-d H:i:s')
    );
    
        
    $CI->db->set($checkout_data);
    $CI->db->insert('tbl_sm_checkouts');
    $inserted_id = $CI->db->insert_id();
    $updated_quantity = 0;
    
    if($inserted_id){
        $warehouse_data['warehouse_id'] = $warehouse_id;
        $warehouse_data['product_id'] = $data['item_id'];
        $warehouse_data['quantity'] = $data['qty'];
        
        //check if warehouse data already exists
        $data_exists = $CI->db->where('warehouse_id', $warehouse_id)->where('product_id' , $data['item_id'])->get('tbl_sm_warehouse_products')->row_array();
        if(isset($data_exists) && !empty($data_exists)){
            $existing_quantity = $data_exists['quantity'];
            $updated_quantity = $existing_quantity - (-($data['qty']));
            $CI->db->where('warehouse_id' , $warehouse_id)->where('product_id' , $data['item_id'])->set('quantity',$updated_quantity)->update('tbl_sm_warehouse_products');
        }else{
            $CI->db->insert('tbl_sm_warehouse_products' , $warehouse_data);
        }
    }
    
    return ['qty' => $updated_quantity];
}