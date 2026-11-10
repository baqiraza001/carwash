$(document).ready(function(){
	$('body').on('click' ,'.cancel-me', function(){
		window.location = admin_url + 'stock_management/suppliers';
	});
});