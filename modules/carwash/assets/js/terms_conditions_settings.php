<script>
(function () {
    if (typeof jQuery === 'undefined') {
        return;
    }

    jQuery(function ($) {
        var tableSelector = '.table-carwash_terms_conditions';

        if ($(tableSelector).length) {
            initDataTable(tableSelector, admin_url + 'carwash/terms_and_conditions', [], [], '', [0, 'DESC']);
        }

        function resetTermConditionForm() {
            $('#cw_tc_id').val('');
            $('#cw_tc_title').val('');
            $('#cw_tc_nature').val('');
            $('#cw_tc_description').val('');
        }

        $('body').off('click', '#add_term_condition').on('click', '#add_term_condition', function () {
            resetTermConditionForm();
            $('#term_condition_modal').modal('show');
        });

        $('body').off('click', '.edit_term_condition').on('click', '.edit_term_condition', function () {
            var id = $(this).data('id');
            $.post(admin_url + 'carwash/get_terms_condition', { id: id }).done(function (response) {
                var data = {};

                try {
                    data = JSON.parse(response);
                } catch (e) {
                    alert_float('danger', 'Unable to load record.');
                    return;
                }

                $('#cw_tc_id').val(data.id || '');
                $('#cw_tc_title').val(data.title || '');
                $('#cw_tc_nature').val(data.nature || '');
                $('#cw_tc_description').val(data.description || '');
                $('#term_condition_modal').modal('show');
            });
        });

        $('body').off('click', '#save_term_condition').on('click', '#save_term_condition', function () {
            var payload = {
                id: $('#cw_tc_id').val(),
                title: $('#cw_tc_title').val(),
                nature: $('#cw_tc_nature').val(),
                description: $('#cw_tc_description').val()
            };

            $.post(admin_url + 'carwash/save_terms_condition', payload).done(function (response) {
                var data = {};

                try {
                    data = JSON.parse(response);
                } catch (e) {
                    alert_float('danger', 'Unexpected server response.');
                    return;
                }

                alert_float(data.status, data.message);

                if (data.status === 'success' || data.status === 'warning') {
                    $(tableSelector).DataTable().ajax.reload();
                    $('#term_condition_modal').modal('hide');
                }
            });
        });

        $('body').off('click', '.delete_term_condition').on('click', '.delete_term_condition', function () {
            if (!confirm_delete()) {
                return;
            }

            var id = $(this).data('id');
            $.post(admin_url + 'carwash/delete_terms_condition', { id: id }).done(function (response) {
                var data = {};

                try {
                    data = JSON.parse(response);
                } catch (e) {
                    alert_float('danger', 'Unexpected server response.');
                    return;
                }

                alert_float(data.status, data.message);
                $(tableSelector).DataTable().ajax.reload();
            });
        });
    });
})();
</script>
