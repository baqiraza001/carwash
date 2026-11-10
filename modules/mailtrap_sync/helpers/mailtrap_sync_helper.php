<?php
function get_country_by_code($countryCode='')
{
	$CI = & get_instance();

	$CI->db->where('iso2', $countryCode);
	$country = $CI->db->get(db_prefix().'countries')->row();
	return $country ? $country->country_id : '';
}


function mailtrap_sync_get_contact_lists() 
{
	$account_id = get_option('mailtrap_sync_account_id');
	$api_key = get_option('mailtrap_sync_api_key');

	$url = "https://mailtrap.io/api/accounts/{$account_id}/contacts/lists";

	$ch = curl_init($url);
	curl_setopt_array($ch, [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => [
			'Authorization: Bearer ' . $api_key,
			'Content-Type: application/json'
		],
	]);

	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($http_code === 200) {
		return json_decode($response, true);
	} else {
		return [
			'error' => true,
			'status' => $http_code,
			'message' => 'Failed to fetch contact lists.'
		];
	}
}