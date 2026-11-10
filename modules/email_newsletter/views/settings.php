<h4>Sendpulse Settings</h4>
<hr />
<?php echo render_input('settings[email_newsletter_sendpulse_sms_from]',_l('Sendpulse SMS From'),get_option('email_newsletter_sendpulse_sms_from'), 'text', array('placeholder' => '') ); ?>
<?php echo render_input('settings[email_newsletter_sendpulse_id]',_l('Sendpulse ID'),get_option('email_newsletter_sendpulse_id'), 'text', array('placeholder' => '') ); ?>
<?php echo render_input('settings[email_newsletter_sendpulse_secret]',_l('Sendpulse Secret'),get_option('email_newsletter_sendpulse_secret'), 'password', array('placeholder' => '') ); ?>

<br />
<br />
<br />
<h4>Mailtrap Settings</h4>
<hr />
<?php echo render_input('settings[email_newsletter_account_id]',_l('Mailtrap Account ID'),get_option('email_newsletter_account_id')); ?>
<?php echo render_input('settings[email_newsletter_api_token]',_l('Mailtrap Api Token'),get_option('email_newsletter_api_token')); ?>