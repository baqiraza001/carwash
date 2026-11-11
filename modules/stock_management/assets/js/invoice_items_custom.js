/* VERSION: 2026-11-11 07:15 PKT
   Custom invoice-item code MOVED OUT of the old (custom) main.js into the
   stock_management module, so the next CRM update can no longer overwrite
   it. Core assets/js/main.js stays 100% stock - do NOT edit it for this.
   Loaded after main.js via app_admin_footer (stock_management_load_js),
   so the function overrides below win over the stock ones.
   What was moved (from old main.js):
   - item_id set / clear / read in the item preview flow
   - item_id hidden input in each added invoice row (add_item_to_table)
   - verify_input_qty() - caps qty at available stock (moved as-is;
     NOTE: it currently has NO caller, see the function comment)
   The carwash client-taxes merge that also lived in old main.js was moved
   to the carwash module instead (assets/js/carwash.js) - it calls the
   carwash endpoint, so it belongs there. */

// When an item is added to the preview, stock main.js triggers
// "item-added-to-preview" with the item data (response.itemid = tblitems id).
// Old main.js set the hidden item_id field from that same response here.
$(document).on("item-added-to-preview", function (e) {
  if (!e.item || e.item_type !== "item" || !e.item.itemid) {
    return;
  }
  var $field = $('.main input[name="item_id"]');
  if ($field.length) {
    $field.val(e.item.itemid);
  }
});

// Clear the hidden item_id field together with the rest of the preview.
// (Old main.js did this inside clear_item_preview_values.)
if (typeof clear_item_preview_values === "function") {
  var _sm_orig_clear_item_preview_values = clear_item_preview_values;
  clear_item_preview_values = function (default_taxes) {
    _sm_orig_clear_item_preview_values(default_taxes);
    $('.main input[name="item_id"]').val("");
  };
}

// Read the hidden item_id field into the preview values, so
// add_item_to_table() can write it into the added row.
// (Old main.js did this inside get_item_preview_values.)
if (typeof get_item_preview_values === "function") {
  var _sm_orig_get_item_preview_values = get_item_preview_values;
  get_item_preview_values = function () {
    var response = _sm_orig_get_item_preview_values();
    var $field = $('.main input[name="item_id"]');
    response.item_id = $field.length ? $field.val() || "" : "";
    return response;
  };
}

