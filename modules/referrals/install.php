<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->field_exists('referred_by_client', 'leads')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'leads`  ADD COLUMN `referred_by_client` INT(11) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('referred_by_client', 'clients')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients`  ADD COLUMN `referred_by_client` INT(11) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('source', 'clients')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients`  ADD COLUMN `source` INT(11) NULL DEFAULT NULL');
}

if (!$CI->db->table_exists(db_prefix() . '_commissions')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_commissions`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `clientid` INT(11) NULL DEFAULT NULL,
        `amount` VARCHAR(255) NULL DEFAULT NULL,
        `status` VARCHAR(50) NULL DEFAULT NULL,
        `invoice_id` INT(11) NULL DEFAULT NULL,
        `source` INT(11) NULL DEFAULT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `created_on` DATETIME NULL DEFAULT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,
        `modified_on` DATETIME NULL DEFAULT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

add_option('referrals_commission_percentage' , '');

if ($CI->db->field_exists('amount', db_prefix() . '_commissions')) {
  $CI->db->query("ALTER TABLE `".db_prefix()."_commissions` MODIFY COLUMN `amount` DECIMAL(15,2) NULL DEFAULT NULL");
}

if (!$CI->db->field_exists('method', '_commissions')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_commissions`  ADD COLUMN `method` INT(11) NULL DEFAULT NULL');
}

$commission_category = $CI->db->where('name' , 'Commission')->get('tblexpenses_categories')->row_array();

if(!isset($commission_category) || empty($commission_category)){
    $data['name'] = 'Commission';
    $CI->db->insert('tblexpenses_categories',$data);
}