<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'sage')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'sage` (
  `id` int(11) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `client_secret` varchar(255) NOT NULL,
  `redirect_uri` varchar(255) DEFAULT NULL,
  `access_token` text DEFAULT NULL,
  `refresh_token` text DEFAULT NULL,
  `rcd` timestamp DEFAULT CURRENT_TIMESTAMP,
  `rmd` timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sage`
  ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'contacts`
  ADD `sage_contactid` varchar(255);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'invoices`
  ADD `sage_invoiceid` varchar(255);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'payment_modes`
  ADD `sage_accountid` varchar(255);');

}