// add_item_to_table() - stock function copied verbatim from the NEW CRM
// assets/js/main.js, plus ONE custom line (the item_id hidden input right
// after the .order input). If Perfex changes this stock function in a
// future update, update this copy the same way.
function add_item_to_table(data, itemid, merge_invoice, bill_expense) {
  // If not custom data passed get from the preview
  data =
    typeof data == "undefined" || data == "undefined"
      ? get_item_preview_values()
      : data;
  if (
    data.description === "" &&
    data.long_description === "" &&
    data.rate === ""
  ) {
    return;
  }

  var table_row = "";
  var item_key = lastAddedItemKey
    ? (lastAddedItemKey += 1)
    : $("body").find("tbody .item").length + 1;
  lastAddedItemKey = item_key;

  table_row +=
    '<tr class="sortable item" data-merge-invoice="' +
    merge_invoice +
    '" data-bill-expense="' +
    bill_expense +
    '">';

  table_row += '<td class="dragger">';

  // Check if quantity is number
  if (isNaN(data.qty)) {
    data.qty = 1;
  }

  // Check if rate is number
  if (data.rate === "" || isNaN(data.rate)) {
    data.rate = 0;
  }

  var amount = data.rate * data.qty;

  var tax_name = "newitems[" + item_key + "][taxname][]";
  $("body").append('<div class="dt-loader"></div>');
  var regex = /<br[^>]*>/gi;
  get_taxes_dropdown_template(tax_name, data.taxname).done(function (
    tax_dropdown
  ) {
    // order input
    table_row +=
      '<input type="hidden" class="order" name="newitems[' +
      item_key +
      '][order]">';

    // Custom (moved from old main.js): keep the item id in the row so the
    // stock deduction in before_invoice_added and verify_input_qty() can
    // use it. Placed right after .order so .order.next() is this input.
    table_row +=
      '<input type="hidden" name="newitems[' +
      item_key +
      '][item_id]" value="' +
      (data.item_id || "") +
      '">';
    table_row += "</td>";

    table_row +=
      '<td class="bold description"><textarea name="newitems[' +
      item_key +
      '][description]" class="form-control" rows="5">' +
      data.description +
      "</textarea>";

    table_row += '<div class="tw-mt-1.5">';
    table_row += '<div class="checkbox checkbox-info">';
    table_row += '<input class="optional-item-checkbox" ' + (data.is_optional ? ' checked' : '') + ' value="1" id="' + item_key + '-optional" type="checkbox" name="newitems[' + item_key + '][is_optional]" data-index="' + item_key + '" />';
    table_row += '<label for="' + item_key + '-optional">' + app.lang.item_is_optional + '</label>';
    table_row += '</div>';

    table_row += '<div class="checkbox" style="' + (data.is_optional ? '' : 'display: none; ') + '">';
    table_row += '<input class="optional-choose-item-checkbox" ' + (data.is_selected ? ' checked' : '') + ' value="1" id="' + item_key + '-optional-choosen" type="checkbox" name="newitems[' + item_key + '][is_selected]" />';
    table_row += '<label for="' + item_key + '-optional-choosen">' + app.lang.item_is_selected + '</label>';
    table_row += '</div>';
    table_row += '</div>';

    table_row += "</td>";

    table_row +=
      '<td><textarea name="newitems[' +
      item_key +
      '][long_description]" class="form-control item_long_description" rows="5">' +
      data.long_description.replace(regex, "\n") +
      "</textarea></td>";

    var custom_fields = $("tr.main td.custom_field");
    var cf_has_required = false;

    if (custom_fields.length > 0) {
      $.each(custom_fields, function () {
        var cf = $(this).clone();
        var cf_html = "";
        var cf_field = $(this).find("[data-fieldid]");
        var cf_name =
          "newitems[" +
          item_key +
          "][custom_fields][items][" +
          cf_field.attr("data-fieldid") +
          "]";

        if (cf_field.is(":checkbox")) {
          var checked = $(this).find('input[type="checkbox"]:checked');
          var checkboxes = cf.find('input[type="checkbox"]');

          $.each(checkboxes, function (i, e) {
            var random_key = Math.random().toString(20).slice(2);
            $(this)
              .attr("id", random_key)
              .attr("name", cf_name)
              .next("label")
              .attr("for", random_key);
            if ($(this).attr("data-custom-field-required") == "1") {
              cf_has_required = true;
            }
          });

          $.each(checked, function (i, e) {
            cf.find('input[value="' + $(e).val() + '"]').attr("checked", true);
          });

          cf_html = cf.html();
        } else if (cf_field.is("input") || cf_field.is("textarea")) {
          if (cf_field.is("input")) {
            cf.find("[data-fieldid]").attr("value", cf_field.val());
          } else {
            cf.find("[data-fieldid]").html(cf_field.val());
          }
          cf.find("[data-fieldid]").attr("name", cf_name);
          if (
            cf.find("[data-fieldid]").attr("data-custom-field-required") == "1"
          ) {
            cf_has_required = true;
          }
          cf_html = cf.html();
        } else if (cf_field.is("select")) {
          if ($(cf_field).attr("data-custom-field-required") == "1") {
            cf_has_required = true;
          }

          var selected = $(this)
            .find("select[data-fieldid]")
            .selectpicker("val");
          selected = typeof (selected != "array")
            ? new Array(selected)
            : selected;

          // Check if is multidimensional by multi-select customfield
          selected = selected[0].constructor === Array ? selected[0] : selected;

          var selectNow = cf.find("select");
          var $wrapper = $("<div/>");
          selectNow.attr("name", cf_name);

          var $select = selectNow.clone();
          $select.prop('required', 1);
          $wrapper.append($select);
          $.each(selected, function (i, e) {
            $wrapper
              .find('select option[value="' + e + '"]')
              .attr("selected", true);
          });

          cf_html = $wrapper.html();
        }
        table_row += '<td class="custom_field">' + cf_html + "</td>";
      });
    }

    table_row +=
      '<td><input type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="newitems[' +
      item_key +
      '][qty]" value="' +
      data.qty +
      '" class="form-control">';

    if (!data.unit || typeof data.unit == "undefined") {
      data.unit = "";
    }

    table_row +=
      '<input type="hidden" placeholder="' +
      app.lang.unit +
      '" name="newitems[' +
      item_key +
      '][unit]" class="form-control input-transparent text-right" value="' +
      data.unit +
      '">';

    table_row += "</td>";

    table_row +=
      '<td class="rate"><input type="number" data-toggle="tooltip" title="' +
      app.lang.item_field_not_formatted +
      '" onblur="calculate_total();" onchange="calculate_total();" name="newitems[' +
      item_key +
      '][rate]" value="' +
      data.rate +
      '" class="form-control"></td>';

    table_row += '<td class="taxrate">' + tax_dropdown + "</td>";

    table_row +=
      '<td class="amount" align="right">' +
      format_money(amount, true) +
      "</td>";

    table_row +=
      '<td><a href="#" class="btn btn-danger pull-left !tw-px-3" onclick="delete_item(this,' +
      itemid +
      '); return false;"><i class="fa fa-trash"></i></a></td>';

    table_row += "</tr>";

    $("table.items tbody").append(table_row);

    $(document).trigger({
      type: "item-added-to-table",
      data: data,
      row: table_row,
    });

    setTimeout(function () {
      calculate_total();
    }, 15);

    var billed_task = $('input[name="task_id"]').val();
    var billed_expense = $('input[name="expense_id"]').val();

    if (billed_task !== "" && typeof billed_task != "undefined") {
      billed_tasks = billed_task.split(",");
      $.each(billed_tasks, function (i, obj) {
        $("#billed-tasks").append(
          hidden_input("billed_tasks[" + item_key + "][]", obj)
        );
      });
    }

    if (billed_expense !== "" && typeof billed_expense != "undefined") {
      billed_expenses = billed_expense.split(",");
      $.each(billed_expenses, function (i, obj) {
        $("#billed-expenses").append(
          hidden_input("billed_expenses[" + item_key + "][]", obj)
        );
      });
    }

    if (
      $("#item_select").hasClass("ajax-search") &&
      $("#item_select").selectpicker("val") !== ""
    ) {
      $("#item_select").prepend("<option></option>");
    }

    init_selectpicker();
    init_datepicker();
    init_color_pickers();
    clear_item_preview_values();
    reorder_items();

    $("body").find("#items-warning").remove();
    $("body").find(".dt-loader").remove();
    $("#item_select").selectpicker("val", "");

    if (cf_has_required && $(".invoice-form").length) {
      validate_invoice_form();
    } else if (cf_has_required && $(".estimate-form").length) {
      validate_estimate_form();
    } else if (cf_has_required && $(".proposal-form").length) {
      validate_proposal_form();
    } else if (cf_has_required && $(".credit-note-form").length) {
      validate_credit_note_form();
    }

    if (bill_expense == "undefined" || !bill_expense) {
      $('select[name="task_select"]')
        .find('[value="' + billed_task + '"]')
        .remove();
      $('select[name="task_select"]').selectpicker("refresh");
    }
    return true;
  });

  return false;
}

// Caps the typed quantity at the available stock for that row's item.
// Moved as-is from old main.js. NOTE: it currently has NO caller anywhere
// (the old qty inputs never called it), so by itself it does nothing.
// Do NOT wire it to the qty inputs as-is: for an item that was never
// stocked, quantity_available returns 0 and it would force the qty to 0.
// If you want the cap, it first needs a guard (only cap when the item
// actually has a stock record).
function verify_input_qty(this_input) {
    let input_qty = $(this_input).val();
    item_id = $(this_input).closest('tr').find('.order').next().val();
    var action= admin_url + 'stock_management/stock_products/quantity_available';
    $.ajax(action, {
        type: 'POST',
        data: {item_id},
    }).done(function(data){
        data = JSON.parse(data);
        let max_qty = data.count;
        max_qty = parseInt(max_qty);

        $(this_input).attr('max', max_qty);
        if(input_qty > max_qty)
            $(this_input).val(max_qty);
    });
}
