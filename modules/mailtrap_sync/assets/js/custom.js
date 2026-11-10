

$(function() {
	var rules = {
		name: {required: true},
	};
	_validate_form($('.mailtrap_sync_form'), rules);
});



function syncData(this_ref, sync_url = '', btn_text = '') {
	var $this = $(this_ref);

	$this.html('<i class="fa fa-spinner fa-spin"></i>')

	$.ajax({
		url:sync_url,
		type:'post',
		dataType:'JSON',
		data:{},
		success:function (response) {
			if (response.success){
				// $this.next().html(`Last Sync At: ${response.sync_time}`);
				alert_float('success', response.message);
			}
			else
				alert_float('danger', 'Error in sync. Try again.');

			$this.html(btn_text);
		},
		error: function() {
			$this.html(btn_text);
			alert_float('danger', 'Error in sync. Try again.');
		}
	})
}
