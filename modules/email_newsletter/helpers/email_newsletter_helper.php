<?php

if ( ! function_exists('einvoicing_force_download'))
{
    /**
     * Force Download
     *
     * Generates headers that force a download to happen
     *
     * @param	string	filename
     * @param	mixed	the data to be downloaded
     * @param	bool	whether to try and send the actual file MIME type
     * @return	void
     */
    function einvoicing_force_download($filename = '', $data = '', $set_mime = FALSE)
    {
        if ($filename === '' OR $data === '')
        {
            return;
        }
        elseif ($data === NULL)
        {
            if ( ! @is_file($filename) OR ($filesize = @filesize($filename)) === FALSE)
            {
                return;
            }

            $filepath = $filename;
            $filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
            $filename = end($filename);
        }
        else
        {
            $filesize = strlen($data);
        }

        // Set the default MIME type to send
        $mime = 'application/octet-stream';

        $x = explode('.', $filename);
        $extension = end($x);

        if ($set_mime === TRUE)
        {
            if (count($x) === 1 OR $extension === '')
            {
                /* If we're going to detect the MIME type,
                 * we'll need a file extension.
                 */
                return;
            }

            // Load the mime types
            $mimes =& get_mimes();

            // Only change the default MIME if we can find one
            if (isset($mimes[$extension]))
            {
                $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
            }
        }

        /* It was reported that browsers on Android 2.1 (and possibly older as well)
         * need to have the filename extension upper-cased in order to be able to
         * download it.
         *
         * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
         */
        if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT']))
        {
            $x[count($x) - 1] = strtoupper($extension);
            $filename = implode('.', $x);
        }

        if ($data === NULL && ($fp = @fopen($filepath, 'rb')) === FALSE)
        {
            return;
        }

        // Clean output buffer
        if (ob_get_level() !== 0 && @ob_end_clean() === FALSE)
        {
            @ob_clean();
        }

        // Generate the server headers
        header('Content-Type: '.$mime);
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.$filesize);
        header('Cache-Control: private, no-transform, no-store, must-revalidate');

        // If we have raw data - just dump it
        if ($data !== NULL)
        {
            exit($data);
        }

        // Flush 1MB chunks of data
        while ( ! feof($fp) && ($data = fread($fp, 1048576)) !== FALSE)
        {
            echo $data;
        }

        fclose($fp);
        exit;
    }
}


function email_newsletter_curl_request($endpoint = '', $user_headers = array(), $post_data = array(), $method = "POST")
{
	set_time_limit(0);
	$curl = curl_init();
	$headers = array(
		"accept: application/json",
		"Content-Type: application/json"
	);
	if(!empty($user_headers))
		$headers =	array_merge($headers, $user_headers);

	curl_setopt_array($curl, array(
        CURLOPT_URL => "$endpoint",
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_ENCODING => "",
	    CURLOPT_MAXREDIRS => 10,
	    CURLOPT_TIMEOUT => 30,
	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	    CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
	    CURLOPT_CUSTOMREQUEST => $method,
		CURLOPT_POSTFIELDS => json_encode($post_data),
		CURLOPT_HTTPHEADER => $headers
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	    echo "cURL Error #:" . $err;
	    return false;
	} else {
	    return $response;
	}
}

function email_newsletter_send_email_template($template_slug, $merge_fields = [])
{
    $CI =& get_instance();
    
    $template = get_email_template_for_sending($template_slug, '');

    if (!$template) return false;

    /**
     * Template is disabled or invalid email?
     * Log activity
     */
    if ($template->active == 0) return false;

    $template = hooks()->apply_filters('before_parse_email_template_message', $template);

    $template = parse_email_template($template, $merge_fields);

    $template = hooks()->apply_filters('after_parse_email_template_message', $template);

    $template->message = get_option('email_header') . $template->message . get_option('email_footer');

    $template = parse_email_template_merge_fields($template, $merge_fields);

    $fromemail = $template->fromemail;
    $fromname  = $template->fromname;

    if ($fromemail == '') $fromemail = get_option('smtp_email');

    if ($fromname == '') $fromname = get_option('companyname');

    $hook_data['template']    = $template;
    $hook_data['fromname']       = $fromname;
    $hook_data['fromemail']       = $fromemail;

    $hook_data['template']->message = check_for_links($hook_data['template']->message);

    /*if (count($attachments) > 0) {
        foreach ($attachments as $attach) {
            if (!isset($attach['read'])) {
                $CI->email->attach($attach['attachment'], 'attachment', $attach['filename'], $attach['type']);
            } else {
                $CI->email->attach($attach['attachment'], '', $attach['filename']);
            }
        }
    }*/


    return $hook_data;
}

function email_newsletter_get_client_by_email($email = '')
{
    $CI =& get_instance();
    
    $client = $CI->db->where('email', $email)->get('tblcontacts')->row_array();
    return $client;
}

function email_newsletter_get_emails_count($campaign_id = 0, $type = 'delivered')
{
    $CI =& get_instance();
    $CI->db->where('campaign_id', $campaign_id);
    $CI->db->where($type, 1);
    return $CI->db->count_all_results('tblemail_newsletter_campaign_details');
}