<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'email_newsletter_newsletter_history')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "email_newsletter_newsletter_history` (
  `id` int(11) NOT NULL,
  `sent_by` text,
  `email_subject` text,
  `email_content` text,
  `total_emails_to_send` text,
  `email_list` text,
  `emails_sent` text,
  `emails_failed` text,
  `created_at` datetime
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'email_newsletter_newsletter_history`
  ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'email_newsletter_newsletter_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

create_email_template('BXFactory Bulk EMails', '<p>Hello.<br /><br />Email from BXFactory:</p><br /><br /><p>Best Regards</p>', 'client', 'BXFactory Bulk Emails', 'bulk-emails-to-client');

add_option('email_newsletter_sendpulse_sms_from', '');
add_option('email_newsletter_sendpulse_id', '');
add_option('email_newsletter_sendpulse_secret', '');

add_option('email_newsletter_mailtrap_account_id', '');
add_option('email_newsletter_mailtrap_api_token', '');

if (!$CI->db->field_exists('sendpulse_campaign_id', 'email_newsletter_newsletter_history')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'email_newsletter_newsletter_history`  ADD COLUMN `sendpulse_campaign_id` int(11) NULL DEFAULT 0');
}

if (!$CI->db->field_exists('message_ids', 'email_newsletter_newsletter_history')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'email_newsletter_newsletter_history`  ADD COLUMN `message_ids` LONGTEXT NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('campaign_name', 'email_newsletter_newsletter_history')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'email_newsletter_newsletter_history`  ADD COLUMN `campaign_name` VARCHAR(500) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('campaign_type', 'email_newsletter_newsletter_history')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'email_newsletter_newsletter_history`  ADD COLUMN `campaign_type` VARCHAR(20) DEFAULT \'email\'');
}

if (!$CI->db->field_exists('customer_status', 'email_newsletter_newsletter_history')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'email_newsletter_newsletter_history`  ADD COLUMN `customer_status` VARCHAR(20) DEFAULT NULL');
}

if (!$CI->db->field_exists('customer_groups', 'email_newsletter_newsletter_history')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'email_newsletter_newsletter_history`  ADD COLUMN `customer_groups` VARCHAR(100) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('sms_body', 'email_newsletter_newsletter_history')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'email_newsletter_newsletter_history`  ADD COLUMN `sms_body` VARCHAR(1000) NULL DEFAULT NULL');
}

if (!$CI->db->table_exists(db_prefix() . 'email_newsletter_campaign_details')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'email_newsletter_campaign_details`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `campaign_id` INT(11) NULL DEFAULT NULL,
        `message_id` VARCHAR(50) NULL DEFAULT NULL,
        `client_id` INT(11) NULL DEFAULT NULL,
        `email` varchar(500) NULL DEFAULT NULL,
        `delivered` TINYINT(4) NULL DEFAULT 0,
        `opened` TINYINT(4) NULL DEFAULT 0,
        `clicked` TINYINT(4) NULL DEFAULT 0,
        `bounced` TINYINT(4) NULL DEFAULT 0,
        `rejected` TINYINT(4) NULL DEFAULT 0,
        `unsubscribed` TINYINT(4) NULL DEFAULT 0,
        `reason` VARCHAR(500) NULL DEFAULT NULL,
        `created_at` datetime NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}